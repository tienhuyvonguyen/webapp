<?php
ini_set("display_errors", "0");
include '../db/dbConnect.php'; // Database connection
$showAlert = false;
$showError = false;
$exists = false;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = strtoupper($_POST['username']);
    $password = $_POST['password'];
    $cpassword = $_POST['cpassword'];
    $email = $_POST['email'];
    $sql = "Select * from users where username= :username limit 1";
    try {
        $result = $conn->prepare($sql);
        $result->bindParam(':username', $username, PDO::PARAM_STR);
        $result->execute();
        $num = $result->rowCount();
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
    // If username already exists
    if ($num == 0) {
        // deepcode ignore PhpSameEvalBinaryExpressiontrue: Accepted
        if (($password == $cpassword) && $exists == false) {
            $hash = password_hash($password, PASSWORD_DEFAULT);
            $sql = "INSERT INTO `users` ( `username`,
            	`userPassword`, `userEmail`) VALUES (:username, :password, :email)";
            try {
                $result = $conn->prepare($sql);
                $result->bindParam(':username', $username, PDO::PARAM_STR);
                $result->bindParam(':password', $hash, PDO::PARAM_STR);
                $result->bindParam(':email', $email, PDO::PARAM_STR);
                $result->execute();
            } catch (PDOException $e) {
                echo "Error: " . $e->getMessage();
            }
            if ($result) {
                $showAlert = true;
            }
            echo "<script>alert('Sign up Successful!'); window.location.href='./login.php';</script>";
        } else {
            $showError = "Passwords do not match";
        }
    }
    if ($num > 0) {
        $exists = "Username not available";
    }
}

?>

<!doctype html>

<html lang="en">

<head>

    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1,
		shrink-to-fit=no">
    <title>Sign up</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css" integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous">
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

        <h1 class="text-center">Signup</h1>
        <form action="signup.php" method="POST">

            <div class="form-group">
                <label for="username">Username</label>
                <input type="text" class="form-control" id="username" name="username" aria-describedby="emailHelp" required="required" max="12">
            </div>

            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" class="form-control" id="password" name="password" required="required" max="18">
            </div>

            <div class="form-group">
                <label for="cpassword">Confirm Password</label>
                <input type="password" class="form-control" id="cpassword" name="cpassword" required="required" max="18">
                <small id="emailHelp" class="form-text text-muted">
                    Make sure to type the same password
                </small>
            </div>

            <div class="form-group">
                <label for="email">Email</label>
                <input type="text" class="form-control" id="email" name="email" aria-describedby="emailHelp" max="50">
            </div>
            <button type="submit" class="btn btn-primary">
                Signup
            </button>
            <a href="login.php" class="btn btn-primary">
                Login
            </a>
            <a href="../index.php" class="btn btn-primary">
                Home
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