<?php 
session_start();
require "../config/config.php";
require "../config/common.php";


if(empty($_SESSION['id']) && empty($_SESSION['logged_in'])){
  header('location:login.php');
}

if($_SESSION['role'] != 1){
  header('location:login.php');
}

if($_POST){
  if(empty($_POST['name']) || empty($_POST['email']) || empty($_POST['password'])  ){
    if(empty($_POST['name'])){
      $nameError = "Name cannot be null";
    }
  
    if(empty($_POST['email'])){
      $emailError = "Email cannot be null";
    }
  
    if(empty($_POST['password'])){
      $passwordError = "Password cannot be null";
    }
  }else if(strlen($_POST['password']) <4){
      $passwordError = "Password must be at least 4 characters";
  }else{
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $password = password_hash($password,PASSWORD_DEFAULT);
    if(empty($_POST['role'])){
        $role = 0;
    }else{
        $role = 1;
    }
    
    $stmt = $pdo->prepare("INSERT INTO users (name,email,password,role) VALUES (:name,:email,:password,:role)");
    $result = $stmt->execute([
        ':name' => $name,
        ':email' => $email,
        ':password' => $password,
        ':role' => $role
    ]);
    if($result){
        echo "<script>alert('Successfully Added');window.location.href='user_list.php'</script>";
    }
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
              <div class="card-body">
                <form action="user_add.php" method="POST" enctype="multipart/form-data">
                <input name="_token" type="hidden" value="<?php echo $_SESSION['_token']; ?>">
                    <div class="form-group">
                        <label for="">Name</label><p style="color:red"><?php echo empty($nameError) ? "" : $nameError;   ?></p>
                        <input type="text" name="name" class="form-control" >
                    </div>
                    <div class="form-group">
                        <label for="">Email</label><p style="color:red"><?php echo empty($emailError) ? "" : $emailError;   ?></p>
                        <input type="text" name="email" class="form-control" >
                    </div>
                    <div class="form-group">
                        <label for="">Password</label><p style="color:red"><?php echo empty($passwordError) ? "" : $passwordError;   ?></p>
                        <input type="password" name="password" class="form-control" >
                    </div>
                    <div class="form-group">
                        <label for="">Role</label>
                        <input type="checkbox" name="role" value="1" >
                    </div>

                    <div class="form-group">
                        <button type="submit" class="mr-3 btn btn-success" name="submit">Submit</button>
                        <a href="user_list.php" class="btn btn-warning">Back</a>
                    </div>
                </form>
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
