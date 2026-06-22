<?php

namespace App\Http\Controllers;

use App\Services\ImportService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ImportController extends Controller
{
    public function __construct(
        private readonly ImportService $importService,
    ) {}

    public function index(): View
    {
        return view('imports.index');
    }

    public function store(Request $request): RedirectResponse
    {
        abort_unless($request->user()?->can('employees.import'), 403);

        $request->validate([
            'file' => ['required', 'file', 'mimes:xlsx,xls,csv', 'max:10240'],
        ]);

        $result = $this->importService->importEmployees($request->file('file'), $request->user());

        return redirect()
            ->route('imports.index')
            ->with('success', "{$result['imported']} personel içe aktarıldı, {$result['skipped']} atlandı.")
            ->with('import_errors', $result['errors']);
    }
}
