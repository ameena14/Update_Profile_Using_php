<?php
$conn = mysqli_connect('localhost', 'root', '', 'newdb') or die('connection failed');

if (isset($_POST['submit'])) {
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $pass = mysqli_real_escape_string($conn, md5($_POST['password']));
    $cpass = mysqli_real_escape_string($conn, md5($_POST['cpassword']));
    $image = $_FILES['image']['name'];
    $image_size = $_FILES['image']['size'];
    $image_tmp_name = $_FILES['image']['tmp_name'];
    $image_folder = 'upload_img/' . $image;

    // Correct SQL query with backticks for table names
    $select = mysqli_query($conn, "SELECT * FROM `user_details` WHERE email='$email' AND password='$pass'") or die('query failed');

    if (mysqli_num_rows($select) > 0) {
        $message[] = 'User already exists';
    } else {
        if ($pass != $cpass) {
            $message[] = 'Confirm password not matched';
        } elseif ($image_size > 2000000) {
            $message[] = 'Image size is too large!';
        } else {
            $insert = mysqli_query($conn, "INSERT INTO `user_details`(name, email, password, image) VALUES('$name', '$email', '$pass', '$image')") or die('query failed');

            if ($insert) {
                move_uploaded_file($image_tmp_name, $image_folder);
                $message[] = 'Registered successfully!';
                header('location:login.php');
            } else {
                $message[] = 'Registration failed!';
            }
        }
    }
}

?>



<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <link rel="stylesheet" href="register.css">
</head>

<body>
    <div class="form-container-register">
        <form action="" method="post" enctype="multipart/form-data">
            <h3>Register Now</h3>
            <?php
            if (isset($message)) {
                foreach ($message as $message) {
                    echo '<div class="message">' . $message . '</div>';
                }
            }
            ?>
            <input type="text" name="name" placeholder="Enter username" class="box" required>
            <input type="email" name="email" placeholder="Enter Email" class="box" required>
            <input type="password" name="password" placeholder="Enter Password" class="box" required>
            <input type="password" name="cpassword" placeholder="Confirm Password" class="box" required>
            <input type="file" name="image" class="box" accept="image/jpg,image/jpeg,image/png" required>
            <input type="submit" name="submit" value="Register Now" class="btn">
            <p>Already have an account? <a href="login.php">Login Now</a></p>
        </form>

    </div>
</body>

</html>