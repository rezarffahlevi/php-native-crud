<?php
include('core/crud.php');
$crud = new CRUD();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <title>Pertemuan 11</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</head>

<body>

    <div class="jumbotron text-center" style="margin-bottom:0">
        <h1>CRUD Pertemuan 11</h1>
        <p>Reza Fahlevi - 181011400928</p>
    </div>

    <nav class="navbar navbar-expand-sm bg-dark navbar-dark">
        <a class="navbar-brand" href="#">Buku Tamu</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#collapsibleNavbar">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="collapsibleNavbar">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" href="#">Beranda</a>
                </li>
            </ul>
        </div>
    </nav>

    <div class="container" style="margin-top:30px">
        <div class="row">
            <div class="col-sm-12">
                <div class="container">
                    <h2>Buku Tamu</h2>
                    <button type="button" class="btn btn-primary" style="margin: 20px 0px;" onclick="modal_add()">
                        Tambah
                    </button>

                    <form class="form-group">
                        <label for="search">Cari:</label>
                        <div class="row">
                            <div class="col-sm-4">
                                <input type="search" class="form-control" name="search" placeholder="Masukkan nama" id="search" value="<?= $_GET['search'] ?? ''; ?>">
                            </div>
                            <div class="col-sm-2">
                                <button type="submit" class="btn btn-info">
                                    Cari
                                </button>
                            </div>
                        </div>
                    </form>
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nama</th>
                                <th>Email</th>
                                <th>Tanggal</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $limit = 5;
                            $count_page = $crud->count_page('tamu', $limit);
                            $page = $_GET['page'] ?? 1;
                            $result = $crud->page('tamu', [], $page, $limit);
                            if (isset($_GET['search'])) {
                                $like = ['nama' => $_GET['search']];
                                $count_page = $crud->count_page('tamu', $limit, $like);
                                $result = $crud->search('tamu', $like);
                            }
                            $no = 0;
                            foreach ($result as $row) : $no++; ?>
                                <tr>
                                    <td><?= ((($page - 1)  * $limit) + $no) ?></td>
                                    <td><?= $row['nama'] ?></td>
                                    <td><?= $row['email'] ?></td>
                                    <td><?= $row['tanggal'] ?></td>
                                    <td>
                                        <button type="button" class="btn btn-success" onclick="modal_edit('<?= base64_encode(json_encode($row)) ?>')">
                                            Ubah
                                        </button>
                                        <button type="button" class="btn btn-danger" onclick="modal_delete('<?= $row['id'] ?>')">
                                            Hapus
                                        </button>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                    <ul class="pagination float-right">
                        <?php
                        for ($i = 1; $i <= $count_page; $i++) {
                            echo "<li class='page-item" . ($i == $page ? ' active' : '') . "'><a class='page-link' href='?page=$i'>$i</a></li>";
                        } ?>
                    </ul>
                </div>
            </div>
        </div>
        <!-- The Modal -->
        <div class="modal" id="modalForm">
            <div class="modal-dialog">
                <div class="modal-content">
                    <form method="post" id="form-buku-tamu">
                        <!-- Modal Header -->
                        <div class="modal-header">
                            <h4 class="modal-title">Form Buku Tamu</h4>
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                        </div>

                        <!-- Modal body -->
                        <div class="modal-body">
                            <div class="form-group">
                                <label for="nama">Nama:</label>
                                <input type="hidden" class="form-control" name="id" id="id">
                                <input type="nama" class="form-control" name="nama" placeholder="Enter nama" id="nama" required>
                            </div>

                            <div class="form-group">
                                <label for="email">Email:</label>
                                <input type="email" class="form-control" name="email" placeholder="Enter email" id="email" required>
                            </div>

                            <div class="form-group">
                                <label for="tanggal">Tanggal:</label>
                                <input type="date" class="form-control" name="tanggal" placeholder="Enter email" id="tanggal" required>
                            </div>
                        </div>

                        <!-- Modal footer -->
                        <div class="modal-footer">
                            <button type="button" class="btn btn-info" data-dismiss="modal">Close</button>
                            <button type="submit" id="simpan" name="simpan" class="btn btn-primary">Simpan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <!-- The Modal -->
        <div class="modal" id="modalDelete">
            <div class="modal-dialog">
                <div class="modal-content">
                    <form method="get" id="form-delete">
                        <!-- Modal Header -->
                        <div class="modal-header">
                            <h4 class="modal-title">Hapus Tamu</h4>
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                        </div>

                        <!-- Modal body -->
                        <div class="modal-body">
                            <input type="hidden" name="id_tamu" id="id_tamu" />
                            Apakah Anda yakin ingin menghapus data ini?
                        </div>

                        <!-- Modal footer -->
                        <div class="modal-footer">
                            <button type="button" class="btn btn-info" data-dismiss="modal">Close</button>
                            <button type="submit" id="hapus" name="hapus" class="btn btn-danger">Hapus</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <script>
            let id = $('#id');
            let nama = $('#nama');
            let email = $('#email');
            let tanggal = $('#tanggal');
            let form = $('#form-buku-tamu');
            let simpan = $('#simpan');

            $(function() {

            });

            function modal_add() {
                id.val('');
                form.trigger("reset");
                simpan.val('insert');
                $('#modalForm').modal('show');
            }

            function modal_edit(params) {
                let data = JSON.parse(atob(params));
                console.log(data)
                id.val(data.id);
                nama.val(data.nama);
                email.val(data.email);
                tanggal.val(data.tanggal);
                simpan.val('update');
                $('#modalForm').modal('show');
            }

            function modal_delete(id) {
                $('#id_tamu').val(id);
                $('#modalDelete').modal('show');
            }
        </script>

        <?php
        // var_dump($_POST);
        if (isset($_POST['simpan'])) {
            $data = [
                'nama'      => $_POST['nama'],
                'email'     => $_POST['email'],
                'tanggal'   => $_POST['tanggal'] ?? date('Y-m-d'),
            ];

            if ($_POST['simpan'] === 'update') {
                $simpan     = $crud->update('tamu', $data, ['id' => $_POST['id']]);
            } else {
                $simpan     = $crud->insert('tamu', $data);
            }
            $crud->message($simpan == 'success', 'index.php');
        } else if (isset($_GET['hapus'])) {
            echo $sql     = $crud->delete('tamu', ['id' => $_GET['id_tamu']]);
            $crud->message($sql == 'success', 'index.php', false);
        }

        ?>
</body>

</html>