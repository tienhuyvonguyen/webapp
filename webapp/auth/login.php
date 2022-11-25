<?php
$showError = false;
require("../db/dbConnect.php");
session_start();
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $myusername = strtoupper($_POST['username']);
    $mypassword = $_POST['password'];
    // captcha
    $recaptcha = $_POST['g-recaptcha-response'];
    // Put secret key here, which we get
    // from google console
    $secret_key = '6LcxVSsjAAAAAGs-Ggs2uLLqk9ZCNK-P9fxfJmvY';

    // Hitting request to the URL, Google will
    // respond with success or error scenario
    $url = 'https://www.google.com/recaptcha/api/siteverify?secret='
        . $secret_key . '&response=' . $recaptcha;

    // Making request to verify captcha
    // file deepcode ignore PT: accept
    $response = file_get_contents($url);

    // Response return by google is in
    // JSON format, so we have to parse
    // that json
    $response = json_decode($response);

    // captcha
    $sql = "SELECT * FROM users WHERE username = :myusername";
    try {
        $result = $conn->prepare($sql);
        $result->bindParam(':myusername', $myusername, PDO::PARAM_STR);
        $result->execute();
        $num = $result->rowCount();
        $data = $result->fetch(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
    $premium = $data['premiumTier'];
    //If result matched $myusername and $mypassword, table row must be 1 row & check captcha
    if ($response->success == false) { //vercode is the session variable that holds the captcha code
        $showError = "Invalid Captcha";
    } elseif ($num == 1 && password_verify($mypassword, $data['userPassword'])) {
        if (!empty($_POST["remember"])) {
            //COOKIES for username set httpdonly
            setcookie("user_login", htmlspecialchars($_POST["username"]), time() + (86400 * 7),  NULL, NULL, NULL, TRUE); // 86400 = 1 day
            //COOKIES for password
            setcookie("user_password", htmlspecialchars($_POST["password"]), time() + (86400 * 7),  NULL, NULL, NULL, TRUE);
        } else {
            if (isset($_COOKIE["user_login"])) {
                setcookie("user_login", "", NULL, NULL, NULL, NULL, TRUE);
                if (isset($_COOKIE["userpassword"])) {
                    setcookie("userpassword", "", NULL, NULL, NULL, NULL, TRUE);
                }
            }
        }
        $_SESSION['login_user'] = htmlspecialchars($myusername); // set username in session
        $_SESSION["login_time_stamp"] = time(); //set login time
        $_SESSION['premiumTier'] =  $premium;
        echo "<script>alert('Login Successful!'); window.location.href='../services/main.php';</script>";
    } elseif ($num == 0) {
        $showError = "Invalid Username or Password";
    } else {
        $showError = "Invalid Username or Password";
    }
}

?>

<!doctype html>

<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1,
		shrink-to-fit=no">
    <title>Login</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css" integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous">
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
</head>

<body style="background-color: burlywood;">
    <center>
        <h1>☜︎☹︎✋︎❄︎☜︎ 𝕸𝕰𝕸𝕰 𝕹𝕱𝕿 ⤜($ ͟ʖ$)⤏</h1>
    </center>

    <?php

    if ($showAlert) {

        echo ' <div class="alert alert-success
			alert-dismissible fade show" role="alert">
	
			<strong>Success!</strong> Your account is
			now created and you can login.
			<button type="button" class="close"
				data-dismiss="alert" aria-label="Close">
				<span aria-hidden="true">×</span>
			</button>
		</div> ';
    }

    if ($showError) {

        echo ' <div class="alert alert-danger
			alert-dismissible fade show" role="alert">
		<strong>Error!</strong> ' . $showError . '
	
	<button type="button" class="close"
			data-dismiss="alert aria-label="Close">
			<span aria-hidden="true">×</span>
	</button>
	</div> ';
    }

    if ($exists) {
        echo ' <div class="alert alert-danger
			alert-dismissible fade show" role="alert">
	
		<strong>Error!</strong> ' . $exists . '
		<button type="button" class="close"
			data-dismiss="alert" aria-label="Close">
			<span aria-hidden="true">×</span>
		</button>
	</div> ';
    }

    ?>

    <div class="container my-4 ">
        <h1 class="text-center">Login</h1>
        <form action="login.php" method="POST">
            <div class="form-group">
                <label for="username">Username</label>
                <input type="text" class="form-control" id="username" name="username" aria-describedby="emailHelp" required="required" value="<?php if (isset($_COOKIE["user_login"])) {
                                                                                                                                                    echo htmlspecialchars($_COOKIE["user_login"]);
                                                                                                                                                } ?>">
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" class="form-control" id="password" name="password" required="required" value="<?php if (isset($_COOKIE["userpassword"])) {
                                                                                                                            echo htmlspecialchars($_COOKIE["userpassword"]);
                                                                                                                        } ?>">
            </div>
            <!-- captcha -->
                <div class="g-recaptcha" data-sitekey="6LcxVSsjAAAAAEDBn2g4J81XrX6lJxV_A-bL7HU_">
                </div>
            <br>
            <!-- captcha -->

            <!-- remember me -->
            <div class="field-group">
                <div><input type="checkbox" name="remember" id="remember" <?php if (isset($_COOKIE["user_login"])) { ?> checked <?php } ?> /> </div>
                <label for="remember-me">Remember me</label>
            </div>
            <!-- remember me -->

            <button type="submit" class="btn btn-primary">
                Login
            </button>
            <a href="signup.php" class="btn btn-primary">
                Signup
            </a>
            <a href="../index.php" class="btn btn-primary">
                Home
            </a>
            <!-- forgot password -->
            <a href="forgotPassword.php" class="btn btn-primary disabled">
                Forgot Password
            </a>
        </form>
    </div>
    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->

    <script src="
https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="
sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous">
    </script>

    <script src="
https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous">
    </script>

    <script src="
https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js" integrity="sha384-OgVRvuATP1z7JjHLkuOU7Xw704+h835Lr+6QL9UvYjZE3Ipu6Tp75j7Bh/kR0JKI" crossorigin="anonymous">
    </script>
</body>

</html>
<style>
    .container1 {
        border: 1px solid rgb(73, 72, 72);
        border-radius: 10px;
        margin: auto;
        padding: 10px;
        text-align: center;
    }

    h1 {
        margin-top: 10px;
    }

    button {
        border-radius: 5px;
        padding: 10px;
        color: #fff;
        background-color: #167deb;
        border-color: #0062cc;
        font-weight: bolder;
        cursor: pointer;
    }

    button:hover {
        text-decoration: none;
        background-color: #0069d9;
        border-color: #0062cc;
    }

    .g-recaptcha {
        margin-left: 0px;
    }
</style>
