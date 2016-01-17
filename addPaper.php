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
    <title>Submitting</title>
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
    <div class="menu-button menu-button-active"  DATA_func="sb">
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
    <?php
    $submittedSuccessfully = false;
    $sameNamePaperExists = false;
    if($_POST["title"] || isset($_GET["stillSubmit"])) {
        $con = mysqli_connect("localhost","root","12345","paperAdministrater");
        if (mysqli_connect_errno())
        {
            echo "Failed to connect to MySQL: " . mysqli_connect_error();
        }
        if(!isset($_GET["stillSubmit"])) {
            $sql = "SELECT paperID, title FROM papers WHERE title LIKE '%" . $_POST['title'] . "%';";
            if ($result = mysqli_query($con, $sql)) {
                while ($row = mysqli_fetch_row($result)) {
                    $sameNamePaperExists = true;
                    echo "<div><a class='clickItem' href='upload/$row[0].pdf' target='_blank'>" . $row[1] . "</a></div>";
                }
            }
            if ($sameNamePaperExists) {
                echo "<div class='warning-text'>Above paper(s) already exist, do you still want to submit this paper?</div>";
                echo "<button onclick='location.assign(\"addPaper.php?stillSubmit=1\")'>Yes</button><button onclick='location.assign(\"addPaper.php\")'>NO</button>";
                $_SESSION["delayQuery"] = "INSERT INTO papers (title, field, year, periodical, submitor, researchers) VALUES ('" . $_POST['title'] . "', '" . $_POST["field"] . "', '" . $_POST["year"] . "','" . $_POST["periodical"] . "','" . $_SESSION["studentID"] . "','" . $_POST["researchers"] . "')";
                $_SESSION["fileSize"] = $_FILES["file"]["size"];
                $_SESSION["fileError"] = $_FILES["file"]["error"];
                $_SESSION["temp_fileName"] = "m" . time();
                move_uploaded_file($_FILES["file"]["tmp_name"],
                    "upload/" . $_SESSION["temp_fileName"]);
            }
            else
            {
                if ($_FILES["file"]["size"] < 20000000) {
                    if ($_FILES["file"]["error"] > 0) {
                    } else {
                        $sql = "INSERT INTO papers (title, field, year, periodical, submitor, researchers) VALUES ('" . $_POST['title'] . "', '" . $_POST["field"] . "', '" . $_POST["year"] . "','" . $_POST["periodical"] . "','" . $_SESSION["studentID"] . "','" . $_POST["researchers"] . "');";
                        //echo $sql;
                        if (!mysqli_query($con, $sql)) {
                            echo "failed";
                        }
                        move_uploaded_file($_FILES["file"]["tmp_name"],
                            "upload/" . mysqli_insert_id($con) . ".pdf");
                        $submittedSuccessfully = true;
                    }
                } else {
                    echo "<script>alert('your file is bigger than 20MB');</script>";
                }
            }
        }
        else
        {
            if ($_SESSION["fileSize"] < 20000000) {
                if ($_SESSION["fileError"] > 0) {
                } else {
                    $sql = $_SESSION["delayQuery"];
                    if (!mysqli_query($con, $sql)) {
                        echo "failed";
                    }
                    exec("mv upload/" . $_SESSION["temp_fileName"] . " upload/" . mysqli_insert_id($con) . ".pdf");
                }
                $submittedSuccessfully = true;
            } else {
                echo "<script>alert('your file is bigger than 20MB');</script>";
            }
        }
        mysqli_close($con);
    }
    ?>
    <?php if(!$sameNamePaperExists) { ?>
        <form action="addPaper.php" method="post" enctype="multipart/form-data">
            <div><input placeholder="TITLE" type="text" name="title" required></div>
            <div><input placeholder="RESEARCHERS" type="text" name="researchers"></div>
            <div><select name="field" id="selectField">
                    <option value ="null">SELECT FIELD</option>
                    <option value ="Date mining">Date Mining</option>
                    <option value ="Machine learning">Machine Learning</option>
                    <option value="Nero Network">Nero Network</option>
                    <option value="Pattern Recognition">Pattern Recognition</option>
                </select>
            </div>
            <div><input placeholder="YEAR" type="text" name="year"></div>
            <div><input placeholder="PERIODICAL" type="text" name="periodical"></div>
            <div><input type="file" name="file" required></div>
            <div><input type="submit" value="SUBMIT"></div>
        </form>
    <?php } ?>
</div>
<script>
    <?php
        if($submittedSuccessfully)
        {
            echo "alert('The file has been submitted successfully!');";
        }
    ?>
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
