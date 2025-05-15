<?php
// include 'config/database.php';
include 'config/database.php';

// Tambah data kartu diskon
if (isset($_POST['tambah'])) {
    $nama = $_POST['nama'];
    $deskripsi = $_POST['deskripsi'];
    $persen_diskon = $_POST['persen_diskon'];
    mysqli_query($conn, "INSERT INTO kartu_diskon (nama, deskripsi, persen_diskon) VALUES ('$nama', '$deskripsi', '$persen_diskon')");
}

// Update data kartu diskon
if (isset($_POST['update'])) {
    $id = $_POST['id'];
    $nama = $_POST['nama'];
    $deskripsi = $_POST['deskripsi'];
    $persen_diskon = $_POST['persen_diskon'];
    mysqli_query($conn, "UPDATE kartu_diskon SET nama='$nama', deskripsi='$deskripsi', persen_diskon='$persen_diskon' WHERE id=$id");
    header("Location: kartu_diskon.php");
}

// Ambil data untuk edit
$edit = null;
if (isset($_GET['edit'])) {
    $id = $_GET['edit'];
    $result = mysqli_query($conn, "SELECT * FROM kartu_diskon WHERE id=$id");
    $edit = mysqli_fetch_assoc($result);
}

// Hapus data kartu diskon
if (isset($_GET['hapus'])) {
    $id = $_GET['hapus'];
    mysqli_query($conn, "DELETE FROM kartu_diskon WHERE id=$id");
    header("Location: kartu_diskon.php");
}

// Ambil semua data kartu diskon
$data = mysqli_query($conn, "SELECT * FROM kartu_diskon");
?>

<?php include 'header.php'; ?>
<?php include 'sidebar.php'; ?>

<div id="layoutSidenav_content">
    <main>
        <div class="container-fluid px-4">
            <h2 class="mt-4">Data Kartu Diskon</h2>
            <div class="card mb-4">
                <div class="card-header">
                    <form method="POST" class="row g-2 align-items-center">
                        <?php if ($edit): ?>
                            <input type="hidden" name="id" value="<?= $edit['id'] ?>">
                        <?php endif; ?>
                        <div class="col">
                            <input type="text" name="nama" class="form-control" placeholder="Nama Kartu" maxlength="45" required value="<?= $edit ? htmlspecialchars($edit['nama']) : '' ?>">
                        </div>
                        <div class="col">
                            <input type="number" name="persen_diskon" class="form-control" placeholder="Persen Diskon" min="0" max="100" required value="<?= $edit ? htmlspecialchars($edit['persen_diskon']) : '' ?>">
                        </div>
                        <div class="col">
                            <input type="text" name="deskripsi" class="form-control" placeholder="Deskripsi" maxlength="255" value="<?= $edit ? htmlspecialchars($edit['deskripsi']) : '' ?>">
                        </div>
                        <div class="col-auto">
                            <?php if ($edit): ?>
                                <button name="update" class="btn btn-success">Update</button>
                                <a href="kartu_diskon.php" class="btn btn-secondary">Batal</a>
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
                                    <th>Nama Kartu</th>
                                    <th>Persen Diskon</th>
                                    <th>Deskripsi</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $no = 1; while ($row = mysqli_fetch_assoc($data)) { ?>
                                <tr>
                                    <td><?= $no++ ?></td>
                                    <td><?= htmlspecialchars($row['nama']) ?></td>
                                    <td><?= htmlspecialchars($row['persen_diskon']) ?>%</td>
                                    <td><?= htmlspecialchars($row['deskripsi']) ?></td>
                                    <td>
                                        <a class="btn btn-sm btn-warning" href="?edit=<?= $row['id'] ?>">Edit</a>
                                        <a class="btn btn-sm btn-danger" href="?hapus=<?= $row['id'] ?>" onclick="return confirm('Yakin hapus data ini?')">Hapus</a>
                                    </td>
                                </tr>
                                <?php } ?>
                                <?php if (mysqli_num_rows($data) == 0): ?>
                                <tr>
                                    <td colspan="5" class="text-center">Belum ada data kartu diskon.</td>
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