<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\UserStoreRequest;
use App\Http\Requests\Admin\UserUpdateRequest;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class UserController extends Controller
{
    /**
     * Display a listing of users with their roles.
     */
    public function index(Request $request): Response
    {
        $users = User::query()
            ->with('role')
            ->latest()
            ->paginate(15)
            ->withQueryString();

        $roles = Role::query()->get(['role_id', 'nama_role']);

        return Inertia::render('Admin/HakAkses', [
            'users' => $users,
            'roles' => $roles,
        ]);
    }

    /**
     * Store a newly created user.
     */
    public function store(UserStoreRequest $request): JsonResponse
    {
        $data = $request->validated();

        $user = User::create([
            'user_uuid' => (string) \Illuminate\Support\Str::uuid(),
            'role_id' => $data['role_id'],
            'nama' => $data['nama'],
            'email' => $data['email'],
            'password' => bcrypt($data['password']),
        ]);

        return response()->json([
            'message' => 'Pengguna berhasil dibuat.',
            'user' => $user->load('role'),
        ], 201);
    }

    /**
     * Update the specified user.
     */
    public function update(UserUpdateRequest $request, User $user): JsonResponse
    {
        $data = $request->validated();

        $user->update([
            'role_id' => $data['role_id'],
            'nama' => $data['nama'],
            'email' => $data['email'],
            ...($data['password'] ? ['password' => bcrypt($data['password'])] : []),
        ]);

        return response()->json([
            'message' => 'Pengguna berhasil diperbarui.',
            'user' => $user->load('role'),
        ]);
    }

    /**
     * Remove the specified user.
     */
    public function destroy(User $user): JsonResponse
    {
        // Prevent deleting self
        if ($user->user_uuid === auth()->id()) {
            return response()->json([
                'message' => 'Tidak dapat menghapus akun sendiri.',
            ], 422);
        }

        $user->delete();

        return response()->json([
            'message' => 'Pengguna berhasil dihapus.',
        ]);
    }

    /**
     * Get roles for dropdown/select.
     */
    public function roles(): JsonResponse
    {
        $roles = Role::query()->get(['role_id', 'nama_role']);

        return response()->json($roles);
    }
}