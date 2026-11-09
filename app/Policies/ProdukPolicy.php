<?php

namespace App\Policies;

use App\Models\Produk;
use App\Models\User;

class ProdukPolicy
{
    /**
     * Determine whether the user can view any products.
     */
    public function viewAny(?User $user): bool
    {
        return true; // public browse
    }

    /**
     * Determine whether the user can view the product.
     */
    public function view(?User $user, Produk $produk): bool
    {
        return true; // public show
    }

    /**
     * Determine whether the user can create products.
     */
    public function create(?User $user): bool
    {
        return $user !== null && ($user->isBph() || $user->hasRole('Danus Shop'));
    }

    /**
     * Determine whether the user can update the product.
     */
    public function update(?User $user, Produk $produk): bool
    {
        return $user !== null && ($user->isBph() || $user->hasRole('Danus Shop'));
    }

    /**
     * Determine whether the user can delete the product.
     */
    public function delete(?User $user, Produk $produk): bool
    {
        return $user !== null && ($user->isBph() || $user->hasRole('Danus Shop'));
    }
}