<!doctype html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css"
        integrity="sha384-TX8t27EcRE3e/ihU7zmQxVncDAy5uIKz4rEkgIXeMed4M0jlfIDPvg6uqKI2xXr2" crossorigin="anonymous">
    <link rel="stylesheet" href="css/style.css">
    <title>Welcome To iDiscuss - coding forum</title>
</head>


<body>
    <?php include "partials/_dbconnect.php"; ?>
    <?php include "partials/_header.php"; ?>

    <?php
    $id = $_GET['thId'];
    $sql = "SELECT * FROM `theards` WHERE `theard_id` = $id";
    $result = mysqli_query($conn, $sql);
    while ($row = mysqli_fetch_assoc($result)) {
        $title = $row['theard_title'];
        $Desc = $row['theard_desc'];
        $theard_user_id=  $row['theard_user_id'];

        //query the find of user who posted the OP
        $sql2= "SELECT  user_email FROM `users` WHERE  sno='$theard_user_id' ";
        $result2= mysqli_query($conn, $sql2);
        $row2 = mysqli_fetch_assoc($result2);
        $posted_by = $row2['user_email'];
    }
    ?>

    <?php
    $showAlert = false;
    $method = $_SERVER['REQUEST_METHOD'];
    if ($method == 'POST') {
        $comment = $_POST['comment'];
        $comment=str_replace("<", "&lt;", $comment);
        $comment=str_replace(">", "&gt;", $comment);
        $sno=$_POST['sno'];
        $sql = "INSERT INTO `comments` ( `comment_content`, `theard_id`, `comment by`, `comment_time`) VALUES ('$comment', '$id', '$sno', current_timestamp())";
        $result = mysqli_query($conn, $sql);
        $showAlert = true;

        if ($showAlert) {
            echo "<div class='alert alert-success alert-dismissible fade show' role='alert'>
            <strong>Success!</strong> Your comment has been added ! 
            <button type='button' class='close' data-dismiss='alert' aria-label='Close'>
              <span aria-hidden='true'>&times;</span>
            </button>
          </div>";
        }
    }
    ?>

    <div class="container my-3">
        <div class="jumbotron">
            <h1 class="display-4"> <?php echo $title; ?></h1>
            <p class="lead"> <?php echo $Desc; ?></p>
            <hr class="my-4">
            <p>It uses utility classes for typography and spacing to space content out within the larger container.</p>
            <p>Posted by: <b> <?php echo  $posted_by; ?></b></p>
        </div>
    </div>

    <?php
    if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] == true ) {
        echo'<div class="container">
            <form action="'.$_SERVER["REQUEST_URI"].'" method="post">
    <div class="form-group">
        <label for="exampleFormControlTextarea1">Type your Comment</label>
        <textarea class="form-control" id="comment" name="comment" rows="3"></textarea>
        <input type="hidden" name="sno" value=" '. $_SESSION["sno"] .' ">
    </div>
    <button type="submit" class="btn btn-success">Post Comment</button>
    </form>
    </div>'; 
} 
    
    else { 
        echo '
    <div class="container">
    <h1 class="py-4">Post a Comment</h1>
        <p class="lead">Your not logged in</p>
    </div>'; 
}
?>

    <div class="container" id="ques">
        <h1 class="py-4">Discussion</h1>
        <?php
        $id = $_GET['thId'];
        $sql = "SELECT * FROM `comments` WHERE `theard_id` = $id";
        $result = mysqli_query($conn, $sql);
        $noResult = true;
        while ($row = mysqli_fetch_assoc($result)) {
            $noResult = false;
            $com_id = $row['comment_id'];
            $content = $row['comment_content'];
            $comment_time = $row['comment_time'];
            $theard_user_id=  $row['comment by'];
            $sql2="SELECT  user_email FROM `users` WHERE  sno='$theard_user_id' ";
            $result2= mysqli_query($conn, $sql2);
            $row2= mysqli_fetch_assoc($result2);

            echo '<div class="media my-3">
                        <img src="img/userdefultimg.jpg" height="60px" class="mr-3" alt="user photo">
                       <div class="media-body">
                    <p class="font-weight-bold my-0"> ' . $row2["user_email"]  . ' at ' . $comment_time . '</p>
                         ' . $content . '
                       </div>
                      </div>';
        }

        if ($noResult) {
            echo '<div class="jumbotron jumbotron-fluid">
                    <div class="container">
                      <p class="display-4">No comment found</p>
                      <p class="lead">Be the first person ask to the comment.</p>
                    </div>
                  </div>';
        }
        ?>
    </div>
    <?php include "partials/_footer.php"; ?>
    <!-- Optional JavaScript; choose one of the two! -->

    <!-- Option 1: jQuery and Bootstrap Bundle (includes Popper) -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"
        integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous">
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-ho+j7jyWK8fNQe+A12Hb8AhRq26LrZ/JpcUGGOn+Y7RsweNrtN/tE3MoK7ZeZDyx" crossorigin="anonymous">
    </script>

    <!-- Option 2: jQuery, Popper.js, and Bootstrap JS
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js" integrity="sha384-9/reFTGAW83EW2RDu2S0VKaIzap3H66lZH81PoYlFhbGU+6BZp6G7niu735Sk7lN" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.min.js" integrity="sha384-w1Q4orYjBQndcko6MimVbzY0tgp4pWB4lZ7lr30WKz0vr/aWKhXdBNmNb5D92v7s" crossorigin="anonymous"></script>
    -->
</body>

</html>