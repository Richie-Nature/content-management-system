<?php require("includes/connectdb.php")?>
<?php require_once("includes/functions.php"); ?>
<?php confirm_logged_in();?> 
<?php 
    include_once("includes/form_functions.php");
    if(isset($_POST['submit'])) {
        $errors = array();

        $required_fields = array('username','password');
        $errors = array_merge($errors, check_required_fields($required_fields,$_POST));

        $fields_with_lengths = array('username' => 30, 'password' => 30);
        $errors = array_merge($errors, check_max_field_lengths($fields_with_lengths,$_POST));

        $username = trim(mysql_prep($_POST['username']));
        $password = trim(mysql_prep($_POST['password']));
        $hashed_password = sha1($password); //md5 message divert algorithm

        if(empty($errors)) {
            $query = "INSERT INTO users (username,hashed_password) VALUES ('$username', '$hashed_password')";
            $result = $connection->query($query);
            if($result) {
                $message = "User successfully created";
            }else{
                $message = "User could not be created";
                $message .= "<br>" . mysqli_error($connection);
            }
        }else{
            if(count($errors) == 1) {
                $message = "There was 1 error in the form";
            }else{
                $message = "There were " . count($errors) . " errors in the form.";
            }
        }
    }else{
        $username = "";
        $password = "";
    }
    ?>
<?php include("includes/header.php"); ?>
<table id = "structure">
    <tr>
        <td id = "navigation">
        <a href="staff.php">Return to Menu</a>
        <br>
        </td>
            <td id = "page">
            <h2>Create New User</h2>
            <?php if(!empty($message)) {echo " <p class=\"message\">" . $message ."</p>";}?>
                <form action="new_user.php" method="post">
                    <?php include_once("includes/login_form.php");?>
                    <input type="submit" value="Create User" name ="submit ">
                </form>
            </td>
    </tr>