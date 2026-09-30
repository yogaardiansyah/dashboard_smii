<x-app-layout>
    @section('title')
        Dashboard Warehouse & Shipment
    @endsection

    @include('layouts.partials.vendor.echarts')

    <!-- WAREHOUSE OCCUPANCY -->
    <div class="grid grid-cols-5" style="column-gap: 0.75rem /* 12px */;">
        {{-- <div style="grid-column-start: 1;">
            <div class="card border-2 border-warning rounded-2xl">
                <div class="box-header flex justify-center items-center">
                    <h3 class="text-3xl font-medium">Inward Warehouse</h3>
                </div>
                <div class="py-6">
                    <div class="px-6">
                        <div class="box-header flex flex-col justify-start items-center">
                            <h3 class="box-title m-0 text-3xl">G1 | <span class="text-orange-500">Ambient</span></h3>
                            <h3 id="G1AmbientPallet" class="box-title m-0 text-2xl">0 PP</h3>
                        </div>
                    </div>
                    <div id="G1Ambient" style="height:350px;"></div>
                    <div class="flex justify-center items-center">
                        <p id="G1AmbientCapacity" class="text-2xl">Capacity: 0 Ton</p>
                    </div>
                </div>
            </div>
        </div> --}}
        <div style="grid-column: span 5/span 5;">
            <div class="card border-2 border-success rounded-2xl">
                <div class="box-header flex items-center justify-center gap-8 py-2">
                    <img src="{{ asset('assets/images/logo/smii.png') }}" alt="SMII Logo"
                        style="height:75px; width:auto; display:block;">
                    <h3 class="text-3xl font-medium text-center">Finished Goods / Outward Warehouse</h3>
                    <img src="{{ asset('assets/images/logo/sindy.png') }}" alt="K3 Logo"
                        style="height:75px; width:auto; display:block;">
                </div>
                <div class="box-body grid grid-cols-5" style="column-gap: 0.75rem /* 12px */;">
                    <div>
                        <div class="box-header flex flex-col justify-start items-center">
                            <h3 class="box-title m-0 text-3xl">WHFG BLOK K & L | <span
                                    class="text-blue-500">13-18C</span></h3>
                        </div>
                        <div id="G216Degree" style="height:350px;"></div>
                        <div class="flex flex-col items-center">
                            <div class="flex justify-center items-center">
                                <span id="G216DegreeNonFGood" class="text-2xl font-semibold text-blue-500">Non F-GOOD: 0
                                    PP</span>
                                <span class="mx-2">|</span>
                                <span id="G216DegreeFGood" class="text-2xl font-semibold text-blue-500">F-GOOD: 0
                                    PP</span>
                            </div>
                            <div class="flex justify-center items-center">
                                <p id="G216DegreeCapacity" class="text-2xl">Capacity: 0 Ton</p>
                                <span class="mx-2">|</span>
                                <h3 id="G216DegreePallet" class="box-title m-0 text-2xl mt-2">0 PP</h3>
                            </div>
                        </div>
                    </div>
                    <div>
                        <div class="box-header flex flex-col justify-start items-center">
                            <h3 class="box-title m-0 text-3xl">WHFG BLOK MN | <span class="text-green-500">20-25C</span>
                            </h3>
                        </div>
                        <div id="G225Degree" style="height:350px;"></div>
                        <div class="flex flex-col items-center">
                            <div class="flex justify-center items-center">
                                <span id="G225DegreeNonFGood" class="text-2xl font-semibold text-green-500">Non F-GOOD:
                                    0
                                    PP</span>
                                <span class="mx-2">|</span>
                                <span id="G225DegreeFGood" class="text-2xl font-semibold text-green-500">F-GOOD: 0
                                    PP</span>
                            </div>
                            <div class="flex justify-center items-center">
                                <p id="G225DegreeCapacity" class="text-2xl">Capacity: 0 Ton</p>
                                <span class="mx-2">|</span>
                                <h3 id="G225DegreePallet" class="box-title m-0 text-2xl mt-2">0 PP</h3>
                            </div>
                        </div>
                    </div>
                    <div>
                        <div class="box-header flex flex-col justify-start items-center">
                            <h3 class="box-title m-0 text-3xl">WHFG BLOK R | <span class="text-green-500">20-25C</span>
                            </h3>
                        </div>
                        <div id="G325Degree" style="height:350px;"></div>
                        <div class="flex flex-col items-center">
                            <div class="flex justify-center items-center">
                                <span id="G325DegreeNonFGood" class="text-2xl font-semibold text-green-500">0 PP</span>
                                <span class="mx-2">|</span>
                                <span id="G325DegreeFGood" class="text-2xl font-semibold text-green-500">F-GOOD:
                                    0
                                    PP</span>
                            </div>
                            <div class="flex justify-center items-center">
                                <p id="G325DegreeCapacity" class="text-2xl">Capacity: 0 Ton</p>
                                <span class="mx-2">|</span>
                                <h3 id="G325DegreePallet" class="box-title m-0 text-2xl mt-2">0 PP</h3>
                            </div>
                        </div>
                    </div>
                    <div>
                        <div class="box-header flex flex-col justify-start items-center">
                            <h3 class="box-title m-0 text-3xl">G3 | <span style="color: #FB923C;">Ambient</span></h3>
                        </div>
                        <div id="G3Ambient" style="height:350px;"></div>
                        <div class="flex flex-col items-center">
                            <div class="flex justify-center items-center">
                                <span id="G3AmbientNonFGood" class="text-2xl font-semibold" style="color: #FB923C;"> 0
                                    PP</span>
                                <span class="mx-2">|</span>
                                <span id="G3AmbientFGood" class="text-2xl font-semibold " style="color: #FB923C;">Act.
                                    F-GOOD: 0
                                    PP</span>
                            </div>
                            <div class="flex justify-center items-center">

                                <p id="G3AmbientCapacity" class="text-2xl">Capacity: 0 Ton</p>
                                <span class="mx-2">|</span>
                                <h3 id="G3AmbientPallet" class="box-title m-0 text-2xl mt-2">0 PP</h3>
                            </div>
                        </div>
                    </div>
                    <div class="grid grid-cols-1 items-start">
                        <div class="flex justify-center self-center text-3xl col-span-2 " style=""> G3 Temp.
                        </div>
                        <div class="flex justify-center text-3xl">WHFG AMBIENT BLOK B</div>
                        <div class="flex text-3xl justify-end" style="color: #FB923C; font-weight: bold;">
                            <p id="tempAtas"></p>
                        </div>
                        <div class="flex justify-center text-3xl">WHFG AMBIENT BLOK C</div>
                        <div class="flex text-3xl justify-end" style="color: #FB923C; font-weight: bold;">
                            <p id="tempTengah"></p>
                        </div>
                        <div class="flex justify-center text-3xl">WHFG AMBIENT BLOK A</div>
                        <div class="flex text-3xl justify-end" style="color: #FB923C; font-weight: bold;">
                            <p id="tempBawah"></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <div class="grid grid-cols-3 gap-x-4 mb-4" style="grid-template-columns: 40% 40% 19%;">
        <!-- Area Chart 1 -->
        <div class="col-span-1" style="margin-top:10px;">
            <div class="box rounded-2xl">
                <div class="box-body analytics-info">
                    <div class="flex py-2" style="width:100%; justify-content:space-between;">
                        <div class="text-3xl font-medium">Production vs Monthly Dispatch</div>
                        <div>
                            <!-- Daftar Tahun -->
                            @php
                                $currentYear = date('Y'); // Mengambil tahun saat ini
                                $currentMonth = date('n'); // Mengambil bulan saat ini (1-12)
                            @endphp
                            <select id="yearFilterArea" class="mr-2 bg-gray-500 text-xl rounded text-black">
                                @for ($i = $currentYear; $i >= $currentYear - 10; $i--)
                                    <option value="{{ $i }}" {{ $i == $currentYear ? 'selected' : '' }}>{{ $i }}</option>
                                @endfor
                            </select>
                            <!-- Daftar bulan -->
                            <select id="monthFilterArea" class="mr-2 bg-gray-500 text-xl rounded text-black">
                                @for ($i = 1; $i <= 12; $i++)
                                    <option value="{{ $i }}" {{ $i == $currentMonth ? 'selected' : '' }}>{{ date('F', mktime(0, 0, 0, $i, 1)) }}</option>
                                @endfor
                            </select>
                        </div>
                    </div>
                    <div class="flex">
                        <canvas id="myBarChartMonthly" width="100%" height="55"
                            style="max-width:100%; max-height: 560px;"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- Area Chart 2 -->
        <div class="col-span-1" style="margin-top:10px;">
            <div class="box rounded-2xl">
                <div class="box-body analytics-info">
                    <div class="flex py-2" style="width:100%; justify-content:space-between;">
                        <div class="text-3xl font-medium">Daily Dispatch</div>
                        {{-- <div>
                            <!-- Daftar Tahun -->
                            <select id="yearFilterArea" class="mr-2 bg-gray-500 text-xl rounded text-black">
                                @php
                                    $currentYear = date('Y'); // Mengambil tahun saat ini
                                @endphp
                                <option value="" class="" disabled selected hidden>{{ $currentYear }}
                                </option>

                                @for ($i = $currentYear; $i >= $currentYear - 10; $i--)
                                    <option value="{{ $i }}">{{ $i }}</option>
                                @endfor
                            </select>
                            <!-- Daftar bulan -->
                            <select id="monthFilterArea" class="mr-2 bg-gray-500 text-xl rounded text-black">
                                <option value="" class="" disabled selected hidden>{{ date('F') }}
                                </option>
                                @for ($i = 1; $i <= 12; $i++)
                                    <option value="{{ $i }}">{{ date('F', mktime(0, 0, 0, $i, 1)) }}
                                    </option>
                                @endfor
                            </select>
                        </div> --}}
                    </div>
                    <div class="flex">
                        <canvas id="myAreaChart" width="100%"  height="55"
                            style="max-width:100%; max-height: 560px;"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- Table Dispatch -->
        <div class="col-span-1" style="margin-top:10px;">
            <div class="box rounded-2xl">
                <div class="mx-10 my-5 flex justify-between items-center">
                    <div class="flex flex-row flex-wrap">
                        <p class="text-2xl font-semibold">Operator</p>
                    </div>
                    <ul class="m-0 mx-5" style="list-style: none;">
                        <li class="dropdown">
                            <button id="dateDisplay2"
                                class="waves-effect waves-light btn btn-outline dropdown-toggle btn-md text-xl"
                                data-bs-toggle="dropdown" href="#" aria-expanded="false">
                                {{ date('d F Y') }}
                            </button>
                            <div class="dropdown-menu dropdown-menu-end" style="will-change: transform;">
                                <div class="px-3 py-2">
                                    <input type="date" id="dateFilterDropdown2"
                                        class="bg-gray-500 text-xl rounded text-black" value="{{ date('Y-m-d') }}">
                                    <button id="applyDateFilterDropdown2"
                                        class="bg-blue-500 text-white px-2 py-1 mt-2 text-xl">Submit</button>
                                </div>
                            </div>
                        </li>
                    </ul>
                </div>
                <div class="table-responsive" style="max-height: 560px; overflow-y: auto;">
                    <table id="dispatchTable" class="table mb-0 w-full border border-gray-400">
                        <thead
                            style="position: sticky; top: 0; background-color: rgb(10, 10, 10); z-index: 1; color: white; border-rad">
                            <tr>
                                <th class="text-xl text-left border-b border-gray-400">Operator</th>
                                <th class="text-center text-xl border-b border-gray-400">Pick</th>
                                <th class="text-center text-xl border-b border-gray-400">Load</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- Data akan diisi oleh JavaScript -->
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>



    @push('scripts')
        <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.8.0/Chart.min.js" crossorigin="anonymous"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/chartjs-plugin-annotation/0.5.7/chartjs-plugin-annotation.min.js">
        </script>
        <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@0.7.0"></script>

        <script>
            // Mengambil dark mode
            const darkModeStorage = localStorage.getItem('darkMode');

            const myCharts = {}; // Init for gauge charts
            var myLineChart; // Init for daily line chart
            var myLineChartMonthly; // Init for monthly line chart

            document.addEventListener('DOMContentLoaded', function() {

                // Set nilai default untuk yearFilterArea ke tahun saat ini
                const currentYear = new Date().getFullYear();
                const yearFilter = document.getElementById('yearFilterArea');
                if (yearFilter) {
                    yearFilter.value = currentYear; // Set nilai dropdown ke tahun saat ini
                }

                // Auto-revert logic: jika user mengganti tahun/bulan, setelah 30 detik
                // otomatis kembali ke tahun/bulan berjalan dan memicu perubahan untuk
                // memuat data terbaru — namun hanya jika pengguna memilih tahun/bulan
                // yang BUKAN tahun/bulan berjalan.
                const currentMonth = new Date().getMonth() + 1; // 1-12
                const monthFilter = document.getElementById('monthFilterArea');
                if (monthFilter) {
                    monthFilter.value = currentMonth; // pastikan default month di DOM sama dengan currentMonth
                }
                const autoRevertDelay = 30000; // 30 detik
                let autoRevertTimer = null;

                function startAutoRevertTimer() {
                    if (!yearFilter || !monthFilter) return;

                    const selYear = parseInt(yearFilter.value) || currentYear;
                    const selMonth = parseInt(monthFilter.value) || currentMonth;

                    // Jika sudah pada bulan & tahun berjalan, batalkan timer
                    if (selYear === currentYear && selMonth === currentMonth) {
                        if (autoRevertTimer) {
                            clearTimeout(autoRevertTimer);
                            autoRevertTimer = null;
                        }
                        return;
                    }

                    if (autoRevertTimer) clearTimeout(autoRevertTimer);
                    autoRevertTimer = setTimeout(() => {
                        if (yearFilter) yearFilter.value = currentYear;
                        if (monthFilter) monthFilter.value = currentMonth;

                        // Dispatch change events so any existing listeners akan ter-trigger
                        const evt = new Event('change', { bubbles: true });
                        if (monthFilter) monthFilter.dispatchEvent(evt);
                        if (yearFilter) yearFilter.dispatchEvent(evt);

                        // Jika ada fungsi khusus untuk memuat ulang chart/data, panggil juga
                        if (typeof updateLineChart === 'function') updateLineChart();
                        if (typeof createMonthlyChart === 'function') createMonthlyChart();
                    }, autoRevertDelay);
                }

                if (yearFilter) yearFilter.addEventListener('change', startAutoRevertTimer);
                if (monthFilter) monthFilter.addEventListener('change', startAutoRevertTimer);

                // Panggil fungsi updateLineChart untuk memuat data awal
                updateLineChart();
                // Set font color based on dark mode
                setFontColor();

                createGaugeChart();
                createDailyChart();
                createMonthlyChart();

                // Panggil fungsi ini dengan tanggal hari ini saat halaman dimuat
                const today = new Date().toISOString().split('T')[0];
                updateTotalDispatch(today);

                // Set interval untuk memperbarui data setiap 5 detik
                setInterval(function() {
                    updateLineChart();
                    updateTotalDispatch(document.getElementById('dateFilterDropdown2').value);
                    getWarehouseData();
                }, 5000); // 5000 milidetik = 5 detik

                // Panggil getWarehouseData() saat halaman dimuat
                getWarehouseData();

                // Membuat semua chart ECharts responsif
                makeChartsResponsive();
            });

            // Fungsi untuk mengatur warna font berdasarkan mode gelap
            function setFontColor() {
                const elements = document.querySelectorAll('.text-black, .text-white'); // Ganti dengan kelas yang sesuai
                elements.forEach(element => {
                    if (darkModeStorage === 'enabled') {
                        element.classList.remove('text-black');
                        element.classList.add('text-white');
                    } else {
                        element.classList.remove('text-white');
                        element.classList.add('text-black');
                    }
                });
            }

            // Fungsi untuk membuat chart ECharts responsif
            function makeChartsResponsive() {
                window.addEventListener('resize', function() {
                    Object.values(myCharts).forEach(chart => chart.resize());
                });
            }

            // Mendapatkan data warehouse
            function getWarehouseData() {
                fetch('/warehouse-data')
                    .then(response => response.json())
                    .then(data => {
                        // console.log('Warehouse data fetched successfully:', data);
                        const warehouseDataArr = data.warehouse_data || [];
                        const temperatureData = data.temperature_data || {};

                        // Ubah array menjadi object dengan key group_name
                        const warehouseData = {};
                        warehouseDataArr.forEach(item => {
                            warehouseData[item.group_name] = item;
                        });

                        const updateElement = (id, value, unit) => {
                            const element = document.getElementById(id);
                            if (element) {
                                element.innerHTML = `${value} ${unit}`;
                            } else {
                                console.error(`Element with ID ${id} not found.`);
                            }
                        };

                        const updateTemperatureElement = (id, value) => {
                            const element = document.getElementById(id);
                            if (element) {
                                element.innerHTML = `${value}°C`;
                            } else {
                                console.error(`Element with ID ${id} not found.`);
                            }
                        };

                        // Ambil suhu dari temperature_data untuk setiap group (bukan per device)
                        const getTemperatureValue = (temperatureData, groupName) => {
                            if (typeof temperatureData !== 'object' || temperatureData === null) {
                                return 0;
                            }
                            return temperatureData[groupName] ?? 0;
                        };

                        // Update suhu untuk G3 Ambience dan lokasi spesifik
                        updateTemperatureElement('tempAtas', getTemperatureValue(temperatureData, 'G3 Atas'));
                        updateTemperatureElement('tempTengah', getTemperatureValue(temperatureData, 'G3 Tengah'));
                        updateTemperatureElement('tempBawah', getTemperatureValue(temperatureData, 'G3 Bawah'));

                        const callCreateGaugeChart = (id, data, tempValue) => {
                            if (data) {
                                createGaugeChart(
                                    id,
                                    data.pallet_occupancy,
                                    Math.round(data.total_ton),
                                    typeof tempValue === 'number' ? tempValue : null,
                                    Math.round(data.total_pallet)
                                );
                            } else {
                                createGaugeChart(id, 0, 0, null);
                            }
                        };

                        if (warehouseData['G2 16 C']) {
                            updateElement('G216DegreeFGood',
                                ` ${Math.round(warehouseData['G2 16 C'].total_fgood_pallet).toLocaleString('id-ID')}`,
                                'PP');
                            updateElement('G216DegreeNonFGood',
                                `F-GOOD : ${Math.round(warehouseData['G2 16 C'].total_fgood_ton).toLocaleString('id-ID')}`,
                                'TON'
                            );
                            updateElement('G216DegreePallet', warehouseData['G2 16 C'].total_pallet_rack.toLocaleString(
                                'id-ID'), 'PP');
                            updateElement('G216DegreeCapacity',
                                `Capacity: ${warehouseData['G2 16 C'].total_estimated_tonnage.toLocaleString('id-ID')}`,
                                'Ton');
                            callCreateGaugeChart('G216Degree', warehouseData['G2 16 C'], getTemperatureValue(
                                temperatureData, 'G2 16 C'));
                        } else {
                            updateElement('G216DegreeFGood', '0', 'PP');
                            updateElement('G216DegreeNonFGood', '0', 'TON');
                            updateElement('G216DegreePallet', '0', 'PP');
                            updateElement('G216DegreeCapacity', 'Capacity: 0', 'Ton');
                            callCreateGaugeChart('G216Degree', null, null);
                        }

                        if (warehouseData['G2 25 C']) {
                            updateElement('G225DegreeFGood',
                                `${Math.round(warehouseData['G2 25 C'].total_fgood_pallet).toLocaleString('id-ID')}`,
                                'PP');
                            updateElement('G225DegreeNonFGood',
                                `F-GOOD : ${Math.round(warehouseData['G2 25 C'].total_fgood_ton).toLocaleString('id-ID')}`,
                                'TON'
                            );
                            updateElement('G225DegreePallet', warehouseData['G2 25 C'].total_pallet_rack.toLocaleString(
                                'id-ID'), 'PP');
                            updateElement('G225DegreeCapacity',
                                `Capacity: ${warehouseData['G2 25 C'].total_estimated_tonnage.toLocaleString('id-ID')}`,
                                'Ton');
                            callCreateGaugeChart('G225Degree', warehouseData['G2 25 C'], getTemperatureValue(
                                temperatureData, 'G2 25 C'));
                        } else {
                            updateElement('G225DegreeFGood', '0', 'PP');
                            updateElement('G225DegreeNonFGood', '0', 'TON');
                            updateElement('G225DegreePallet', '0', 'PP');
                            updateElement('G225DegreeCapacity', 'Capacity: 0', 'Ton');
                            callCreateGaugeChart('G225Degree', null, null);
                        }

                        if (warehouseData['G3 25 C']) {
                            updateElement('G325DegreeFGood',
                                `${Math.round(warehouseData['G3 25 C'].total_fgood_pallet).toLocaleString('id-ID')}`,
                                'PP');
                            updateElement('G325DegreeNonFGood',
                                `F-GOOD : ${Math.round(warehouseData['G3 25 C'].total_fgood_ton).toLocaleString('id-ID')}`,
                                'TON');
                            updateElement('G325DegreePallet', warehouseData['G3 25 C'].total_pallet_rack.toLocaleString(
                                'id-ID'), 'PP');
                            updateElement('G325DegreeCapacity',
                                `Capacity: ${warehouseData['G3 25 C'].total_estimated_tonnage.toLocaleString('id-ID')}`,
                                'Ton');
                            callCreateGaugeChart('G325Degree', warehouseData['G3 25 C'], getTemperatureValue(
                                temperatureData, 'G3 25 C'));
                        } else {
                            updateElement('G325DegreeFGood', '0', 'PP');
                            updateElement('G325DegreeNonFGood', '0', 'TON');
                            updateElement('G325DegreePallet', '0', 'PP');
                            updateElement('G325DegreeCapacity', 'Capacity: 0', 'Ton');
                            callCreateGaugeChart('G325Degree', null, null);
                        }

                        if (warehouseData['G3 Ambience']) {
                            updateElement('G3AmbientFGood',
                                `${Math.round(warehouseData['G3 Ambience'].total_fgood_pallet).toLocaleString('id-ID')}`,
                                'PP');
                            updateElement('G3AmbientNonFGood',
                                `F-GOOD : ${Math.round(warehouseData['G3 Ambience'].total_fgood_ton).toLocaleString('id-ID')}`,
                                'TON');
                            updateElement('G3AmbientPallet', warehouseData['G3 Ambience'].total_pallet_rack.toLocaleString(
                                'id-ID'), 'PP');
                            updateElement('G3AmbientCapacity',
                                `Capacity: ${warehouseData['G3 Ambience'].total_estimated_tonnage.toLocaleString('id-ID')}`,
                                'Ton');
                            callCreateGaugeChart('G3Ambient', warehouseData['G3 Ambience'], getTemperatureValue(
                                temperatureData, 'G3 Ambience'));
                        } else {
                            updateElement('G3AmbientFGood', '0', 'PP');
                            updateElement('G3AmbientNonFGood', '0', 'TON');
                            updateElement('G3AmbientPallet', '0', 'PP');
                            updateElement('G3AmbientCapacity', 'Capacity: 0', 'Ton');
                            callCreateGaugeChart('G3Ambient', null, null);
                        }
                    })
                    .catch(error => {
                        console.error('Error fetching warehouse data:', error);
                    });
            }

            function createGaugeChart(temp, occupancy, total_ton, actualTemp, total_pallet) {
                const gauge = document.getElementById(`${temp}`);
                if (!gauge) {
                    // console.error(`Element with ID ${temp} not found.`);
                    return;
                }
                let normalText;

                // jika darkmode, init dark
                if (darkModeStorage === 'enabled') {
                    myCharts[temp] = echarts.init(gauge, 'dark');
                    normalText = 'grey';
                } else {
                    myCharts[temp] = echarts.init(gauge);
                    normalText = 'grey';
                }

                // Pastikan semua nilai number, bukan undefined/null
                var number = (typeof occupancy === 'number' && !isNaN(occupancy)) ? occupancy.toFixed(1) : '0.0';
                let totalTonSafe = (typeof total_ton === 'number' && !isNaN(total_ton)) ? total_ton : 0;
                let totalPalletSafe = (typeof total_pallet === 'number' && !isNaN(total_pallet)) ? total_pallet : 0;
                let bgColor =
                    number >= 90 ? '#FF5E5C' :
                    number >= 70 ? '#FFB95C' :
                    number <= 10 ? '#FF5E5C' :
                    number <= 20 ? '#FFB95C' :
                    ''; // default

                let txtColor =
                    number >= 90 ? 'white' :
                    number >= 70 ? 'black' :
                    number <= 10 ? 'white' :
                    number <= 20 ? 'black' :
                    normalText; // default

                // If occupancy > 80, use red color
                let tempColor;
                if (typeof number === 'string' ? parseFloat(number) > 80 : number > 80) {
                    tempColor = '#FF0000'; // red
                } else {
                    tempColor =
                        temp === "G1Ambient" ? '#F97316' :
                        temp === "G216Degree" ? '#3B82F6' :
                        temp === "G225Degree" ? '#22C55E' :
                        temp === "G325Degree" ? '#22C55E' :
                        temp === "G3Ambient" ? '#FB923C' :
                        'orange'; // default
                }

                let data2 = {
                    value: (typeof actualTemp === 'number' && !isNaN(actualTemp)) ? Number(actualTemp.toFixed(1)) : 0,
                    itemStyle: {
                        color: tempColor
                    },
                    title: {
                        fontSize: 20,
                        offsetCenter: ['0%', '30%']
                    },
                    detail: {
                        show: true,
                        fontSize: 25,
                        color: tempColor,
                        formatter: '{value}°C',
                        offsetCenter: ['0%', '55%']
                    }
                };

                const gaugeData = [{
                    value: (typeof number === 'string') ? parseFloat(number) : (typeof number === 'number' ? number :
                        0),
                    name: 'Occupancy',
                    itemStyle: {
                        color: tempColor
                    },
                    title: {
                        fontSize: window.innerWidth > 768 ? 25 : 15,
                        offsetCenter: ['0%', '-40%'],
                        color: tempColor
                    },
                    detail: {
                        valueAnimation: true,
                        fontSize: window.innerWidth > 768 ? 30 : 20,
                        formatter: '{value}%',
                        offsetCenter: ['0%', '-10%'],
                        lineHeight: 25,
                        width: 100,
                        height: 20,
                        color: tempColor,
                        rich: {}
                    }
                }];
                if (temp !== "G1Ambient") {
                    gaugeData.push(data2);
                }
                const option = {
                    series: [{
                        type: 'gauge',
                        startAngle: 90,
                        endAngle: -270,
                        pointer: {
                            show: false
                        },
                        progress: {
                            show: true,
                            overlap: true,
                            roundCap: false,
                            clip: false,
                            width: 20
                        },
                        axisLine: {
                            lineStyle: {
                                shadowColor: 'black',
                                shadowBlur: 4,
                                shadowOffsetX: 0,
                                shadowOffsetY: 0,
                                width: 20
                            }
                        },
                        splitLine: {
                            show: false
                        },
                        axisTick: {
                            show: false
                        },
                        axisLabel: {
                            show: false
                        },
                        data: gaugeData,
                    }],
                    title: {
                        text: `Act.: ${totalTonSafe.toLocaleString('id-ID', { minimumFractionDigits: 0, maximumFractionDigits: 0 })} Ton | ${totalPalletSafe.toLocaleString('id-ID', { minimumFractionDigits: 0, maximumFractionDigits: 0 })} PP`,
                        left: 'center',
                        top: '1%',
                        bottom: '5%',
                        textStyle: {
                            fontSize: window.innerWidth > 768 ? 20 : 15,
                            color: tempColor
                        }
                    }
                };

                option && myCharts[temp].setOption(option);
            }


            // create line chart for daily
            function createDailyChart() {
                var dailyChart = document.getElementById("myAreaChart");
                myLineChart = new Chart(dailyChart, {
                    type: 'line',
                    data: {
                        labels: [],
                        datasets: [{
                            label: "Dispatch (In Tonna)",
                            lineTension: 0.3,
                            backgroundColor: "rgba(2,117,216,0.2)",
                            borderColor: "rgba(2,117,216,1)",
                            pointRadius: 5,
                            pointBackgroundColor: "rgba(2,117,216,1)",
                            pointBorderColor: "rgba(255,255,255,0.8)",
                            pointHoverRadius: 5,
                            pointHoverBackgroundColor: "rgba(2,117,216,1)",
                            pointHitRadius: 50,
                            pointBorderWidth: 2,
                            data: [],
                        }],
                    },
                    options: {
                        scales: {
                            xAxes: [{
                                time: {
                                    unit: 'date'
                                },
                                gridLines: {
                                    display: false
                                }
                            }],
                            yAxes: [{
                                ticks: {
                                    min: 0,
                                    max: 100,
                                    maxTicksLimit: 5
                                },
                                gridLines: {
                                    color: "rgba(0, 0, 0, .125)",
                                },
                                scaleLabel: {
                                    display: true,
                                    labelString: '(Tonnage)',
                                },
                            }]
                        },
                        legend: {
                            display: false
                        }
                    }
                });
            }


            // create line chart for monthly
            function createMonthlyChart() {
                var monthlyChart = document.getElementById("myBarChartMonthly");
                myLineChartMonthly = new Chart(monthlyChart, {
                    type: 'line',
                    data: {
                        labels: [],
                        datasets: [{
                            label: "Dispatch (In Tonnage)",
                            lineTension: 0.3,
                            backgroundColor: "rgba(2,117,216,0.2)",
                            borderColor: "rgba(2,117,216,1)",
                            pointRadius: 5,
                            pointBackgroundColor: "rgba(2,117,216,1)",
                            pointBorderColor: "rgba(255,255,255,0.8)",
                            pointHoverRadius: 5,
                            pointHoverBackgroundColor: "rgba(2,117,216,1)",
                            pointHitRadius: 50,
                            pointBorderWidth: 2,
                            data: [],
                        }],
                    },
                    options: {
                        title: {
                            display: true,
                            text: 'Monthly Dispatch',
                            fontSize: 25,
                            color: 'grey'
                        },
                        scales: {
                            xAxes: [{
                                time: {
                                    unit: 'date'
                                },
                                gridLines: {
                                    display: false
                                }
                            }],
                            yAxes: [{
                                ticks: {
                                    min: 0,
                                    max: 100,
                                    maxTicksLimit: 5
                                },
                                gridLines: {
                                    color: "rgba(0, 0, 0, .125)",
                                }
                            }]
                        },
                        legend: {
                            display: false
                        }
                    }
                });
            }

             // set new data for monthly line chart
            let myBarChartMonthly;

            function setMonthlyChartOption(data, year) {
                const labels = data.map(item => item.month);
                const dispatchData = data.map(item => Number(parseFloat(item.total_dispatch || 0).toFixed(2)));
                const productionData = data.map(item => Number(parseFloat(item.total_production || 0).toFixed(2)));

                if (myBarChartMonthly) {
                    myBarChartMonthly.destroy();
                }

                const ctx = document.getElementById("myBarChartMonthly").getContext("2d");

                // compute dynamic max for Y axis from both datasets (add 10% padding)
                const allValues = productionData.concat(dispatchData);
                const maxValue = allValues.length ? Math.max(...allValues) : 0;
                const yMax = Math.ceil(maxValue * 1.1) || 10;

                myBarChartMonthly = new Chart(ctx, {
                    type: 'bar',
                    data: {
                        labels: labels,
                        datasets: [{
                                label: "Production (In Tonnage)",
                                backgroundColor: "rgba(92,184,92,0.5)",
                                borderColor: "rgba(92,184,92,1)",
                                borderWidth: 2,
                                barThickness: 30,
                                maxBarThickness: 50,
                                data: productionData,
                                yAxisID: "yProduction",
                                datalabels: {
                                    align: 'top',
                                    anchor: 'end',
                                    color: '#A9A9A9', // abu-abu
                                    font: {
                                        weight: 'bold',
                                        size: 14
                                    },
                                    formatter: function(value) {
                                        return Math.round(value).toLocaleString('id-ID');
                                    }
                                }
                            },
                            {
                                label: "Dispatch (In Tonnage)",
                                backgroundColor: "rgba(2,117,216,0.5)",
                                borderColor: "rgba(2,117,216,1)",
                                borderWidth: 2,
                                barThickness: 30,
                                maxBarThickness: 50,
                                data: dispatchData,
                                yAxisID: "yDispatch",
                                datalabels: {
                                    align: 'top',
                                    anchor: 'end',
                                    color: '#A9A9A9', // abu-abu
                                    font: {
                                        weight: 'bold',
                                        size: 14
                                    },
                                    formatter: function(value) {
                                        return Math.round(value).toLocaleString('id-ID');
                                    }
                                }
                            }
                        ],
                    },
                    options: {
                        // responsive: true,
                        // maintainAspectRatio: false,
                        plugins: {
                            datalabels: {
                                display: true,
                                offset: 5
                            }
                        },
                        legend: {
                            display: true,
                            position: 'top',
                            labels: {
                                fontSize: 14
                            }
                        },
                        tooltips: {
                            mode: 'index',
                            intersect: false,
                            titleFontSize: 16,
                            bodyFontSize: 14,
                            backgroundColor: 'rgba(255,255,255,0.9)',
                            titleFontColor: '#0066ff',
                            bodyFontColor: '#000',
                            borderColor: '#ddd',
                            borderWidth: 1
                        },
                        scales: {
                            xAxes: [{
                                gridLines: {
                                    display: false,
                                },
                                ticks: {
                                    fontSize: 16,
                                },
                            }],
                            yAxes: [{
                                    id: 'yProduction',
                                    type: 'linear',
                                    position: 'right',
                                    display: false,
                                            ticks: {
                                                beginAtZero: true,
                                                max: yMax,
                                                fontSize: 16,
                                                maxTicksLimit: 8,
                                                callback: function(value) {
                                                    return value.toLocaleString('id-ID');
                                                }
                                            },
                                    gridLines: {
                                        drawOnChartArea: false,
                                    },
                                },
                                {
                                    id: 'yDispatch',
                                    type: 'linear',
                                    position: 'left',
                                    display: true,
                                    ticks: {
                                        beginAtZero: true,
                                        max: yMax,
                                        fontSize: 16,
                                        maxTicksLimit: 8,
                                        callback: function(value) {
                                            return value.toLocaleString('id-ID');
                                        }
                                    },
                                    scaleLabel: {
                                        display: true,
                                        labelString: '(Tonnage)',
                                        fontSize: 18
                                    },
                                }
                            ],
                        },
                        animation: {
                            duration: 0,
                        },
                    }
                });

                // Register the datalabels plugin
                Chart.plugins.register(ChartDataLabels);
            }

            // set new data for daily line chart
            function setDailyChartOption(label, ton) {
                myLineChart.data.labels = label;
                myLineChart.data.datasets[0].data = ton;
                myLineChart.options = {
                    plugins: {
                        datalabels: {
                            anchor: 'end',
                            align: 'top',
                            color: '#A9A9A9', // abu-abu
                            font: {
                                weight: 'bold',
                                size: 16
                            },
                            formatter: function(value) {
                                return Math.round(value); // bulatkan tanpa koma
                            }
                        }
                    },
                    scales: {
                        xAxes: [{
                            time: {
                                unit: 'date'
                            },
                            gridLines: {
                                display: false
                            },
                            ticks: {
                                fontSize: 16
                            }
                        }],
                        yAxes: [{
                            ticks: {
                                min: 0,
                                max: Math.round(Math.max(...ton) * 1.1),
                                maxTicksLimit: 5,
                                fontSize: 22 // diperbesar dari 20 ke 28
                            },
                            gridLines: {
                                color: "rgba(0, 0, 0, .125)",
                            },
                            scaleLabel: {
                                display: true,
                                labelString: '(Tonnage)',
                                fontSize: 20 // diperbesar dari default
                            },
                        }]
                    },
                    annotation: {
                        annotations: [{
                            type: 'line',
                            mode: 'horizontal',
                            scaleID: 'y-axis-0',
                            value: Math.max(...ton),
                            borderColor: 'red',
                            borderWidth: 1,
                            label: {
                                enabled: true,
                                content: 'Threshold',
                                position: 'center'
                            }
                        }]
                    },
                    legend: {
                        display: false
                    },
                    tooltips: {
                        titleFontSize: 22,
                        bodyFontSize: 20,
                        displayColors: false,
                        backgroundColor: '#FFF',
                        titleFontColor: '#0066ff',
                        bodyFontColor: '#000',
                    }
                };
                myLineChart.update();
            }

           



            // Update berdasarkan bulan dan minggu yang dipilih
            document.getElementById('monthFilterArea').addEventListener('change', updateLineChart);
            document.getElementById('yearFilterArea').addEventListener('change', updateLineChart);

            // Update daily dan monthly chart
            function updateLineChart() {
                const year = document.getElementById('yearFilterArea').value;
                const month = document.getElementById('monthFilterArea').value;

                // Fetch data untuk chart bulanan dan harian
                fetch(`/area-data?year=${year}&month=${month}`)
                    .then(response => response.json())
                    .then(data => {
                        if (data && data.monthlyData) {
                            setMonthlyChartOption(data.monthlyData, year); // Kirim tahun ke fungsi
                        }
                        if (data && data.labels && data.tons) {
                            setDailyChartOption(data.labels, data.tons.map(value => parseInt(value.replace(/\./g, ''),
                                10)));
                        }
                    })
                    .catch(error => console.error('Error fetching area chart data:', error));
            }

            // Update tampilan daily dispatch tabel
            function updateTotalDispatch(selectedDate) {
                document.getElementById('dateFilterDropdown2').value = selectedDate; // Set nilai input tanggal ke hari ini

                // Mengubah teks tombol untuk menampilkan tanggal yang dipilih
                const formattedDate = new Date(selectedDate).toLocaleDateString('id-ID', {
                    day: '2-digit',
                    month: 'long',
                    year: 'numeric'
                });
                document.getElementById('dateDisplay2').innerText = formattedDate;

                // Fetch dan update data berdasarkan tanggal yang dipilih
                fetch(`/warehouse-dispatch-filter?date=${selectedDate}`)
                    .then(response => {
                        if (!response.ok) {
                            throw new Error(`HTTP error! status: ${response.status}`);
                        }
                        return response.json();
                    })
                    .then(data => {
                        // Cek apakah data yang diterima valid
                        if (data && data.data) { // Memastikan ada data yang diterima
                            // Kosongkan tabel sebelum menambahkan data baru
                            const tbody = document.querySelector('#dispatchTable tbody');
                            tbody.innerHTML = ''; // Menghapus isi tabel

                            // Urutkan data berdasarkan alfabet
                            const sortedData = Object.keys(data.data).sort().map(emp => ({
                                emp,
                                totalPickQty: data.data[emp].total_pick_qty || 0,
                                totalLoadQty: data.data[emp].total_load_qty || 0
                            }));

                            // Iterasi data untuk menambahkan baris ke tabel
                            sortedData.forEach(({
                                emp,
                                totalPickQty,
                                totalLoadQty
                            }) => {
                                tbody.innerHTML += `
                        <tr class="border-b border-gray-200">
                            <td class="text-left text-xl font-semibold">${emp}</td>
                            <td class="text-center text-xl">${totalPickQty.toLocaleString('id-ID')}</td>
                            <td class="text-center text-xl">${totalLoadQty.toLocaleString('id-ID')}</td>
                        </tr>
                    `;
                            });
                        } else {
                            // Jika tidak ada data, tampilkan baris kosong
                            const tbody = document.querySelector('#dispatchTable tbody');
                            tbody.innerHTML = `
                    <tr>
                        <td class="text-center text-2xl" colspan="3">No data available</td>
                    </tr>
                `;
                        }
                    })
                    .catch(error => console.error('Error fetching filtered data:', error));
            }

            // Event listener untuk tombol filter tanggal di dropdown daily dispatch
            document.getElementById('applyDateFilterDropdown2').addEventListener('click', function() {
                const selectedDate = document.getElementById('dateFilterDropdown2')
                    .value; // Ambil nilai tanggal yang dipilih
                updateTotalDispatch(selectedDate); // Panggil fungsi dengan parameter tanggal
            });

            let lastDateStr = null;

            function updateDateDisplay2() {
                const now = new Date();
                const formattedDate = now.toLocaleDateString('id-ID', {
                    day: '2-digit',
                    month: 'long',
                    year: 'numeric'
                });
                const dateStr = now.toISOString().split('T')[0];

                document.getElementById('dateDisplay2').innerText = formattedDate;
                document.getElementById('dateFilterDropdown2').value = dateStr;

                // Jika tanggal berubah, update data dispatch
                if (lastDateStr !== dateStr) {
                    lastDateStr = dateStr;
                    updateTotalDispatch(dateStr);
                }
            }

            // Jalankan saat halaman dimuat
            updateDateDisplay2();

            // Update setiap menit (60000 ms)
            setInterval(updateDateDisplay2, 60000);
        </script>
    @endpush

</x-app-layout>
