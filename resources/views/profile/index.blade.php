@extends('layouts.app')

@section('content')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<div class="max-w-5xl mx-auto px-4 py-8">
    
    <div class="mb-6">
        <a href="{{ route('dashboard') }}" class="inline-flex items-center gap-2 text-slate-500 hover:text-slate-700 transition font-medium text-sm">
            <i class="fas fa-arrow-left"></i> Kembali ke Dashboard
        </a>
    </div>

    @if(session('success'))
        <div class="mb-6 p-4 bg-emerald-100 text-emerald-700 rounded-2xl font-semibold flex items-center gap-2 border border-emerald-200">
            <i class="fas fa-check-circle"></i> {{ session('success') }}
        </div>
    @endif

    <div class="bg-white rounded-3xl shadow-sm border border-slate-100 overflow-hidden mb-8">
        <div class="bg-gradient-to-r from-blue-600 to-indigo-700 px-6 py-8 text-white flex flex-col sm:flex-row items-center gap-4">
            <div class="w-20 h-20 bg-white/20 rounded-full flex items-center justify-center text-3xl font-bold uppercase border-2 border-white/50">
                {{ substr($user->username, 0, 2) }}
            </div>
            <div class="text-center sm:text-left flex-1">
                <h2 class="text-2xl font-bold tracking-tight">{{ $user->username }}</h2>
                <p class="text-blue-100 text-sm font-medium">{{ $user->email }}</p>
                <span class="mt-2 inline-block px-3 py-0.5 bg-white/20 rounded-full text-xs font-bold uppercase tracking-wider">
                    Role Aktif: {{ ucfirst($user->role) }}
                </span>
            </div>
        </div>

        <div class="p-6 space-y-6">
            <div class="flex flex-wrap gap-2 border-b border-slate-100 pb-4">
                <button onclick="bukaTab('data-profil-utama')" id="btn-data-profil-utama" class="tab-btn px-4 py-2.5 bg-blue-600 text-white text-xs font-bold rounded-xl transition shadow-sm">
                    <i class="fas fa-user mr-1"></i> Data Profil & Riwayat
                </button>
                
                @if(strtolower($user->role) === 'penjual')
                    <button onclick="bukaTab('laporan-penjualan-tab')" id="btn-laporan-penjualan-tab" class="tab-btn px-4 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold rounded-xl transition">
                        <i class="fas fa-chart-line mr-1 text-emerald-600"></i> Laporan Grafik Penjualan
                    </button>
                @else
                    <button onclick="bukaTab('laporan-pembelian-tab')" id="btn-laporan-pembelian-tab" class="tab-btn px-4 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold rounded-xl transition">
                        <i class="fas fa-shopping-bag mr-1 text-blue-600"></i> Laporan Grafik Pembelian
                    </button>
                @endif
            </div>

            {{-- TAB: DATA PROFIL --}}
            <div id="data-profil-utama" class="tab-content space-y-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-slate-700">
                    <div class="bg-slate-50 p-4 rounded-2xl border border-slate-100">
                        <span class="text-[10px] text-slate-400 block font-bold uppercase tracking-wider mb-1">No. Telepon</span>
                        <span class="font-semibold text-slate-700">{{ $user->no_telp ?? '-' }}</span>
                    </div>
                    <div class="bg-slate-50 p-4 rounded-2xl border border-slate-100">
                        <span class="text-[10px] text-slate-400 block font-bold uppercase tracking-wider mb-1">Alamat Domisili</span>
                        <span class="font-semibold text-slate-700">{{ $user->alamat ?? '-' }}</span>
                    </div>
                </div>

                <hr class="border-slate-100">

                <div class="flex flex-col sm:flex-row gap-4">
                    <form action="{{ route('profile.switch-role') }}" method="POST" class="flex-1">
                        @csrf
                        @if(strtolower($user->role) === 'pembeli')
                            <button type="submit" class="w-full bg-emerald-600 hover:bg-emerald-700 text-white font-bold py-3.5 px-4 rounded-xl transition flex items-center justify-center gap-2 shadow-sm shadow-emerald-100">
                                <i class="fas fa-store text-sm"></i> Beralih ke Mode Penjual
                            </button>
                        @else
                            <button type="submit" class="w-full bg-amber-600 hover:bg-amber-700 text-white font-bold py-3.5 px-4 rounded-xl transition flex items-center justify-center gap-2 shadow-sm shadow-amber-100">
                                <i class="fas fa-shopping-cart text-sm"></i> Beralih ke Mode Pembeli
                            </button>
                        @endif
                    </form>

                    <a href="{{ route('profile.edit') }}" class="flex-1 bg-blue-600 hover:bg-blue-700 text-white font-bold py-3.5 px-4 rounded-xl transition flex items-center justify-center gap-2 text-center shadow-sm shadow-blue-100">
                        <i class="fas fa-user-edit text-sm"></i> Edit Data Profil
                    </a>
                </div>

                <div class="mt-8">
                    @if(strtolower($user->role) === 'pembeli')
                        <div class="bg-white rounded-3xl border border-slate-100 p-2">
                            <div class="flex items-center gap-2 mb-4 px-4 pt-4">
                                <i class="fas fa-shopping-bag text-blue-600 text-lg"></i>
                                <h3 class="text-lg font-bold text-slate-800">Riwayat Pembelian & Booking Barang</h3>
                            </div>
                            <div class="overflow-x-auto">
                                <table class="w-full text-left border-collapse">
                                    <thead>
                                        <tr class="border-b border-slate-100 text-slate-400 text-xs font-bold uppercase tracking-wider">
                                            <th class="pb-3 pl-4">Produk/Kos</th>
                                            <th class="pb-3">Harga</th>
                                            <th class="pb-3">Tanggal Transaksi</th>
                                            <th class="pb-3 text-center pr-4">Status</th>
                                        </tr>
                                    </thead>
                                    <tbody class="text-sm font-medium text-slate-700 divide-y divide-slate-50">
                                        @forelse($riwayat_pembelian as $trx)
                                            <tr>
                                                <td class="py-4 pl-4">
                                                    <div class="font-bold text-slate-800">{{ $trx->barang->nama_barang ?? 'Produk Dihapus' }}</div>
                                                    <div class="text-xs text-slate-400">Trx ID: #{{ $trx->id_transaksi }}</div>
                                                </td>
                                                <td class="py-4">Rp {{ number_format($trx->total_harga, 0, ',', '.') }}</td>
                                                <td class="py-4 text-slate-500">{{ $trx->created_at ? $trx->created_at->format('d M Y, H:i') : '-' }}</td>
                                                <td class="py-4 text-center pr-4">
                                                    <span class="px-2.5 py-0.5 rounded-full text-xs font-bold uppercase tracking-wide
                                                        {{ strtolower($trx->status) === 'selesai' ? 'bg-green-100 text-green-700' : (strtolower($trx->status) === 'batal' ? 'bg-red-100 text-red-700' : 'bg-amber-100 text-amber-700') }}">
                                                        {{ $trx->status }}
                                                    </span>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="4" class="text-center py-8 text-slate-400 font-medium">
                                                    <i class="fas fa-folder-open text-2xl block mb-2 text-slate-300"></i>
                                                    Belum ada riwayat transaksi pembelian.
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    @else
                        <div class="bg-white rounded-3xl border border-slate-100 p-2">
                            <div class="flex items-center gap-2 mb-4 px-4 pt-4">
                                <i class="fas fa-file-invoice-dollar text-emerald-600 text-lg"></i>
                                <h3 class="text-lg font-bold text-slate-800">Riwayat Penjualan / Pesanan Masuk</h3>
                            </div>
                            <div class="overflow-x-auto">
                                <table class="w-full text-left border-collapse">
                                    <thead>
                                        <tr class="border-b border-slate-100 text-slate-400 text-xs font-bold uppercase tracking-wider">
                                            <th class="pb-3 pl-4">Nama Barang</th>
                                            <th class="pb-3">Nama Pembeli</th>
                                            <th class="pb-3">Total Dana</th>
                                            <th class="pb-3">Tanggal Transaksi</th>
                                            <th class="pb-3 text-center pr-4">Status</th>
                                        </tr>
                                    </thead>
                                    <tbody class="text-sm font-medium text-slate-700 divide-y divide-slate-50">
                                        @forelse($riwayat_penjualan as $trx)
                                            <tr>
                                                <td class="py-4 pl-4">
                                                    <div class="font-bold text-slate-800">{{ $trx->barang->nama_barang ?? 'Produk Dihapus' }}</div>
                                                    <div class="text-xs text-slate-400">Trx ID: #{{ $trx->id_transaksi }}</div>
                                                </td>
                                                <td class="py-4">
                                                    <div class="font-bold text-slate-700">{{ $trx->user->username ?? 'User Kos' }}</div>
                                                    <div class="text-xs text-slate-400">{{ $trx->user->no_telp ?? 'No Telp -' }}</div>
                                                </td>
                                                <td class="py-4 text-emerald-600 font-bold">Rp {{ number_format($trx->total_harga, 0, ',', '.') }}</td>
                                                <td class="py-4 text-slate-500">{{ $trx->created_at ? $trx->created_at->format('d M Y, H:i') : '-' }}</td>
                                                <td class="py-4 text-center pr-4">
                                                    <span class="px-2.5 py-0.5 rounded-full text-xs font-bold uppercase tracking-wide
                                                        {{ strtolower($trx->status) === 'selesai' ? 'bg-green-100 text-green-700' : (strtolower($trx->status) === 'batal' ? 'bg-red-100 text-red-700' : 'bg-amber-100 text-amber-700') }}">
                                                        {{ $trx->status }}
                                                    </span>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="5" class="text-center py-8 text-slate-400 font-medium">
                                                    <i class="fas fa-box-open text-2xl block mb-2 text-slate-300"></i>
                                                    Belum ada riwayat produk yang terjual.
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            {{-- TAB: LAPORAN PENJUALAN --}}
            @if(strtolower($user->role) === 'penjual')
            <div id="laporan-penjualan-tab" class="tab-content hidden space-y-6">

                {{-- TOMBOL DOWNLOAD REPORT --}}
                <div class="flex flex-wrap gap-3 p-4 bg-slate-50 rounded-2xl border border-slate-100">
                    <div class="flex-1">
                        <p class="text-xs font-bold text-slate-500 uppercase tracking-wider mb-1">Download Laporan</p>
                        <p class="text-xs text-slate-400">Unduh laporan transaksi penjualan kamu</p>
                    </div>
                    <a href="{{ route('report.penjual.pdf') }}" 
                       class="inline-flex items-center gap-2 bg-red-600 hover:bg-red-700 text-white font-bold px-4 py-2.5 rounded-xl text-xs transition shadow-sm">
                        <i class="fas fa-file-pdf"></i> Download PDF
                    </a>
                    <a href="{{ route('report.penjual.excel') }}" 
                       class="inline-flex items-center gap-2 bg-emerald-600 hover:bg-emerald-700 text-white font-bold px-4 py-2.5 rounded-xl text-xs transition shadow-sm">
                        <i class="fas fa-file-excel"></i> Download Excel
                    </a>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                    <div class="bg-slate-50 p-4 rounded-2xl border border-slate-100">
                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Produk Terjual (30 Hari)</p>
                        <p class="text-xl font-black text-slate-800 mt-1">{{ $sales_1_bulan_barang ?? 0 }} Unit</p>
                    </div>
                    <div class="bg-slate-50 p-4 rounded-2xl border border-slate-100">
                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Pendapatan Masuk (30 Hari)</p>
                        <p class="text-xl font-black text-emerald-600 mt-1">Rp {{ number_format($sales_1_bulan_dana ?? 0, 0, ',', '.') }}</p>
                    </div>
                    <div class="bg-slate-50 p-4 rounded-2xl border border-slate-100">
                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Produk Terjual (1 Tahun)</p>
                        <p class="text-xl font-black text-slate-800 mt-1">{{ $sales_1_tahun_barang ?? 0 }} Unit</p>
                    </div>
                    <div class="bg-slate-50 p-4 rounded-2xl border border-slate-100">
                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Pendapatan Masuk (1 Tahun)</p>
                        <p class="text-xl font-black text-emerald-600 mt-1">Rp {{ number_format($sales_1_tahun_dana ?? 0, 0, ',', '.') }}</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="bg-white p-4 rounded-2xl border border-slate-100 shadow-sm">
                        <h4 class="text-xs font-bold text-slate-500 mb-3 uppercase tracking-wider text-center">Grafik Keuntungan Pendapatan (Rupiah)</h4>
                        <div class="h-60"><canvas id="chartDanaPenjualan"></canvas></div>
                    </div>
                    <div class="bg-white p-4 rounded-2xl border border-slate-100 shadow-sm">
                        <h4 class="text-xs font-bold text-slate-500 mb-3 uppercase tracking-wider text-center">Grafik Volume Barang Terjual (Unit)</h4>
                        <div class="h-60"><canvas id="chartBarangPenjualan"></canvas></div>
                    </div>
                </div>
            </div>
            @endif

            {{-- TAB: LAPORAN PEMBELIAN --}}
            @if(strtolower($user->role) !== 'penjual')
            <div id="laporan-pembelian-tab" class="tab-content hidden space-y-6">
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                    <div class="bg-slate-50 p-4 rounded-2xl border border-slate-100">
                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Barang Dibeli (30 Hari)</p>
                        <p class="text-xl font-black text-slate-800 mt-1">{{ $buy_1_bulan_barang ?? 0 }} Unit</p>
                    </div>
                    <div class="bg-slate-50 p-4 rounded-2xl border border-slate-100">
                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Uang Dihabiskan (30 Hari)</p>
                        <p class="text-xl font-black text-blue-600 mt-1">Rp {{ number_format($buy_1_bulan_dana ?? 0, 0, ',', '.') }}</p>
                    </div>
                    <div class="bg-slate-50 p-4 rounded-2xl border border-slate-100">
                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Barang Dibeli (1 Tahun)</p>
                        <p class="text-xl font-black text-slate-800 mt-1">{{ $buy_1_tahun_barang ?? 0 }} Unit</p>
                    </div>
                    <div class="bg-slate-50 p-4 rounded-2xl border border-slate-100">
                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Uang Dihabiskan (1 Tahun)</p>
                        <p class="text-xl font-black text-blue-600 mt-1">Rp {{ number_format($buy_1_tahun_dana ?? 0, 0, ',', '.') }}</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="bg-white p-4 rounded-2xl border border-slate-100 shadow-sm">
                        <h4 class="text-xs font-bold text-slate-500 mb-3 uppercase tracking-wider text-center">Grafik Alokasi Dana Keluar (Rupiah)</h4>
                        <div class="h-60"><canvas id="chartDanaPembelian"></canvas></div>
                    </div>
                    <div class="bg-white p-4 rounded-2xl border border-slate-100 shadow-sm">
                        <h4 class="text-xs font-bold text-slate-500 mb-3 uppercase tracking-wider text-center">Grafik Kuantitas Kebutuhan Dibeli (Unit)</h4>
                        <div class="h-60"><canvas id="chartBarangPembelian"></canvas></div>
                    </div>
                </div>
            </div>
            @endif

        </div>
    </div>

</div>

<script>
    function bukaTab(idTab) {
        document.querySelectorAll('.tab-content').forEach(content => content.classList.add('hidden'));
        document.querySelectorAll('.tab-btn').forEach(btn => {
            btn.classList.remove('bg-blue-600', 'text-white', 'shadow-sm');
            btn.classList.add('bg-slate-100', 'text-slate-700');
        });
        document.getElementById(idTab).classList.remove('hidden');
        const activeBtn = document.getElementById('btn-' + idTab);
        activeBtn.classList.remove('bg-slate-100', 'text-slate-700');
        activeBtn.classList.add('bg-blue-600', 'text-white', 'shadow-sm');
    }

    document.addEventListener("DOMContentLoaded", function() {
        const opsiGrafik = { responsive: true, maintainAspectRatio: false, scales: { y: { beginAtZero: true } } };

        @if(strtolower($user->role) === 'penjual')
            new Chart(document.getElementById('chartDanaPenjualan'), {
                type: 'bar',
                data: {
                    labels: ['1 Bulan Terakhir', '1 Tahun Terakhir'],
                    datasets: [{ label: 'Total Keuntungan (Rp)', data: [{{ $sales_1_bulan_dana ?? 0 }}, {{ $sales_1_tahun_dana ?? 0 }}], backgroundColor: ['#10B981', '#059669'], borderRadius: 12 }]
                },
                options: opsiGrafik
            });
            new Chart(document.getElementById('chartBarangPenjualan'), {
                type: 'line',
                data: {
                    labels: ['1 Bulan Terakhir', '1 Tahun Terakhir'],
                    datasets: [{ label: 'Barang Laku (Unit)', data: [{{ $sales_1_bulan_barang ?? 0 }}, {{ $sales_1_tahun_barang ?? 0 }}], borderColor: '#3B82F6', backgroundColor: 'rgba(59,130,246,0.1)', fill: true, tension: 0.3 }]
                },
                options: opsiGrafik
            });
        @endif

        @if(strtolower($user->role) !== 'penjual')
            new Chart(document.getElementById('chartDanaPembelian'), {
                type: 'bar',
                data: {
                    labels: ['1 Bulan Terakhir', '1 Tahun Terakhir'],
                    datasets: [{ label: 'Dana Keluar (Rp)', data: [{{ $buy_1_bulan_dana ?? 0 }}, {{ $buy_1_tahun_dana ?? 0 }}], backgroundColor: ['#3B82F6', '#1D4ED8'], borderRadius: 12 }]
                },
                options: opsiGrafik
            });
            new Chart(document.getElementById('chartBarangPembelian'), {
                type: 'line',
                data: {
                    labels: ['1 Bulan Terakhir', '1 Tahun Terakhir'],
                    datasets: [{ label: 'Barang Dibeli (Unit)', data: [{{ $buy_1_bulan_barang ?? 0 }}, {{ $buy_1_tahun_barang ?? 0 }}], borderColor: '#818CF8', backgroundColor: 'rgba(129,140,248,0.1)', fill: true, tension: 0.3 }]
                },
                options: opsiGrafik
            });
        @endif
    });
</script>
@endsection