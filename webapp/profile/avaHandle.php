<?php
include('../db/dbConnect.php');
include('../auth/session.php');
try {
    $sql = "select avatar from users where username = :username";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':username', $login_session);
    $stmt->execute();
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    $oldAvatar = $row['avatar'];
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}

if (isset($_POST["submit"])) {
    $target_dir  = "../uploads/avatars/";
    // $userUniq = md5($login_session); // md5 hash of the username to create a unique folder for each user
    $target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
    $uploadOk = 1;
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION)); // bypass possible
    $check = getimagesize($_FILES["fileToUpload"]["tmp_name"], $imageFileType); // check image
    if ($check !== false) {
        $uploadOk = 1;
    } else {
        echo "<script>alert('File is not an image!');window.location.href='userProfile.php';</script>";
        $uploadOk = 0;
        die();
    }
    // Check file size
    if ($_FILES["fileToUpload"]["size"] > 500000) { // 500kb
        echo "<script>alert('Sorry, your Avatar is too large.')</script>";
        $uploadOk = 0;
        die();
    }
    $whiteList = array('png', 'jpg', 'jpeg', 'gif'); // whitelist of file types
    if (!in_array($imageFileType, $whiteList)) {
        echo "<script>alert('Sorry, only JPG, JPEG, PNG & GIF files are allowed.')</script>";
        $uploadOk = 0;
        die();
    }
    if ($uploadOk == 0) {
        echo "Sorry, your file was not uploaded.";
        die();
    } else {
        if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
            echo "The file " . htmlspecialchars(basename($_FILES["fileToUpload"]["name"])) . " has been uploaded.";
        } else {
            echo "<script>alert('Sorry, there was an error uploading your file.'); window.location.href='userProfile.php';</script>";
            die();
        }
    }
    $newAvatar = $target_file;
    if ($newAvatar == $oldAvatar) {
        $newAvatar = $oldAvatar;
    }
    try {
        $sql = "UPDATE users SET avatar = :avatar WHERE username = :username ";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':avatar', $newAvatar);
        $stmt->bindParam(':username', $login_session);
        $stmt->execute();
        echo "<script>alert('Avatar updated successfully');window.location.href='userProfile.php';</script>";
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}
