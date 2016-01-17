<head>  
</head>  
<body>  

<?php
$con = mysql_connect("localhost","root","12345");
if (!$con)
  {
  die('Could not connect: ' . mysql_error());
  }

// Create database
//if (mysql_query("CREATE DATABASE my_db",$con))
 // {
//  echo "Database created";
//  }
//else
//  {
//  echo "Error creating database: " . mysql_error();
//  }

// Create table in my_db database
mysql_select_db("paperAdministrater", $con);
$sql = "INSERT INTO users (name, studentID, password, valid)
			VALUES ('$_POST[name]', '$_POST[studentID]', '$_POST[password]', 1)";
mysql_query($sql,$con);

mysql_close($con);

 echo "<script>alert('Registered successfully!');window.location.href='index.html';</script>";
?>
</body>  
</html> 