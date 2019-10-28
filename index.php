<?php
    session_start();

    $error = "";


    if (array_key_exists("logout", $_GET)){
        unset($_SESSION);
        setcookie("id", "", time() - 60*60);

        $_COOKIE["id"] = "";
    }
    else if (array_key_exists("id", $_SESSION) && array_key_exists("id", $_COOKIE)){
        header("Location: loggedpage.php");
    }

    if (array_key_exists("submit", $_POST)){

        $link = mysqli_connect("localhost", "sestrada", "sestrada", "mynotes");

        if (mysqli_connect_error()){
            die("Database connection error");
        }

        if(!$_POST['email']){
            $error .= 'Please enter your email<br>';
        }
        if(!$_POST['password']){
            $error .= 'Please enter a password<br>';
        }
        if ($error != ""){
            $error = '<p>There were some errors in your form</p>'.$error;
        }
        if ($error == ""){

            if ($_POST['signUp'] == '1'){
                $query = "SELECT `id` FROM `users` WHERE `username` = '".mysqli_real_escape_string($link, $_POST['email'])."'";
                $result = mysqli_query($link, $query);

                if (mysqli_num_rows($result) > 0){
                    $error = 'The user already exists';
                }
                else{
                    $query = "INSERT INTO `users` (`username`, `password`) VALUES ('".mysqli_real_escape_string($link, $_POST['email'])."', '".mysqli_real_escape_string($link, $_POST['password'])."')";

                    if (!mysqli_query($link, $query)){
                        $error = 'Could not sign you up, please try again later';
                    }
                    else{
                        $hash = password_hash($_POST['password'], PASSWORD_DEFAULT);
                        $insertID = mysqli_insert_id($link);
                        $query = "UPDATE `users` SET `password` = '".mysqli_real_escape_string($link, $hash)."' WHERE `id` = ".mysqli_insert_id($link)." LIMIT 1";
                        mysqli_query($link, $query);

                        $_SESSION['id'] = $insertID;

                        if ($_POST['stayLoggedIn'] == '1'){
                            setcookie("id", $insertID, time() + 60*60*24);
                        }

                        header("Location: loggedpage.php");
                    }
                }
            }
            else{
                $query = "SELECT * FROM `users` WHERE `username` = '".mysqli_real_escape_string($link, $_POST['email'])."'";
                $result = mysqli_query($link, $query);

                $row = mysqli_fetch_array($result);

                if (isset($row)){
                    if (password_verify($_POST['password'], $row['password'])){
                        $_SESSION['id'] = $row['id'];

                        $insertID = $row['id'];

                        if ($_POST['stayLoggedIn'] == '1'){
                            setcookie("id", $insertID, time() + 60*60*24);
                        }

                        header("Location: loggedpage.php");

                    }
                    else{
                        $error = "Wrong email/password";
                    }
                }
                else{
                    $error = "Wrong email/password";
                }
            }
        }
    }

?>

<!doctype html>
<html lang="en">
<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">

    <link rel="stylesheet" type="text/css" href="styles.css">

    <title>Online Notes</title>
</head>
<body>

    <div class="container col-md-4 offset-md-4" >
        <h1>Online Notes</h1>

        <div id="error"><?php echo $error; ?></div>
        <form method="post" action="">
            <div class="form-group">
                <label for="inputEmail1">Email</label>
                <input type="email" class="form-control" name="email" id="inputEmail1" placeholder="Email">
            </div>
            <div class="form-group">
                <label for="inputPassword1">Password</label>
                <input type="password" class="form-control" name="password" id="inputPassword1" placeholder="Password">
            </div>
            <div class="form-group form-check">
                <input type="checkbox" class="form-check-input" id="checkbox1" name="stayLoggedIn" value="1">
                <label class="form-check-label" for="checkbox1">Stay logged in</label>
            </div>
            <div class="form-group">
                <input type="hidden" name="signUp" value="1">
                <button type="submit" class="btn btn-primary" name="submit" value="Sign up">Sign up</button>
            </div>
        </form>
        <form method="post" action="">
            <div class="form-group">
                <label for="inputEmail2">Email</label>
                <input type="email" class="form-control" name="email" id="inputEmail2" placeholder="Email">
            </div>
            <div class="form-group">
                <label for="inputPassword2">Password</label>
                <input type="password" class="form-control" name="password" id="inputPassword2" placeholder="Password">
            </div>
            <div class="form-group form-check">
                <input type="checkbox" class="form-check-input" name="stayLoggedIn" id="checkbox2" value="1">
                <label class="form-check-label" for="checkbox2">Stay logged in</label>
            </div>
            <div class="form-group">
                <input type="hidden" name="signUp" value="0">
                <button type="submit" class="btn btn-primary" name="submit" value="Log in">Log in</button>
            </div>

        </form>
    </div>


<!-- Optional JavaScript -->
<!-- jQuery first, then Popper.js, then Bootstrap JS -->
<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
</body>
</html>
