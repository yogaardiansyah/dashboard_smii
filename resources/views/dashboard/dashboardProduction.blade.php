<x-app-layout>
    @section('title')
        Dashboard Production
    @endsection

    @include('layouts.partials.vendor.echarts')
    @include('layouts.partials.vendor.chartjs')

    @php
        function getLineColor($line)
        {
            $colors = [
                'A' => 'rgb(96 165 250)',
                'B' => 'rgb(248 113 113)',
                'C' => 'rgb(216 180 254)',
                'D' => 'rgb(134 239 172)',
                'E' => 'rgb(253 186 116)',
                'F' => 'rgb(167 139 250)',
            ];
            return $colors[$line] ?? '';
        }

        function getLineName($line)
        {
            $names = [
                'A' => 'Liquid',
                'B' => 'Pastry',
                'C' => 'P1',
                'D' => 'P2',
                'E' => 'P3',
                'F' => 'P4',
            ];
            return $names[$line] ?? $line;
        }
    @endphp

    <div class="h-full flex flex-col p-4">
        <div class=" card box-header flex items-center justify-center gap-8 py-2">
            <img src="{{ asset('assets/images/logo/smii.png') }}" alt="SMII Logo"
                style="height:80px; width:auto; display:block;">
            <h3 class="text-5xl font-medium text-center">Dashboard Production</h3>
            <img src="{{ asset('assets/images/logo/sindy.png') }}" alt="K3 Logo"
                style="height:80px; width:auto; display:block;">
        </div>
        <div class="flex justify-between mb-4">
            <div class="box pull-up w-1/3 mr-2">
                <div class="box-body h-36">
                    <div class="flex justify-between items-center">
                        <div class="bs-5 ps-10 border-info">
                            <p class="text-fade mb-10 text-3xl">Total Production (YTD)</p>
                            <h2 id="yearProduction" class="my-0 fw-700 text-4xl"></h2>
                        </div>
                        <div class="icon">
                            <i class="fa-solid fa-calendar bg-info-light me-0 fs-24 rounded-3"></i>
                        </div>
                    </div>
                    <p id="yearComparison" class="text-danger mb-0 mt-10"><i class="fa-solid fa-arrow-down"></i></p>
                </div>
            </div>
            <div class="box pull-up w-1/3 mx-2">
                <div class="box-body h-36">
                    <div class="flex justify-between items-center">
                        <div class="bs-5 ps-10 border-info">
                            <p class="text-fade mb-10 text-3xl">Total Production (MTD)</p>
                            <h2 id="weightThisMonth" class="my-0 fw-700 text-4xl"></h2>
                        </div>
                        <div class="icon">
                            <i class="fa-solid fa-box bg-info-light me-0 fs-24 rounded-3"></i>
                        </div>
                    </div>
                    <p id="weightComparison" class="text-danger mb-0 mt-10"><i class="fa-solid fa-arrow-down"></i></p>
                </div>
            </div>
            <div class="box pull-up w-1/3 ml-2">
                <div class="box-body h-36">
                    <div class="flex justify-between items-center">
                        <div class="bs-5 ps-10 border-info">
                            <p class="text-fade mb-10 text-3xl">Total Quantity (MTD)</p>
                            <h2 id="qtyThisMonth" class="my-0 fw-700 text-4xl"></h2>
                        </div>
                        <div class="icon">
                            <i class="fa-solid fa-boxes-stacked bg-info-light me-0 fs-24 rounded-3"></i>
                        </div>
                    </div>
                    <p id="qtyComparison" class="text-danger mb-0 mt-10"><i class="fa-solid fa-arrow-down"></i></p>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-5 gap-x-4 mb-4 flex-grow">
            <div class="col-span-2 flex flex-col h-full">
                <div class="box rounded-2xl flex-grow h-full">
                    <div class="box-body analytics-info" style="height:100%; display:flex; flex-direction:column; min-height:0; overflow:hidden;">
                        <div class="flex justify-between">
                            <div class="text-5xl font-medium">Marsho Production (Year)</div>
                            <div class="flex">
                                <select id="yearFilterYear" class="mr-2 text-3xl rounded text-black">
                                    @php
                                        $currentYear = date('Y');
                                    @endphp
                                    <option value="{{ $currentYear }}" class="" disabled selected hidden>
                                        {{ $currentYear }}</option>
                                    @foreach ($availableYears as $year)
                                        <option value="{{ $year }}">{{ $year }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div id="yearChart" style="width:100%; height:100%; min-height:450px; flex:1; min-width:0;"></div>
                    </div>
                </div>
            </div>

            <div class="col-span-3 flex flex-col h-full">
                <div class="box rounded-2xl flex-grow h-full">
                    <div class="box-body analytics-info" style="height:100%; display:flex; flex-direction:column; min-height:0; overflow:hidden;">
                        <div class="flex justify-between">
                            <div class="text-5xl font-medium">Marsho Production (Month)</div>
                            <div class="flex">
                                <select id="yearFilterBar" class="mr-2 text-3xl rounded text-black">
                                    @php
                                        $currentYear = date('Y');
                                    @endphp
                                    <option value="" class="" disabled selected hidden>{{ $currentYear }}
                                    </option>
                                    @foreach ($availableYears as $year)
                                        <option value="{{ $year }}">{{ $year }}</option>
                                    @endforeach
                                </select>
                                <select id="monthFilterBar" class="mr-2 text-3xl rounded text-black">
                                    <option value="" class="" disabled selected hidden>{{ date('F') }}
                                    </option>
                                    @for ($i = 1; $i <= 12; $i++)
                                        <option value="{{ $i }}">{{ date('F', mktime(0, 0, 0, $i, 1)) }}
                                        </option>
                                    @endfor
                                </select>
                            </div>
                        </div>
                        <div id="barChart" style="width:100%; height:100%; min-height:450px; flex:1; min-width:0;"></div>
                    </div>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-5 gap-x-4 flex-grow">
            <div class="col-span-2 flex flex-col">
                <div class="card rounded-2xl flex-grow">
                    <div class="box-header items-center">
                        <div class="flex justify-between">
                            <h4 class="font-medium text-5xl">Marsho Line Production</h4>
                            <button id="dateDisplay"
                                class="waves-effect waves-secondary btn btn-outline dropdown-toggle btn-md text-2xl"
                                style="padding: 20px 30px; font-size: 24px;" data-bs-toggle="dropdown" href="#"
                                aria-expanded="false">
                                {{ date('d F Y') }}
                            </button>
                            <div class="dropdown-menu dropdown-menu-end" style="will-change: transform;">
                                <div class="px-3 py-2">
                                    <input type="date" id="dateFilterDropdown"
                                        class="bg-gray-200 text-black text-xl" value="{{ date('Y-m-d') }}">
                                    <button id="applyDateFilterDropdown" class=" text-white px-2 py-1 mt-2"></button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="table-responsive flex-grow overflow-auto">
                        <table class="table mb-0 w-full table-hover table-striped table-bordered">
                            <thead>
                                <tr>
                                    <th class="text-2xl">Line</th>
                                    <th class="text-center text-2xl">Shift 2</th>
                                    <th class="text-center text-2xl">Shift 3</th>
                                    <th class="text-center text-2xl">Shift 1</th>
                                    <th class="text-center text-2xl">Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td colspan="5" class="text-center text-2xl">Tidak ada data untuk ditampilkan.
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="col-span-3 flex flex-col">
                <div class="box rounded-2xl flex-grow">
                    <div class="box-body analytics-info">
                        <div class="flex justify-between">
                            <div class="text-5xl font-medium">Marsho Line Daily Utilization</div>
                        </div>
                        <div class="grid grid-cols-3 gap-x-2 gap-y-4 mt-2">
                            @foreach (['A', 'B', 'C', 'D', 'E', 'F'] as $line)
                                <div class="card rounded-2xl pull-up"
                                    style="border:2px solid {{ getLineColor($line) }}">
                                    <div class="box-header text-center">
                                        <h3 class="box-title m-0 text-3xl">{{ getLineName($line) }}</h3>
                                    </div>
                                    <div class="flex justify-center items-center" style="height: 200px;">
                                        <div id="line{{ $line }}Chart" style="width:100%; height:100%;">
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            // Inisialisasi ECharts dengan ukuran yang disesuaikan
            var barChartContainer = document.getElementById('barChart');
            var barChart = echarts.init(barChartContainer, null, {
                width: 'auto',
                height: 'auto'
            });
            var yearChart = echarts.init(document.getElementById('yearChart'), null, {
                width: 'auto',
                height: 'auto'
            });

            function resizeDashboardCharts() {
                if (barChartContainer && barChartContainer.parentElement) {
                    const barContainerHeight = barChartContainer.parentElement.clientHeight;
                    if (barContainerHeight > 0) {
                        barChartContainer.style.height = `${Math.max(barContainerHeight - 10, 450)}px`;
                    }
                }

                if (document.getElementById('yearChart') && document.getElementById('yearChart').parentElement) {
                    const yearContainerHeight = document.getElementById('yearChart').parentElement.clientHeight;
                    if (yearContainerHeight > 0) {
                        document.getElementById('yearChart').style.height = `${Math.max(yearContainerHeight - 10, 450)}px`;
                    }
                }

                barChart.resize();
                yearChart.resize();
            }

            // Fungsi untuk membuat chart responsif
            function makeChartsResponsive() {
                window.addEventListener('resize', function() {
                    resizeDashboardCharts();
                });
            }

            // Panggil fungsi untuk membuat chart responsif
            makeChartsResponsive();

            // Inisialisasi untuk setiap Line (A, B, C, D. E, F) Gauge Chart
            var doughnutData;
            const myCharts = {};

            // Line A, B, C, D, E, F
            const lines = ['A', 'B', 'C', 'D', 'E', 'F'];

            // Friendly names for display (used in legend, tooltips, and table rows)
            const LINE_NAMES = {
                'A': 'Liquid',
                'B': 'Pastry',
                'C': 'P1',
                'D': 'P2',
                'E': 'P3',
                'F': 'P4'
            };

            // =================================================================
            // >> KODE BARU: LOGIKA UNTUK RESET FILTER OTOMATIS
            // =================================================================
            let filterResetTimer; // Variabel untuk menyimpan ID timer

            // Fungsi untuk mereset semua filter ke tanggal & waktu saat ini
            function resetFiltersToCurrent() {
                console.log('30 detik tidak aktif, filter direset ke waktu saat ini.');

                const now = new Date();
                const currentYear = now.getFullYear();
                const currentMonth = now.getMonth() + 1; // getMonth() 0-11, jadi +1
                const currentDate = now.toISOString().split('T')[0]; // Format YYYY-MM-DD

                // Set nilai filter ke waktu saat ini
                document.getElementById('yearFilterYear').value = currentYear;
                document.getElementById('yearFilterBar').value = currentYear;
                document.getElementById('monthFilterBar').value = currentMonth;
                document.getElementById('dateFilterDropdown').value = currentDate;

                // Panggil fungsi update agar chart dan tabel diperbarui
                updateYearChart();
                updateBarChart();
                updateFilterDropdown();
            }

            // Fungsi untuk memulai atau mereset timer
            function startFilterResetTimer() {
                clearTimeout(filterResetTimer); // Hapus timer yang ada
                filterResetTimer = setTimeout(resetFiltersToCurrent, 30000); // Set timer baru 30 detik
            }
            // =================================================================
            // << AKHIR DARI KODE BARU
            // =================================================================


            document.addEventListener('DOMContentLoaded', function() {
                fetchDashboardData();
                updateBarChart();
                updateFilterDropdown();
                createInterval();
                updateYearChart();
                setTimeout(function() {
                    resizeDashboardCharts();
                }, 100);

                // =================================================================
                // >> KODE BARU: MENAMBAHKAN EVENT LISTENER PADA SEMUA FILTER
                // =================================================================
                const filtersToMonitor = [
                    document.getElementById('yearFilterYear'),
                    document.getElementById('yearFilterBar'),
                    document.getElementById('monthFilterBar'),
                    document.getElementById('dateFilterDropdown')
                ];

                filtersToMonitor.forEach(filter => {
                    if (filter) {
                        filter.addEventListener('change', startFilterResetTimer);
                    }
                });
                // =================================================================
                // << AKHIR DARI KODE BARU
                // =================================================================

            }, {
                passive: true
            });

            function createInterval() {
                setInterval(() => {
                    updateBarChart();
                    updateYearChart();
                    updateFilterDropdown();
                }, 5000);
            }

            function fetchDashboardData() {
                fetch('/get-dashboard-production')
                    .then(response => response.json())
                    .then(data => {
                        // console.log('Dashboard Data:', data);


                        // Inisialisasi standard data
                        doughnutData = data.gaugeStandarData;

                        // Pastikan doughnutData terdefinisi sebelum memanggil createGaugeChart
                        if (doughnutData) {
                            createGaugeChart();
                        } else {
                            console.error('doughnutData is undefined');
                        }
                    })
                    .catch(error => console.error('Error fetching dashboard data:', error));
            }

            function createGaugeChart() {
                if (!doughnutData) {
                    console.error('doughnutData is undefined');
                    return;
                }

                lines.forEach(line => {
                    const ctx = document.getElementById(`line${line}Chart`);
                    if (ctx) {
                        myCharts[line] = echarts.init(ctx, null, {
                            width: 'auto',
                            height: 'auto'
                        });

                        const standardValue = parseFloat(doughnutData[line]);
                        if (isNaN(standardValue)) {
                            console.error(`Standard value for line ${line} is undefined or not a number`);
                            return;
                        }

                        const maxValue = standardValue * 1.5;
                        const actualValue = 0;

                        // Helper untuk format nilai ke 'K'
                        function formatStandard(val) {
                            if (val >= 1000) {
                                return (val / 1000).toLocaleString('id-ID', {
                                    maximumFractionDigits: 1
                                }) + 'K';
                            }
                            return val.toLocaleString('id-ID');
                        }

                        const option = {
                            series: [{
                                type: 'gauge',
                                max: maxValue,
                                center: ['50%', '50%'],
                                radius: '100%',
                                axisLine: {
                                    lineStyle: {
                                        width: 20,
                                        color: [
                                            [standardValue / maxValue, '#3498db'],
                                            [1, '#e74c3c']
                                        ]
                                    }
                                },
                                pointer: {
                                    itemStyle: {
                                        color: 'auto'
                                    }
                                },
                                axisTick: {
                                    distance: -20,
                                    length: 8,
                                    lineStyle: {
                                        color: '#fff',
                                        width: 2
                                    }
                                },
                                splitLine: {
                                    distance: -20,
                                    length: 20,
                                    lineStyle: {
                                        color: '#fff',
                                        width: 4
                                    }
                                },
                                axisLabel: {
                                    color: 'inherit',
                                    distance: -60,
                                    fontSize: 10,
                                    rotate: 'tangential',
                                    formatter: function(value) {
                                        if (Math.abs(value - standardValue) < 0.0001) {
                                            return formatStandard(standardValue);
                                        }
                                        return '';
                                    }
                                },
                                detail: {
                                    valueAnimation: true,
                                    formatter: function(value) {
                                        return value.toLocaleString('id-ID') + ' kg';
                                    },
                                    color: 'inherit',
                                    fontSize: 20,
                                    offsetCenter: [0, '50%']
                                },
                                data: [{
                                    value: actualValue
                                }]
                            }],
                            // Tambahkan teks custom di bawah gauge
                            graphic: [{
                                type: 'text',
                                left: 'center',
                                top: '90%', // posisinya di bawah gauge
                                style: {
                                    text: 'Standard: ' + formatStandard(standardValue),
                                    fill: '#59AC77',
                                    fontSize: 18,
                                    fontWeight: 'bold'
                                }
                            }]
                        };

                        myCharts[line].setOption(option);

                        window.addEventListener('resize', function() {
                            myCharts[line].resize();
                        });
                    } else {
                        console.error(`Element with ID line${line}Chart not found.`);
                    }
                });
            }


            // Event listener untuk tombol filter tanggal di dropdown
            document.getElementById('applyDateFilterDropdown').addEventListener('click', function() {
                updateFilterDropdown();
            });

            function updateFilterDropdown() {
                const selectedDate = document.getElementById('dateFilterDropdown').value;
                // Mengubah teks tombol untuk menampilkan tanggal yang dipilih
                const formattedDate = new Date(selectedDate).toLocaleDateString('id-ID', {
                    day: '2-digit',
                    month: 'long',
                    year: 'numeric'
                });
                document.getElementById('dateDisplay').innerText = formattedDate;

                // Fetch dan update data berdasarkan tanggal yang dipilih
                fetch(`/data-filter?date=${selectedDate}`)
                    .then(response => response.json())
                    .then(data => {
                        // console.log('Data Filter:', data);
                        // Kosongkan tabel sebelum menambahkan data baru
                        const tbody = document.querySelector('table tbody');
                        tbody.innerHTML = '';

                        // Pastikan data memiliki struktur yang benar
                        if (data && data.data && Array.isArray(data.data) && data.data.length > 0) {
                            const totals = {};
                            const lines = ['A', 'B', 'C', 'D', 'E', 'F']; // Daftar line yang akan ditampilkan

                            // Inisialisasi totals untuk semua line
                            lines.forEach(line => {
                                totals[line] = {
                                    shift1: 0,
                                    shift2: 0,
                                    shift3: 0,
                                    total: 0
                                };
                            });

                            // Hitung total per line dan shift
                            data.data.forEach(item => {
                                if (lines.includes(item.line)) {
                                    const shiftKey = `shift${item.shift.slice(-1)}`;
                                    totals[item.line][shiftKey] += parseFloat(item.total_weight);
                                    totals[item.line].total += parseFloat(item.total_weight);
                                }
                            });

                            // Tampilkan data ke dalam tabel
                            lines.forEach(line => {
                                const shifts = totals[line];
                                const row = document.createElement('tr');
                                row.innerHTML = `
                                <td class="pt-0 px-0 b-0 border-b">
                                    <div class="flex items-center">
                                        <div class="w-10 h-50 rounded ${getLineColor(line)}"></div>
                                        <span class="text-fade text-2xl ml-2 font-semibold">${LINE_NAMES[line] || line}</span>
                                    </div>
                                </td>
                                <td class="text-right b-0 pt-0 px-0 border-b">
                                    <span class="text-fade text-2xl mr-2">${shifts.shift2.toLocaleString('id-ID')} kg</span>
                                </td>
                                <td class="text-right b-0 pt-0 px-0 border-b">
                                    <span class="text-fade text-2xl mr-2">${shifts.shift3.toLocaleString('id-ID')} kg</span>
                                </td>
                                <td class="text-right b-0 pt-0 px-0 border-b">
                                    <span class="text-fade text-2xl mr-2">${shifts.shift1.toLocaleString('id-ID')} kg</span>
                                </td>
                                <td class="text-right b-0 pt-0 px-0 border-b">
                                    <span class="text-fade text-2xl flex justify-end mr-2">${shifts.total.toLocaleString('id-ID')} kg</span>
                                </td>
                            `;
                                tbody.appendChild(row);

                                // Update Gauge Chart
                                updateGaugeChart(line, shifts.total);
                            });

                            // Tambahkan baris total per shift
                            addTotalPerShiftRow(totals, tbody);

                            // Tambahkan baris grand total
                            addGrandTotalRow(totals, tbody);
                        } else {
                            // console.log("Tidak ada data yang tersedia");
                            // Update Gauge Chart to 0 if no data
                            lines.forEach(line => {
                                updateGaugeChart(line, 0); // Set to 0 if no data
                            });
                        }
                    })
                    .catch(error => console.error('Error fetching filtered data:', error));
            }

            function getLineColor(line) {
                const colors = {
                    'A': 'bg-blue-400',
                    'B': 'bg-red-400',
                    'C': 'bg-purple-300',
                    'D': 'bg-green-300',
                    'E': 'bg-orange-300',
                    'F': 'bg-purple-600'
                };
                return colors[line] || '';
            }

            function updateGaugeChart(line, totalWeight) {
                if (myCharts[line]) {
                    myCharts[line].setOption({
                        series: [{
                            axisLine: {
                                lineStyle: {
                                    width: 20, // Adjusted width
                                    color: [
                                        [0.3, '#e74c3c'],
                                        [0.7, '#f1c40f'],
                                        [1, '#3498db']
                                    ]
                                }
                            },
                            data: [{
                                value: totalWeight.toFixed(2)
                            }]
                        }]
                    });
                }
            }

            function addTotalPerShiftRow(totals, tbody) {
                const totalPerShift = Object.values(totals).reduce((acc, curr) => {
                    acc.shift1 += curr.shift1;
                    acc.shift2 += curr.shift2;
                    acc.shift3 += curr.shift3;
                    return acc;
                }, {
                    shift1: 0,
                    shift2: 0,
                    shift3: 0
                });

                const totalPerShiftRow = document.createElement('tr');
                totalPerShiftRow.innerHTML = `
                                <td class="text-right text-3xl" colspan="1"><strong>Total</strong></td>
                                <td class="text-right text-3xl"><strong>${totalPerShift.shift2.toLocaleString('id-ID')} kg</strong></td>
                                <td class="text-right text-3xl"><strong>${totalPerShift.shift3.toLocaleString('id-ID')} kg</strong></td>
                                <td class="text-right text-3xl"><strong>${totalPerShift.shift1.toLocaleString('id-ID')} kg</strong></td>
                                <td class="text-left text-3xl flex justify-end mr-2"><strong>${(totalPerShift.shift1 + totalPerShift.shift2 + totalPerShift.shift3).toLocaleString('id-ID')} kg</strong></td>
                            `;
                tbody.appendChild(totalPerShiftRow);
            }

            function addGrandTotalRow(totals, tbody) {
                const grandTotal = Object.values(totals).reduce((acc, curr) => acc + curr.total, 0);
                const grandTotalRow = document.createElement('tr');
                tbody.appendChild(grandTotalRow);
            }

            // Fungsi untuk mengatur opsi chart
            function setBarChartOption(label, actualData, standardData, actualHeight) {
                var option;

                const rawData = actualData;

                // Jika label berupa ISO date (YYYY-MM-DD), tampilkan hanya nomor hari (1,2,3,..)
                const displayLabels = label.map(l => {
                    if (/^\d{4}-\d{2}-\d{2}$/.test(l)) {
                        const d = new Date(l);
                        if (!isNaN(d)) return String(d.getDate());
                        // fallback: ambil bagian terakhir setelah '-'
                        return l.replace(/^.*-/, '');
                    }
                    return l;
                });
                const grid = {
                    left: 100,
                    right: 100,
                    top: 120, // Tambah ruang untuk legend di atas
                    bottom: 80 // Tambah ruang bawah agar label tidak bertabrakan
                };

                // Deteksi mode gelap
                const isDarkMode = localStorage.getItem('darkMode');

                const series = ['A', 'B', 'C', 'D', 'E', 'F'].map((name, sid) => {
                    // use friendly display name for legend/tooltip, fallback to short name
                    const displayName = LINE_NAMES[name] || name;
                    return {
                        name: displayName,
                        type: 'bar',
                        stack: 'total',
                        barWidth: '60%',
                        label: {
                            show: false
                        },
                        data: rawData[sid],
                        markLine: {
                            data: [{
                                yAxis: standardData[0],
                                label: {
                                    formatter: function(params) {
                                        return params.value.toLocaleString('id-ID');
                                    },
                                    color: '#A9A9A9',
                                    fontSize: 20
                                }
                            }],
                            lineStyle: {
                                color: 'red',
                                type: 'dashed',
                            }
                        }
                    };
                });

                option = {
                    tooltip: {
                        trigger: 'axis',
                        axisPointer: {
                            type: 'shadow'
                        },
                        formatter: function(params) {
                            let tooltipText = '';
                            let total = 0;

                            // Mengonversi param.value ke angka dan menjumlahkannya ke total
                            params.forEach(param => {
                                const value = parseFloat(param.value); // Konversi string ke angka
                                total += value; // Tambahkan nilai ke total
                                tooltipText +=
                                    `<span style="color:${param.color}">${param.seriesName}: ${value.toLocaleString('id-ID')}</span><br/>`;
                            });

                            // Menampilkan total di bagian akhir tooltip
                            tooltipText +=
                                `<span style="color:#000;font-weight:bold">Total: ${total.toLocaleString('id-ID')}</span>`;

                            return tooltipText;
                        }
                    },
                    color: ['#609CFA', '#F87171', '#D8B4FE', '#86EFAC', '#FDBA74', '#A78BFA'],
                    legend: {
                        selectedMode: true,
                        textStyle: {
                            fontSize: 20,
                            color: '#A9A9A9',
                        },
                        top: 30, // Pindahkan legend ke atas grid
                        left: 'center',
                        itemGap: 30 // Jarak antar item legend
                    },
                    grid,
                    yAxis: {
                        type: 'value',
                        min: 0,
                        max: Math.round(Math.max(Number(actualHeight) || 0, Array.isArray(standardData) && standardData.length > 0 ? Number(standardData[0]) : Number(standardData) || 0) * 1.03) || null,
                        axisLabel: {
                            fontSize: 17,
                            formatter: function(value) {
                                return value.toLocaleString('id-ID'); // Memformat angka dengan titik
                            },
                            color: '#A9A9A9', // Warna label berdasarkan mode
                        }
                    },
                    xAxis: {
                        type: 'category',
                        data: displayLabels,
                        axisLabel: {
                            fontSize: 15,
                            color: '#A9A9A9', // Warna label berdasarkan mode
                        }
                    },
                    series
                };

                // Menggunakan opsi yang telah ditentukan untuk menampilkan chart
                option && barChart.setOption(option);
            }

            // Update Bar Chart berdasarkan bulan dan minggu yang dipilih
            document.getElementById('monthFilterBar').addEventListener('change', updateBarChart);
            document.getElementById('yearFilterBar').addEventListener('change', updateBarChart);

            function updateBarChart() {
                const monthEl = document.getElementById('monthFilterBar');
                const yearEl = document.getElementById('yearFilterBar');
                const month = monthEl ? monthEl.value : '';
                const year = yearEl ? yearEl.value : '';

                // Fetch dan update data bar chart berdasarkan bulan dan minggu yang dipilih
                fetch(`/bar-data?month=${month}&year=${year}`)
                    .then(response => {
                        if (!response.ok) {
                            throw new Error(`HTTP ${response.status}`);
                        }
                        return response.json();
                    })
                    .then(data => {
                        // console.log('Bar Chart Data:', data); // Log data respons

                        // Perbarui Perbandingan Berat dan Kuantitas
                        const weightEl = document.getElementById('weightThisMonth');
                        if (weightEl) {
                            weightEl.innerText = `${(Number(data.weightThisMonth || 0) / 1000).toLocaleString('id-ID')} Ton`;
                        }
                        const weightCompEl = document.getElementById('weightComparison');
                        if (weightCompEl) {
                            weightCompEl.className = "text-success mb-0 mt-10";
                            weightCompEl.innerHTML = ` ${data.weightComparison}`;
                        }
                        const qtyEl = document.getElementById('qtyThisMonth');
                        if (qtyEl) {
                            qtyEl.innerText = `${(Number(data.qtyThisMonth || 0)).toLocaleString('id-ID')} Pcs`;
                        }
                        const qtyCompEl = document.getElementById('qtyComparison');
                        if (qtyCompEl) {
                            qtyCompEl.className = "text-success mb-0 mt-10";
                            qtyCompEl.innerHTML = `<i class="fa-solid fa-arrow-down text-danger"></i>  ${data.qtyComparison} since last month`;
                        }

                        // Fungsi untuk mengekstrak angka dari string
                        function extractNumber(str) {
                            const match = str.match(/-?\d+(\.\d+)?/);
                            return match ? parseFloat(match[0]) : NaN;
                        }

                        // Parsing nilai untuk memastikan perbandingan angka
                        const weightComparisonValue = extractNumber(data.weightComparison);
                        const qtyComparisonValue = extractNumber(data.qtyComparison);

                        // Cek apakah weightComparison menunjukkan penurunan
                        if (weightComparisonValue < 0) {
                            document.getElementById('weightComparison').className = "text-danger mb-0 mt-10";
                            document.getElementById('weightComparison').innerHTML =
                                `<i class="fa-solid fa-arrow-down text-danger"></i>  ${data.weightComparison} since last month`;
                        } else {
                            document.getElementById('weightComparison').className = "text-success mb-0 mt-10";
                            document.getElementById('weightComparison').innerHTML =
                                `<i class="fa-solid fa-arrow-up text-success"></i>  ${data.weightComparison} since last month`;
                        }

                        // Cek apakah qtyComparison menunjukkan penurunan
                        if (qtyComparisonValue < 0) {
                            document.getElementById('qtyComparison').className = "text-danger mb-0 mt-10";
                            document.getElementById('qtyComparison').innerHTML =
                                `<i class="fa-solid fa-arrow-down text-danger"></i>  ${data.qtyComparison} since last month`;
                        } else {
                            document.getElementById('qtyComparison').className = "text-success mb-0 mt-10";
                            document.getElementById('qtyComparison').innerHTML =
                                `<i class="fa-solid fa-arrow-up text-success"></i>  ${data.qtyComparison} since last month`;
                        }

                        // Memastikan data yang diterima valid dan render chart meskipun nilainya 0
                        if (data && Array.isArray(data.labels) && Array.isArray(data.actual_qty)) {
                            const standardQty = Array.isArray(data.standard_qty) ? data.standard_qty.map(Number) : [0];
                            const actualHeight = Number(data.actual_height) || 0;
                            setBarChartOption([...new Set(data.labels)], data.actual_qty, standardQty, actualHeight);
                        }
                    })
                    .catch(() => {});
            }


            document.addEventListener('DOMContentLoaded', function() {
                document.getElementById('yearFilterYear').addEventListener('change', updateYearChart);
            });

            function updateYearChart() {
                const year = document.getElementById('yearFilterYear').value;

                if (!year) {
                    return;
                }

                fetch(`/year-data?year=${year}`)
                    .then(response => {
                        if (!response.ok) {
                            throw new Error(`HTTP ${response.status}`);
                        }
                        return response.json();
                    })
                    .then(data => {
                        // Isi Data Perbandingan
                        const yearProdEl = document.getElementById('yearProduction');
                        if (yearProdEl) {
                            yearProdEl.innerText =
                                `${(Number(data.thisYearTotal || 0) / 1000).toLocaleString('id-ID', { minimumFractionDigits: 2, maximumFractionDigits: 2 })} Ton`;
                        }

                        const comparisonElement = document.getElementById('yearComparison');
                        if (comparisonElement && data.yearly_comparison) {
                            if (data.yearly_comparison.includes('Up')) {
                                comparisonElement.className = "text-success mb-0 mt-10";
                                comparisonElement.innerHTML =
                                    `<i class="fa-solid fa-arrow-up text-success"></i> ${data.yearly_comparison} since last year to date`;
                            } else {
                                comparisonElement.className = "text-danger mb-0 mt-10";
                                comparisonElement.innerHTML =
                                    `<i class="fa-solid fa-arrow-down text-danger"></i> ${data.yearly_comparison} since last year to date`;
                            }
                        }

                        // Memastikan data yang diterima valid dan render chart meskipun nilainya 0
                        if (data && Array.isArray(data.labels) && data.actual_qty) {
                            const actualData = ['A', 'B', 'C', 'D', 'E', 'F'].map(line => {
                                return Array.isArray(data.actual_qty[line]) ? data.actual_qty[line].map(Number) : [];
                            });

                            const standardQty = Number(data.standard_qty) || 0;
                            const actualHeight = parseFloat(data.actual_height) || 0;

                            setYearChartOption(
                                data.labels,
                                actualData,
                                standardQty,
                                actualHeight
                            );
                        }
                    })
                    .catch(() => {});
            }

            function setYearChartOption(labels, actualData, standardQty, actualHeight) {
                var option;

                const grid = {
                    left: 100,
                    right: 100,
                    top: 120,
                    bottom: 80
                };

                const isDarkMode = localStorage.getItem('darkMode');

                const series = ['A', 'B', 'C', 'D', 'E', 'F'].map((name, sid) => {
                    const displayName = LINE_NAMES[name] || name;
                    return {
                        name: displayName,
                        type: 'bar',
                        stack: 'total',
                        barWidth: '70%',
                        label: {
                            show: false
                        },
                        data: actualData[sid],
                        markLine: {
                            data: [{
                                yAxis: standardQty,
                                label: {
                                    formatter: function(params) {
                                        return params.value.toLocaleString('id-ID');
                                    },
                                    color: '#A9A9A9',
                                    fontSize: 18
                                }
                            }],
                            lineStyle: {
                                color: 'red',
                                type: 'dashed',
                            }
                        }
                    };
                });

                option = {
                    tooltip: {
                        trigger: 'axis',
                        axisPointer: {
                            type: 'shadow'
                        },
                        formatter: function(params) {
                            let tooltipText = '';
                            let total = 0;
                            params.forEach(param => {
                                const value = parseFloat(param.value);
                                total += value;
                                tooltipText +=
                                    `<span style="color:${param.color}">${param.seriesName}: ${param.value.toLocaleString('id-ID')}</span><br/>`;
                            });
                            tooltipText +=
                                `<span style="color:#000;font-weight:bold">Total: ${total.toLocaleString('id-ID')}</span>`;
                            return tooltipText;
                        }
                    },
                    color: ['#609CFA', '#F87171', '#D8B4FE', '#86EFAC', '#FDBA74', '#A78BFA'],
                    legend: {
                        selectedMode: true,
                        textStyle: {
                            fontSize: 20,
                            color: '#A9A9A9',
                        },
                        top: 30,
                        left: 'center',
                        itemGap: 30,
                        orient: 'horizontal'
                    },
                    grid,
                    yAxis: {
                        type: 'value',
                        min: 0,
                        max: Math.round(Math.max(Number(actualHeight) || 0, Number(standardQty) || 0) * 1.03) || null,
                        axisLabel: {
                            fontSize: 17,
                            formatter: function(value) {
                                return value.toLocaleString('id-ID');
                            },
                            color: '#A9A9A9',
                        }
                    },
                    xAxis: {
                        type: 'category',
                        data: labels,
                        axisLabel: {
                            fontSize: 15,
                            color: '#A9A9A9',
                        }
                    },
                    series
                };

                option && yearChart.setOption(option);
            }
        </script>
    @endpush

</x-app-layout>
