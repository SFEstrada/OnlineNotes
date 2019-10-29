<?php
    session_start();

    $error = "";


    if (array_key_exists("logout", $_GET)){
        unset($_SESSION);
        setcookie("id", "", time() - 60*60);
        $_COOKIE["id"] = "";

        session_destroy();
        header("Location: index.php");
    }
    else if (array_key_exists("id", $_SESSION) && array_key_exists("id", $_COOKIE)){
        header("Location: loggedpage.php");
    }

    if (array_key_exists("submit", $_POST)){

        include("connection.php");

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

<?php include("header.php"); ?>
    <div class="container container-form">
            <div class="col-md-6 offset-md-3 form-wrapper">
                <h1>Online Notes</h1>
                <form method="post" action="" id="signUpForm" class="needs-validation">
                    <h3>Keep your thoughts and adventures with you all the time</h3>
                    <p class="form-banner">Don't loose any more ideas! Sign up now!</p>

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
                    <div class="form-group form-check">
                        <input type="checkbox" class="form-check-input" id="checkboxTerms" required>
                        <label class="form-check-label" for="checkboxTerms">Accept the terms and conditions</label>
                        <div class="invalid-feedback">
                            You must agree with before signing up
                        </div>
                    </div>
                    <div class="form-group form-button">
                        <input type="hidden" name="signUp" value="1">
                        <button type="submit" class="btn btn-primary" name="submit" value="Sign up">Sign up</button>
                        <button type="button" class="btn btn-outline-light changeForm">Or, Log in</button>
                    </div>
                </form>
                <form method="post" action="" id="logInForm">
                    <h3>Write more ideas</h3>
                    <h3></h3>
                    <p class="form-banner">Log in with your username and password</p>
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
                    <div class="form-group form-button">
                        <input type="hidden" name="signUp" value="0">
                        <button type="submit" class="btn btn-primary" name="submit" value="Log in">Log in</button>
                        <button type="button" class="btn btn-outline-light changeForm">Or, Sign Up</button>
                    </div>
                </form>
            </div>
    </div>
    <div id="error" class="container col-md-4"><?php
        if ($error != ""){
            echo '<div class="alert alert-danger alert-dismissible" role="alert">'.$error.'<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>';
        }
        ?></div>
<?php include("footer.php"); ?>