<?php
session_start();

//ע����¼
//if($_GET['action'] == "logout"){
//	unset($_SESSION['userid']);
//	echo 'Logout successfully! Click here to <a href="index.html">Log in</a>';
//	exit;
//}

//��¼
if(!isset($_POST['submit'])){
	exit('not');
}
$userid = htmlspecialchars($_POST['studentID']);

$password = htmlspecialchars($_POST['password']);
//echo $userid;
//echo $password;

//�������ݿ������ļ�
include('conn.php');
//����û����������Ƿ���ȷ
$check_query = mysql_query("select * from users where studentID='{$userid}' and password='{$password}' and valid = 1");
$result = mysql_fetch_array($check_query);
$username = htmlspecialchars($result['name']);
//while($result = mysql_fetch_array($check_query)){
//	echo $result['valid'];
//}

if($result){
	//��¼�ɹ�
	$_SESSION['studentID'] = $userid;
	echo "<script>window.location.href='bookmark.php';</script>";
	//echo $username,', Welcome to  <a href="my.php">User Center</a><br />';
	//echo 'Click here to <a href="logout.php">Log Out</a><br />';
	exit;
} else {
	if($userid != $result['studentID'])
	{echo "<script>alert('Wrong studentID');window.location.href='index.html';</script>";}
else if($password != $result['password'])
	{echo "<script>alert('Wrong password');window.location.href='index.html';</script>";}

}
?>