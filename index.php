<?php require("includes/connectdb.php")?>
<?php require_once("includes/functions.php"); ?>
<?php
    find_selected_page();
?>
<?php include("includes/header.php"); ?>
<table id = "structure">
    <tr>
        <td id = "navigation">
            <?php echo public_navigation($sel_subject, $sel_page) ?>
            <br>
            <br>
            <a href="login.php">Login</a>
            </td>
                <td id = "page">
                <?php #$sel_subject = get_subject_by_id($sel_subj,$connection);?>
                <?php #$on_page = get_pages_by_id($sel_page,$connection);?>
                <?php if($sel_page) {?>
                    <h2><?php echo htmlentities($sel_page['menu_name']); ?></h2>
                    <div class="page_content">
                    <p><?php echo strip_tags(nl2br($sel_page['content']), "<b><br><p><a>"); ?></p><br>
                    </div>
                    <?php } else { //nothing selected ?>
                    <h2>Welcome to Widget Corp</h2>
                    <?php } ?>
                </td>
            </tr>
        </table> 
 <?php require("includes/footer.php");?> 
