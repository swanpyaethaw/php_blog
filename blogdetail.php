<?php 
session_start();
require "config/config.php";
require "config/common.php";


if(empty($_SESSION['user_id']) && empty($_SESSION['logged_in'])){
  header('location:login.php');
  }  
 
  
  
  
$stmt = $pdo->prepare("SELECT * FROM posts WHERE id=" . $_GET['id']);
$stmt->execute();
$result = $stmt->fetchAll();

$post_id = $_GET['id'];
  if($_POST){
    if(empty($_POST['comment'])){  
        $cmtError = "Comment cannot be null";
    }else{
      $comment = $_POST['comment'];
      $author_id = $_SESSION['user_id'];
      $stmt = $pdo->prepare("INSERT INTO comments (content,author_id,post_id) VALUES (:content,:author_id,:post_id)");
      $result = $stmt->execute([
          ':content' => $comment,
          ':author_id' => $author_id,
          ':post_id' => $post_id
      ]);
      if($result){
        header('location:blogdetail.php?id='. $post_id);
      }
    }
  
  }


$cmstmt = $pdo->prepare("SELECT * FROM comments WHERE post_id=" . $post_id);
$cmstmt->execute();
$cmResult = $cmstmt->fetchAll();

if($cmResult){
  $urResult = [];
  foreach($cmResult as $value){
    $author_id = $value['author_id'];
    $urstmt = $pdo->prepare("SELECT * FROM users WHERE id=" . $author_id);
    $urstmt->execute();
    $urResult[]  = $urstmt->fetchAll();
  }

}




?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>AdminLTE 3 | Widgets</title>

  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
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
  <div class="">


    <!-- Main content -->
    <section class="content">
    <div class="row">
          <div class="col-md-12">
            <!-- Box Comment -->
            <div class="card card-widget">
              <div class="card-header">
              <div class="card-title" style="float:none;text-align:center">
                    <h4><?php echo escape($result[0]['title']) ?></h4>
               </div>
              </div>
              <!-- /.card-header -->
              <div class="card-body">
                <img class="img-fluid card-img" src="admin/images/<?php echo $result[0]['image'] ?>" alt="Photo">

                <p><?php echo escape($result[0]['content']) ?></p>
                <h3>Comments</h3>
                <a href="index.php" class="btn btn-warning">Back</a>
              </div>
              <!-- /.card-body -->
              <div class="card-footer card-comments">
                <div class="card-comment">
                  <?php if($cmResult){ ?>
                  <div class="comment-text" style="margin-left: 0px;">
                    <?php foreach($cmResult as $key=>$value){
                      ?>
                      <span class="username">
                     <?php echo escape($urResult[$key][0]['name']) ?>
                      <span class="text-muted float-right"><?php echo $value['created_at'] ?></span>
                    </span><!-- /.username -->
                    <?php echo escape($value['content']) ?>
                      <?php } ?>
                  </div>
                  <?php } ?>
                  <!-- /.comment-text -->
                </div>
                <!-- /.card-comment -->

              </div>
                
              <!-- /.card-footer -->
              <div class="card-footer">
                <form action="" method="POST">
                <input name="_token" type="hidden" value="<?php echo $_SESSION['_token']; ?>">
                  <!-- .img-push is used to add margin to elements next to floating images -->
                  <div class="img-push">
                  <p style="color:red"><?php echo empty($cmtError)  ? "" : $cmtError;   ?></p>
                    <input type="text" name="comment" class="form-control form-control-sm" placeholder="Press enter to post comment">
                  </div>
                </form>
              </div>
              <!-- /.card-footer -->
            </div>
            <!-- /.card -->
          </div>
         
        </div>
        <!-- /.row -->
    </section>
    <!-- /.content -->

    <a id="back-to-top" href="#" class="btn btn-primary back-to-top" role="button" aria-label="Scroll to top">
      <i class="fas fa-chevron-up"></i>
    </a>
  </div>
  <!-- /.content-wrapper -->

  <footer class="main-footer" style="margin-left: 0px;">
    <div class="float-right d-none d-sm-block">
      <b>Version</b> 3.2.0
    </div>
    <strong>Copyright &copy; 2014-2021 <a href="https://adminlte.io">AdminLTE.io</a>.</strong> All rights reserved.
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
