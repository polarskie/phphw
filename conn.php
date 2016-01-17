<?php
/*****************************
*���ݿ�����
*****************************/
$conn = @mysql_connect("localhost","root","12345");
if (!$conn){
	die("Fail".mysql_error());
}
mysql_select_db("paperAdministrater", $conn);
//�ַ�ת��������
mysql_query("set character set 'gbk'");
//д��
mysql_query("set names 'gbk'");
?>