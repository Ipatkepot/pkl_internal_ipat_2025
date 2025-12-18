@extends('layouts.app')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">

    <!-- HEADER -->
    <h4 class="fw-bold mb-4">Dashboard Admin</h4>

    <!-- STAT CARD -->
    <div class="row g-4 mb-4">
        <div class="col-md-3">
            <div class="card bg-dark text-white">
                <div class="card-body">
                    <h6>Total Produk</h6>
                    <h3>120</h3>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card bg-dark text-white">
                <div class="card-body">
                    <h6>Total Transaksi</h6>
                    <h3>85</h3>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card bg-dark text-white">
                <div class="card-body">
                    <h6>Penghasilan</h6>
                    <h3>Rp 12.500.000</h3>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card bg-dark text-white">
                <div class="card-body">
                    <h6>User Aktif</h6>
                    <h3>24</h3>
                </div>
            </div>
        </div>
    </div>

    <!-- CHART -->
    <div class="row mb-4">
        <div class="col-md-8">
            <div class="card bg-dark text-white">
                <div class="card-body">
                    <h5>Grafik Penjualan</h5>
                    <canvas id="salesChart" height="120"></canvas>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card bg-dark text-white">
                <div class="card-body">
                    <h5>Produk Terlaris</h5>
                    <canvas id="productChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- TABLE -->
    <div class="card bg-dark text-white">
        <div class="card-body">
            <h5>Data Produk</h5>
            <div class="table-responsive">
                <table class="table table-dark table-hover">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Nama Produk</th>
                            <th>Stok</th>
                            <th>Harga</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>1</td>
                            <td>Beras Premium</td>
                            <td>50</td>
                            <td>Rp 65.000</td>
                            <td><span class="badge bg-success">Aktif</span></td>
                        </tr>
                        <tr>
                            <td>2</td>
                            <td>Minyak Goreng</td>
                            <td>20</td>
                            <td>Rp 32.000</td>
                            <td><span class="badge bg-warning">Hampir Habis</span></td>
                        </tr>
                        <tr>
                            <td>3</td>
                            <td>Gula Pasir</td>
                            <td>0</td>
                            <td>Rp 14.000</td>
                            <td><span class="badge bg-danger">Habis</span></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>
@endsection

@section('scripts')
<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
const salesChart = new Chart(document.getElementById('salesChart'), {
    type: 'line',
    data: {
        labels: ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun'],
        datasets: [{
            label: 'Penjualan',
            data: [12, 19, 15, 22, 30, 25],
            borderColor: '#ffa31a',
            backgroundColor: 'rgba(255,163,26,0.2)',
            fill: true,
            tension: 0.4
        }]
    }
});

const productChart = new Chart(document.getElementById('productChart'), {
    type: 'doughnut',
    data: {
        labels: ['Beras', 'Minyak', 'Gula'],
        datasets: [{
            data: [50, 30, 20],
            backgroundColor: ['#ffa31a', '#00cfe8', '#ea5455']
        }]
    }
});
</script>
@endsection
