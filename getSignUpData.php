<?php
session_start();
if(isset($_SESSION['result']))
	$data=$_SESSION['result'];
else
	$data=null;
session_destroy();
echo $data;
?>