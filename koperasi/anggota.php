<?php
// include 'config/database.php';
include 'config/database.php';

// Tambah data anggota
if (isset($_POST['tambah'])) {
    $status_aktif = isset($_POST['status_aktif']) ? 1 : 0;
    $pegawai_id = $_POST['pegawai_id'];
    $kartu_diskon_id = $_POST['kartu_diskon_id'];
    mysqli_query($conn, "INSERT INTO anggota (status_aktif, pegawai_id, kartu_diskon_id) VALUES ('$status_aktif', '$pegawai_id', '$kartu_diskon_id')");
}

// Update data anggota
if (isset($_POST['update'])) {
    $id = $_POST['id'];
    $status_aktif = isset($_POST['status_aktif']) ? 1 : 0;
    $pegawai_id = $_POST['pegawai_id'];
    $kartu_diskon_id = $_POST['kartu_diskon_id'];
    mysqli_query($conn, "UPDATE anggota SET status_aktif='$status_aktif', pegawai_id='$pegawai_id', kartu_diskon_id='$kartu_diskon_id' WHERE id=$id");
    header("Location: anggota.php");
}

// Ambil data untuk edit
$edit = null;
if (isset($_GET['edit'])) {
    $id = $_GET['edit'];
    $result = mysqli_query($conn, "SELECT * FROM anggota WHERE id=$id");
    $edit = mysqli_fetch_assoc($result);
}

// Hapus data anggota
if (isset($_GET['hapus'])) {
    $id = $_GET['hapus'];
    mysqli_query($conn, "DELETE FROM anggota WHERE id=$id");
    header("Location: anggota.php");
}

// Ambil data anggota untuk tabel
$data = mysqli_query($conn, "SELECT a.*, p.nama AS nama_pegawai, k.nama AS nama_kartu, k.persen_diskon 
                             FROM anggota a
                             LEFT JOIN pegawai p ON a.pegawai_id = p.id
                             LEFT JOIN kartu_diskon k ON a.kartu_diskon_id = k.id");

// Ambil data pegawai & kartu_diskon untuk dropdown
$pegawai = mysqli_query($conn, "SELECT * FROM pegawai");
$kartu_diskon = mysqli_query($conn, "SELECT * FROM kartu_diskon");
?>

<?php include 'header.php'; ?>
<?php include 'sidebar.php'; ?>

<div id="layoutSidenav_content">
<div class="container-fluid px-4">
    <h2 class="mt-4">Data Anggota</h2>

    <!-- Form tambah/edit anggota -->
    <div class="card mb-4">
        <div class="card-header">
            <form method="POST" class="row g-2 align-items-center">
                <?php if ($edit): ?>
                    <input type="hidden" name="id" value="<?= $edit['id'] ?>">
                <?php endif; ?>
                <div class="col">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="status_aktif" id="status_aktif" value="1" <?= $edit && $edit['status_aktif'] ? 'checked' : '' ?>>
                        <label class="form-check-label" for="status_aktif">Status Aktif</label>
                    </div>
                </div>
                <div class="col">
                    <select name="pegawai_id" class="form-select" required>
                        <option value="">Pilih Pegawai</option>
                        <?php mysqli_data_seek($pegawai, 0); while ($p = mysqli_fetch_assoc($pegawai)) { ?>
                            <option value="<?= $p['id'] ?>" <?= $edit && $edit['pegawai_id'] == $p['id'] ? 'selected' : '' ?>>
                                <?= htmlspecialchars($p['nama']) ?>
                            </option>
                        <?php } ?>
                    </select>
                </div>
                <div class="col">
                    <select name="kartu_diskon_id" class="form-select" required>
                        <option value="">Pilih Kartu Diskon</option>
                        <?php mysqli_data_seek($kartu_diskon, 0); while ($k = mysqli_fetch_assoc($kartu_diskon)) { ?>
                            <option value="<?= $k['id'] ?>" <?= $edit && $edit['kartu_diskon_id'] == $k['id'] ? 'selected' : '' ?>>
                                <?= htmlspecialchars($k['nama']) ?> (<?= htmlspecialchars($k['persen_diskon']) ?>%)
                            </option>
                        <?php } ?>
                    </select>
                </div>
                <div class="col-auto">
                    <?php if ($edit): ?>
                        <button name="update" class="btn btn-success">Update</button>
                        <a href="anggota.php" class="btn btn-secondary">Batal</a>
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
                            <th>Status</th>
                            <th>Pegawai</th>
                            <th>Kartu Diskon</th>
                            <th>Persen Diskon</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $no = 1; while ($row = mysqli_fetch_assoc($data)) { ?>
                        <tr>
                            <td><?= $no++ ?></td>
                            <td><?= $row['status_aktif'] ? 'Aktif' : 'Tidak Aktif' ?></td>
                            <td><?= htmlspecialchars($row['nama_pegawai']) ?></td>
                            <td><?= htmlspecialchars($row['nama_kartu']) ?></td>
                            <td><?= htmlspecialchars($row['persen_diskon']) ?>%</td>
                            <td>
                                <a class="btn btn-sm btn-warning" href="?edit=<?= $row['id'] ?>">Edit</a>
                                <a class="btn btn-sm btn-danger" href="?hapus=<?= $row['id'] ?>" onclick="return confirm('Yakin hapus data ini?')">Hapus</a>
                            </td>
                        </tr>
                        <?php } ?>
                        <?php if (mysqli_num_rows($data) == 0): ?>
                        <tr>
                            <td colspan="6" class="text-center">Belum ada data anggota.</td>
                        </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php include 'footer.php'; ?>