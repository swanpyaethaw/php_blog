<?php 
session_start();
require "../config/config.php";

if(empty($_SESSION['id']) && empty($_SESSION['logged_in'])){
  header('location:login.php');
}

if(isset($_POST['submit'])){
    $file = 'images/' . $_FILES['image']['name'];
    $imageType = pathinfo($file,PATHINFO_EXTENSION);

    if($imageType != 'jpg' && $imageType != 'png' && $imageType != 'jpeg'){
        echo "<script>alert('Image type must be png,jpg,jpeg')</script>";
    }else{
        $title = $_POST['title'];
        $content = $_POST['content'];
        $image = $_FILES['image']['name'];
        $author_id = $_SESSION['user_id'];
        move_uploaded_file($_FILES['image']['tmp_name'],$file);
        $stmt = $pdo->prepare("INSERT INTO posts (title,content,image,author_id) VALUES (:title,:content,:image,:author_id)");
        $result = $stmt->execute([
            ':title' => $title,
            ':content' => $content,
            ':image' => $image,
            ':author_id' => $author_id
        ]);
        if($result){
            echo "<script>alert('Successfully Added');window.location.href='index.php'</script>";
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
                <form action="add.php" method="POST" enctype="multipart/form-data">
                    <div class="form-group">
                        <label for="">Title</label>
                        <input type="text" name="title" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="">Content</label>
                        <textarea name="content" class="form-control" id="" cols="30" rows="10" required></textarea>
                    </div>
                    <div class="form-group">
                        <label for="">Image</label>
                        <input type="file" name="image" required>
                    </div>
                    <div class="form-group">
                        <button type="submit" class="mr-3 btn btn-success" name="submit">Submit</button>
                        <a href="index.php" class="btn btn-warning">Back</a>
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
