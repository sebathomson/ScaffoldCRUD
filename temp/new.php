<?php 
include('config.php'); 
if (isset($_POST['submitted'])) { 
foreach($_POST AS $key => $value) { $_POST[$key] = mysql_real_escape_string($value); } 
$sql = "INSERT INTO `users` ( `fn` ,  `mn` ,  `ln` ,  `email` ,  `pass` ,  `display_name` ,  `created_on`  ) VALUES(  '{$_POST['fn']}' ,  '{$_POST['mn']}' ,  '{$_POST['ln']}' ,  '{$_POST['email']}' ,  '{$_POST['pass']}' ,  '{$_POST['display_name']}' ,  '{$_POST['created_on']}'  ) "; 
mysql_query($sql) or die(mysql_error()); 
echo "Added row.<br />"; 
echo "<a href='list.php'>Back To Listing</a>"; 
} 
?>

<form action='' method='POST'> 
<p><b>Fn:</b><br /><input type='text' name='fn'/> 
<p><b>Mn:</b><br /><input type='text' name='mn'/> 
<p><b>Ln:</b><br /><input type='text' name='ln'/> 
<p><b>Email:</b><br /><input type='text' name='email'/> 
<p><b>Pass:</b><br /><input type='text' name='pass'/> 
<p><b>Display Name:</b><br /><input type='text' name='display_name'/> 
<p><b>Created On:</b><br /><input type='text' name='created_on'/> 
<p><input type='submit' value='Add Row' /><input type='hidden' value='1' name='submitted' /> 
</form> 
