<?php

session_start();
// error_reporting(0);
require "../config/config.php";
require "../config/common.php";




if(empty($_SESSION['id']) && empty($_SESSION['logged_in'])){
  header('location:login.php');
}

if($_SESSION['role'] != 1){
  header('location:login.php');
}

if (isset($_POST['search'])) {
  setcookie('search',$_POST['search'], time() + (86400 * 30), "/");
}else{
  if (empty($_GET['pageno'])) {
    unset($_COOKIE['search']); 
    setcookie('search', null, -1, '/'); 
  }
}
?>



<?php include "header.php" ?>

<!-- Main content -->
<div class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">User Listings</h3>
                    </div>

                    <!-- php code for table -->
                    <?php 

                      if(!empty($_GET['pageno'])){
                        $pageno = $_GET['pageno'];
                      }else{
                        $pageno = 1;
                      }

                      $num_of_records = 2;
                      $offset = ($pageno-1) * $num_of_records;
                      

                      if(empty($_POST['search']) && empty($_COOKIE['search'])){
                        $stmt = $pdo->prepare("SELECT * FROM users ORDER BY id DESC");
                        $stmt->execute();
                        $result = $stmt->fetchAll();
                        $total_pages = ceil(count($result)/$num_of_records);
                        $stmt = $pdo->prepare("SELECT * FROM users ORDER BY id DESC LIMIT $offset,$num_of_records");
                        $stmt->execute();
                        $result = $stmt->fetchAll();
                      }else{
                        $search = $_POST['search'] ? $_POST['search'] : $_COOKIE['search'];  
                        $stmt = $pdo->prepare("SELECT * FROM users WHERE name LIKE '%$search%' ORDER BY id DESC");
                        $stmt->execute();
                        $result = $stmt->fetchAll();
                        $total_pages = ceil(count($result)/$num_of_records);
                        $stmt = $pdo->prepare("SELECT * FROM users WHERE name LIKE '%$search%' ORDER BY id DESC LIMIT $offset,$num_of_records");
                        $stmt->execute();
                        $result = $stmt->fetchAll();
                      }
                     

                    ?>

                    <!-- /.card-header -->
                    <div class="card-body">
                        <a href="user_add.php" class="btn btn-success mb-3">Create New User</a>
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th style="width: 10px">#</th>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Role</th>
                                    <th style="width: 40px">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                        if($result){
                          $i = 1;
                        foreach($result as $user){
                  ?>
                                <tr>
                                    <td><?php echo $i ?></td>
                                    <td><?php echo escape($user['name']) ?></td>
                                    <td><?php echo escape($user['email'])  ?></td>
                                    <td>
                                      <?php 
                                      if($user['role'] == 1){
                                        echo 'admin';
                                      }else{
                                        echo 'user';
                                      }
                                      
                                      ?>
                                    <td>
                                        <div class="btn-group">
                                            <div class="container">
                                                <a href="user_edit.php?id=<?php echo $user['id'] ?>"
                                                    class="btn btn-warning">Edit</a>
                                            </div>
                                            <div class="container">
                                                <a href="user_delete.php?id=<?php echo $user['id'] ?>" class="btn btn-danger" onclick="return confirm('Are you sure want to delete?')" >Delete</a>
                                            </div>

                                        </div>

                                    </td>
                                </tr>
                                <?php
                  $i++;
                  }
                }
                ?>
                            </tbody>
                        </table>
                    </div>
                    <!-- /.card-body -->
                    <div class="container d-flex justify-content-end">
                        <nav aria-label="Page navigation example">
                            <ul class="pagination">
                                <li class="page-item"><a class="page-link" href="?pageno=1">First</a></li>
                                <li class="page-item <?php if($pageno <= 1){echo 'disabled'; } ?>">
                                <a class="page-link" href="?pageno=<?php if($pageno <=1){ echo '#'; } else { echo $pageno-1; } ?>">Previous</a>
                              </li>
                                <li class="page-item"><a class="page-link" href="#"><?php echo $pageno ?></a></li>
                                <li class="page-item <?php if($pageno >= $total_pages){ echo 'disabled'; } ?>"><a class="page-link" href="?pageno=<?php if($pageno >= $total_pages){echo '#'; } else {echo $pageno+1 ; } ?>">Next</a></li>
                                <li class="page-item"><a class="page-link" href="?pageno=<?php echo $total_pages ?>">Last</a></li>
                            </ul>
                        </nav>
                    </div>

                </div>
                <!-- /.card -->


                <!-- /.card -->
            </div>
            <!-- /.col-md-6 -->
        </div>
        <!-- /.row -->
    </div><!-- /.container-fluid -->
</div>
<?php include "footer.html" ?>