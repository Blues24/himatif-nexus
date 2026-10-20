<?php

namespace App\Http\Controllers\DanusSHop;

use App\Helpers\CurrencyHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\ProdukStoreRequest;
use App\Http\Requests\ProdukUpdateRequest;
use App\Services\ProdukService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class ProdukController extends Controller
{
    private readonly ProdukService $service;

    public function __construct(ProdukService $service)
    {
        $this->service = $service;
    }

    public function index(Request $request): Response
    {
        $search = $request->query('q', '');

        return Inertia::render('Shop/Index', [
            'products' => fn () => filled($search)
                ? $this->service->search($search)->items()
                : $this->service->paginate()->items(),
            'lowStock' => fn () => $this->service->getLowStock()->map(fn ($p) => $p->only(['produk_id', 'nama_produk', 'stok_produk'])),
            'filters' => ['q' => $search],
        ]);
    }

    public function show(int $id): Response
    {
        $product = $this->service->findOrFail($id);

        return Inertia::render('Shop/Show', [
            'product' => array_merge($product->toArray(), [
                'harga_formatted' => CurrencyHelper::format($product->harga_produk),
            ]),
        ]);
    }

    public function create(): Response
    {
        return Inertia::render('Shop/Create');
    }

    public function store(ProdukStoreRequest $request): RedirectResponse
    {
        $data = $request->validated();

        if ($request->hasFile('gambar_produk')) {
            $data['gambar_produk'] = $request->file('gambar_produk')->store('produk', 'public');
        }

        $this->service->create($data);

        return redirect()->route('produk.index')
            ->with('success', 'Produk berhasil ditambahkan.');
    }

    public function edit(int $id): Response
    {
        $product = $this->service->findOrFail($id);

        return Inertia::render('Shop/Edit', [
            'product' => $product->only([
                'produk_id', 'nama_produk', 'deskripsi_produk',
                'harga_produk', 'stok_produk', 'gambar_produk',
            ]),
        ]);
    }

    public function update(ProdukUpdateRequest $request, int $id): RedirectResponse
    {
        $data = $request->validated();

        if ($request->hasFile('gambar_produk')) {
            $data['gambar_produk'] = $request->file('gambar_produk')->store('produk', 'public');

            $existing = $this->service->findOrFail($id);
            if ($existing->gambar_produk && \Storage::disk('public')->exists($existing->gambar_produk)) {
                \Storage::disk('public')->delete($existing->gambar_produk);
            }
        }

        $this->service->update($id, $data);

        return redirect()->route('produk.edit', $id)
            ->with('success', 'Produk berhasil diperbarui.');
    }

    public function destroy(int $id): RedirectResponse
    {
        $this->service->delete($id);

        return back()->with('success', 'Produk berhasil dihapus.');
    }
}
