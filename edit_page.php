<?php session_start(); ?>
<?php require("includes/connectdb.php")?>
<?php require_once("includes/functions.php"); ?>
<?php confirm_logged_in();?> 
<?php include("includes/header.php");?>
<?php find_selected_page();?>
<?php check_sent_id($_GET['page']);
      include_once("includes/form_functions.php");

      #start form processing
      #only execute the form processing if the form has been submitted
      if(isset($_POST['submit'])) {
          $errors = array(); #array to hld errors

          $required_fields = array('menu_name', 'position', 'content');
          $errors = array_merge($errors, check_required_fields($required_fields));

          $fields_with_lengths = array('menu_name' => 30);
          $errors = array_merge($errors, check_max_field_lengths($fields_with_lengths));

          $id = mysql_prep($_GET['page']);
          $menu_name = trim(mysql_prep($_POST['menu_name']));
          $position = mysql_prep($_POST['position']);
          $visible = mysql_prep($_POST['visible']);
          $content = addslashes($_POST['content']);

          if(empty($errors)) {
              $query = "UPDATE pages SET
                            menu_name = '$menu_name',
                            position = $position,
                            visible = $visible,
                            content = '$content'
                        WHERE id = $id";
             $result = $connection->query($query);

             if(mysqli_affected_rows($connection) == 1) {
                 $message = "The page was successfully updated";
             } else {
                 $message = "The page could not be updated";
                 $message .= "<br>" . mysqli_error($connection);
             }
          }else{
              if(count($errors) == 1) {
                  $message = "There was 1 error in the form";
              }else{
                  $message = "There were " . count($errors) . " errors in the form";
              }
          }
      }
      ?>

<table id = "structure">
            <tr>
                <td id = "navigation">
                    <?php echo navigation($sel_subject, $sel_page) ?>  <br><br>
                    <a href="new_subject.php">+ Add a new subject</a>  
                </td>
                <td id = "page">
                    <h2>Edit Page: <?php echo $sel_page['menu_name'];?></h2>
                    <?php if(!empty($message)) {echo "<p class=\"message\">" .$message . "</p>";} ?>
                    <?php 
                    #output a list of the fields that had errors
                    if(!empty($errors)) { display_errors($errors);} 
                    ?>
                   
                    <form action="edit_page.php?page=<?php echo urlencode($sel_page['id'])?>" method="post">
                    <?php include("includes/page_form.php") ?>
                            <input type="submit" value="Update Page" name = "submit">
                            &nbsp;&nbsp;
                            <a href="delete_subject.php?page=
                            <?php echo urlencode($sel_page['id']);?>" onclick="return confirm('Delete this Page?'); " >Delete Page</a>
                    </form>
                    <br>
                    <a href="content.php?page=<?php echo $sel_page['id'];?>">Cancel</a><br>
                </td>
            </tr>
        </table> 
        <?php include("includes/footer.php");?>