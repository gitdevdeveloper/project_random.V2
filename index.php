<?php
include "config.php";
session_start();

$stmt1 = $conn->query("SELECT * FROM tb_img WHERE id='1'");
$stmt1->execute();
$img = $stmt1->fetch();

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>โปรแกรมสุ่ม</title>

    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback" />
    <!-- fontawesome -->
    <script src="https://kit.fontawesome.com/59b81ffa56.js" crossorigin="anonymous"></script>

    <!-- Ionicons -->
    <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css" />

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous" />
    <link rel="stylesheet" href="dist/css/adminlte.min.css" />

    <!-- DataTables -->
    <link rel="stylesheet" href="plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="plugins/datatables-responsive/css/responsive.bootstrap4.min.css">
    <link rel="stylesheet" href="plugins/datatables-buttons/css/buttons.bootstrap4.min.css">



    <!-- overlayScrollbars -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/overlayscrollbars/1.13.2/css/OverlayScrollbars.min.css" integrity="sha512-jN4O0AUkRmE6Jwc8la2I5iBmS+tCDcfUd1eq8nrZIBnDKTmCp5YxxNN1/aetnAH32qT+dDbk1aGhhoaw5cJNlw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>

<style>
    body {
        font-family: 'Kanit';
    }

    .grid-template {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
    }

    .grid-template .card {
        width: 400px;
        height: 250px;
    }

    .card-title {
        color: cmyk(0%, 0%, 0%, 0%);
        font-weight: bold;
    }

    .text-1 {
        position: absolute;
        top: 100px;
        left: 200px;
    }

    h5.card-title {
        font-size: 38px;
    }

    @page {
        size: A4;
        margin: 0;
    }

    @media print {
        .page {
            margin: 0;
            border: initial;
            border-radius: initial;
            width: initial;
            min-height: initial;
            box-shadow: initial;
            background: initial;
            page-break-after: always;
        }

        .d-grid {
            display: none;
        }

        button.btn {
            display: none;
        }

        .card-title {
            color: cmyk(0%, 0%, 0%, 0%);
        }
    }
</style>

<body class="hold-transition sidebar-mini layout-fixed">


    <?php
    if (isset($_GET['input_img'])) {
        if (isset($_POST['input_img'])) {
    ?>
            <div class="container">
                <div class="d-grid gap-2 col-6" style="margin:10px auto ;">
                    <button class="btn btn-primary" onclick="myFunction()"><i class="fa-solid fa-print"></i> พิมพ์</button>
                    <button class="btn btn-secondary" onclick="myFunction2()"><i class="fa-solid fa-rotate-left"></i> ย้อนกลับ</button>
                </div>
                <div class="grid-template">
                    <?php
                    $total = $_POST['number'];
                    function random_number_with_dupe($len = 6, $dup = 1, $sort = false)
                    {
                        $num = range(0, 9);
                        shuffle($num);

                        $num = array_slice($num, 0, ($len - $dup) + 1);

                        if ($dup > 0) {
                            $k = array_rand($num, 1);
                            for ($i = 0; $i < ($dup - 1); $i++) {
                                $num[] = $num[$k];
                            }
                        }

                        if ($sort) {
                            sort($num);
                        }

                        return implode('', $num);
                    }

                    for ($i = 0; $i < $total; $i++) {
                        $number = random_number_with_dupe(6) . PHP_EOL;
                        
                        $result  = $conn->prepare("SELECT SQL_CALC_FOUND_ROWS  number FROM tb_number WHERE number = '$number'");
                        $result->execute();
                        $result = $conn->prepare("SELECT FOUND_ROWS()");
                        $result->execute();
                        $row_count = $result->fetchColumn();
                        if ($row_count > 0) {
                            $number = rand(000000, 999999);
                            $result  = $conn->prepare("SELECT SQL_CALC_FOUND_ROWS  number FROM tb_number WHERE number = '$number'");
                            $result->execute();
                            $result = $conn->prepare("SELECT FOUND_ROWS()");
                            $result->execute();
                            $row_count = $result->fetchColumn();

                            $sql = $conn->prepare("INSERT INTO tb_number (number) VALUES (:number)");
                            $sql->bindParam(":number", $number);
                            $sql->execute();
                        } else {
                            $sql = $conn->prepare("INSERT INTO tb_number (number) VALUES (:number)");
                            $sql->bindParam(":number", $number);
                            $sql->execute();
                        }
                    ?>
                        <div class="card text-bg-dark">
                            <img src="upload/<?= $img['images']; ?>" class="card-img" alt="...">
                            <div class="text-1">
                                <h5 class="card-title"><?= $number; ?></h5>
                            </div>
                        </div>
                    <?php
                    }
                    ?>

                </div>
            </div>
        <?php
        } else {
        ?>
            <script>
                window.location = "index?random_img";
            </script>
        <?php
        }
    } else {
        ?>
        <div class="wrapper">
            <!-- Navbar -->
            <nav class="main-header navbar navbar-expand navbar-white navbar-light">
                <!-- Left navbar links -->
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
                    </li>
                </ul>

            </nav>
            <!-- /.navbar -->

            <!-- Main Sidebar Container -->

            <?php
            if (isset($_GET['alldata'])) {
                if (isset($_GET['deletedata'])) {
                    $deletestmt = $conn->query("DELETE FROM tb_number  ");
                    $deletestmt->execute();
                    $_SESSION['success'] = "success";
            ?>
                    <script>
                        window.location = 'index?alldata';
                    </script>
                <?php
                }
                ?>
                <aside class="main-sidebar sidebar-dark-primary elevation-4">
                    <!-- Brand Logo -->
                    <a href="index" class="brand-link">
                        <img src="dist/img/AdminLTELogo.png" alt="AdminLTE Logo" class="brand-image img-circle elevation-3" style="opacity: 0.8" />
                        <span class="brand-text font-weight-light">โปรแกรมสุ่ม</span>
                    </a>
                    <!-- Sidebar -->
                    <div class="sidebar">
                        <!-- Sidebar Menu -->
                        <nav class="mt-2">
                            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                                <li class="nav-item">
                                    <a href="index" class="nav-link">
                                        <i class="nav-icon fa-solid fa-gauge"></i>
                                        <p>สุ่มแบบตัวเลข</p>
                                    </a>
                                </li>

                                <li class="nav-item">
                                    <a href="?random_img" class="nav-link">
                                        <i class="nav-icon fa-solid fa-gauge"></i>
                                        <p>สุ่มแบบรูปภาพ</p>
                                    </a>
                                </li>

                                <li class="nav-item">
                                    <a href="?alldata" class="nav-link active">
                                        <i class="nav-icon fa-solid fa-list-ol"></i>
                                        <p>ตัวเลขทั้งหมด</p>
                                    </a>
                                </li>
                            </ul>
                        </nav>
                        <!-- /.sidebar-menu -->
                    </div>
                    <!-- /.sidebar -->
                </aside>

                <!-- Content Wrapper. Contains page content -->
                <div class="content-wrapper">
                    <!-- Main content -->
                    <section class="content">
                        <div class="container-fluid">
                            <div class="row">
                                <div class="card mt-3">
                                    <h5 class="card-header">ข้อมูลทั้งหมด
                                        <button type="button" class="btn btn-danger btn-sm" onclick="deletedata()"><i class="fa-solid fa-trash-can"></i> ลบทั้งหมด</button>
                                    </h5>
                                    <div class="card-body">
                                        <table class="table" id="example1">
                                            <thead>
                                                <tr>
                                                    <th scope="col">#</th>
                                                    <th scope="col">ตัวเลขทั้งหมด</th>
                                                    <th scope="col">เวลา</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                $stmt = $conn->query("SELECT * FROM tb_number  ORDER BY id DESC ");
                                                $stmt->execute();
                                                $tb_number = $stmt->fetchAll();
                                                $num = 0;
                                                if (!$tb_number) {
                                                } else {
                                                    foreach ($tb_number as $row) {
                                                        $num++;
                                                ?>
                                                        <tr>
                                                            <th><?= $num; ?></th>
                                                            <td><?= $row['number']; ?></td>
                                                            <td><?= $row['date']; ?></td>
                                                        </tr>
                                                <?php
                                                    }
                                                }
                                                ?>

                                            </tbody>
                                        </table>
                                    </div>
                                </div>

                            </div>
                            <!-- /.row (main row) -->
                        </div>
                        <!-- /.container-fluid -->
                    </section>
                    <!-- /.content -->
                </div>
            <?php
            } elseif (isset($_GET['random_img'])) {
            ?>
                <aside class="main-sidebar sidebar-dark-primary elevation-4">
                    <!-- Brand Logo -->
                    <a href="index" class="brand-link">
                        <img src="dist/img/AdminLTELogo.png" alt="AdminLTE Logo" class="brand-image img-circle elevation-3" style="opacity: 0.8" />
                        <span class="brand-text font-weight-light">โปรแกรมสุ่ม</span>
                    </a>
                    <!-- Sidebar -->
                    <div class="sidebar">
                        <!-- Sidebar Menu -->
                        <nav class="mt-2">
                            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                                <li class="nav-item">
                                    <a href="index" class="nav-link">
                                        <i class="nav-icon fa-solid fa-gauge"></i>
                                        <p>สุ่มแบบตัวเลข</p>
                                    </a>
                                </li>

                                <li class="nav-item">
                                    <a href="?random_img" class="nav-link active">
                                        <i class="nav-icon fa-solid fa-gauge"></i>
                                        <p>สุ่มแบบรูปภาพ</p>
                                    </a>
                                </li>

                                <li class="nav-item">
                                    <a href="?alldata" class="nav-link">
                                        <i class="nav-icon fa-solid fa-list-ol"></i>
                                        <p>ตัวเลขทั้งหมด</p>
                                    </a>
                                </li>
                            </ul>
                        </nav>
                        <!-- /.sidebar-menu -->
                    </div>
                    <!-- /.sidebar -->
                </aside>

                <!-- Content Wrapper. Contains page content -->
                <div class="content-wrapper">
                    <!-- Main content -->
                    <section class="content">
                        <div class="container-fluid">
                            <div class="row">
                                <div class="card mt-3">
                                    <h5 class="card-header">โปรแกรมสุ่ม</h5>
                                    <div class="card-body">
                                        <form action="?input_img" method="post">
                                            <div class="input-group mb-3">
                                                <span class="input-group-text" id="inputGroup-sizing-default">ระบุจำนวน</span>
                                                <input type="number" class="form-control" name="number" min="1" max="999999" aria-label="Sizing example input" required aria-describedby="inputGroup-sizing-default">
                                            </div>
                                            <div class="d-grid gap-2 col-6 mx-auto">
                                                <button class="btn btn-primary" name="input_img" type="submit">สุ่ม</button>
                                                <a href="?alldata" class="btn btn-outline-primary">ดูข้อมูล</a>
                                            </div>
                                        </form>
                                    </div>
                                </div>

                                <div class="card mt-3">
                                    <h5 class="card-header"><i class="fa-regular fa-pen-to-square"></i> รูปภาพ</h5>
                                    <div class="card-body">
                                        <form action="?upload" method="post" enctype="multipart/form-data">
                                            <input type="hidden" name="img2" value="<?= $img['images']; ?>">
                                            <div class="mb-3">
                                                <label for="formFile" class="form-label">เลือกรูปภาพที่ต้องการเปลี่ยน</label>
                                                <input class="form-control" type="file" name="images" id="formFile">
                                                <img src="upload/<?= $img['images']; ?>" class="w-25 m-3" alt="">
                                            </div>
                                            <div class="d-grid gap-2 col-6 mx-auto">
                                                <button class="btn btn-warning" name="upload_img" type="submit"><i class="fa-regular fa-pen-to-square"></i> บันทึก</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>

                            </div>
                            <!-- /.row (main row) -->
                        </div>
                        <!-- /.container-fluid -->
                    </section>
                    <!-- /.content -->
                </div>
            <?php
            } else {
            ?>
                <aside class="main-sidebar sidebar-dark-primary elevation-4">
                    <!-- Brand Logo -->
                    <a href="index" class="brand-link">
                        <img src="dist/img/AdminLTELogo.png" alt="AdminLTE Logo" class="brand-image img-circle elevation-3" style="opacity: 0.8" />
                        <span class="brand-text font-weight-light">โปรแกรมสุ่ม</span>
                    </a>
                    <!-- Sidebar -->
                    <div class="sidebar">
                        <!-- Sidebar Menu -->
                        <nav class="mt-2">
                            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                                <li class="nav-item">
                                    <a href="index" class="nav-link active">
                                        <i class="nav-icon fa-solid fa-gauge"></i>
                                        <p>สุ่มแบบตัวเลข</p>
                                    </a>
                                </li>

                                <li class="nav-item">
                                    <a href="?random_img" class="nav-link">
                                        <i class="nav-icon fa-solid fa-gauge"></i>
                                        <p>สุ่มแบบรูปภาพ</p>
                                    </a>
                                </li>

                                <li class="nav-item">
                                    <a href="?alldata" class="nav-link">
                                        <i class="nav-icon fa-solid fa-list-ol"></i>
                                        <p>ตัวเลขทั้งหมด</p>
                                    </a>
                                </li>
                            </ul>
                        </nav>
                        <!-- /.sidebar-menu -->
                    </div>
                    <!-- /.sidebar -->
                </aside>

                <!-- Content Wrapper. Contains page content -->
                <div class="content-wrapper">
                    <!-- Main content -->
                    <section class="content">
                        <div class="container-fluid">
                            <div class="row">
                                <div class="card mt-3">
                                    <h5 class="card-header">โปรแกรมสุ่ม</h5>
                                    <div class="card-body">
                                        <form action="" method="post">
                                            <div class="input-group mb-3">
                                                <span class="input-group-text" id="inputGroup-sizing-default">ระบุจำนวน</span>
                                                <input type="number" class="form-control" name="number" min="1" max="999999" aria-label="Sizing example input" required aria-describedby="inputGroup-sizing-default">
                                            </div>
                                            <div class="d-grid gap-2 col-6 mx-auto">
                                                <button class="btn btn-primary" name="input" type="submit">สุ่ม</button>
                                                <a href="?alldata" class="btn btn-outline-primary">ดูข้อมูล</a>
                                            </div>
                                        </form>
                                    </div>
                                </div>

                                <?php
                                $total = 0;
                                if (isset($_POST['input'])) {
                                    $total = $_POST['number'];
                                    function random_number_with_dupe($len = 6, $dup = 1, $sort = false)
                                    {
                                        $num = range(0, 9);
                                        shuffle($num);

                                        $num = array_slice($num, 0, ($len - $dup) + 1);

                                        if ($dup > 0) {
                                            $k = array_rand($num, 1);
                                            for ($i = 0; $i < ($dup - 1); $i++) {
                                                $num[] = $num[$k];
                                            }
                                        }

                                        if ($sort) {
                                            sort($num);
                                        }

                                        return implode('', $num);
                                    }
                                ?>
                                    <div class="card">
                                        <h5 class="card-header">อัพโหลดล่าสุด #<?= $total; ?></h5>
                                        <div class="card-body">
                                            <table class="table table-striped table-hover" id="example1">
                                                <thead>
                                                    <tr>
                                                        <th scope="col">#</th>
                                                        <th scope="col">ตัวเลข</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php
                                                    $num = 0;
                                                    for ($i = 0; $i < $total; $i++) {
                                                        $num++;
                                                        $number = random_number_with_dupe(6) . PHP_EOL;

                                                        $result  = $conn->prepare("SELECT SQL_CALC_FOUND_ROWS  number FROM tb_number WHERE number = '$number'");
                                                        $result->execute();
                                                        $result = $conn->prepare("SELECT FOUND_ROWS()");
                                                        $result->execute();
                                                        $row_count = $result->fetchColumn();
                                                        if ($row_count > 0) {
                                                            $number = rand(000000, 999999);
                                                            $result  = $conn->prepare("SELECT SQL_CALC_FOUND_ROWS  number FROM tb_number WHERE number = '$number'");
                                                            $result->execute();
                                                            $result = $conn->prepare("SELECT FOUND_ROWS()");
                                                            $result->execute();
                                                            $row_count = $result->fetchColumn();

                                                            $sql = $conn->prepare("INSERT INTO tb_number (number) VALUES (:number)");
                                                            $sql->bindParam(":number", $number);
                                                            $sql->execute();
                                                        } else {
                                                            $sql = $conn->prepare("INSERT INTO tb_number (number) VALUES (:number)");
                                                            $sql->bindParam(":number", $number);
                                                            $sql->execute();
                                                        }
                                                    ?>

                                                        <tr>
                                                            <th><?= $num; ?></th>
                                                            <td><?= $number; ?></td>
                                                        </tr>
                                                    <?php
                                                    }
                                                    if ($sql) {
                                                        $_SESSION['success'] = "success";
                                                    }
                                                    ?>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                <?php
                                }
                                ?>
                            </div>
                            <!-- /.row (main row) -->
                        </div>
                        <!-- /.container-fluid -->
                    </section>
                    <!-- /.content -->
                </div>
            <?php
            }
            ?>




            <!-- Control Sidebar -->
            <aside class="control-sidebar control-sidebar-dark">
                <!-- Control sidebar content goes here -->
            </aside>
            <!-- /.control-sidebar -->
        </div>
    <?php
    }
    ?>


    <?php
    if (isset($_GET['upload'])) {
        if (isset($_POST['upload_img'])) {
            $id  = '1';
            $img2 = $_POST['img2'];
            $images = $_FILES['images'];

            $upload = $_FILES['images']['name'];

            if ($upload != '') {
                $allow = array('jpg', 'jpeg', 'png');
                $extension = explode('.', $images['name']);
                $fileActExt = strtolower(end($extension));
                $fileNew = rand() . "." . $fileActExt;  // rand function create the rand number 
                $filePath = 'upload/' . $fileNew;

                if (in_array($fileActExt, $allow)) {
                    if ($images['size'] > 0 && $images['error'] == 0) {
                        move_uploaded_file($images['tmp_name'], $filePath);
                    }
                }
            } else {
                $fileNew = $img2;
            }

            $sql = $conn->prepare("UPDATE tb_img SET images = :images   WHERE id  = :id ");
            $sql->bindParam(":id", $id);
            $sql->bindParam(":images", $fileNew);
            $sql->execute();

            if ($sql) {
                $_SESSION['success'] = "อัพเดทข้อมูลเรียบร้อยเเล้ว";
    ?>
                <script>
                    window.location = "index?random_img";
                </script>
    <?php
                exit();
            }
        }
    }
    ?>

    <!-- ./wrapper -->

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.js" integrity="sha256-H+K7U5CnXl1h5ywQfKtSj8PCmoN9aaq30gDh27Xc0jk=" crossorigin="anonymous"></script>
    <!-- jQuery UI 1.11.4 -->
    <script src="https://code.jquery.com/ui/1.13.1/jquery-ui.js" integrity="sha256-6XMVI0zB8cRzfZjqKcD01PBsAy3FlDASrlC8SxCpInY=" crossorigin="anonymous"></script>


    <!-- DataTables  & Plugins -->
    <script src="plugins/datatables/jquery.dataTables.min.js"></script>
    <script src="plugins/datatables-bs4/js/dataTables.bootstrap4.min.js"></script>
    <script src="plugins/datatables-responsive/js/dataTables.responsive.min.js"></script>
    <script src="plugins/datatables-responsive/js/responsive.bootstrap4.min.js"></script>
    <script src="plugins/datatables-buttons/js/dataTables.buttons.min.js"></script>
    <script src="plugins/datatables-buttons/js/buttons.bootstrap4.min.js"></script>
    <script src="plugins/jszip/jszip.min.js"></script>
    <script src="plugins/pdfmake/pdfmake.min.js"></script>
    <script src="plugins/pdfmake/vfs_fonts.js"></script>
    <script src="plugins/datatables-buttons/js/buttons.html5.min.js"></script>
    <script src="plugins/datatables-buttons/js/buttons.print.min.js"></script>
    <script src="plugins/datatables-buttons/js/buttons.colVis.min.js"></script>
    <!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js" integrity="sha384-7+zCNj/IqJ95wo16oMtfsKbZ9ccEh31eOz1HGyDuCQ6wgnyJNSYdrPa03rtR1zdB" crossorigin="anonymous">
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.min.js" integrity="sha384-QJHtvGhmr9XOIpI6YVutG+2QOK9T+ZnN4kzFN1RtK3zEFEIsxhlmWl5/YESvpZ13" crossorigin="anonymous">
    </script>

    <!-- overlayScrollbars -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/overlayscrollbars/1.13.2/js/OverlayScrollbars.min.js" integrity="sha512-5UqQ4jRiUk3pxl9wZxAtep5wCxqcoo6Yu4FI5ufygoOMV2I2/lOtH1YjKdt3FcQ9uhcKFJapG0HAQ0oTC5LnOw==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

    <!-- AdminLTE App -->
    <script src="dist/js/adminlte.js"></script>

    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        function deletedata() {
            Swal.fire({
                title: 'คุณแน่ใจใช่ไหม ?',
                text: "คุณต้องการลบข้อมูลทั้งหมด !",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#0d6efd',
                cancelButtonColor: '#dc3545',
                confirmButtonText: 'ใช่',
                cancelButtonText: 'ไม่'
            }).then((result) => {
                if (result.isConfirmed) {
                    const Toast = Swal.mixin({
                        toast: true,
                        position: 'top-end',
                        showConfirmButton: false,
                        timer: 500,
                        timerProgressBar: true,
                        didOpen: (toast) => {
                            toast.addEventListener('mouseenter', Swal.stopTimer)
                            toast.addEventListener('mouseleave', Swal.resumeTimer)
                        }
                    })
                    Toast.fire({
                        icon: 'success',
                        title: 'ลบข้อมูลเรียบร้อยเเล้ว',
                        timer: 500
                    }), setInterval(() => {
                        window.location = '?alldata&deletedata';
                    }, 500);
                }
            })
        }
    </script>
    <!-- alert -->
    <?php if (isset($_SESSION['success'])) { ?>
        <script>
            const Toast = Swal.mixin({
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 1500,
                timerProgressBar: true,
                didOpen: (toast) => {
                    toast.addEventListener('mouseenter', Swal.stopTimer)
                    toast.addEventListener('mouseleave', Swal.resumeTimer)
                }
            })

            Toast.fire({
                icon: 'success',
                title: '<?= $_SESSION['success']; ?>'
            })
        </script>

    <?php
        unset($_SESSION['success']);
    }
    ?>


    <script>
        $(document).ready(function() {
            $('#example').DataTable();
        });
    </script>

    <script>
        $(function() {
            $("#example1").DataTable({
                "responsive": true,
                "lengthChange": false,
                "autoWidth": false,
                "buttons": ["copy", "csv", "excel", "pdf", "print"]
            }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');
            $('#example2').DataTable({
                "paging": true,
                "lengthChange": false,
                "searching": false,
                "ordering": true,
                "info": true,
                "autoWidth": false,
                "responsive": true,
            });
        });

        function myFunction() {
            window.print();
        }

        function myFunction2() {
            window.location = "index?random_img";
        }
    </script>
</body>

</html>