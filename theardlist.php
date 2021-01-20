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

    <body>
        <?php include "partials/_dbconnect.php"; ?>
        <?php include "partials/_header.php"; ?>

        <?php
        $id = $_GET['catid'];
        $sql = "SELECT * FROM `categories` WHERE `categories-id` = $id";
        $result = mysqli_query($conn, $sql);
        while ($row = mysqli_fetch_assoc($result)) {
            $catName = $row['categories-name'];
            $catDesc = $row['categories-description'];
        }
        ?>

        <?php
        $showAlert = false;
        $method = $_SERVER['REQUEST_METHOD'];
        if ($method == 'POST') {
            $th_title = $_POST['title'];
            $th_title=str_replace("<", "&lt;", $th_title);
            $th_title=str_replace(">", "&gt;", $th_title);
            $th_desc = $_POST['desc']; 
            $th_desc= str_replace("<", "&lt;", $th_desc);
            $th_desc=str_replace(">", "&gt;", $th_desc);
            $sno=$_POST['sno'];
            $sql = "INSERT INTO `theards` (`theard_title`, `theard_desc`, `theard_cat_id`, `theard_user_id`, `timestamp`) VALUES ('$th_title', '$th_desc', '$id', '$sno', current_timestamp()); ";
            $result = mysqli_query($conn, $sql);
            $showAlert = true;

            if ($showAlert) {
                echo "<div class='alert alert-success alert-dismissible fade show' role='alert'>
            <strong>Success!</strong> Your thread has been added ! please wait for community to respond
            <button type='button' class='close' data-dismiss='alert' aria-label='Close'>
              <span aria-hidden='true'>&times;</span>
            </button>
          </div>";
            }
        }
        ?>


        <div class="container my-3">
            <div class="jumbotron">
                <h1 class="display-4">Welcome to <?php echo $catName; ?> forums</h1>
                <p class="lead"> <?php echo $catDesc; ?> </p>
                <hr class="my-4">
                <p>It uses utility classes for typography and spacing to space content out within the larger container.
                </p>
                <a class="btn btn-success btn-lg" href="#" role="button">Learn more</a>
            </div>
        </div>

        <?php
        if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] == true) {
            echo '<div class="container">
        <form action=" ' . $_SERVER["REQUEST_URI"] . ' " method="post">
            <div class="form-group">
                <label for="exampleInputEmail1">Problem Title</label>
                <input type="text" class="form-control" id="title" name="title" aria-describedby="emailHelp">
                <small id="emailHelp" class="form-text text-muted">just submit a short theard title.</small>
            </div>
            <input type="hidden" name="sno" value=" '. $_SESSION["sno"] .' ">
            <div class="form-group">
                <label for="exampleFormControlTextarea1">Elaborate a problem</label>
                <textarea class="form-control" id="desc" name="desc" rows="3"></textarea>
            </div>
            <button type="submit" class="btn btn-success">Submit</button>
        </form>
    </div>';
        } else {
            echo '<div class="container">
              <h1 class="py-4">Start a Discursion</h1>
              <p class="lead">Your not logged in</p>
              </div>';
        }
        ?>

        <div class="container mb-5" id="ques">
            <h1 class="py-4">Browse Questions</h1>
            <?php
            $id = $_GET['catid'];
            $sql = "SELECT * FROM `theards` WHERE `theard_cat_id` = $id";
            $result = mysqli_query($conn, $sql);
            $noResult = true;
            while ($row = mysqli_fetch_assoc($result)) {
                $noResult = false;
                $id = $row['theard_id'];
                $title = $row['theard_title'];
                $Desc = $row['theard_desc'];
                $theard_time = $row['timestamp'];
                $theard_user_id =  $row['theard_user_id'];
                $sql2="SELECT  user_email FROM `users` WHERE  sno='$theard_user_id' ";
                $result2= mysqli_query($conn, $sql2);
                $row2= mysqli_fetch_assoc($result2);
              
                echo '<div class="media my-3">
                        <img src="img/userdefultimg.jpg" height="60px" class="mr-3" alt="user photo">
                       <div class="media-body">'.
                         '<h5 class="mt-0"><a class="text-dark" href="theard.php?thId=' . $id . ' ">' . $title . '</a></h5>
                         ' . $Desc . '
                       </div>' . '<p class="font-weight-bold my-0"> Asked by: ' . $row2["user_email"]  . ' at ' . $theard_time . '</p>
                      </div>';
            }

            if ($noResult) {
                echo '<div class="jumbotron jumbotron-fluid">
                    <div class="container">
                      <p class="display-4">No threads found</p>
                      <p class="lead">BE the frist person ask to the Questions.</p>
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