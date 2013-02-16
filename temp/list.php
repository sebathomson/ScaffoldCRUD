<?php 
include('config.php'); 
echo "<table border=1 >"; 
echo "<tr>"; 
echo "<td><b>Id</b></td>"; 
echo "<td><b>Fn</b></td>"; 
echo "<td><b>Mn</b></td>"; 
echo "<td><b>Ln</b></td>"; 
echo "<td><b>Email</b></td>"; 
echo "<td><b>Pass</b></td>"; 
echo "<td><b>Display Name</b></td>"; 
echo "<td><b>Created On</b></td>"; 
echo "</tr>"; 
$result = mysql_query("SELECT * FROM `users`") or trigger_error(mysql_error()); 
while($row = mysql_fetch_array($result)){ 
foreach($row AS $key => $value) { $row[$key] = stripslashes($value); } 
echo "<tr>";  
echo "<td valign='top'>" . nl2br( $row['id']) . "</td>";  
echo "<td valign='top'>" . nl2br( $row['fn']) . "</td>";  
echo "<td valign='top'>" . nl2br( $row['mn']) . "</td>";  
echo "<td valign='top'>" . nl2br( $row['ln']) . "</td>";  
echo "<td valign='top'>" . nl2br( $row['email']) . "</td>";  
echo "<td valign='top'>" . nl2br( $row['pass']) . "</td>";  
echo "<td valign='top'>" . nl2br( $row['display_name']) . "</td>";  
echo "<td valign='top'>" . nl2br( $row['created_on']) . "</td>";  
echo "<td valign='top'><a href=edit.php?id={$row['id']}>Edit</a></td><td><a href=del.php?id={$row['id']}>Delete</a></td> "; 
echo "</tr>"; 
} 
echo "</table>"; 
echo "<a href=new.php>New Row</a>"; 
?>