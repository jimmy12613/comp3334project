<?php
session_start();
?>

<?php
session_destroy(); 
header("refresh:0; url=index1.php");				
?>
