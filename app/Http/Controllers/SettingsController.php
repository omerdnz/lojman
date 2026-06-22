<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateCapacitySettingsRequest;
use App\Services\SettingsService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class SettingsController extends Controller
{
    public function __construct(
        private readonly SettingsService $settings
    ) {}

    public function capacity(): View
    {
        return view('settings.capacity', [
            'defaultCapacity' => $this->settings->defaultRoomCapacity(),
        ]);
    }

    public function updateCapacity(UpdateCapacitySettingsRequest $request): RedirectResponse
    {
        $this->settings->set(
            SettingsService::DEFAULT_ROOM_CAPACITY,
            $request->integer('default_room_capacity'),
            'Varsayılan Oda Kapasitesi',
            'Kapasitesi tanımsız (0) odalar için kullanılacak yatak sayısı.',
            'capacity'
        );

        return back()->with('success', 'Kapasite ayarları güncellendi.');
    }
}
