<?php
// include 'config/database.php';
include 'config/database.php';

// Tambah data
if (isset($_POST['tambah'])) {
    $nip = $_POST['nip'];
    $nama = $_POST['nama'];
    $jenis_kelamin = $_POST['jenis_kelamin'];
    $jabatan = $_POST['jabatan'];
    mysqli_query($conn, "INSERT INTO pegawai (nip, nama, jenis_kelamin, jabatan) VALUES ('$nip','$nama','$jenis_kelamin','$jabatan')");
}

// Edit data
if (isset($_POST['update'])) {
    $id = $_POST['id'];
    $nip = $_POST['nip'];
    $nama = $_POST['nama'];
    $jenis_kelamin = $_POST['jenis_kelamin'];
    $jabatan = $_POST['jabatan'];
    mysqli_query($conn, "UPDATE pegawai SET nip='$nip', nama='$nama', jenis_kelamin='$jenis_kelamin', jabatan='$jabatan' WHERE id=$id");
    header("Location: pegawai.php");
}

// Ambil data untuk edit
$edit = null;
if (isset($_GET['edit'])) {
    $id = $_GET['edit'];
    $result = mysqli_query($conn, "SELECT * FROM pegawai WHERE id=$id");
    $edit = mysqli_fetch_assoc($result);
}

// Hapus data
if (isset($_GET['hapus'])) {
    $id = $_GET['hapus'];
    mysqli_query($conn, "DELETE FROM pegawai WHERE id=$id");
    header("Location: pegawai.php");
}

// Ambil data
$data = mysqli_query($conn, "SELECT * FROM pegawai");
?>

<?php include 'header.php'; ?>
<?php include 'sidebar.php'; ?>

<div id="layoutSidenav_content">
    <main>
        <div class="container-fluid px-4">
            <h2 class="mt-4">Data Pegawai</h2>
            <div class="card mb-4">
                <div class="card-header">
                    <form method="POST" class="row g-2 align-items-center">
                        <?php if ($edit): ?>
                            <input type="hidden" name="id" value="<?= $edit['id'] ?>">
                        <?php endif; ?>
                        <div class="col">
                            <input type="text" name="nip" class="form-control" placeholder="NIP" maxlength="10" required value="<?= $edit ? htmlspecialchars($edit['nip']) : '' ?>">
                        </div>
                        <div class="col">
                            <input type="text" name="nama" class="form-control" placeholder="Nama" maxlength="45" required value="<?= $edit ? htmlspecialchars($edit['nama']) : '' ?>">
                        </div>
                        <div class="col">
                            <select name="jenis_kelamin" class="form-select" required>
                                <option value="">Jenis Kelamin</option>
                                <option value="L" <?= $edit && $edit['jenis_kelamin'] == 'L' ? 'selected' : '' ?>>Laki-laki</option>
                                <option value="P" <?= $edit && $edit['jenis_kelamin'] == 'P' ? 'selected' : '' ?>>Perempuan</option>
                            </select>
                        </div>
                        <div class="col">
                            <input type="text" name="jabatan" class="form-control" placeholder="Jabatan" maxlength="45" required value="<?= $edit ? htmlspecialchars($edit['jabatan']) : '' ?>">
                        </div>
                        <div class="col-auto">
                            <?php if ($edit): ?>
                                <button name="update" class="btn btn-success">Update</button>
                                <a href="pegawai.php" class="btn btn-secondary">Batal</a>
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
                                    <th>NIP</th>
                                    <th>Nama</th>
                                    <th>Jenis Kelamin</th>
                                    <th>Jabatan</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $no = 1; while ($row = mysqli_fetch_assoc($data)) { ?>
                                <tr>
                                    <td><?= $no++ ?></td>
                                    <td><?= htmlspecialchars($row['nip']) ?></td>
                                    <td><?= htmlspecialchars($row['nama']) ?></td>
                                    <td><?= $row['jenis_kelamin'] == 'L' ? 'Laki-laki' : 'Perempuan' ?></td>
                                    <td><?= htmlspecialchars($row['jabatan']) ?></td>
                                    <td>
                                        <a class="btn btn-sm btn-warning" href="?edit=<?= $row['id'] ?>">Edit</a>
                                        <a class="btn btn-sm btn-danger" href="?hapus=<?= $row['id'] ?>" onclick="return confirm('Yakin hapus data ini?')">Hapus</a>
                                    </td>
                                </tr>
                                <?php } ?>
                                <?php if (mysqli_num_rows($data) == 0): ?>
                                <tr>
                                    <td colspan="6" class="text-center">Belum ada data pegawai.</td>
                                </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </main>
</div>

<?php include 'footer.php'; ?>