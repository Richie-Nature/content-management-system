<?php session_start();?>
<?php require("includes/connectdb.php")?>
<?php require_once("includes/functions.php"); ?>
<?php
    if(logged_in()) {
        redirect_to("staff.php");
    }
?>
<?php 
    include_once("includes/form_functions.php");
    if(isset($_POST['login'])) {
        $errors = array();

        $required_fields = array('username','password');
        $errors = array_merge($errors, check_required_fields($required_fields,$_POST));

        $fields_with_lengths = array('username' => 30, 'password' => 30);
        $errors = array_merge($errors, check_max_field_lengths($fields_with_lengths,$_POST));

        $username = trim(mysql_prep($_POST['username']));
        $password = trim(mysql_prep($_POST['password']));
        $hashed_password = sha1($password); //md5 message divert algorithm
        
        if(empty($errors)){
            $query = "SELECT id,username FROM users WHERE username = '$username' AND hashed_password = '$hashed_password'";
            $result = $connection->query($query);
            confirm_query($result, $connection);
            if(mysqli_num_rows($result) == 1){
                $found_user = mysqli_fetch_array($result);
                $_SESSION['user_id'] = $found_user['id'];
                $_SESSION['username'] = $found_user['username'];
                redirect_to("staff.php");
            }else{
                $message = "Invalid Credentials.<br>Make sure your caps lock is turned off and try again";
            }
        }else{
                if(count($errors) == 1) {
                    $message = "There was 1 error in this form";
                }else{
                    $message = "There were " . count($errors) . " errors found this form";
                }
        }
    }else{
        if(isset($_GET['logout']) && $_GET['logout'] == 1) {
            $message = "You are now logged out";
        }
            $username = "";
            $password = "";
    }
?>
<?php include("includes/header.php"); ?>
<table id = "structure">
    <tr>
        <td id = "navigation">
        <a href="index.php">Return to public site</a>
        <br>
        </td>
            <td id = "page">
            <h2>LogIn to Account</h2>
            <?php if(!empty($message)) {echo " <p class=\"message\">" . $message ."</p>";}?>
                <form action="login.php" method="post">
                    <?php include_once("includes/login_form.php");?>
                    <input type="submit" value="Login" name ="login">
                </form>
            </td>
    </tr>