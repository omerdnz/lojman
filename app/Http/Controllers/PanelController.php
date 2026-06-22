<?php

namespace App\Http\Controllers;

use App\Services\DashboardService;
use Illuminate\View\View;

class PanelController extends Controller
{
    public function __construct(
        private readonly DashboardService $dashboardService
    ) {}

    public function index(): View
    {
        return view('panel.index', [
            'stats' => $this->dashboardService->getStats(),
        ]);
    }
}
