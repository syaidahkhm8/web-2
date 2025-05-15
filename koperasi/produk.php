<?php
// include 'config/database.php';
include 'config/database.php';

// Tambah data produk
if (isset($_POST['tambah'])) {
    $kode = $_POST['kode'];
    $nama = $_POST['nama'];
    $deskripsi = $_POST['deskripsi'];
    $harga = $_POST['harga'];
    $stok = $_POST['stok'];
    $jenis_produk_id = $_POST['jenis_produk_id'];

    mysqli_query($conn, "INSERT INTO produk (kode, nama, deskripsi, harga, stok, jenis_produk_id)
                         VALUES ('$kode', '$nama', '$deskripsi', '$harga', '$stok', '$jenis_produk_id')");
}

// Update data produk
if (isset($_POST['update'])) {
    $id = $_POST['id'];
    $kode = $_POST['kode'];
    $nama = $_POST['nama'];
    $deskripsi = $_POST['deskripsi'];
    $harga = $_POST['harga'];
    $stok = $_POST['stok'];
    $jenis_produk_id = $_POST['jenis_produk_id'];

    mysqli_query($conn, "UPDATE produk SET kode='$kode', nama='$nama', deskripsi='$deskripsi', harga='$harga', stok='$stok', jenis_produk_id='$jenis_produk_id' WHERE id=$id");
    header("Location: produk.php");
}

// Ambil data untuk edit
$edit = null;
if (isset($_GET['edit'])) {
    $id = $_GET['edit'];
    $result = mysqli_query($conn, "SELECT * FROM produk WHERE id=$id");
    $edit = mysqli_fetch_assoc($result);
}

// Hapus data produk
if (isset($_GET['hapus'])) {
    $id = $_GET['hapus'];
    mysqli_query($conn, "DELETE FROM produk WHERE id=$id");
    header("Location: produk.php");
}

// Ambil data produk dengan nama jenis produk
$data = mysqli_query($conn, "SELECT p.*, j.nama AS nama_jenis 
                             FROM produk p 
                             LEFT JOIN jenis_produk j ON p.jenis_produk_id = j.id");

// Ambil data jenis produk untuk dropdown
$jenis_produk = mysqli_query($conn, "SELECT * FROM jenis_produk");
?>

<?php include 'header.php'; ?>
<?php include 'sidebar.php'; ?>

<div id="layoutSidenav_content">
<div class="container-fluid px-4">
    <h2 class="mt-4">Data Produk</h2>

    <!-- Form tambah/edit produk -->
    <div class="card mb-4">
        <div class="card-header">
            <form method="POST" class="row g-2 align-items-center">
                <?php if ($edit): ?>
                    <input type="hidden" name="id" value="<?= $edit['id'] ?>">
                <?php endif; ?>
                <div class="col">
                    <input type="text" name="kode" class="form-control" placeholder="Kode Produk" maxlength="5" required value="<?= $edit ? htmlspecialchars($edit['kode']) : '' ?>">
                </div>
                <div class="col">
                    <input type="text" name="nama" class="form-control" placeholder="Nama Produk" maxlength="45" required value="<?= $edit ? htmlspecialchars($edit['nama']) : '' ?>">
                </div>
                <div class="col">
                    <input type="text" name="deskripsi" class="form-control" placeholder="Deskripsi" value="<?= $edit ? htmlspecialchars($edit['deskripsi']) : '' ?>">
                </div>
                <div class="col">
                    <input type="number" name="harga" class="form-control" placeholder="Harga" min="0" step="0.01" required value="<?= $edit ? htmlspecialchars($edit['harga']) : '' ?>">
                </div>
                <div class="col">
                    <input type="number" name="stok" class="form-control" placeholder="Stok" min="0" required value="<?= $edit ? htmlspecialchars($edit['stok']) : '' ?>">
                </div>
                <div class="col">
                    <select name="jenis_produk_id" class="form-select" required>
                        <option value="">Pilih Jenis Produk</option>
                        <?php mysqli_data_seek($jenis_produk, 0); while ($j = mysqli_fetch_assoc($jenis_produk)) { ?>
                            <option value="<?= $j['id'] ?>" <?= $edit && $edit['jenis_produk_id'] == $j['id'] ? 'selected' : '' ?>>
                                <?= htmlspecialchars($j['nama']) ?>
                            </option>
                        <?php } ?>
                    </select>
                </div>
                <div class="col-auto">
                    <?php if ($edit): ?>
                        <button name="update" class="btn btn-success">Update</button>
                        <a href="produk.php" class="btn btn-secondary">Batal</a>
                    <?php else: ?>
                        <button name="tambah" class="btn btn-primary">Tambah</button>
                    <?php endif; ?>
                </div>
            </form>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover align-middle">
                    <thead class="table-dark">
                        <tr>
                            <th>No</th>
                            <th>Kode</th>
                            <th>Nama Produk</th>
                            <th>Deskripsi</th>
                            <th>Harga</th>
                            <th>Stok</th>
                            <th>Jenis Produk</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $no = 1; while ($row = mysqli_fetch_assoc($data)) { ?>
                        <tr>
                            <td><?= $no++ ?></td>
                            <td><?= htmlspecialchars($row['kode']) ?></td>
                            <td><?= htmlspecialchars($row['nama']) ?></td>
                            <td><?= htmlspecialchars($row['deskripsi']) ?></td>
                            <td><?= number_format($row['harga'], 2, ',', '.') ?></td>
                            <td><?= htmlspecialchars($row['stok']) ?></td>
                            <td><?= htmlspecialchars($row['nama_jenis']) ?></td>
                            <td>
                                <a class="btn btn-sm btn-warning" href="?edit=<?= $row['id'] ?>">Edit</a>
                                <a class="btn btn-sm btn-danger" href="?hapus=<?= $row['id'] ?>" onclick="return confirm('Yakin hapus data ini?')">Hapus</a>
                            </td>
                        </tr>
                        <?php } ?>
                        <?php if (mysqli_num_rows($data) == 0): ?>
                        <tr>
                            <td colspan="8" class="text-center">Belum ada data produk.</td>
                        </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php include 'footer.php'; ?>