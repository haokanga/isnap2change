<?php
    //check login status
    require_once('student-validation.php');

    require_once("../mysql-lib.php");
    require_once("../debug.php");
    $pageName = "pre-task-material";

    //check whether a request is GET or POST
    if ($_SERVER["REQUEST_METHOD"] == "GET") {
        if(isset($_GET["quiz_id"])) {
            $quizID = $_GET["quiz_id"];
            
        } else {

        }
    } else {

    }

    $conn = null;

    try {
        $conn = db_connect();

        $week = getWeekByQuiz($conn, $quizID);

        //check whether the week is locked or not
        if ($week > getStudentWeek($conn, $studentID)) {
            echo '<script>alert("This is a locked quiz!")</script>';
            echo '<script>window.location="game-home.php"</script>';
        }

        //get due time for this week
        $dueTime = getStuWeekRecord($conn, $studentID, $week);

        //get learning material
        $materialRes = getLearningMaterial($conn, $quizID);

        //get quiz type
        $quizType = getQuizType($conn, $quizID);

    } catch(Exception $e) {
        if($conn != null) {
            db_close($conn);
        }

        debug_err($e);
        //to do: handle sql error
        //...
        exit;
    }

    db_close($conn);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="initial-scale=1.0, width=device-width, user-scalable=no">
    <title>Pre Task Material</title>
    <link rel="stylesheet" href="./css/common.css">
    <link href='https://fonts.googleapis.com/css?family=Maitree|Lato:400,900' rel='stylesheet' type='text/css'>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
    <script src="./js/snap.js"></script>
    <style>
        .material {
            max-width: 1000px;
            margin: 0 auto;
        }
        .material-title {
            text-align: center;
            font-size: 24px;
            max-width: 700px;
            margin: 20px auto;
        }
        .material-content {
            background-color: #dedfdf;
            padding: 14px 120px;
            border-radius: 10px;
            color: #000;
            font-family: Maitree, serif;
            font-size: 18px;
            min-height: 350px;
        }
        .material-content p {
            margin: 0 0 30px 0;
        }
        .material-start {
            display: block;
            width: 64px;
            height: 24px;
            background-size: 100% 100%;
            margin: 20px auto;
            background-image: url("./img/start_icon_1.png");
        }
    </style>
</head>
<body>

<div class="page-wrapper">
    <div class="header-wrapper">
        <div class="header">
            <a class="home-link">SNAP</a>
            <div class="settings">
                <div class="setting-icon dropdown">
                    <ul class="dropdown-menu">
                        <li class="dropdown-item"><a href="settings.php">Setting</a></li>
                        <li class="dropdown-item"><a href="logout.php">Logout</a></li>
                    </ul>
                </div>
                <a class="setting-text"><?php echo $studentUsername?></a>
            </div>
        </div>
    </div>


    <div class="content-wrapper">
        <div class="material">
            <h2 class="material-title">Read the follow information about <?php echo strtolower($materialRes->TopicName) ?> then finish the task provided.</h2>
            <div class="material-content">
                <?php echo $materialRes->Content; ?>
            </div>
        <?php
            switch ($quizType) {
                case "MCQ": ?>
                    <a href="" class="material-start"></a>
         <?php      break;
                case "SAQ": ?>
                    <a href="" class="material-start"></a>
         <?php      break;
                case "Matching": ?>
                    <a href="" class="material-start"></a>
         <?php      break;
                case "Poster": ?>
                    <a href="poster.php?quiz_id=<?php echo $quizID?>" class="material-start"></a>
         <?php      break;
                case "Calculator": ?>
                    <a href="cost-calculator.php?quiz_id=<?php echo $quizID?>" class="material-start"></a>
        <?php       break;
                case "DrinkingTool": ?>
                    <a href="standard-drinking-tool.php?quiz_id=<?php echo $quizID?>" class="material-start"></a>
        <?php       break;
            }  ?>
        </div>
    </div>

    <a href="weekly-task.php?week=<?php echo $week?>" class="cancel-task">
        <span class="cancel-icon"></span>
        <span class="cancel-label">Cancel Task</span>
    </a>


    <div class="footer-wrapper">
        <div class="footer">
            <div class="footer-content">
                <a href="#" class="footer-logo"></a>
                <ul class="footer-nav">
                    <li class="footer-nav-item"><a href="#">Any Legal Stuff</a></li>
                    <li class="footer-nav-item"><a href="#">Acknowledgements</a></li>
                </ul>
            </div>
        </div>
    </div>
</div>


<script>
    snap.enableBackTop()
</script>
</body>
</html>

