<?php 
session_start();
require "config/config.php";
require "config/common.php";


if(empty($_SESSION['user_id']) && empty($_SESSION['logged_in'])){
  header('location:login.php');
}



?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>AdminLTE 3 | Widgets</title>

    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="plugins/fontawesome-free/css/all.min.css">
    <!-- Ionicons -->
    <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="dist/css/adminlte.min.css">
</head>

<body class="hold-transition sidebar-mini">
    <div class="wrapper">

        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper" style="margin-left: 0px;">
            <!-- Content Header (Page header) -->
            <section class="content-header">
                <div class="container-fluid">
                    <h1 style="text-align: center;">Blog Site</h1>
                </div><!-- /.container-fluid -->
            </section>
 <?php 
 
 
if(!empty($_GET['pageno'])){
  $pageno = $_GET['pageno'];
}else{
  $pageno = 1;
}

$num_of_records = 6;
$offset = ($pageno-1) * $num_of_records;

$stmt = $pdo->prepare("SELECT * FROM posts ORDER BY id DESC");
$stmt->execute();
$result = $stmt->fetchAll();

$totalpages = ceil(count($result)/$num_of_records);


$stmt = $pdo->prepare("SELECT * FROM posts ORDER BY id DESC LIMIT $offset,$num_of_records");
$stmt->execute();
$result = $stmt->fetchAll();
 
 
 ?>
            <!-- Main content -->
            <section class="content">
                <div class="row">
                    <?php 
                        if($result){
                        foreach($result as $post){
                  ?>
                    <div class="col-md-4">
                        <!-- Box Comment -->
                        <div class="card card-widget">
                            <div class="card-header">
                                <div class="card-title" style="float:none;text-align:center">
                                    <h4><?php echo escape($post['title']) ?></h4>
                                </div>
                            </div>
                            <!-- /.card-header -->
                            <div class="card-body">
                                <a href="blogdetail.php?id=<?php echo $post['id'] ?>"> <img class="img-fluid pad"
                                        src="admin/images/<?php echo $post['image'] ?>" alt="Photo"></a>
                            </div>
                            <!-- /.card-body -->


                        </div>
                        <!-- /.card -->
                    </div>
                    <?php
                  }
                }
                ?>



                </div>
                <!-- /.row -->
            </section>
            <!-- /.content -->
            <div class="container d-flex justify-content-end">
                <nav aria-label="Page navigation example">
                    <ul class="pagination">
                        <li class="page-item "><a class="page-link" href="index.php?pageno=1">First</a></li>
                        <li class="page-item <?php if($pageno <= 1 ){ echo 'disabled'; } ?>">
                        <a class="page-link" href="index.php?pageno=<?php if($pageno <=1 ){echo '#'; }else{echo $pageno-1;} ?>">Previous</a>
                      </li>
                        <li class="page-item"><a class="page-link" href="index.php?pageno=<?php echo $pageno; ?>"><?php echo $pageno; ?></a></li>
                        <li class="page-item <?php if($pageno >= $totalpages){echo 'disabled'; } ?>"><a class="page-link" href="index.php?pageno=<?php if($pageno >= $totalpages){echo '#'; }else{echo $pageno+1; } ?>">Next</a></li>
                        <li class="page-item"><a class="page-link" href="index.php?pageno=<?php echo $totalpages ?>">Last</a></li>
                    </ul>
                </nav>
            </div>


           
        </div>
        <!-- /.content-wrapper -->

        <footer class="main-footer" style="margin-left: 0px;">
            <div class="float-right d-none d-sm-block">
                <a href="logout.php" class="btn btn-default">Logout</a>
            </div>
            <strong>Copyright &copy; 2014-2021 <a href="https://adminlte.io">AdminLTE.io</a>.</strong> All rights
            reserved.
        </footer>

        <!-- Control Sidebar -->
        <aside class="control-sidebar control-sidebar-dark">
            <!-- Control sidebar content goes here -->
        </aside>
        <!-- /.control-sidebar -->
    </div>
    <!-- ./wrapper -->

    <!-- jQuery -->
    <script src="plugins/jquery/jquery.min.js"></script>
    <!-- Bootstrap 4 -->
    <script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
    <!-- AdminLTE App -->
    <script src="dist/js/adminlte.min.js"></script>
    <!-- AdminLTE for demo purposes -->
    <script src="dist/js/demo.js"></script>
</body>

</html>