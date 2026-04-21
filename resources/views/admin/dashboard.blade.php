@extends('layouts.app')

@section('title', 'Dashboard - Admin Buku Tamu')

@section('content')
<section class="bg-white soft-border rounded-xl shadow-sm p-8">
    
    <div class="flex flex-col sm:flex-row items-start justify-between gap-6 mb-6">
        <div>
            <h2 class="text-3xl font-extrabold text-gray-900">Dashboard</h2>
            <p class="text-sm text-gray-500 mt-1">Ringkasan aktivitas tamu terbaru</p>
        </div>
        {{-- <div class="flex items-center gap-4">
            <a href="{{ route('tamu.export.pdf') }}" target="_blank" class="inline-flex items-center gap-3 bg-emerald-600 hover:bg-emerald-700 text-white px-4 py-2 rounded-lg shadow-md">
                <img src="{{ asset('assets/icons/Icon_export.svg') }}" class="h-5 w-5" alt="Export Icon" />
                <span class="font-medium">Export Data</span>
            </a>
        </div> --}}
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
        <div class="bg-white soft-border rounded-lg p-6">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-sm text-gray-600">Tamu Hari Ini</p>
                    <div class="mt-3">
                        <span class="text-4xl font-extrabold text-sky-600">{{ $tamuHariIni }}</span>
                        <p class="text-sm text-gray-400 mt-2">{{ now()->format('d F Y') }}</p>
                    </div>
                </div>
                <div class="w-10 h-10 rounded-full bg-sky-50 flex items-center justify-center overflow-hidden">
                    <img src="{{ asset('assets/icons/Icon_Tamu_Harian.svg') }}" alt="ikon tamu hari ini" class="w-5 h-5 object-contain" />
                </div>
            </div>
        </div>
        
        <div class="bg-white soft-border rounded-lg p-6">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-sm text-gray-600">Tamu Bulan Ini</p>
                    <div class="mt-3">
                        <span class="text-4xl font-extrabold text-sky-600">{{ $tamuBulanIni }}</span>
                        <p class="text-sm text-gray-400 mt-2">{{ now()->format('F Y') }}</p>
                    </div>
                </div>
                <div class="w-10 h-10 rounded-full bg-sky-50 flex items-center justify-center overflow-hidden">
                    <img src="{{ asset('assets/icons/Icon_Tamu_Bulan_Ini.svg') }}" alt="ikon tamu bulan ini" class="w-5 h-5 object-contain" />
                </div>
            </div>
        </div>

        <div class="bg-white soft-border rounded-lg p-6">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-sm text-gray-600">Total Tamu</p>
                    <div class="mt-3">
                        <span class="text-4xl font-extrabold text-sky-600">{{ $totalTamu }}</span>
                        <p class="text-sm text-gray-400 mt-2">Sejak awal sistem</p>
                    </div>
                </div>
                <div class="w-10 h-10 rounded-full bg-sky-50 flex items-center justify-center overflow-hidden">
                    <img src="{{ asset('assets/icons/Icon_Total_Tamu.svg') }}" alt="ikon total tamu" class="w-5 h-5 object-contain" />
                </div>
            </div>
        </div>
    </div>

    <div class="bg-white soft-border rounded-lg p-6">
        <div class="flex flex-col sm:flex-row items-start justify-between gap-4 mb-6">
            <div>
                <h3 class="text-xl font-semibold text-gray-900">Statistik Tamu</h3>
                <p class="text-sm text-gray-500 mt-1">Jumlah tamu berdasarkan periode</p>
            </div>
            <div class="flex flex-col items-end gap-3">
                <div class="relative inline-block text-left">
                    <select id="periodSelect" class="border soft-border rounded-md px-3 py-2 text-sm bg-white">
                        <option value="7"  {{ $period == 7  ? 'selected' : '' }}>7 Hari Terakhir</option>
                        <option value="14" {{ $period == 14 ? 'selected' : '' }}>14 Hari Terakhir</option>
                        <option value="30" {{ $period == 30 ? 'selected' : '' }}>1 Bulan Terakhir</option>
                    </select>
                </div>
            </div>
        </div>
        <div class="pt-2" style="height: 240px;">
            <canvas id="visitorsChart"></canvas>
        </div>
    </div>
</section>
@endsection


@push('scripts')
<script>
    const labels = {!! json_encode($labels) !!};
    const values = {!! json_encode($counts) !!};
    const canvas = document.getElementById('visitorsChart');
    
    let visitorsChart; 

    if (canvas) {
        const ctx = canvas.getContext('2d');

        Chart.defaults.font.family = "'Inter', system-ui, -apple-system, 'Segoe UI', Roboto, 'Helvetica Neue', Arial";
        Chart.defaults.color = '#374151';

        function computeDiffs(values) {
            const diffs = [];
            for (let i = 0; i < values.length; i++) {
                if (i === 0) diffs.push(0);
                else diffs.push(values[i] - values[i - 1]);
            }
            return diffs;
        }

        const chartConfig = {
            type: 'bar',
            data: {
                labels: labels, // Data awal saat load
                datasets: [{
                    label: 'Total Kunjungan',
                    data: values, // Data awal saat load
                    backgroundColor: values.map(() => 'rgba(14,116,248,1)'),
                    borderRadius: 6,
                    barThickness: 28
                }]
            },
            options: {
                maintainAspectRatio: false,
                scales: {
                    x: { grid: { display: false }, ticks: { color: '#6b7280' } },
                    y: {
                        beginAtZero: true,
                        ticks: { color: '#6b7280', stepSize: 1 },
                        grid: { color: 'rgba(15,23,42,0.06)', drawBorder: false }
                    }
                },
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        enabled: false,
                        external: function(context) {
                            let tooltipEl = document.getElementById('chartjs-tooltip');
                            if (!tooltipEl) {
                                tooltipEl = document.createElement('div');
                                tooltipEl.id = 'chartjs-tooltip';
                                tooltipEl.className = 'bg-white p-3 rounded-lg shadow-lg text-sm soft-border';
                                tooltipEl.style.pointerEvents = 'none';
                                tooltipEl.style.position = 'absolute';
                                tooltipEl.style.transform = 'translate(-50%, -100%)';
                                tooltipEl.style.transition = 'all .08s ease';
                                document.body.appendChild(tooltipEl);
                            }
                            const tooltipModel = context.tooltip;
                            if (tooltipModel.opacity === 0) {
                                tooltipEl.style.opacity = 0;
                                return;
                            }
                            if (tooltipModel.dataPoints) {
                                const idx = tooltipModel.dataPoints[0].dataIndex;
                                const dataset = context.chart.data.datasets[0].data;
                                const value = dataset[idx];
                                const diffs = computeDiffs(dataset);
                                const diff = diffs[idx];
                                const arrow = diff > 0 ? '▲' : (diff < 0 ? '▼' : '•');
                                const diffText = diff === 0 ? '0' : Math.abs(diff);
                                tooltipEl.innerHTML = `
                                    <div class="text-xs text-slate-400">Total Kunjungan</div>
                                    <div class="mt-1 text-base font-semibold text-slate-800">${value} Tamu</div>
                                    <div class="mt-1 text-sm ${diff > 0 ? 'text-green-600' : 'text-red-500'}">${arrow} ${diffText} dibanding sebelumnya</div>
                                `;
                            }
                            const rect = context.chart.canvas.getBoundingClientRect();
                            const caretX = rect.left + tooltipModel.caretX;
                            const caretY = rect.top + tooltipModel.caretY;
                            tooltipEl.style.left = caretX + 'px';
                            tooltipEl.style.top = (caretY - 10) + 'px';
                            tooltipEl.style.opacity = 1;
                        }
                    }
                },
                interaction: { intersect: true, mode: 'nearest' },
                onHover: (event, elements, chart) => {
                    const dataset = chart.data.datasets[0];
                    if (elements.length) {
                        const hoveredIndex = elements[0].index;
                        dataset.backgroundColor = dataset.data.map((_, i) =>
                            i === hoveredIndex ? 'rgba(14,116,248,1)' : 'rgba(14,116,248,0.3)'
                        );
                    } else {
                        dataset.backgroundColor = dataset.data.map(() => 'rgba(14,116,248,1)');
                    }
                    chart.update('none');
                }
            }
        };

        visitorsChart = new Chart(ctx, chartConfig);

        const periodSelect = document.getElementById('periodSelect');
        
        if (periodSelect) {
            periodSelect.addEventListener('change', async function() {
                const period = this.value;
                
                // 1. Tampilkan status loading
                periodSelect.disabled = true;
                visitorsChart.data.labels = ['Memuat data...'];
                visitorsChart.data.datasets[0].data = [];
                visitorsChart.update();

                try {
                    // 2. Panggil endpoint API
                    const response = await fetch(`{{ route('dashboard.chartdata') }}?period=${period}`);
                    
                    if (!response.ok) {
                        throw new Error('Gagal mengambil data');
                    }
                    
                    const data = await response.json(); 

                    // 3. Update chart dengan data baru
                    visitorsChart.data.labels = data.labels;
                    visitorsChart.data.datasets[0].data = data.counts;
                    visitorsChart.update();

                    // 4. Update URL di browser tanpa reload
                    const currentUrl = new URL(window.location.href);
                    currentUrl.searchParams.set('period', period);
                    window.history.pushState(null, '', currentUrl.toString());

                } catch (error) {
                    console.error('Error fetching chart data:', error);
                    visitorsChart.data.labels = ['Gagal memuat data'];
                    visitorsChart.update();
                } finally {
                    // 5. Selesai loading (selalu jalankan)
                    periodSelect.disabled = false;
                }
            });
        }
        
    } 

</script>
@endpush