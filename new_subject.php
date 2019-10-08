<?php require("includes/connectdb.php")?>
<?php require_once("includes/functions.php"); ?>
<?php confirm_logged_in();?> 
<?php include("includes/header.php"); ?>
<?php
   find_selected_page();
    
?>

<table id = "structure">
            <tr>
                <td id = "navigation">
                    <?php echo navigation($sel_subject, $sel_page) ?>  
                </td>
                <td id = "page">
                    <h2>Add Subject</h2>
                    <?php create_edit_form("create_subject.php"); ?>
                    <?php
                        #subjectcount + 1 because we are adding a subject
                        for($count=1; $count <= $subject_count+1; $count++) {
                        echo "<option value=\"{$count}\">{$count}</option>";
                            }?>
                            </select>
                        </p>
                        <p>Visible:
                            <input type="radio" name="visible" value="0">No
                            &nbsp;
                            <input type="radio" name="visible" value="1">Yes
                        </p>
                            <input type="submit" value="Add Subject">
                    </form>
                    <br>
                    <a href="content.php">Cancel</a>
                </td>
            </tr>
        </table> 
 <?php require("includes/footer.php");?> 
