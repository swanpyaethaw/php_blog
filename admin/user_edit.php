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
    $id = $_POST['id'];
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $stmt = $pdo->prepare("SELECT * FROM users WHERE email=:email");
    $stmt->execute([
    ':email' => $email,
    ]);
$userPass = $stmt->fetch();
if(is_array($userPass = $stmt->fetch())){
  $oldPassword = $userPass['password'];
  if($oldPassword !== $password){
    $password = password_hash($password,PASSWORD_DEFAULT);
  }else{
    $password = $oldPassword;
  }
}

 

  
    if(empty($_POST['role'])){
        $role = 0;
    }else{
        $role = 1;
    }
    
    $stmt = $pdo->prepare("SELECT * FROM users WHERE email=:email AND id != :id");
    $stmt->execute([
    ':email' => $email,
    ':id' => $id
    ]);
$user = $stmt->fetch();


if($user){
    echo "<script>alert('Email Duplicated');</script>";
}else{
    $stmt = $pdo->prepare("UPDATE users SET name=:name,email=:email,password=:password,role=:role WHERE id=:id");
    $result = $stmt->execute([
        ':name' => $name,
        ':email' => $email,
        ':password' => $password,
        ':role' => $role,
        ':id' => $id
]);
if($result){
    echo "<script>alert('Successfully Updated');window.location.href='user_list.php'</script>";
}
}
  }
    }

    $stmt = $pdo->prepare("SELECT * FROM users WHERE id=" . $_GET['id']);
    $stmt->execute();
    $result = $stmt->fetchAll();
    
?>

<?php include "header.php" ?>

    <!-- Main content -->
    <div class="content">
      <div class="container-fluid">
        <div class="row">
          <div class="col-md-12">
            <div class="card">
              <div class="card-body">
                <form action="" method="POST" >
                <input name="_token" type="hidden" value="<?php echo $_SESSION['_token']; ?>">
                <input type="hidden" name="id"  value="<?php echo $result[0]['id'] ?>">
                    <div class="form-group">
                        <label for="">Name</label><p style="color:red"><?php echo empty($nameError) ? "" : $nameError;   ?></p>
                        <input type="text" name="name" class="form-control" value="<?php echo escape($result[0]['name']) ?>"  >
                    </div>
                    <div class="form-group">
                        <label for="">Email</label><p style="color:red"><?php echo empty($emailError) ? "" : $emailError;   ?></p>
                        <input type="text" name="email" class="form-control" value="<?php echo escape($result[0]['email']) ?>" >
                    </div>
                    <div class="form-group">
                        <label for="">Password</label><p style="color:red"><?php echo empty($passwordError) ? "" : $passwordError;   ?></p>
                        <input type="password" name="password" class="form-control" value="<?php echo escape($result[0]['password']) ?>">
                    </div>
                    <div class="form-group">
                        <label for="">Admin</label>
                        <input type="checkbox" name="role" value="1" <?php if($result[0]['role']==1){ echo 'checked'; } ?>>
                    </div>

                    <div class="form-group">
                        <button type="submit" class="mr-3 btn btn-success" name="submit">Edit</button>
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
