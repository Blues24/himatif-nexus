<?php

namespace App\Policies;

use App\Models\PenyewaanAlat;
use App\Models\User;

class PenyewaanAlatPolicy
{
    /**
     * Determine whether the user can view any rentals.
     */
    public function viewAny(?User $user): bool
    {
        return $user !== null;
    }

    /**
     * Determine whether the user can view the rental.
     */
    public function view(?User $user, PenyewaanAlat $penyewaan): bool
    {
        if ($user === null) {
            return false;
        }

        return $user->isBph()
            || $user->hasRole('Portal')
            || $penyewaan->user_id === $user->user_uuid;
    }

    /**
     * Determine whether the user can create rentals.
     */
    public function create(?User $user): bool
    {
        return $user !== null && ($user->isBph() || $user->hasRole('Portal'));
    }

    /**
     * Determine whether the user can approve/reject/return the rental.
     */
    public function approve(?User $user, PenyewaanAlat $penyewaan): bool
    {
        return $user !== null && ($user->isBph() || $user->hasRole('Portal'));
    }
}