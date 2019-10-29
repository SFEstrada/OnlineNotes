<?php
    session_start();
    $notesContent = "";

    if (array_key_exists('id', $_COOKIE) && $_COOKIE['id']){
        $_SESSION['id'] = $_COOKIE['id'];
    }

    if (array_key_exists('id', $_SESSION) && $_SESSION['id']){
        //echo 'Logged in! <a href="index.php?logout=1">Log out</a>';
        include("connection.php");

        $query = "SELECT notes FROM `users` WHERE id = '".mysqli_real_escape_string($link, $_SESSION['id'])."' LIMIT 1";

        $row = mysqli_fetch_array(mysqli_query($link, $query));
        $notesContent = $row['notes'];

    }
    else{
        header("Location: index.php");
    }

    include("header.php");
?>

    <a class="btn btn-secondary" type="button" href="index.php?logout=1">Log Out</a>
    <div class="container container-notes">
        <textarea id="notes-area" class="col-md-12 form-control"><?php echo $notesContent?></textarea>
    </div>

<?php
    include("footer.php");

?>
