<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rapor Merkezi — {{ config('app.name') }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=Playfair+Display:wght@600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="{{ asset('css/report.css') }}" rel="stylesheet">
</head>
<body class="rp-body rp-builder-body">

<div class="rp-toolbar no-print">
    <div class="rp-toolbar-brand">
        <div class="icon"><i class="bi bi-buildings"></i></div>
        <span>Kurumsal Rapor Merkezi</span>
    </div>
    <div class="rp-toolbar-actions">
        <a href="{{ route('panel.index') }}" class="rp-btn rp-btn-outline">
            <i class="bi bi-house"></i> Ana Sayfa
        </a>
    </div>
</div>

<div class="rp-container rp-builder-container">
    <header class="rp-builder-hero">
        <div class="rp-builder-hero-badge"><i class="bi bi-stars"></i> Üst Yönetim Raporlama</div>
        <h1>Lojman Durum Raporu Oluştur</h1>
        <p>Rapor bölümlerini ve grafik türlerini kartlardan seçin. Yüksek çözünürlüklü, kurumsal sunuma hazır çıktı alın.</p>
    </header>

    <form method="GET" action="{{ route('reports.index') }}" id="reportBuilderForm">
        <input type="hidden" name="generate" value="1">

        <section class="rp-builder-section">
            <div class="rp-builder-section-head">
                <span class="rp-step">1</span>
                <div>
                    <h2>Rapor Bölümleri</h2>
                    <p>Sunumda yer alacak kartları işaretleyin</p>
                </div>
            </div>
            <div class="rp-picker-grid">
                @foreach($sectionOptions as $option)
                <label class="rp-picker-card rp-section-card">
                    <input type="checkbox" name="sections[]" value="{{ $option['id'] }}"
                        {{ in_array($option['id'], $defaults['sections'], true) ? 'checked' : '' }}>
                    <span class="rp-picker-card-inner">
                        <span class="rp-picker-icon"><i class="bi {{ $option['icon'] }}"></i></span>
                        <span class="rp-picker-title">{{ $option['label'] }}</span>
                        <span class="rp-picker-desc">{{ $option['description'] }}</span>
                        <span class="rp-picker-check"><i class="bi bi-check-lg"></i></span>
                    </span>
                </label>
                @endforeach
            </div>
        </section>

        @php
            $chartGroups = [
                'room_status_chart' => ['key' => 'room_status', 'title' => 'Oda Durum Dağılımı', 'icon' => 'bi-door-open'],
                'capacity_chart' => ['key' => 'capacity', 'title' => 'Kapasite Kullanımı', 'icon' => 'bi-layers'],
                'block_chart' => ['key' => 'block', 'title' => 'Blok Bazlı Doluluk', 'icon' => 'bi-building'],
                'gender_chart' => ['key' => 'gender', 'title' => 'Cinsiyet Dağılımı', 'icon' => 'bi-people'],
            ];
        @endphp

        @foreach($chartGroups as $inputName => $group)
        <section class="rp-builder-section">
            <div class="rp-builder-section-head">
                <span class="rp-step">{{ $loop->iteration + 1 }}</span>
                <div>
                    <h2><i class="bi {{ $group['icon'] }}"></i> {{ $group['title'] }}</h2>
                    <p>Grafik tipini seçin — yüksek çözünürlükte render edilir</p>
                </div>
            </div>
            <div class="rp-picker-grid rp-picker-grid-chart">
                @foreach($chartTypeOptions as $chart)
                <label class="rp-picker-card rp-chart-type-card">
                    <input type="radio" name="{{ $inputName }}" value="{{ $chart['id'] }}"
                        {{ ($defaults['charts'][$group['key']] ?? 'doughnut') === $chart['id'] ? 'checked' : '' }}>
                    <span class="rp-picker-card-inner">
                        <span class="rp-picker-icon rp-picker-icon-sm"><i class="bi {{ $chart['icon'] }}"></i></span>
                        <span class="rp-picker-title">{{ $chart['label'] }}</span>
                        <span class="rp-picker-desc">{{ $chart['description'] }}</span>
                    </span>
                </label>
                @endforeach
            </div>
        </section>
        @endforeach

        <div class="rp-builder-actions">
            <button type="submit" class="rp-btn rp-btn-primary rp-btn-xl">
                <i class="bi bi-file-earmark-bar-graph"></i> Raporu Oluştur
            </button>
            <p class="rp-builder-hint">Seçimleriniz yeni sekmede kurumsal rapor olarak açılır. Yazdırma ve dışa aktarma seçenekleri rapor ekranındadır.</p>
        </div>
    </form>
</div>

<script>
document.getElementById('reportBuilderForm')?.addEventListener('submit', function (e) {
    const checked = this.querySelectorAll('input[name="sections[]"]:checked');
    if (!checked.length) {
        e.preventDefault();
        alert('Lütfen en az bir rapor bölümü seçin.');
    }
});
</script>
</body>
</html>
