<?php
include 'config/database.php';

// Ambil jumlah data dari masing-masing tabel sesuai database
$jml_anggota      = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM anggota"));
$jml_pegawai      = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM pegawai"));
$jml_produk       = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM produk"));
$jml_jenis_produk = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM jenis_produk"));
$jml_kartu_diskon = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM kartu_diskon"));
$jml_pesanan      = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM pesanan"));
$jml_detail_pesanan = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM detail_pesanan"));
$jml_pembayaran   = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM pembayaran"));

// Query jumlah anggota berdasarkan tahun masuk
$tahunData = [];
$jumlahanggota = [];
$result = mysqli_query($conn, "
    SELECT k.nama AS nama_kartu, COUNT(a.id) AS total
    FROM kartu_diskon k
    LEFT JOIN anggota a ON a.kartu_diskon_id = k.id
    GROUP BY k.id
    ORDER BY k.nama
");
while ($row = mysqli_fetch_assoc($result)) {
    $tahunData[] = $row['nama_kartu'];
    $jumlahanggota[] = $row['total'];
}

// Query jumlah anggota berdasarkan kartu_diskon
$kartuDiskonData = [];
$jumlahAnggotaPerKartu = [];
$result = mysqli_query($conn, "
    SELECT k.nama AS nama_kartu, COUNT(a.id) AS total 
    FROM kartu_diskon k
    LEFT JOIN anggota a ON a.kartu_diskon_id = k.id
    GROUP BY k.id
    ORDER BY k.nama
");
while ($row = mysqli_fetch_assoc($result)) {
    $kartuDiskonData[] = $row['nama_kartu'];
    $jumlahAnggotaPerKartu[] = $row['total'];
}

// Ambil jumlah anggota aktif & tidak aktif
$jml_anggota_aktif = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM anggota WHERE status_aktif=1"));
$jml_anggota_nonaktif = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM anggota WHERE status_aktif=0"));

// Ambil jumlah anggota per kartu diskon
$kartu_labels = [];
$kartu_data = [];
$res_kartu = mysqli_query($conn, "
    SELECT k.nama, COUNT(a.id) as total 
    FROM kartu_diskon k
    LEFT JOIN anggota a ON a.kartu_diskon_id = k.id
    GROUP BY k.id
");
while ($row = mysqli_fetch_assoc($res_kartu)) {
    $kartu_labels[] = $row['nama'];
    $kartu_data[] = $row['total'];
}
?>



<?php include 'header.php'; ?>
<?php include 'sidebar.php'; ?>


<link href="css/table.css" rel="stylesheet" />
<!-- Konten halaman di sini -->
<div id="layoutSidenav_content">
    <main>
        <div class="container-fluid px-4">
            <h1 class="mt-4">Dashboard</h1>
            <ol class="breadcrumb mb-4">
                <li class="breadcrumb-item active">Dashboard</li>
            </ol>
            <div class="row">
                <div class="col-xl-3 col-md-6">
                    <div class="card bg-primary text-white mb-4">
                        <div class="card-body">
                            Manajemen Anggota
                            <span class="badge bg-light text-dark ms-2"><?= $jml_anggota ?></span>
                        </div>
                        <div class="card-footer d-flex align-items-center justify-content-between">
                            <a class="small text-white stretched-link" href="anggota.php">Lihat Detail</a>
                            <div class="small text-white"><i class="fas fa-angle-right"></i></div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-md-6">
                    <div class="card bg-warning text-white mb-4">
                        <div class="card-body">
                            Pengelolaan Data Produk
                            <span class="badge bg-light text-dark ms-2"><?= $jml_produk ?></span>
                        </div>
                        <div class="card-footer d-flex align-items-center justify-content-between">
                            <a class="small text-white stretched-link" href="produk.php">Lihat Detail</a>
                            <div class="small text-white"><i class="fas fa-angle-right"></i></div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-md-6">
                    <div class="card bg-success text-white mb-4">
                        <div class="card-body">
                            Pemesanan Produk
                            <span class="badge bg-light text-dark ms-2"><?= $jml_pesanan ?></span>
                        </div>
                        <div class="card-footer d-flex align-items-center justify-content-between">
                            <a class="small text-white stretched-link" href="pesanan.php">Lihat Detail</a>
                            <div class="small text-white"><i class="fas fa-angle-right"></i></div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-md-6">
                    <div class="card bg-danger text-white mb-4">
                        <div class="card-body">
                            Transaksi Keuangan
                            <span class="badge bg-light text-dark ms-2"><?= $jml_pembayaran ?></span>
                        </div>
                        <div class="card-footer d-flex align-items-center justify-content-between">
                            <a class="small text-white stretched-link" href="pembayaran.php">Lihat Detail</a>
                            <div class="small text-white"><i class="fas fa-angle-right"></i></div>
                        </div>
                    </div>
                </div>
            </div>
                        
            <div class="card mb-4">
                <div class="card-header">
                    <i class="fas fa-table me-1"></i>
                    Data Anggota
                </div>
                <div class="card-body">
                    <table id="datatablesSimple">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Status Aktif</th>
                                <th>Pegawai</th>
                                <th>Kartu Diskon</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $query = "
                                SELECT 
                                    a.id,
                                    a.status_aktif,
                                    p.nama AS nama_pegawai,
                                    k.nama AS nama_kartu
                                FROM anggota a
                                LEFT JOIN pegawai p ON a.pegawai_id = p.id
                                LEFT JOIN kartu_diskon k ON a.kartu_diskon_id = k.id
                            ";
                            $res = mysqli_query($conn, $query);
                            while ($d = mysqli_fetch_assoc($res)) {
                                $status = $d['status_aktif'] ? 'Aktif' : 'Tidak Aktif';
                                echo "<tr>
                                    <td>{$d['id']}</td>
                                    <td>{$status}</td>
                                    <td>" . htmlspecialchars($d['nama_pegawai']) . "</td>
                                    <td>" . htmlspecialchars($d['nama_kartu']) . "</td>
                                </tr>";
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>

<?php
// Query produk per jenis_produk
$jenis_produk_labels = [];
$produk_per_jenis = [];
$res_jenis = mysqli_query($conn, "
    SELECT jp.nama AS jenis, COUNT(p.id) AS total
    FROM jenis_produk jp
    LEFT JOIN produk p ON p.jenis_produk_id = jp.id
    GROUP BY jp.id
    ORDER BY jp.nama
");
while ($row = mysqli_fetch_assoc($res_jenis)) {
    $jenis_produk_labels[] = $row['jenis'];
    $produk_per_jenis[] = $row['total'];
}

// Query pesanan per produk
$produk_labels = [];
$pesanan_per_produk = [];
$res_produk = mysqli_query($conn, "
    SELECT pr.nama AS produk, COALESCE(SUM(dp.jumlah),0) AS total
    FROM produk pr
    LEFT JOIN detail_pesanan dp ON dp.produk_id = pr.id
    GROUP BY pr.id
    ORDER BY pr.nama
");
while ($row = mysqli_fetch_assoc($res_produk)) {
    $produk_labels[] = $row['produk'];
    $pesanan_per_produk[] = $row['total'];
}
?>

            <div class="card mb-4">
                <div class="card-header">
                    <i class="fas fa-table me-1"></i>
                    Data Jumlah Tabel
                </div>
                <div class="card-body">
                    <div class="row text-center">
                        <div class="col-md-3 mb-3">
                            <div class="card border-primary">
                                <div class="card-body">
                                    <h5 class="card-title">Anggota</h5>
                                    <span class="display-6"><?= $jml_anggota ?></span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3 mb-3">
                            <div class="card border-secondary">
                                <div class="card-body">
                                    <h5 class="card-title">Pegawai</h5>
                                    <span class="display-6"><?= $jml_pegawai ?></span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3 mb-3">
                            <div class="card border-success">
                                <div class="card-body">
                                    <h5 class="card-title">Produk</h5>
                                    <span class="display-6"><?= $jml_produk ?></span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3 mb-3">
                            <div class="card border-warning">
                                <div class="card-body">
                                    <h5 class="card-title">Jenis Produk</h5>
                                    <span class="display-6"><?= $jml_jenis_produk ?></span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3 mb-3">
                            <div class="card border-info">
                                <div class="card-body">
                                    <h5 class="card-title">Kartu Diskon</h5>
                                    <span class="display-6"><?= $jml_kartu_diskon ?></span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3 mb-3">
                            <div class="card border-danger">
                                <div class="card-body">
                                    <h5 class="card-title">Pesanan</h5>
                                    <span class="display-6"><?= $jml_pesanan ?></span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3 mb-3">
                            <div class="card border-dark">
                                <div class="card-body">
                                    <h5 class="card-title">Detail Pesanan</h5>
                                    <span class="display-6"><?= $jml_detail_pesanan ?></span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3 mb-3">
                            <div class="card border-primary">
                                <div class="card-body">
                                    <h5 class="card-title">Pembayaran</h5>
                                    <span class="display-6"><?= $jml_pembayaran ?></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </main>
    <footer class="py-4 bg-light mt-auto">
        <div class="container-fluid px-4">
            <div class="d-flex align-items-center justify-content-between small">
                <div class="text-muted">
                    Copyright &copy; Syaidah Khumairoh <?= date('Y'); ?>
                </div>
                <div>
                    <a href="#">Privacy Policy</a>
                    &middot;
                    <a href="#">Terms &amp; Conditions</a>
                </div>
            </div>
        </div>
    </footer>
</div>


<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdn.jsdelivr.net/npm/simple-datatables@latest" integrity="sha384-8x2c3b4e5f5a5d5f5a5d5f5a5d5f5a5d5f5a5d5f5a5d" crossorigin="anonymous"></script>    



