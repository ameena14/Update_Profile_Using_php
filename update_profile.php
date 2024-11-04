<?php
$conn = mysqli_connect('localhost', 'root', '', 'newdb') or die('connection failed');

session_start();
$user_id = $_SESSION['user_id'];

if (isset($_POST['update_profile'])){
    $update_name=mysqli_real_escape_string($conn,$_POST['update_name']);
    $update_email=mysqli_real_escape_string($conn,$_POST['update_email']);

    mysqli_query($conn,"UPDATE `user_details` SET name='$update_name',email='$update_email' WHERE id='$user_id'")
    or die('query failed');

    $old_pass= $_POST['old_pass'];
    $update_pass=mysqli_real_escape_string($conn,md5($_POST['update_pass']));
    $new_pass=mysqli_real_escape_string($conn,md5($_POST['new_pass']));
    $confirm_pass=mysqli_real_escape_string($conn,md5($_POST['confirm_pass']));


    if(!empty($update_pass) ||!empty($new_pass) ||!empty($confirm_pass)){
        if($update_pass !=$old_pass){
            $message[]= 'Old Pasword Not Matched';
        }elseif($new_pass != $confirm_pass){
            $message[]= 'Confirm Pasword Not Matched';
        }else{
            mysqli_query($conn,"UPDATE `user_details` SET password='$confirm_pass' WHERE id='$user_id'")
                or die('query failed');
            $message[]='Password Updated Successfully';
        }
    }
    $update_image = $_FILES['update_image']['name'];
    $update_image_size = $_FILES['update_image']['size'];
    $update_image_tmp_name = $_FILES['update_image']['tmp_name'];
    $update_image_folder = 'upload_img/' . $update_image;

    if(!empty($update_image)){
        if($$update_image_size > 2000000){
            $message[]='image is too  large';
        }else{
             $image_update_query=mysqli_query($conn,"UPDATE `user_details` SET image =
             '$update_image' WHERE id='$user_id'") or die('query failed');
            if($image_update_query){
                move_uploaded_file($update_image_tmp_name, $update_image_folder);            }
            
            $message[]='image updated successfully';

        }
        }

}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>update_profile</title>
    <link rel="stylesheet" href="update.css">
</head>
<body>
    <div class="update-profile">
    <?php
         $select = mysqli_query($conn, "SELECT * FROM `user_details` WHERE id = '$user_id'") or die('query failed');
         if(mysqli_num_rows($select) > 0){
            $fetch = mysqli_fetch_assoc($select);
         }

      ?>
      <form action="" method="post" enctype="multipart/form-data">
        <?php
        if(empty($fetch['image']) || !file_exists('uploaded_img/'.$fetch['image'])){
            echo '<img src="girl.png">';
         } else {
            echo '<img src="/uploaded_img/'.$fetch['image'].'">';
         }
            if (isset($message)) {
                foreach ($message as $message) {
                    echo '<div class="message">' . $message . '</div>';
                }
            }
        ?>
        <div class="flexing">
            <div class="inputBox">
                <span>Username : </span>
                <input type="text" name="update_name" value="<?php echo $fetch['name'] ?>"
                class="box">
                <span>Your email : </span>
                <input type="email" name="update_email" value="<?php echo $fetch['email'] ?>"
                class="box">
                <span>Update Your Pic : </span>
                <input type="file" name="update_image" accept="image/jpg,image/jpeg,image/png" 
                class="box">
            </div>
            <div class="inputBox">
                <input type="hidden" name="old_pass" value="<?php echo $fetch['password']?>">
                <span>old password :</span>
                <input type="password" name="update_pass" placeholder="enter previous password"
                class="box">
                <span>new password :</span>
                <input type="password" name="new_pass" placeholder="enter new password"
                class="box">
                <span>confirm password :</span>
                <input type="password" name="confirm_pass" placeholder="confirm new  password"
                class="box">
            </div>
        </div>
            <input type="submit" value="update profile" name="update_profile" class="btn">
            <a href="home.php" class="delete-btn">Go Back</a>


      </form>


    </div>
</body>
</html>