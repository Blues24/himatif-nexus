<?php

namespace App\Policies;

use App\Models\Kegiatan;
use App\Models\User;

class KegiatanPolicy
{
    /**
     * Determine whether the user can view any activities.
     */
    public function viewAny(?User $user): bool
    {
        return true; // public browse
    }

    /**
     * Determine whether the user can view the activity.
     */
    public function view(?User $user, Kegiatan $kegain): bool
    {
        return true; // public show
    }

    /**
     * Determine whether the user can create activities.
     */
    public function create(?User $user): bool
    {
        return $user !== null && ($user->isBph() || $user->hasRole('Infokom'));
    }

    /**
     * Determine whether the user can update the activity.
     */
    public function update(?User $user, Kegiatan $kegain): bool
    {
        return $user !== null && ($user->isBph() || $user->hasRole('Infokom') || $kegain->user_id === $user->user_uuid);
    }

    /**
     * Determine whether the user can delete the activity.
     */
    public function delete(?User $user, Kegiatan $kegain): bool
    {
        return $user !== null && ($user->isBph() || $user->hasRole('Infokom') || $kegain->user_id === $user->user_uuid);
    }
}