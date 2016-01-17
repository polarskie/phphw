<?php
session_start();

if(!isset($_SESSION["studentID"]))
{
  header("location:index.html");
}
?>
<?php
if(isset($_POST['vd'])){
    if($_POST['vd']==0){
        $mysqli = mysql_connect("localhost","root","12345");
        if (!$mysqli) {
            die('Could not connect: ' . mysql_error());
            exit();
        } else {
            mysql_select_db("paperAdministrater",$mysqli);
            $name=$_POST['sID'];
            $papernum=$_POST['pID'];
            $val=$_POST['vd'];

            $res=mysql_query("UPDATE bookmarks SET valid = '0'
	WHERE studentID = '{$name}' AND paperID = '{$papernum}'");
            if ($res == TRUE) {
                echo "A record has been delete.";

            } else {
                printf("Could not delete record: %s\n", mysql_error($mysqli));
            }

            $result = mysql_query("SELECT * FROM papers WHERE paperID= '{$papernum}'");

            while($row = mysql_fetch_array($result))
            {
                $tmp=$row['bookmarknum']-1;
                mysql_query("UPDATE papers SET bookmarknum = '{$tmp}'
		WHERE paperID = '{$papernum}'");
            }


            mysql_close($mysqli);
        }
    }
    if($_POST['vd']==1){
        $mysqli = mysql_connect("localhost","root","12345");
        if (!$mysqli) {
            die('Could not connect: ' . mysql_error());
            exit();
        } else {
            mysql_select_db("paperAdministrater",$mysqli);
            $name=$_POST['sID'];
            $papernum=$_POST['pID'];
            $val=$_POST['vd'];
            $res=mysql_query("SELECT * FROM bookmarks
	WHERE studentID = '{$name}' AND paperID = '{$papernum}' AND valid='{$val}'");
            if(!mysql_fetch_array($res))
            {
                $sql ="INSERT INTO bookmarks (studentID,paperID,valid) VALUES ('{$name}','{$papernum}','{$val}')";
                $res = mysql_query($sql,$mysqli);

                $result = mysql_query("SELECT * FROM papers WHERE paperID= '{$papernum}'");

                while($row = mysql_fetch_array($result))
                {
                    $tmp=$row['bookmarknum']+1;
                    mysql_query("UPDATE papers SET bookmarknum = '{$tmp}'
			WHERE paperID = '{$papernum}'");
                }
            }
            else
                echo "record has existed";
            if ($res === TRUE) {
                //echo "A record has been inserted.";

            } else {
                printf("Could not insert record: %s\n", mysql_error($mysqli));
            }


            mysql_close($mysqli);
        }
    }
}
else
{

}
?>
<html>
<head>
    <link rel="stylesheet" type="text/css" href="main.css" />
    <script type="text/javascript" src="jquery-1.12.0.min.js"></script>
    <title>My Favorites</title>
</head>
<body>
<header>
    <h1>
        Paper Administrator System
    </h1>
</header>
<div class="menu">
    <div class="menu-button menu-button-active" DATA_func="mf">
        My Favorites
    </div>
    <div class="menu-button"  DATA_func="sr">
        Search
    </div>
    <div class="menu-button"  DATA_func="ts">
        Time Sort
    </div>
    <div class="menu-button"  DATA_func="hs">
        Heat Sort
    </div>
    <div class="menu-button"  DATA_func="sb">
        Submitting
    </div>
    <div class="menu-button"  DATA_func="dl">
        Deleting
    </div>
    <div class="menu-button"  DATA_func="lg">
        Logout
    </div>
</div>
<div class="content">
    <h3>Bookmark List:</h3>
    <ul>
        <?php
        $con = mysql_connect("localhost","root","12345");
        if (!$con)
        {
            die('Could not connect: ' . mysql_error());
        }

        mysql_select_db("paperAdministrater", $con);

        $tmp1 = $_SESSION["studentID"];

        /*
        $result = mysql_query("SELECT * FROM papers_v1
           WHERE studentID = '$tmp1'");
        */

        $result = mysql_query("SELECT * FROM bookmarks
   WHERE studentID = '$tmp1' and valid = 1");

        //$result = mysql_query("SELECT * FROM bookmark_v1");

        while($row = mysql_fetch_array($result))
        {
            $tmp2 = $row['paperID'];
            $result2 = mysql_query("SELECT * FROM papers
      WHERE paperID = '$tmp2'");
            while($row2 = mysql_fetch_array($result2))
            {
                //echo $row2['title'];
                //echo "<br />";
                if($row2['valid']==1)
                {
                    $tmp3=$row2['title'];
                    echo "<li>"."<a href='upload/".$row["paperID"].".pdf'>".$tmp3."</a>"."</li>";

                    echo
                        "<form action=".$_SERVER['PHP_SELF']." method=\"post\" name=\"addbookmark\">
    <input type=\"hidden\" name=\"sID\" value= ".$_SESSION["studentID"]."/>
    <input type=\"hidden\" name=\"pID\" value= ".$row["paperID"]."/>
    <input type=\"hidden\" name=\"vd\" value='0'/>
    <input type=\"submit\" value= 'dislike'/>
</form>";

                }
            }
        }

        ?>

    </ul>
</div>
<script>
    $(".menu-button").click(function(e){
        switch(e.target.getAttribute("DATA_func"))
        {
            case "mf":
                location.assign("bookmark.php");
                break;
            case "sr":
                location.assign("search.php");
                break;
            case "sb":
                location.assign("addPaper.php");
                break;
            case "dl":
                location.assign("deletePaper.php");
                break;
            case "lg":
                location.assign("logout.php");
                break;
            case "hs":
                location.assign("sort_by_bookmarknum.php");
                break;
            case "ts":
                location.assign("sort_by_time.php");
                break;
        }
    });
</script>
</body>
</html>

