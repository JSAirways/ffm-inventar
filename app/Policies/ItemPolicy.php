<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Item;

class ItemPolicy
{
    public function viewAny(User $user): bool
    {
        return true; // All @dzg-ev.com users can view
    }

    public function view(User $user, Item $item): bool
    {
        return true;
    }

    public function create(User $user): bool
    {
        return $user->exists; // Only DB users can edit
    }

    public function update(User $user, Item $item): bool
    {
        return $user->exists;
    }

    public function delete(User $user, Item $item): bool
    {
        return $user->exists;
    }

    public function restore(User $user, Item $item): bool
    {
        return $user->exists;
    }

    public function forceDelete(User $user, Item $item): bool
    {
        return $user->exists;
    }
}