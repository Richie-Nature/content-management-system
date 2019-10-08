<?php require("includes/connectdb.php")?>
<?php require_once("includes/functions.php"); ?>
<?php 
    validate_form('menu_name', 'position');
?>
<?php 
    $menu_name = mysql_prep($_POST['menu_name']);
    $position = mysql_prep($_POST['position']);
    $visible = mysql_prep($_POST['visible']);
?>
<?php
    $query = "INSERT INTO subjects (menu_name, position, visible)
               VALUES ('$menu_name',$position,$visible)";

    $result = $connection->query($query);
    if($result) {
        //success
        redirect_to("content.php");
        exit;
    }else{
        //errror
        echo "<p>Subject creation failed.</p>";
        echo "<p>" . mysqli_error() . "</p>";
    }
?>

<?php mysqli_close($connection);?>