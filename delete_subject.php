<?php session_start(); ?>
<?php require("includes/connectdb.php")?>
<?php require_once("includes/functions.php"); ?>
<?php confirm_logged_in();?> 
<?php 
if(isset($_GET['subj'])) { 
    check_sent_id($_GET['subj']);
    $id = mysql_prep($_GET['subj']);

    if($subject = get_subject_by_id($id,$connection)) { 
        $query = "DELETE FROM subjects WHERE id = {$id} LIMIT 1";
        $result = $connection->query($query);
        if(mysqli_affected_rows($connection) == 1 ) {
            redirect_to("content.php");
        }else {
            echo "<p> Subject deletion failed.</p>";
            echo "<p>" . mysqli_error($connection) . "</p>";
            echo "<a href=\"content.php\">Return to Main Page</a>";
        }
    }else{
        redirect_to("content.php");
    }
}
elseif(isset($_GET['page'])) {
    check_sent_id($_GET['page']);
    $id = mysql_prep($_GET['page']);

        $query = "DELETE FROM pages WHERE id = {$id} LIMIT 1";
        $result = $connection->query($query);
        if(mysqli_affected_rows($connection) == 1 ) {
            redirect_to("content.php");
        }else {
            echo "<p> Page deletion failed.</p>";
            echo "<p>" . mysqli_error($connection) . "</p>";
            echo "<a href=\"content.php\">Return to Main Page</a>";
        }
    }else{
        redirect_to("content.php");
    }

        ?>

        <?php mysqli_close($connection);?>