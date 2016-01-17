<?php session_start();


if(!isset($_SESSION["studentID"]))
{
    header("location:index.html");
}
?>

<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" type="text/css" href="main.css" />
    <script type="text/javascript" src="jquery-1.12.0.min.js"></script>
    <title>Deleting</title>
</head>
<body>
<header>
    <h1>
        Paper Administrator System
    </h1>
</header>
<div class="menu">
    <div class="menu-button" DATA_func="mf">
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
    <div class="menu-button menu-button-active"  DATA_func="dl">
        Deleting
    </div>
    <div class="menu-button"  DATA_func="lg">
        Logout
    </div>
</div>
<div class="content">
    <form action="deletePaper.php" method="post" hidden>
        <input id="deleteID" type="text" name="deleteID" required>
    </form>
    <?php
    $con = mysqli_connect("localhost", "root", "12345", "paperAdministrater");
    if (mysqli_connect_errno()) {
        echo "Failed to connect to MySQL: " . mysqli_connect_error();
    }
        if ($_POST["deleteID"]) {
            $sql = "UPDATE papers SET valid = 0 where paperID = " . $_POST["deleteID"] . ";";
            if(!mysqli_query($con, $sql))
            {
                $deleted=false;
            }
            else
            {
                $deleted=true;
            }
        }
        $sql = "SELECT paperID, title FROM papers where submitor = " . $_SESSION["studentID"] . " AND valid = 1;";
        //echo $sql;
        if ($result = mysqli_query($con, $sql)) {
            // Fetch one and one row
            echo "<ul>";
            while ($row = mysqli_fetch_row($result)) {
                echo "<li class='clickItem' DATA_paperID='" . $row[0] . "'>" . $row[1] . "</li>";
            }
            // Free result set
            mysqli_free_result($result);
            echo "</ul>";
        } else {
            echo "failed";
        }

        mysqli_close($con);
    ?>
</div>
<script>
    $(".clickItem").click(function(e){
        //alert(e.target.getAttribute("DATA_paperID"));
        if(confirm("Are you sure to delete the paper:\n" + e.target.innerHTML))
        {
            $("#deleteID").val(e.target.getAttribute("DATA_paperID"));
            document.forms[0].submit();
        }
    });
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
    <?php
    if($_POST["deleteID"]){
        if(!$deleted)
        {
            echo "alert('failed in deleting the paper');";
        }
        else {
            echo "alert('succeeded in deleting the paper');";
        }
    }?>

</script>
</body>
</html>
