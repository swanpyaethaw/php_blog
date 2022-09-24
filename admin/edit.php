<?php 
session_start();
require "../config/config.php";

if(empty($_SESSION['id']) && empty($_SESSION['logged_in'])){
  header('location:login.php');
}

if(isset($_POST['submit'])){    
    $title = $_POST['title'];
    $content = $_POST['content'];
    $id = $_POST['id'];
    if($_FILES['image']['name'] != null){
        $file = 'images/' . $_FILES['image']['name'];
        $imageType = pathinfo($file,PATHINFO_EXTENSION);
        if($imageType != 'jpg' && $imageType != 'png' && $imageType != 'jpeg'){
            echo "<script>alert('Image type must be png,jpg,jpeg')</script>";
        }else{ 
            $image = $_FILES['image']['name'];
            move_uploaded_file($_FILES['image']['tmp_name'],$file);
            $stmt = $pdo->prepare("UPDATE posts SET title=:title,content=:content,image=:image WHERE id=:id");
            $result = $stmt->execute([
                ':title' => $title,
                ':content' => $content,
                ':image' => $image,
                ':id' => $id
            ]);
            if($result){
                echo "<script>alert('Updated Successfully');window.location.href='index.php'</script>";
            }
    
        }
    }else{
        $stmt = $pdo->prepare("UPDATE posts SET title='$title',content='$content' WHERE id=$id");
        $result = $stmt->execute();
        if($result){
            echo "<script>alert('Updated Successfully');window.location.href='index.php'</script>";
        }


    }
   
}

$stmt = $pdo->prepare("SELECT * FROM posts WHERE id=" . $_GET['id']);
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
                        <form action="" method="POST" enctype="multipart/form-data">
                            <input type="hidden" name="id"  value="<?php echo $result[0]['id'] ?>">
                            <div class="form-group">
                                <label for="">Title</label>
                                <input type="text" name="title" class="form-control"
                                    value="<?php echo $result[0]['title'] ?>" required>
                            </div>
                            <div class="form-group">
                                <label for="">Content</label>
                                <textarea name="content" class="form-control" id="" cols="30" rows="10"
                                    required><?php echo $result[0]['content'] ?></textarea>
                            </div>
                            <div class="form-group">
                                <img src="images/<?php echo $result[0]['image'] ?>" alt="" width="150" height="150"><br><br>
                                <label for="">Image</label>
                                <input type="file" name="image">
                            </div>
                            <div class="form-group">
                                <button type="submit" class="mr-3 btn btn-success" name="submit">Edit</button>
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