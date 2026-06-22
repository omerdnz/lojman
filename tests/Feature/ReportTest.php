<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ReportTest extends TestCase
{
    use RefreshDatabase;

    public function test_reports_builder_page_renders_card_selection(): void
    {
        $this->seed();

        $admin = User::query()->where('username', 'admin')->first();

        $response = $this->actingAs($admin)->get(route('reports.index'));

        $response->assertOk();
        $response->assertSee('Kurumsal Rapor Merkezi', false);
        $response->assertSee('Rapor Bölümleri', false);
        $response->assertSee('Raporu Oluştur', false);
        $response->assertSee('name="sections[]"', false);
        $response->assertSee('room_status_chart', false);
    }

    public function test_reports_generate_page_with_selected_charts(): void
    {
        $this->seed();

        $admin = User::query()->where('username', 'admin')->first();

        $response = $this->actingAs($admin)->get(route('reports.index', [
            'generate' => 1,
            'sections' => ['kpi', 'executive', 'charts'],
            'room_status_chart' => 'bar',
            'capacity_chart' => 'doughnut',
            'block_chart' => 'line',
            'gender_chart' => 'pie',
        ]));

        $response->assertOk();
        $response->assertSee('Yönetici Özeti', false);
        $response->assertSee('chartRoomStatus', false);
        $response->assertSee('RpChartConfig', false);
        $response->assertSee('"room_status":"bar"', false);
    }
}
