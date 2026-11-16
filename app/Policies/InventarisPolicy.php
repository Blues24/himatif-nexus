<?php

namespace App\Policies;

use App\Models\Inventaris;
use App\Models\User;

class InventarisPolicy
{
    /**
     * Determine whether the user can view any inventory.
     */
    public function viewAny(?User $user): bool
    {
        return $user !== null;
    }

    /**
     * Determine whether the user can view the inventory item.
     */
    public function view(?User $user, Inventaris $inventaris): bool
    {
        return $user !== null;
    }

    /**
     * Determine whether the user can create inventory items.
     */
    public function create(?User $user): bool
    {
        return $user !== null && $user->isBph();
    }

    /**
     * Determine whether the user can update the inventory item.
     */
    public function update(?User $user, Inventaris $inventaris): bool
    {
        return $user !== null && $user->isBph();
    }

    /**
     * Determine whether the user can delete the inventory item.
     */
    public function delete(?User $user, Inventaris $inventaris): bool
    {
        return $user !== null && $user->isBph();
    }
}