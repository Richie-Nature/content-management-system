<?php require("includes/connectdb.php")?>
<?php require_once("includes/functions.php"); ?>
<?php include("includes/header.php"); ?>
<?php
    find_selected_page();
?>

<table id = "structure">
            <tr>
                <td id = "navigation">
                    <?php echo navigation($sel_subject, $sel_page) ?>
                    <br>
                    <a href="new_subject.php">+ Add a new subject</a>   
                </td>
                <td id = "page">
                <?php #$sel_subject = get_subject_by_id($sel_subj,$connection);?>
                <?php #$on_page = get_pages_by_id($sel_page,$connection);?>
                <?php if(!is_null($sel_subject)) {//subject selected?>
                    <h2><?php echo $sel_subject['menu_name']; ?></h2>
                    <?php } elseif (!is_null($sel_page)) { //page selected ?>
                    <h2><?php echo $sel_page['menu_name'];?></h2>
                    <div class="page_content">
                    <p><?php echo $sel_page['content']; ?></p><br>
                    <a href="edit_page.php?page=<?php echo urlencode($sel_page['id']);?>">Edit Page</a>
                    </div>
                    <?php } else { //nothing selected ?>
                    <h2>Select a subject or page to edit</h2>
                    <?php } ?>
                </td>
            </tr>
        </table> 
 <?php require("includes/footer.php");?> 
