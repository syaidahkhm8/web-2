<?php
// include 'config/database.php';
include 'config/database.php';

// Tambah data detail pesanan
if (isset($_POST['tambah'])) {
    $pesanan_id = $_POST['pesanan_id'];
    $produk_id = $_POST['produk_id'];
    $jumlah = $_POST['jumlah'];
    mysqli_query($conn, "INSERT INTO detail_pesanan (pesanan_id, produk_id, jumlah) VALUES ('$pesanan_id', '$produk_id', '$jumlah')");
}

// Update data detail pesanan
if (isset($_POST['update'])) {
    $pesanan_id = $_POST['pesanan_id'];
    $produk_id = $_POST['produk_id'];
    $jumlah = $_POST['jumlah'];
    mysqli_query($conn, "UPDATE detail_pesanan SET jumlah='$jumlah' WHERE pesanan_id=$pesanan_id AND produk_id=$produk_id");
    header("Location: detail_pesanan.php");
}

// Ambil data untuk edit
$edit = null;
if (isset($_GET['edit_pesanan']) && isset($_GET['edit_produk'])) {
    $pesanan_id = $_GET['edit_pesanan'];
    $produk_id = $_GET['edit_produk'];
    $result = mysqli_query($conn, "SELECT * FROM detail_pesanan WHERE pesanan_id=$pesanan_id AND produk_id=$produk_id");
    $edit = mysqli_fetch_assoc($result);
}

// Hapus data detail pesanan
if (isset($_GET['hapus_pesanan']) && isset($_GET['hapus_produk'])) {
    $pesanan_id = $_GET['hapus_pesanan'];
    $produk_id = $_GET['hapus_produk'];
    mysqli_query($conn, "DELETE FROM detail_pesanan WHERE pesanan_id=$pesanan_id AND produk_id=$produk_id");
    header("Location: detail_pesanan.php");
}

// Ambil semua data detail pesanan dengan join ke pesanan dan produk
$data = mysqli_query($conn, "
    SELECT dp.*, p.tanggal, pr.nama AS nama_produk
    FROM detail_pesanan dp
    LEFT JOIN pesanan p ON dp.pesanan_id = p.id
    LEFT JOIN produk pr ON dp.produk_id = pr.id
");

// Ambil data pesanan dan produk untuk dropdown
$pesanan = mysqli_query($conn, "SELECT * FROM pesanan");
$produk = mysqli_query($conn, "SELECT * FROM produk");
?>

<?php include 'header.php'; ?>
<?php include 'sidebar.php'; ?>

<div id="layoutSidenav_content">
<div class="container-fluid px-4">
    <h2 class="mt-4">Data Detail Pesanan</h2>

    <!-- Form tambah/edit detail pesanan -->
    <div class="card mb-4">
        <div class="card-header">
            <form method="POST" class="row g-2 align-items-center">
                <?php if ($edit): ?>
                    <input type="hidden" name="pesanan_id" value="<?= $edit['pesanan_id'] ?>">
                    <input type="hidden" name="produk_id" value="<?= $edit['produk_id'] ?>">
                <?php endif; ?>
                <div class="col">
                    <select name="pesanan_id" class="form-select" required <?= $edit ? 'disabled' : '' ?>>
                        <option value="">Pilih Pesanan</option>
                        <?php mysqli_data_seek($pesanan, 0); while ($p = mysqli_fetch_assoc($pesanan)) { ?>
                            <option value="<?= $p['id'] ?>" <?= $edit && $edit['pesanan_id'] == $p['id'] ? 'selected' : '' ?>>
                                <?= 'ID: '.$p['id'].' - Tanggal: '.$p['tanggal'] ?>
                            </option>
                        <?php } ?>
                    </select>
                </div>
                <div class="col">
                    <select name="produk_id" class="form-select" required <?= $edit ? 'disabled' : '' ?>>
                        <option value="">Pilih Produk</option>
                        <?php mysqli_data_seek($produk, 0); while ($pr = mysqli_fetch_assoc($produk)) { ?>
                            <option value="<?= $pr['id'] ?>" <?= $edit && $edit['produk_id'] == $pr['id'] ? 'selected' : '' ?>>
                                <?= htmlspecialchars($pr['nama']) ?>
                            </option>
                        <?php } ?>
                    </select>
                </div>
                <div class="col">
                    <input type="number" name="jumlah" class="form-control" placeholder="Jumlah" min="1" required value="<?= $edit ? htmlspecialchars($edit['jumlah']) : '' ?>">
                </div>
                <div class="col-auto">
                    <?php if ($edit): ?>
                        <button name="update" class="btn btn-success">Update</button>
                        <a href="detail_pesanan.php" class="btn btn-secondary">Batal</a>
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
                            <th>ID Pesanan</th>
                            <th>Tanggal Pesanan</th>
                            <th>Produk</th>
                            <th>Jumlah</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $no = 1; while ($row = mysqli_fetch_assoc($data)) { ?>
                        <tr>
                            <td><?= $no++ ?></td>
                            <td><?= htmlspecialchars($row['pesanan_id']) ?></td>
                            <td><?= htmlspecialchars($row['tanggal']) ?></td>
                            <td><?= htmlspecialchars($row['nama_produk']) ?></td>
                            <td><?= htmlspecialchars($row['jumlah']) ?></td>
                            <td>
                                <a class="btn btn-sm btn-warning" href="?edit_pesanan=<?= $row['pesanan_id'] ?>&edit_produk=<?= $row['produk_id'] ?>">Edit</a>
                                <a class="btn btn-sm btn-danger" href="?hapus_pesanan=<?= $row['pesanan_id'] ?>&hapus_produk=<?= $row['produk_id'] ?>" onclick="return confirm('Yakin hapus data ini?')">Hapus</a>
                            </td>
                        </tr>
                        <?php } ?>
                        <?php if (mysqli_num_rows($data) == 0): ?>
                        <tr>
                            <td colspan="6" class="text-center">Belum ada data detail pesanan.</td>
                        </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php include 'footer.php'; ?>