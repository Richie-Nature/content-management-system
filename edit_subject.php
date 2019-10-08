<?php session_start(); ?>
<?php require("includes/connectdb.php")?>
<?php require_once("includes/functions.php"); ?>
<?php confirm_logged_in();?> 
<?php 
//first check if subject id is an integer
if(isset($_POST['submit'])) { 
    check_sent_id($_GET['subj']);
    validate_form('menu_name', 'position');

    if(empty($errors)) { 
        //perform update
            $id = mysql_prep($_GET['subj']);
            $menu_name = mysql_prep($_POST['menu_name']);
            $position = mysql_prep($_POST['position']);
            $visible = mysql_prep($_POST['visible']);

            $query = "UPDATE subjects SET menu_name = '{$menu_name}',position = '{$position}',
                                        visible = {$visible} WHERE id = {$id}";
            $result = $connection->query($query);
            if(mysqli_affected_rows($connection) == 1) {
                //success
                $message = "The subject was successfully updated.";
            }else{
                //failed
                $message = "The subject update failed.";
                $message .= "<br>" . mysqli_error($connection);
            }
    }else{
        //errors occured
        $message = "There were " . count($errors) . " errors in the form.";
    }
}//end if isset post
?>
<?php include("includes/header.php");?>
<?php find_selected_page();  ?>
<table id = "structure">
            <tr>
                <td id = "navigation">
                    <?php echo navigation($sel_subject, $sel_page) ?>  <br><br>
                    <a href="new_subject.php">+ Add a new subject</a>  
                </td>
                <td id = "page">
                    <h2>Edit Subject: <?php echo $sel_subject['menu_name'];?></h2>
                    <?php if(!empty($message)) {echo "<p class=\"message\">" .$message . "</p>";} ?>
                    <?php 
                    #output a list of the fields that had errors
                    if(!empty($errors)){
                        echo "<p class=\"error\">";
                        echo "Please review the following fields:<br>";
                        foreach($errors as $error) {
                            echo " - " . $error . "<br>";
                        }
                        echo "</p>";
                    }
                    ?>
                    <?php create_edit_form("edit_subject.php?subj=".urlencode($sel_subject['id']),$sel_subject['menu_name']) ?>
                    <?php
                        for($count=1; $count <= $subject_count+1; $count++) {
                        echo "<option value=\"{$count}\"";
                        if($sel_subject['position'] == $count) {
                            echo " selected";
                        }
                        echo ">{$count}</option>";
                    }
                    ?>
                            </select>
                        </p>
                        <p>Visible:
                            <input type="radio" name="visible" value="0" 
                            <?php if($sel_subject['visible'] == 0){echo " checked";}?>>No
                            &nbsp;
                            <input type="radio" name="visible" value="1"
                            <?php if($sel_subject['visible'] == 1){echo " checked";}?>>Yes
                        </p>
                            <input type="submit" value="Edit Subject" name = "submit">
                            &nbsp;&nbsp;
                            <a href="delete_subject.php?subj=
                            <?php echo urlencode($sel_subject['id']);?>" onclick="return confirm('Delete this Subject?'); " >Delete Subject</a>
                    </form>
                    <br>
                    <a href="content.php">Cancel</a>

                    <div style="margin-top: 2em; border-top: 1px solid #000000;">
                        <h3>Pages in this subject:</h3>
                        <ul>
                        <?php
                            $subject_pages = get_pages_for_subject($sel_subject['id'],$connection);
                            while($page = mysqli_fetch_assoc($subject_pages)){?>
                            <li><a href="content.php?page=<?php echo $page['id']?>"><?php echo $page['menu_name']?></a></li><?php
                            }
                            ?>
                        </ul>
                        <br>
                        + <a href="new_page.php?subj=<?php echo $sel_subject['id'];?>">
                        Add a new page to this subject</a>
                    </div>
                </td>
            </tr>
        </table> 
 <?php require("includes/footer.php");?> 
