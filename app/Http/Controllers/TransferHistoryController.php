<?php

namespace App\Http\Controllers;

use App\Models\TransferHistory;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TransferHistoryController extends Controller
{
    public function index(Request $request): View
    {
        $histories = TransferHistory::query()
            ->with(['employee', 'fromRoom.floor.block', 'toRoom.floor.block', 'performedBy'])
            ->when($request->filled('search'), function ($q) use ($request) {
                $search = $request->string('search');
                $q->whereHas('employee', fn ($q) => $q->where('full_name', 'like', "%{$search}%"));
            })
            ->latest('created_at')
            ->paginate(50)
            ->withQueryString();

        return view('transfers.index', compact('histories'));
    }
}
