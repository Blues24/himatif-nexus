<?php

namespace App\Http\Controllers\Infokom;

use App\Http\Controllers\Controller;
use App\Http\Requests\FormatifStoreRequest;
use App\Http\Requests\FormatifUpdateRequest;
use App\Services\FormatifService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class FormatifController extends Controller
{
    public function __construct(private readonly FormatifService $service) {}

    public function index(Request $request): Response
    {
        $search = $request->query('q', '');

        return Inertia::render('Blog/Index', [
            'formatifs' => fn () => filled($search)
                ? $this->service->search($search)->items()
                : $this->service->paginate()->items(),
            'filters' => ['q' => $search],
        ]);
    }

    public function show(int $id): Response
    {
        $formatif = $this->service->findOrFail($id);

        return Inertia::render('Blog/Show', [
            'formatif' => $formatif,
        ]);
    }

    public function create(): Response
    {
        return Inertia::render('Blog/Create');
    }

    public function store(FormatifStoreRequest $request): RedirectResponse
    {
        $data = $request->validated();

        if ($request->hasFile('thumbnail_path')) {
            $data['thumbnail_path'] = $request->file('thumbnail_path')->store('formatif', 'public');
        }

        $data['user_id'] = $request->user()->user_uuid;

        $this->service->create($data);

        return redirect()->route('formatif.index')
            ->with('success', 'Formatif berhasil ditambahkan.');
    }

    public function edit(int $id): Response
    {
        $formatif = $this->service->findOrFail($id);

        return Inertia::render('Blog/Edit', [
            'formatif' => $formatif,
        ]);
    }

    public function update(FormatifUpdateRequest $request, int $id): RedirectResponse
    {
        $data = $request->validated();

        if ($request->hasFile('thumbnail_path')) {
            $data['thumbnail_path'] = $request->file('thumbnail_path')->store('formatif', 'public');

            $existing = $this->service->findOrFail($id);
            if ($existing->thumbnail_path && \Storage::disk('public')->exists($existing->thumbnail_path)) {
                \Storage::disk('public')->delete($existing->thumbnail_path);
            }
        }

        $this->service->update($id, $data);

        return redirect()->route('formatif.edit', $id)
            ->with('success', 'Formatif berhasil diperbarui.');
    }

    public function destroy(int $id): RedirectResponse
    {
        $this->service->delete($id);

        return back()->with('success', 'Formatif berhasil dihapus.');
    }

    public function published(): Response
    {
        return Inertia::render('Blog/Show', [
            'formatifs' => fn () => $this->service->published()->items(),
        ]);
    }
}
