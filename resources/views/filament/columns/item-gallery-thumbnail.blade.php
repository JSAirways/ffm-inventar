@php
    $images = $getRecord()->images;
@endphp

@if ($images->isNotEmpty())
    <a href="{{ Storage::url($images[0]->path) }}" data-fancybox="gallery-{{ $getRecord()->id }}">
        <img src="{{ Storage::url($images[0]->path) }}"
             class="w-14 h-14 object-cover rounded shadow border" />
    </a>

@foreach($images->skip(1) as $image)
        <a href="{{ Storage::url($image->path) }}"
           data-fancybox="gallery-{{ $getRecord()->id }}"
           class="hidden"></a>
@endforeach
@else
<div class="w-14 h-14 bg-gray-100 text-gray-400 flex items-center justify-center rounded">
    —
</div>
@endif

@push('scripts')
@vite('resources/js/app.js')
@endpush

@push('styles')
@vite('resources/css/app.css')
@endpush
