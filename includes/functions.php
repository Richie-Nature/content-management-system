<?php
 
 function logged_in() {
   return isset($_SESSION['user_id']);
 }
 function confirm_logged_in() {
  if(!logged_in()) {
    redirect_to("login.php");
  }
 }
function mysql_prep($value) {
  $magic_quotes_active = get_magic_quotes_gpc();
  $new_enough_php = function_exists("mysqli_real_escape_string");
  #the above-> i.e php >= v4.3.0 
    if($new_enough_php) {#if php v4.3.0 or higher 
      #undo any magic quote effects so mysqli_real_escape_string can
      #do the work
       if($magic_quotes_active) {
          $value = stripslashes($value);
          $value = mysqli_real_escape_string($value);
       }//end magic quotes if
    }//end new enough if
    else{#before php v4.3.0
      #if magic quotes arent already on then add slashes manually
      if(!$magic_quotes_active) {
         $value = addslashes($value);
        #if magic quotes are active, then the slashes already exist
      }#end not magic quotes
    }#end else
    return $value;
}
function redirect_to($location = NULL) {
  if($location != NULL)
  header("Location: {$location}");
  exit;
}
function confirm_query ($result_set,$connection) {
    if(!$result_set) {
      die("database query failed: ". mysqli_error($connection));
    } 
}

function get_all_subjects($public = true,$connection) {
  $query = "SELECT * FROM subjects ";
  if($public) { 
  $query .= "WHERE visible = 1 ";
  }
  $query .= "ORDER BY position ASC";
   $subject_set = $connection->query($query);
  confirm_query($subject_set,$connection);
  return $subject_set;
}

function get_pages_for_subject($subject_id,$connection,$public = true) {
  $query = "SELECT * FROM pages WHERE subject_id = {$subject_id} ";
  if($public) { 
  $query .= "AND visible = 1 ";
  }
  $query .= "ORDER BY position ASC";
  $page_set = $connection->query($query);
  confirm_query($page_set,$connection);
  return $page_set;
}
function get_subject_by_id($subject_id,$connection) {
  $query = "SELECT * FROM `subjects` WHERE id = '$subject_id' LIMIT 1";
  #$query .="WHERE id={$subject_id}";
  #$query .= " LIMIT 1";
  $result_set = $connection->query($query);
  confirm_query($result_set,$connection);
  #fetch array will return false if no rows are returned
  if ($subject = mysqli_fetch_assoc($result_set)) { 
      return $subject;
    } else {
      return NULL;
    }
}

function get_pages_by_id($page_id) {
  global $connection;
  $query = "SELECT * FROM `pages` WHERE id = '$page_id' LIMIT 1";
  $result = $connection->query($query);
  confirm_query($result,$connection);

  if($page = mysqli_fetch_assoc($result)) {
    return $page;
  }else{
    return NULL;
  }
}
function get_default_page($subject_id,$connection) {
  //get all visible pages
  $page_set = get_pages_for_subject($subject_id,$connection,true);
  if($first_page = mysqli_fetch_assoc($page_set)) {
    return $first_page;
  } else {
    return NULL;
  }
}
function find_selected_page() {
  global $sel_subject;
  global $sel_page;
  global $connection;
      if(isset($_GET['subj'])) {
        $sel_subject = get_subject_by_id($_GET['subj'],$connection);
        $sel_page = get_default_page($sel_subject['id'],$connection);
        
    }elseif(isset($_GET['page'])) {
        $sel_subject = NULL;
        $sel_page = get_pages_by_id($_GET['page'],$connection);
    }else {
        $sel_page = NULL;
        $sel_subject = NULL;
    }
}
function navigation($sel_subject, $sel_page,$public = false) {
  global $connection;
  $output = "<ul class=\"subjects\">";
        $subject_set = get_all_subjects($public,$connection);
        while ($subject = mysqli_fetch_assoc($subject_set)){
   
         $output .=  "<li class =";  
          if ($subject["id"] == $sel_subject['id']) {$output .= "selected";}
         $output .=  "><a href = \"edit_subject.php?subj=" . urlencode($subject["id"]) ."\" >
                {$subject['menu_name']}
            </a></li>";
    
        $page_set = get_pages_for_subject($subject['id'],$connection,$public);
        $output .= "<ul class = 'pages'>";
        while ($page = mysqli_fetch_assoc($page_set)){
          $output .=  "<li class =";  
          if ($page["id"] == $sel_page['id']) {$output .= "selected";}
         $output .=  "><a href = \"content.php?page=" . urlencode($page["id"]) ."\" >
                {$page['menu_name']}
            </a></li>";   
         }
        $output .= "</ul>";
        }
      $output .= "</ul>";
     return $output; #created an output buffer inorder to avoid echoing from a function as that leads errors
}
function check_sent_id($subj_id) {
  if(intval($subj_id == 0)){#intval returns the int in a value if its zero meaning not int
    redirect_to("content.php");
  }
}
function validate_form($field1, $field2) {
  $errors = array();
  $required_fields = array($field1, $field2);
  foreach($required_fields as $fieldname) {
      if(!isset($_POST[$fieldname]) || empty($_POST[$fieldname])) {
          $errors[] = $fieldname;
      }
  }

  $fields_with_lengths = array($field1 => 30);
  foreach($fields_with_lengths as $fieldname => $maxlength) {
      if(strlen(trim(mysql_prep($_POST[$fieldname]))) > $maxlength) {
          $errors[] = $fieldname; 
          #if sting length after been trimed of whitespace and added slashes is greater maxlength
      }
  }
  if(!empty($errors)) {
  redirect_to("new_subject.php");
      }
}
function create_edit_form($action, $value="fill",$title="Subject name") {?> 
  <?php 
  global $connection;
  global $subject_set;
  ?>
    <form action=<?php echo $action ?> method = "post">
        <p><?php echo $title?>:
        <input type="text" name="menu_name" id="menu_name" value = "<?php echo $value;?>">
        </p>
        <p>Position:
        <select name="position">
        <?php 
        global $subject_set,$subject_count,$page_count,$page_set;
        
          $subject_set = get_all_subjects($public = false,$connection);
          $subject_count = mysqli_num_rows($subject_set); 
  }

  function public_navigation($sel_subject, $sel_page, $public = true) {
    global $connection;
    $output = "<ul class=\"subjects\">";
          $subject_set = get_all_subjects($public, $connection);
          while ($subject = mysqli_fetch_assoc($subject_set)){
           $output .=  "<li class =";  
            if ($subject["id"] == $sel_subject['id']) {$output .= "selected";}
           $output .=  "><a href = \"index.php?subj=" . urlencode($subject["id"]) ."\" >
                  {$subject['menu_name']}
              </a></li>";
          
          if ($subject["id"] == $sel_subject['id']) {
            $page_set = get_pages_for_subject($subject['id'],$connection, $public);
            $output .= "<ul class = 'pages'>";
            while ($page = mysqli_fetch_assoc($page_set)){
              $output .=  "<li class =";  
              if ($page["id"] == $sel_page['id']) {$output .= "selected";}
            $output .=  "><a href = \"index.php?page=" . urlencode($page["id"]) ."\" >
                    {$page['menu_name']}
                </a></li>";   
            }
            $output .= "</ul>";
            }
          }
        $output .= "</ul>";
       return $output; #created an output buffer inorder to avoid echoing from a function as that leads errors
  }