<?php

namespace App\Http\Controllers\DanusSHop;

use App\Http\Controllers\Controller;
use App\Services\TransaksiService;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;

class TransaksiController extends Controller
{
    public function __construct(protected TransaksiService $service) {}

    public function index(): Response
    {
        return Inertia::render('Shop/Orders', [
            'transactions' => fn () => $this->service->paginate()->items(),
        ]);
    }

    public function show(int $id): Response
    {
        $transaction = $this->service->findOrFail($id);

        return Inertia::render('Shop/OrderDetail', [
            'transaction' => $transaction,
        ]);
    }

    public function confirm(int $id): RedirectResponse
    {
        $this->service->confirm($id);

        return back()->with('success', 'Transaksi dikonfirmasi.');
    }
}
