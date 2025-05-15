<?php
// include 'config/database.php';
include 'config/database.php';

// Tambah data pembayaran
if (isset($_POST['tambah'])) {
    $jumlah_bayar = $_POST['jumlah_bayar'];
    $tanggal = $_POST['tanggal'];
    $pesanan_id = $_POST['pesanan_id'];

    mysqli_query($conn, "INSERT INTO pembayaran (jumlah_bayar, tanggal, pesanan_id)
                         VALUES ('$jumlah_bayar', '$tanggal', '$pesanan_id')");
}

// Update data pembayaran
if (isset($_POST['update'])) {
    $id = $_POST['id'];
    $jumlah_bayar = $_POST['jumlah_bayar'];
    $tanggal = $_POST['tanggal'];
    $pesanan_id = $_POST['pesanan_id'];

    mysqli_query($conn, "UPDATE pembayaran SET jumlah_bayar='$jumlah_bayar', tanggal='$tanggal', pesanan_id='$pesanan_id' WHERE id=$id");
    header("Location: pmbayaran.php");
}

// Ambil data untuk edit
$edit = null;
if (isset($_GET['edit'])) {
    $id = $_GET['edit'];
    $result = mysqli_query($conn, "SELECT * FROM pembayaran WHERE id=$id");
    $edit = mysqli_fetch_assoc($result);
}

// Hapus data pembayaran
if (isset($_GET['hapus'])) {
    $id = $_GET['hapus'];
    mysqli_query($conn, "DELETE FROM pembayaran WHERE id=$id");
    header("Location: pmbayaran.php");
}

// Ambil data pembayaran dengan join pesanan
$data = mysqli_query($conn, "
    SELECT pembayaran.*, pesanan.tanggal AS tanggal_pesanan 
    FROM pembayaran 
    LEFT JOIN pesanan ON pembayaran.pesanan_id = pesanan.id
");

// Ambil data pesanan untuk dropdown
$pesanan = mysqli_query($conn, "SELECT * FROM pesanan");
?>

<?php include 'header.php'; ?>
<?php include 'sidebar.php'; ?>

<div id="layoutSidenav_content">
<div class="container-fluid px-4">
    <h2 class="mt-4">Data Pembayaran</h2>

    <!-- Form tambah/edit pembayaran -->
    <div class="card mb-4">
        <div class="card-header">
            <form method="POST" class="row g-2 align-items-center">
                <?php if ($edit): ?>
                    <input type="hidden" name="id" value="<?= $edit['id'] ?>">
                <?php endif; ?>
                <div class="col">
                    <input type="number" name="jumlah_bayar" class="form-control" placeholder="Jumlah Bayar" min="0" step="0.01" required value="<?= $edit ? htmlspecialchars($edit['jumlah_bayar']) : '' ?>">
                </div>
                <div class="col">
                    <input type="date" name="tanggal" class="form-control" required value="<?= $edit ? htmlspecialchars($edit['tanggal']) : '' ?>">
                </div>
                <div class="col">
                    <select name="pesanan_id" class="form-select" required>
                        <option value="">Pilih Pesanan</option>
                        <?php mysqli_data_seek($pesanan, 0); while ($p = mysqli_fetch_assoc($pesanan)) { ?>
                            <option value="<?= $p['id'] ?>" <?= $edit && $edit['pesanan_id'] == $p['id'] ? 'selected' : '' ?>>
                                <?= 'ID: '.$p['id'].' - Tanggal: '.$p['tanggal'] ?>
                            </option>
                        <?php } ?>
                    </select>
                </div>
                <div class="col-auto">
                    <?php if ($edit): ?>
                        <button name="update" class="btn btn-success">Update</button>
                        <a href="pmbayaran.php" class="btn btn-secondary">Batal</a>
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
                            <th>Jumlah Bayar</th>
                            <th>Tanggal Bayar</th>
                            <th>ID Pesanan</th>
                            <th>Tanggal Pesanan</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $no = 1; while ($row = mysqli_fetch_assoc($data)) { ?>
                        <tr>
                            <td><?= $no++ ?></td>
                            <td><?= number_format($row['jumlah_bayar'], 2, ',', '.') ?></td>
                            <td><?= htmlspecialchars($row['tanggal']) ?></td>
                            <td><?= htmlspecialchars($row['pesanan_id']) ?></td>
                            <td><?= htmlspecialchars($row['tanggal_pesanan']) ?></td>
                            <td>
                                <a class="btn btn-sm btn-warning" href="?edit=<?= $row['id'] ?>">Edit</a>
                                <a class="btn btn-sm btn-danger" href="?hapus=<?= $row['id'] ?>" onclick="return confirm('Yakin hapus data ini?')">Hapus</a>
                            </td>
                        </tr>
                        <?php } ?>
                        <?php if (mysqli_num_rows($data) == 0): ?>
                        <tr>
                            <td colspan="6" class="text-center">Belum ada data pembayaran.</td>
                        </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php include 'footer.php'; ?>