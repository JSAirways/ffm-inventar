<?php

namespace App\Filament\Resources;

use App\Models\Item;
use App\Models\Location;
use App\Models\Category;

use App\Enums\ItemStatus;

use App\Filament\Resources\ItemResource\Pages;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\FileUpload;

use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Actions\Action;
use Filament\Tables\Filters\TabsFilter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextInputColumn;
use Filament\Tables\Columns\SelectColumn;

use Filament\Resources\Resource;
use Filament\Resources\Pages\ListRecords;

use Illuminate\Database\Eloquent\Model;

use Filament\Forms\Components\Section;
use Filament\Forms\Get;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Filament\Forms\Components\Repeater;

use Filament\Tables\Columns\ViewColumn;





class ItemResource extends Resource
{
    protected static ?string $model = Item::class;

    protected static ?string $navigationIcon = 'heroicon-o-archive-box';

    public static function form(Form $form): Form
    {
        return $form->schema([
            TextInput::make('amount')->numeric()->default(1)->minValue(1),
            TextInput::make('description')->required(),
            Select::make('location_id')->relationship('location', 'name')->required(),
            Select::make('category_id')->relationship('category', 'name')->nullable(),
            Select::make('status')
                ->options(ItemStatus::options())
                ->default(ItemStatus::Available->value),
            Section::make('Item Info')
                ->schema([
                    Select::make('status')
                        ->label('Status')
                        ->options(ItemStatus::asSelectArray())
                        ->required()
                        ->reactive(),

                    Select::make('loaned_to_location_id')
                        ->label('Loaned To Location')
                        ->relationship('loanedToLocation', 'name')
                        ->placeholder('Select location...')
                        ->visible(fn (Get $get) => $get('status') === ItemStatus::OnLoan->value)
                        ->required(fn (Get $get) => $get('status') === ItemStatus::OnLoan->value),
                ]),
            Repeater::make('images')
                ->label('Images')
                ->relationship('images')
                ->schema([
                    FileUpload::make('path')
                        ->label('Image')
                        ->image()
                        ->imageEditor()
                        ->disk('public')
                        ->directory('items/gallery')
                        ->preserveFilenames()
                        ->storeFiles(false) // <--- Important!
                        ->getUploadedFileNameForStorageUsing(function (TemporaryUploadedFile $file, $record) {
                            return app(\App\Services\ImageProcessor::class)->handle($file);
                        })
                        ->columnSpanFull()
                        ->required(),
                ])
                ->defaultItems(0)
                ->addActionLabel('Add Image')
                ->reorderable()
                ->columns(1),
            Textarea::make('notes')->rows(3)->nullable()
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('thumbnail_url')
                    ->label('Thumbnail')
                    ->square() // optional
                    ->width(60)  // adjust as needed
                    ->height(60)
                    ->extraImgAttributes(['loading' => 'lazy', 'referrerpolicy' => 'no-referrer'])
                    ->toggleable(isToggledHiddenByDefault: false),
                TextInputColumn::make('amount')
                    ->label('Quantity')
                    ->type('number')
                    ->sortable(),

                TextInputColumn::make('description')
                    ->label('Description')
                    ->searchable()
                    ->sortable(),

                SelectColumn::make('category_id')
                    ->label('Category')
                    ->options(Category::all()->pluck('name', 'id')->toArray())
                    ->sortable(),

                SelectColumn::make('status')
                    ->label('Status')
                    ->options(ItemStatus::asSelectArray())
                    ->sortable(),

                TextInputColumn::make('loanedToLocation.name')
                    ->label('Loaned To')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),


                SelectColumn::make('location_id')
                    ->label('Location')
                    ->options(Location::all()->pluck('name', 'id')->toArray())
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('location_id')
                    ->label('Location')
                    ->relationship('location', 'name'),

                SelectFilter::make('category_id')
                    ->label('Category')
                    ->relationship('category', 'name'),

                SelectFilter::make('status')
                    ->label('Status')
                    ->options(\App\Enums\ItemStatus::class), // assuming you're using enum
            ])
            ->actions([
                Action::make('duplicate')
                    ->icon('heroicon-o-document-duplicate')
                    ->tooltip('Duplicate Item')
                    ->action(function (\App\Models\Item $record) {
                        $newItem = $record->replicate([
                            'id',
                            'created_at',
                            'updated_at',
                        ]);

                        // Copy image if present
                        if ($record->photo_path && Storage::disk('public')->exists($record->photo_path)) {
                            $newPath = 'items/' . Str::uuid() . '.' . pathinfo($record->photo_path, PATHINFO_EXTENSION);
                            Storage::disk('public')->copy($record->photo_path, $newPath);
                            $newItem->photo_path = $newPath;
                        }

                        $newItem->save();

                        return redirect(route('filament.admin.resources.items.edit', ['record' => $newItem->id]));
                    })
                    ->color('gray')
                    ->iconButton(),
                
                Tables\Actions\EditAction::make()->iconButton(),
                Tables\Actions\DeleteAction::make()->iconButton(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListItems::route('/'),
            'create' => Pages\CreateItem::route('/create'),
            'edit' => Pages\EditItem::route('/{record}/edit'),
        ];
    }

    // In your ItemResource
    public static function canViewAny(): bool
    {
        return auth()->user()?->can('viewAny', Item::class) ?? false;
    }

    public static function canCreate(): bool
    {
        return auth()->user()?->can('create', Item::class) ?? false;
    }

    public static function canEdit(Model $record): bool
    {
        return auth()->user()?->can('update', $record) ?? false;
    }

    public static function canDelete(Model $record): bool
    {
        return auth()->user()?->can('delete', $record) ?? false;
    }

}
