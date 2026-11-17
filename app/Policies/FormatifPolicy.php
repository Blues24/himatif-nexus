<?php

namespace App\Policies;

use App\Models\Formatif;
use App\Models\User;

class FormatifPolicy
{
    /**
     * Determine whether the user can view any articles.
     */
    public function viewAny(?User $user): bool
    {
        return true; // public browse
    }

    /**
     * Determine whether the user can view the article.
     */
    public function view(?User $user, Formatif $formatif): bool
    {
        return true; // public show
    }

    /**
     * Determine whether the user can create articles.
     */
    public function create(?User $user): bool
    {
        return $user !== null && ($user->isBph() || $user->hasRole('Infokom'));
    }

    /**
     * Determine whether the user can update the article.
     */
    public function update(?User $user, Formatif $formatif): bool
    {
        return $user !== null && ($user->isBph() || $user->hasRole('Infokom') || $formatif->user_id === $user->user_uuid);
    }

    /**
     * Determine whether the user can delete the article.
     */
    public function delete(?User $user, Formatif $formatif): bool
    {
        return $user !== null && ($user->isBph() || $user->hasRole('Infokom') || $formatif->user_id === $user->user_uuid);
    }
}