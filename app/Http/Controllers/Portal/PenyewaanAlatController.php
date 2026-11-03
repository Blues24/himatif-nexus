<?php

namespace App\Http\Controllers\Portal;

use App\Http\Controllers\Controller;
use App\Http\Requests\PenyewaanAlatStoreRequest;
use App\Services\PenyewaanAlatService;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;

class PenyewaanAlatController extends Controller
{
    private readonly PenyewaanAlatService $service;

    public function __construct(PenyewaanAlatService $service)
    {
        $this->service = $service;
    }

    public function index(): Response
    {
        return Inertia::render('Inventory/Loans', [
            'loans' => fn () => $this->service->paginate()->items(),
        ]);
    }

    public function create(): Response
    {
        return Inertia::render('Inventory/LoanCreate');
    }

    public function store(PenyewaanAlatStoreRequest $request): RedirectResponse
    {
        $this->service->submitRequest($request->validated());

        return redirect()->route('penyewaan.index')
            ->with('success', 'Permintaan peminjaman berhasil diajukan.');
    }

    public function approve(int $id): RedirectResponse
    {
        $this->service->handleRequest($id, 'approve');

        return back()->with('success', 'Peminjaman disetujui.');
    }

    public function reject(int $id): RedirectResponse
    {
        $this->service->handleRequest($id, 'reject');

        return back()->with('success', 'Peminjaman ditolak.');
    }

    public function markReturned(int $id): RedirectResponse
    {
        $this->service->markReturned($id);

        return back()->with('success', 'Alat berhasil dikembalikan.');
    }
}
