<?php

namespace App\Http\Controllers\Portal;

use App\Http\Controllers\Controller;
use App\Http\Requests\InventarisStoreRequest;
use App\Http\Requests\InventarisUpdateRequest;
use App\Services\InventarisService;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;

class InventarisController extends Controller
{
    private readonly InventarisService $service;

    public function __construct(InventarisService $service)
    {
        $this->service = $service;
    }

    public function index(): Response
    {
        return Inertia::render('Inventory/Index', [
            'baik' => fn () => $this->service->byCondition('baik')->items(),
            'rusak' => fn () => $this->service->byCondition('rusak')->items(),
            'hilang' => fn () => $this->service->byCondition('hilang')->items(),
        ]);
    }

    public function create(): Response
    {
        return Inertia::render('Inventory/Create');
    }

    public function store(InventarisStoreRequest $request): RedirectResponse
    {
        $this->service->create($request->validated());

        return redirect()->route('inventaris.index')
            ->with('success', 'Inventaris berhasil ditambahkan.');
    }

    public function edit(int $id): Response
    {
        $item = $this->service->findOrFail($id);

        return Inertia::render('Inventory/Edit', [
            'item' => $item->only(['inventaris_id', 'nama_barang', 'stok', 'kondisi']),
        ]);
    }

    public function update(InventarisUpdateRequest $request, int $id): RedirectResponse
    {
        $this->service->update($id, $request->validated());

        return redirect()->route('inventaris.edit', $id)
            ->with('success', 'Inventaris berhasil diperbarui.');
    }

    public function destroy(int $id): RedirectResponse
    {
        $this->service->delete($id);

        return back()->with('success', 'Inventaris berhasil dihapus.');
    }
}
