<head>  
</head>  
<body>  
<?php  
    session_start();  
    unset($_SESSION["studentID"]);
    session_destroy();  
    echo "<script>;window.location.href='index.html';</script>";
?>  
</body>  
</html> 