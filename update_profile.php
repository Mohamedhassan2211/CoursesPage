<?php

include 'config.php';
session_start();
$user_id = $_SESSION['user_id'];

if(isset($_POST['update_profile'])){
    $update_name = mysqli_real_escape_string($conn, $_POST['update_name']);
    $update_email = mysqli_real_escape_string($conn, $_POST['update_email']);

    mysqli_query($conn, "UPDATE user_form SET name = '$update_name' , email='$update_email' WHERE id='$user_id'")or die('query fialed');

    $old_pass = $_POST['old_pass'];
    $update_pass = mysqli_real_escape_string($conn, md5($_POST['update_pass']));
    $new_pass = mysqli_real_escape_string($conn, md5($_POST['new_pass']));
    $confirm_pass = mysqli_real_escape_string($conn, md5($_POST['confirm_pass']));

    if(!empty($update_pass) || !empty($new_pass) || !empty($confirm_pass)){
    if($update_pass != $old_pass){
            $message[] = 'old password not correct';
        }elseif($new_pass != $confirm_pass){
            $message[] = 'confirm password not matched!';
        }else{
            mysqli_query($conn, "UPDATE user_form SET password = '$confirm_pass' WHERE id ='$user_id'")or die('query fialed');
            $message[] = 'password updated successfully!';
        }
    }

    $update_image=$_FILES['update_image']['name'];
    $update_image_size=$_FILES['update_image']['size'];
    $update_image_tmp_name=$_FILES['update_image']['tmp_name'];
    $update_image_folder='uploaded_img/'.$update_image;
    
    if(!empty($update_image)){
        if($update_image_size >2000000){
            $message[] = 'image is to large';
        }else{
            $image_update_query = mysqli_query($conn, "UPDATE user_form SET image = '$update_image' WHERE id = '$user_id'")or die('query fialed');
            if($image_update_query){
                move_uploaded_file($update_image_tmp_name, $update_image_folder);
            }

            $message[] = 'image updated successfully';

        }
    }
}


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="CSS1/style5.css">
    <link rel="icon" href="Images1/code icon .png" type="image/x-icon">
    <title>Update profile</title>
</head>
<body>

        <div class="login-box">
        <br><br><br><br><br><br><br><br><br><br><br><br>
        <?php
		 $select =mysqli_query($conn, "SELECT * FROM user_form WHERE id = '$user_id'")or die('query fialed');
		 if(mysqli_num_rows($select) > 0){
			$fetch = mysqli_fetch_assoc($select);
		 }
		
		?>
        <form action="" method="post" enctype="multipart/form-data">
            <?php
                if($fetch['image'] ==''){
                 echo '<img src="Images0/default-avatar.png" width=400px>';
                }else{
                  echo '<img src="uploaded_img/'.$fetch['image'].'"width=150px ,height=150px>';
                }
                if(isset($message)){
                    foreach($message as $message){
                        echo '<div class="message">'.$message.'</div>';
                    }
                } 
            ?>
        <div class="input-box">
            <span>username :</span>
            <input type="text" name ="update_name" value="<?php echo $fetch['name'] ?>" class="input-field" placeholder="Full Name" autocomplete="off" required>
        </div>
        <div class="input-box">
            <span>your email :</span>
            <input type="email" name="update_email" value="<?php echo $fetch['email'] ?>" class="input-field"  placeholder="Email" autocomplete="off" required>
        </div>
       
        <div class="input-box">
        </div>
        <div class="input-box">
            <input type="hidden" name="old_pass" value="<?php echo $fetch['password'] ?>" class="input-field">
            <span>old password :</span>
            <input type="password" name="update_pass" class="input-field" placeholder="enter previous password" autocomplete="off">
        </div>
        <div class="input-box">
            <span>new password :</span>
            <input type="password" name="new_pass" class="input-field" placeholder="enter new password" autocomplete="off" minlength="4">
        </div>
        <div class="input-box">
            <span>confirm password :</span>
            <input type="password" name="confirm_pass" class="input-field" placeholder="confirm new password" autocomplete="off">
        </div>
        <div class="input-box">
            <span>update your pic :</span>
            <input type="file" name="update_image"  placeholder="picture" autocomplete="off" accept="image/jpg, image/jpeg, image/png">
            <br><br>
        </div>
        <div class="input-submit">
            <input type="submit" name="update_profile" value="update profile" class="submit-btn" id="submit">
            <label for="submit">update profile</label>
        </div>
        <div class="sign-up-link">
            <a href="home.php">go back</a> 
            <br><br>
        </div>

        </form>
    </div>
</body>
</html>