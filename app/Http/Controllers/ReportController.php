<?php

namespace App\Http\Controllers;

use App\Services\ReportService;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\View\View;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ReportController extends Controller
{
    private const CHART_TYPES = ['doughnut', 'pie', 'bar', 'line', 'polarArea', 'radar'];

    private const SECTIONS = ['kpi', 'executive', 'charts', 'table', 'cards', 'unassigned'];

    public function __construct(
        private readonly ReportService $reportService,
    ) {}

    public function index(Request $request): View
    {
        if (! $request->boolean('generate')) {
            return view('reports.builder', [
                'chartTypeOptions' => $this->chartTypeOptions(),
                'sectionOptions' => $this->sectionOptions(),
                'defaults' => $this->defaultSelections(),
            ]);
        }

        return view('reports.show', $this->reportPayload($request));
    }

    public function excel(): StreamedResponse
    {
        $occupancy = $this->reportService->occupancyReport();
        $unassigned = $this->reportService->unassignedReport();
        $summary = $this->reportService->summary();

        $spreadsheet = new Spreadsheet;
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Doluluk Raporu');

        $sheet->mergeCells('A1:H1');
        $sheet->setCellValue('A1', 'LOJMAN GENEL DURUM RAPORU');
        $sheet->getStyle('A1')->applyFromArray([
            'font' => ['bold' => true, 'size' => 14, 'color' => ['rgb' => 'FFFFFF']],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '0F172A']],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
        ]);

        $sheet->mergeCells('A2:H2');
        $sheet->setCellValue('A2', 'Oluşturulma: '.$summary['generated_at']);

        $summaryRows = [
            ['Metrik', 'Değer'],
            ['Toplam Personel', $summary['total_employees']],
            ['Yerleşmiş', $summary['assigned']],
            ['Atanmamış', $summary['unassigned']],
            ['Toplam Oda', $summary['total_rooms']],
            ['Toplam Kapasite', $summary['total_capacity']],
            ['Dolu Yatak', $summary['total_occupied']],
            ['Doluluk Oranı (%)', $summary['occupancy_rate']],
        ];
        $sheet->fromArray($summaryRows, null, 'A4');

        $headerRow = 14;
        $headers = ['Oda No', 'Blok', 'Kat', 'Cinsiyet', 'Kapasite', 'Dolu', 'Boş', 'Sakinler'];
        $sheet->fromArray($headers, null, 'A'.$headerRow);

        $row = $headerRow + 1;
        foreach ($occupancy as $room) {
            $occupants = collect($room['occupants'])->pluck('full_name')->implode(', ');
            $sheet->fromArray([
                $room['room_number'],
                $room['block'],
                $room['floor'],
                $room['gender'],
                $room['capacity'],
                $room['occupied'],
                $room['available'],
                $occupants,
            ], null, 'A'.$row);
            $row++;
        }

        $sheet2 = $spreadsheet->createSheet();
        $sheet2->setTitle('Atanmamış');
        $sheet2->fromArray(['Sicil', 'Ad Soyad', 'Cinsiyet', 'Departman'], null, 'A1');
        $row = 2;
        foreach ($unassigned as $emp) {
            $sheet2->fromArray([
                $emp['personnel_number'],
                $emp['full_name'],
                $emp['gender'],
                $emp['department'],
            ], null, 'A'.$row);
            $row++;
        }

        $writer = new Xlsx($spreadsheet);

        return response()->streamDownload(function () use ($writer) {
            $writer->save('php://output');
        }, 'lojman_rapor_'.date('Y-m-d').'.xlsx', [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        ]);
    }

    public function pdf(): Response
    {
        $summary = $this->reportService->summary();
        $occupancy = $this->reportService->occupancyReport();
        $unassigned = $this->reportService->unassignedReport();
        $chartData = $this->reportService->chartDatasets();
        $executive = $this->reportService->executiveSummary($summary, $chartData['room_counts']);

        $pdf = Pdf::loadView('reports.pdf', compact('summary', 'occupancy', 'unassigned', 'chartData', 'executive'))
            ->setPaper('a4', 'landscape');

        return $pdf->download('lojman_rapor_'.date('Y-m-d').'.pdf');
    }

    /**
     * @return array<string, mixed>
     */
    private function reportPayload(Request $request): array
    {
        $summary = $this->reportService->summary();
        $occupancy = $this->reportService->occupancyReport();
        $unassigned = $this->reportService->unassignedReport();
        $chartData = $this->reportService->chartDatasets();
        $roomCounts = $chartData['room_counts'];

        return [
            'summary' => $summary,
            'occupancy' => $occupancy,
            'unassigned' => $unassigned,
            'chartData' => $chartData,
            'executive' => $this->reportService->executiveSummary($summary, $roomCounts),
            'sections' => $this->parseSections($request),
            'charts' => [
                'room_status' => $this->validatedChartType($request, 'room_status_chart'),
                'capacity' => $this->validatedChartType($request, 'capacity_chart'),
                'block' => $this->validatedChartType($request, 'block_chart'),
                'gender' => $this->validatedChartType($request, 'gender_chart'),
            ],
            'fullRooms' => $roomCounts['full'],
            'emptyRooms' => $roomCounts['empty'],
            'warningRooms' => $roomCounts['warning'],
            'partialRooms' => $roomCounts['partial'],
            'exportQuery' => $request->query(),
        ];
    }

    /**
     * @return list<string>
     */
    private function parseSections(Request $request): array
    {
        $raw = $request->input('sections', implode(',', self::SECTIONS));
        $parts = is_array($raw) ? $raw : explode(',', (string) $raw);

        $selected = array_values(array_intersect($parts, self::SECTIONS));

        return $selected !== [] ? $selected : self::SECTIONS;
    }

    private function validatedChartType(Request $request, string $key, string $default = 'doughnut'): string
    {
        $type = (string) $request->input($key, $default);

        return in_array($type, self::CHART_TYPES, true) ? $type : $default;
    }

    /**
     * @return list<array{id: string, label: string, icon: string, description: string}>
     */
    private function chartTypeOptions(): array
    {
        return [
            ['id' => 'doughnut', 'label' => 'Halka', 'icon' => 'bi-record-circle', 'description' => 'Yönetim sunumları için ideal'],
            ['id' => 'pie', 'label' => 'Pasta', 'icon' => 'bi-pie-chart', 'description' => 'Oran dağılımını vurgular'],
            ['id' => 'bar', 'label' => 'Çubuk', 'icon' => 'bi-bar-chart', 'description' => 'Karşılaştırma için net'],
            ['id' => 'line', 'label' => 'Çizgi', 'icon' => 'bi-graph-up', 'description' => 'Trend hissi verir'],
            ['id' => 'polarArea', 'label' => 'Kutupsal', 'icon' => 'bi-bullseye', 'description' => 'Gösterişli dağılım'],
            ['id' => 'radar', 'label' => 'Radar', 'icon' => 'bi-diagram-3', 'description' => 'Çoklu metrik özeti'],
        ];
    }

    /**
     * @return list<array{id: string, label: string, icon: string, description: string}>
     */
    private function sectionOptions(): array
    {
        return [
            ['id' => 'kpi', 'label' => 'KPI Kartları', 'icon' => 'bi-speedometer2', 'description' => 'Üst düzey sayısal göstergeler'],
            ['id' => 'executive', 'label' => 'Yönetici Özeti', 'icon' => 'bi-briefcase', 'description' => 'Karar vericiler için kısa değerlendirme'],
            ['id' => 'charts', 'label' => 'Görsel Analiz', 'icon' => 'bi-pie-chart-fill', 'description' => 'Seçilen grafik türleriyle analiz'],
            ['id' => 'table', 'label' => 'Detay Tablosu', 'icon' => 'bi-table', 'description' => 'Oda bazlı doluluk listesi'],
            ['id' => 'cards', 'label' => 'Oda Kartları', 'icon' => 'bi-grid-3x3-gap', 'description' => 'Kart görünümünde oda özeti'],
            ['id' => 'unassigned', 'label' => 'Atanmamış Personel', 'icon' => 'bi-person-dash', 'description' => 'Yerleştirme bekleyen personel'],
        ];
    }

    /**
     * @return array{sections: list<string>, charts: array<string, string>}
     */
    private function defaultSelections(): array
    {
        return [
            'sections' => ['kpi', 'executive', 'charts', 'table', 'unassigned'],
            'charts' => [
                'room_status' => 'doughnut',
                'capacity' => 'bar',
                'block' => 'bar',
                'gender' => 'pie',
            ],
        ];
    }
}
