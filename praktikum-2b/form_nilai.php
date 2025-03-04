<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Form Nilai</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
</head>

<body>
    <nav class="navbar navbar-light bg-light">
        <div class="container">
            <a class="navbar-brand" style="padding: .75rem 1.25rem;">Sistem Penilaian</a>
        </div>
    </nav>

    <div class="container mt-4">
        <div class="card" style="border-style: none;">
            <div class="card-header bg-white">
                <h3 style="margin-left: 0;">Form Nilai Siswa</h3>
            </div>
            <div class="card-body col-12 text-end align-middle">
                <form class="form-horizontal" method="POST" action="">
                    <div class="form-group row">
                        <label for="nama" class="col-sm-4 col-form-label"><strong>Nama Lengkap</strong></label>
                        <div class="col-sm-4">
                            <input type="text" class="form-control" id="nama" placeholder="Nama Lengkap" name="nama" required autocomplete="off">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="matkul" class="col-sm-4 col-form-label"><strong>Mata Kuliah</strong></label>
                        <div class="col-sm-4">
                            <select class="custom-select" name="matkul" id="matkul" required>
                                <option value="DDP" required>Dasar-Dasar Pemrograman</option>
                                <option value="BD1" required>Basis Data I</option>
                                <option value="WEB1" required>Pemrograman Web</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="uts" class="col-sm-4 col-form-label"><strong>Nilai UTS</strong></label>
                        <div class="col-sm-2">
                            <input type="number" class="form-control" id="uts" placeholder="Nilai UTS" min="0" max="100" name="nilai_uts" required>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="uas" class="col-sm-4 col-form-label"><strong>Nilai UAS</strong></label>
                        <div class="col-sm-2">
                            <input type="number" class="form-control" id="uas" placeholder="Nilai UAS" min="0" max="100" name="nilai_uas" required>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="tugas" class="col-sm-4 col-form-label"><strong>Nilai Tugas/Praktikum</strong></label>
                        <div class="col-sm-2">
                            <input type="number" class="form-control" id="tugas" placeholder="Nilai Tugas" min="0" max="100" name="nilai_tugas" required>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="offset-4 col-8">
                            <button name="proses" type="submit" class="btn btn-success" value="Simpan">Submit</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>

</html>