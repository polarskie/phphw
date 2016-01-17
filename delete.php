<?php
session_start();
if(!isset($_SESSION['userid'])){
	echo "<script>alert('Please login first!');window.location.href='index.html';</script>";
    //header("Location:index.html");
    exit();
}

//echo $_SESSION['userid'];
$number=$_SESSION['userid'];
//echo $number;
include('conn.php');
//更新表中valid=0
mysql_query("UPDATE users SET valid = 0
WHERE studentID='{$number}' and valid = 1");
//弹出窗口
unset($_SESSION["userid"]);  
session_destroy();  
echo "<script>alert('Delete account successfully!');window.location.href='index.html';</script>";


?>
