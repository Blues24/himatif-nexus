<?php

namespace App\Http\Controllers\Infokom;

use App\Http\Controllers\Controller;
use App\Http\Requests\KegiatanStoreRequest;
use App\Http\Requests\KegiatanUpdateRequest;
use App\Services\KegiatanService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class KegiatanController extends Controller
{
    public function __construct(protected KegiatanService $service) {}

    public function index(Request $request): Response
    {
        $status = $request->query('status');

        return Inertia::render('Infokom/Kegiatan/Index', [
            'kegiatans' => fn () => filled($status)
                ? $this->service->byStatus($status)->items()
                : $this->service->paginate()->items(),
            'upcoming' => fn () => $this->service->upcoming(),
            'filters' => ['status' => $status],
        ]);
    }

    public function show(int $id): Response
    {
        $kegiatan = $this->service->findOrFail($id);

        return Inertia::render('Infokom/Kegiatan/Show', [
            'kegiatan' => $kegiatan,
        ]);
    }

    public function create(): Response
    {
        return Inertia::render('Infokom/Kegiatan/Create');
    }

    public function store(KegiatanStoreRequest $request): RedirectResponse
    {
        $this->service->create($request->validated());

        return redirect()->route('kegiatan.index')
            ->with('success', 'Kegiatan berhasil ditambahkan.');
    }

    public function edit(int $id): Response
    {
        $kegiatan = $this->service->findOrFail($id);

        return Inertia::render('Infokom/Kegiatan/Edit', [
            'kegiatan' => $kegiatan->only([
                'kegiatan_id',
                'nama_kegiatan',
                'deskripsi_kegiatan',
                'tanggal_pelaksanaan',
                'status_kegiatan',
            ]),
        ]);
    }

    public function update(KegiatanUpdateRequest $request, int $id): RedirectResponse
    {
        $this->service->update($id, $request->validated());

        return redirect()->route('kegiatan.index')
            ->with('success', 'Kegiatan berhasil diperbarui.');
    }

    public function destroy(int $id): RedirectResponse
    {
        $this->service->delete($id);

        return redirect()->route('kegiatan.index')
            ->with('success', 'Kegiatan berhasil dihapus.');
    }
}
