<?php
include 'config/database.php';

// Tambah data pesanan
if (isset($_POST['tambah'])) {
    $tanggal = $_POST['tanggal'];
    $diskon = $_POST['diskon'];
    $status_bayar = isset($_POST['status_bayar']) ? 1 : 0;
    $anggota_id = $_POST['anggota_id'];
    mysqli_query($conn, "INSERT INTO pesanan (tanggal, diskon, status_bayar, anggota_id) VALUES ('$tanggal', '$diskon', '$status_bayar', '$anggota_id')");
}

// Update data pesanan
if (isset($_POST['update'])) {
    $id = $_POST['id'];
    $tanggal = $_POST['tanggal'];
    $diskon = $_POST['diskon'];
    $status_bayar = isset($_POST['status_bayar']) ? 1 : 0;
    $anggota_id = $_POST['anggota_id'];
    mysqli_query($conn, "UPDATE pesanan SET tanggal='$tanggal', diskon='$diskon', status_bayar='$status_bayar', anggota_id='$anggota_id' WHERE id=$id");
    header("Location: pesanan.php");
}

// Ambil data untuk edit
$edit = null;
if (isset($_GET['edit'])) {
    $id = $_GET['edit'];
    $result = mysqli_query($conn, "SELECT * FROM pesanan WHERE id=$id");
    $edit = mysqli_fetch_assoc($result);
}

// Hapus data pesanan
if (isset($_GET['hapus'])) {
    $id = $_GET['hapus'];
    mysqli_query($conn, "DELETE FROM pesanan WHERE id=$id");
    header("Location: pesanan.php");
}

// Ambil data pesanan dengan nama anggota
$data = mysqli_query($conn, "SELECT p.*, a.id AS anggota_id, ag.status_aktif, ag.pegawai_id, ag.kartu_diskon_id
                             FROM pesanan p
                             LEFT JOIN anggota ag ON p.anggota_id = ag.id
                             LEFT JOIN anggota a ON p.anggota_id = a.id");

// Ambil data anggota untuk dropdown
$anggota = mysqli_query($conn, "SELECT a.id, p.nama AS nama_pegawai FROM anggota a LEFT JOIN pegawai p ON a.pegawai_id = p.id");
?>

<?php include 'header.php'; ?>
<?php include 'sidebar.php'; ?>

<div id="layoutSidenav_content">
<div class="container-fluid px-4">
    <h2 class="mt-4">Data Pesanan</h2>

    <!-- Form tambah/edit pesanan -->
    <div class="card mb-4">
        <div class="card-header">
            <form method="POST" class="row g-2 align-items-center">
                <?php if ($edit): ?>
                    <input type="hidden" name="id" value="<?= $edit['id'] ?>">
                <?php endif; ?>
                <div class="col">
                    <input type="date" name="tanggal" class="form-control" required value="<?= $edit ? htmlspecialchars($edit['tanggal']) : '' ?>">
                </div>
                <div class="col">
                    <input type="number" name="diskon" class="form-control" placeholder="Diskon" min="0" max="100" required value="<?= $edit ? htmlspecialchars($edit['diskon']) : '' ?>">
                </div>
                <div class="col">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="status_bayar" id="status_bayar" value="1" <?= $edit && $edit['status_bayar'] ? 'checked' : '' ?>>
                        <label class="form-check-label" for="status_bayar">Sudah Bayar</label>
                    </div>
                </div>
                <div class="col">
                    <select name="anggota_id" class="form-select" required>
                        <option value="">Pilih Anggota</option>
                        <?php mysqli_data_seek($anggota, 0); while ($a = mysqli_fetch_assoc($anggota)) { ?>
                            <option value="<?= $a['id'] ?>" <?= $edit && $edit['anggota_id'] == $a['id'] ? 'selected' : '' ?>>
                                <?= 'ID: '.$a['id'].' - '.htmlspecialchars($a['nama_pegawai']) ?>
                            </option>
                        <?php } ?>
                    </select>
                </div>
                <div class="col-auto">
                    <?php if ($edit): ?>
                        <button name="update" class="btn btn-success">Update</button>
                        <a href="pesanan.php" class="btn btn-secondary">Batal</a>
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
                            <th>Tanggal</th>
                            <th>Diskon (%)</th>
                            <th>Status Bayar</th>
                            <th>Anggota</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $no = 1; while ($row = mysqli_fetch_assoc($data)) { ?>
                        <tr>
                            <td><?= $no++ ?></td>
                            <td><?= htmlspecialchars($row['tanggal']) ?></td>
                            <td><?= htmlspecialchars($row['diskon']) ?></td>
                            <td><?= $row['status_bayar'] ? 'Sudah Bayar' : 'Belum Bayar' ?></td>
                            <td><?= 'ID: '.$row['anggota_id'] ?></td>
                            <td>
                                <a class="btn btn-sm btn-warning" href="?edit=<?= $row['id'] ?>">Edit</a>
                                <a class="btn btn-sm btn-danger" href="?hapus=<?= $row['id'] ?>" onclick="return confirm('Yakin hapus data ini?')">Hapus</a>
                            </td>
                        </tr>
                        <?php } ?>
                        <?php if (mysqli_num_rows($data) == 0): ?>
                        <tr>
                            <td colspan="6" class="text-center">Belum ada data pesanan.</td>
                        </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php include 'footer.php'; ?>