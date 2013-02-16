<?php 
include('config.php'); 
if (isset($_GET['id']) ) { 
$id = (int) $_GET['id']; 
if (isset($_POST['submitted'])) { 
foreach($_POST AS $key => $value) { $_POST[$key] = mysql_real_escape_string($value); } 
$sql = "UPDATE `users` SET  `fn` =  '{$_POST['fn']}' ,  `mn` =  '{$_POST['mn']}' ,  `ln` =  '{$_POST['ln']}' ,  `email` =  '{$_POST['email']}' ,  `pass` =  '{$_POST['pass']}' ,  `display_name` =  '{$_POST['display_name']}' ,  `created_on` =  '{$_POST['created_on']}'   WHERE `id` = '$id' "; 
mysql_query($sql) or die(mysql_error()); 
echo (mysql_affected_rows()) ? "Edited row.<br />" : "Nothing changed. <br />"; 
echo "<a href='list.php'>Back To Listing</a>"; 
} 
$row = mysql_fetch_array ( mysql_query("SELECT * FROM `users` WHERE `id` = '$id' ")); 
?>

<form action='' method='POST'> 
<p><b>Fn:</b><br /><input type='text' name='fn' value='<?php= stripslashes($row['fn']) ?>' /> 
<p><b>Mn:</b><br /><input type='text' name='mn' value='<?php= stripslashes($row['mn']) ?>' /> 
<p><b>Ln:</b><br /><input type='text' name='ln' value='<?php= stripslashes($row['ln']) ?>' /> 
<p><b>Email:</b><br /><input type='text' name='email' value='<?php= stripslashes($row['email']) ?>' /> 
<p><b>Pass:</b><br /><input type='text' name='pass' value='<?php= stripslashes($row['pass']) ?>' /> 
<p><b>Display Name:</b><br /><input type='text' name='display_name' value='<?php= stripslashes($row['display_name']) ?>' /> 
<p><b>Created On:</b><br /><input type='text' name='created_on' value='<?php= stripslashes($row['created_on']) ?>' /> 
<p><input type='submit' value='Edit Row' /><input type='hidden' value='1' name='submitted' /> 
</form> 
<?php } ?> 
