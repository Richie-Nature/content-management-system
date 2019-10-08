<?php session_start(); ?>
<?php require("includes/connectdb.php")?>
<?php require_once("includes/functions.php"); ?>
<?php confirm_logged_in();?> 
<?php check_sent_id($_GET['subj']);
      include_once("includes/form_functions.php");

      #start form processing
      #only execute the form processing if the form has been submitted
      if(isset($_POST['submit'])) {
          $errors = array(); #array to hld errors

          $required_fields = array('menu_name', 'position', 'content');
          $errors = array_merge($errors, check_required_fields($required_fields));

          $fields_with_lengths = array('menu_name' => 30);
          $errors = array_merge($errors, check_max_field_lengths($fields_with_lengths));

          $id = mysql_prep($_GET['subj']);
          $menu_name = trim(mysql_prep($_POST['menu_name']));
          $position = mysql_prep($_POST['position']);
          $visible = mysql_prep($_POST['visible']);
          $content = mysql_prep($_POST['content']);

          if(empty($errors)) {
              $query = "INSERT INTO pages (subject_id,menu_name,position,visible,content) VALUES ($id,'$menu_name',$position,$visible,'$content')";
             $result = $connection->query($query);

             if(mysqli_affected_rows($connection) == 1) {
                 $message = "The page was successfully added";
             } else {
                 $message = "The page could not be added";
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
<?php include("includes/header.php");
   find_selected_page();?>

<table id = "structure">
            <tr>
                <td id = "navigation">
                    <?php echo navigation($sel_subject, $sel_page); ?>  
                    <br>
                    <a href="new_subject.php">+ Add a new subject</a>                
                </td>
                <td id = "page">
                    <h2>Adding New Page</h2>
                    <?php if(!empty($message)) {echo "<p class=\"message\">" . $message . "</p>";} ?>
                    <?php if(!empty($errors)) {display_errors($errors);}?>


                   <form action="new_page.php?subj=<?php echo $sel_subject['id'];?>" method="post">
                    <?php $new_page = true; ?>
                    <?php include("includes/page_form.php"); ?>
                    <input type="hidden" name="id" value = "<?php echo $sel_page['id']; ?>">
                    <input type="submit" value="Create Page" name = "submit">
                    </form>
                    <br>
                    <a href="edit_subject.php?subj=<?php echo $sel_subject['id'];?>">Cancel</a>
                </td>
            </tr>
        </table> 
 <?php require("includes/footer.php");?> 
