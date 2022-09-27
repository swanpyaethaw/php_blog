<?php 
session_start();
require "config/config.php";
require "config/common.php";

if(isset($_POST['submit'])){

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
    $stmt = $pdo->prepare("SELECT * FROM users WHERE email=:email");
    $stmt->bindValue(':email',$email);
    $stmt->execute();
    $user = $stmt->fetch();

    if($user){
        echo "<script>alert('Email Duplicated');</script>";
    }else{
        $stmt = $pdo->prepare("INSERT INTO users (name,email,password) VALUES (:name,:email,:password)");
        $result = $stmt->execute([
            ':name' => $name,
            ':email' => $email,
            ':password' => $password
        ]);
        if($result){
            echo "<script>alert('Register Successfully!You can now login');window.location.href='login.php'</script>";
        }
    }
      }
    
}


?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Blog | Log in</title>

    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="plugins/fontawesome-free/css/all.min.css">
    <!-- icheck bootstrap -->
    <link rel="stylesheet" href="plugins/icheck-bootstrap/icheck-bootstrap.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="dist/css/adminlte.min.css">
</head>

<body class="hold-transition login-page">
    <div class="login-box">
        <div class="login-logo">
            <a href="../../index2.html"><b>Blog</b></a>
        </div>
        <!-- /.login-logo -->
        <div class="card">
            <div class="card-body login-card-body">
                <p class="login-box-msg">Register New Account</p>

                <form action="register.php" method="POST">
                     <input name="_token" type="hidden" value="<?php echo $_SESSION['_token']; ?>">
                <p style="color:red"><?php echo empty($nameError) ? "" : $nameError;   ?></p>
                        <div class="input-group mb-3">
                        <input type="text" name="name" class="form-control" placeholder="Name" value="<?php echo empty($name) ? '' : $name; ?>">
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-envelope"></span>
                            </div>
                        </div>
                    </div>
                    <p style="color:red"><?php echo empty($emailError) ? "" : $emailError;   ?></p>
                    <div class="input-group mb-3">
                        <input type="email" name="email" class="form-control" placeholder="Email" value="<?php echo empty($email) ? '' : $email; ?>">
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-envelope"></span>
                            </div>
                        </div>
                    </div>
                    <p style="color:red"><?php echo empty($passwordError) ? "" : $passwordError;   ?></p>
                    <div class="input-group mb-3">
                        <input type="password" name="password" class="form-control" placeholder="Password" value="<?php echo empty($password) ? '' : $password; ?>">
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-lock"></span>
                            </div>
                        </div>
                    </div>
                    <div class="row">

                            <div class="container">
                            <button type="submit" name="submit" class="btn btn-primary btn-block">Register</button>
                            <a href="login.php" class="btn btn-default btn-block">Login</a>

                    </div>
                </form>


                <!-- <p class="mb-0">
        <a href="register.html" class="text-center">Register a new membership</a>
      </p> -->
            </div>
            <!-- /.login-card-body -->
        </div>
    </div>
    <!-- /.login-box -->

    <!-- jQuery -->
    <script src="plugins/jquery/jquery.min.js"></script>
    <!-- Bootstrap 4 -->
    <script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
    <!-- AdminLTE App -->
    <script src="dist/js/adminlte.min.js"></script>

</body>

</html>