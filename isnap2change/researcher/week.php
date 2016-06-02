<?php      
	session_start();
    require_once("../connection.php");
    require_once("../debug.php");
    require_once("/researcher-validation.php");    
    $conn = db_connect();
    $overviewName = "week";
    
    //if update/insert/remove school
    if($_SERVER["REQUEST_METHOD"] == "POST"){        
        $week = $_POST['week'];
        $update_stmt = "SET SQL_SAFE_UPDATES=0;
            UPDATE Quiz SET Week = NULL WHERE Week = ?;
            SET SQL_SAFE_UPDATES=1;";			
        $update_stmt = $conn->prepare($update_stmt);                            
        if(! $update_stmt -> execute(array($week))){
            echo "<script language=\"javascript\">  alert(\"Error occurred to delete ".$overviewName.". Contact with developers.\"); </script>";
        } else{
        }         
    }
    //General error: 2014 Cannot execute queries while other unbuffered queries are active. Consider using PDOStatement::fetchAll().
    unset($update_stmt);

    // get week and quiz count
    $weekSql = "SELECT Week, COUNT(*) AS QuizNum FROM Quiz GROUP BY Week";
    $weekQuery = $conn->prepare($weekSql);
    $weekQuery->execute();
    $weekResult = $weekQuery->fetchAll(PDO::FETCH_OBJ);     
    
    // get max week
    $weekNumSql = "SELECT MAX(Week) AS WeekNum FROM Quiz";
    $weekNumQuery = $conn->prepare($weekNumSql);
    $weekNumQuery->execute();
    $weekNumResult = $weekNumQuery->fetch(PDO::FETCH_OBJ);    
        
    db_close($conn); 
    
?>
<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>iSNAP2Change Admin</title>

    <!-- Bootstrap Core CSS -->
    <link href="../bower_components/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- MetisMenu CSS -->
    <link href="../bower_components/metisMenu/dist/metisMenu.min.css" rel="stylesheet">

    <!-- DataTables CSS -->
    <link href="../bower_components/datatables-plugins/integration/bootstrap/3/dataTables.bootstrap.css" rel="stylesheet">

    <!-- DataTables Responsive CSS -->
    <!-- <link href="../bower_components/datatables-responsive/css/dataTables.responsive.css" rel="stylesheet"> -->

    <!-- Custom CSS -->
    <link href="../dist/css/sb-admin-2.css" rel="stylesheet">

    <!-- Custom Fonts -->
    <link href="../bower_components/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->

    <!--w3data.js to include html-->
    <script src="../js/w3data.js"></script>
    
    <style>
    .glyphicon:hover {
        background-color: rgb(153, 153, 102);
    }
    </style>
</head>

<body>

    <div id="wrapper">

        <div w3-include-html="navigation.html"></div> 

        <div id="page-wrapper">
            <div class="row">
                <div class="col-lg-12">
                    <h1 class="page-header">Week Overview</h1>
                </div>
                <!-- /.col-lg-12 -->
            </div>
            <!-- /.row -->
            <div class="row">
                <div class="col-lg-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            Week Information Table
                        </div>
                        <!-- /.panel-heading -->
                        <div class="panel-body">
                            <div class="dataTable_wrapper">
                                <table class="table table-striped table-bordered table-hover" id="dataTables-example">
                                    <thead>
                                        <tr>
                                            <th>Week</th>
                                            <th>QuizNum</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    <?php 
                                    for($i=1; $i<=$weekNumResult->WeekNum; $i++) {
                                        $notEmpty = false;
                                        for($j=0; $j<count($weekResult); $j++){ 
                                            if ($weekResult[$j]->Week == $i){ ?>
                                        <tr class="<?php if(($i - 1) % 2 == 0){echo "odd";} else {echo "even";} ?>">
                                            <td><a id="<?php echo $i; ?>" href="quiz.php?week=<?php echo $i; ?>"><?php echo "Week ".$i; ?></a>
                                            </td>
                                            <td><?php echo $weekResult[$j]->QuizNum; ?><span class="glyphicon glyphicon-remove pull-right" aria-hidden="true" data-toggle="modal" data-target="#dialog"></span></td>
                                        </tr>
                                        <?php $notEmpty = true; }
                                        }                                        
                                        if(!$notEmpty){ ?>
                                        <tr class="<?php if(($i - 1) % 2 == 0){echo "odd";} else {echo "even";} ?>">
                                            <td><a id="<?php echo $i; ?>" href="quiz.php?week=<?php echo $i; ?>"><?php echo "Week ".$i; ?></a>
                                            </td>
                                            <td><?php echo 0; ?><span class="glyphicon glyphicon-remove pull-right" aria-hidden="true" data-toggle="modal" data-target="#dialog"></span></td>
                                        </tr>    
                                    <?php }
                                    } ?>    
                                    </tbody>
                                </table>
                            </div>
                            <!-- /.table-responsive -->
                            <div class="well row">
                                <h4>School Overview Notification</h4>
                                <div class="alert alert-info">
                                    <p>View weeks by filtering or searching. You can create/delete any week.</p>
                                </div>
                                <div class="alert alert-warning">
                                    <p>If you need to add another week, you don't need to explictly add it in this page. Go to <a href="quiz.php">Quiz Overview</a> to add a new quiz with that week number can simply work.</p>
                                </div>
                                <div class="alert alert-danger">
                                    <p><strong>Reminder</strong>: If you remove one week, all the <strong>quizzes</strong> linked to this week will still exist, but their "week" attribute will be set to "null" and you should assign them to another week if you need via <a href="quiz.php">Quiz Overview</a>.</p>
                                </div>
                            </div>
                        </div>
                        <!-- /.panel-body -->
                    </div>
                    <!-- /.panel -->
                </div>
                <!-- /.col-lg-12 -->
            </div>
            <!-- /.row -->
            
        </div>
        <!-- /#page-wrapper -->

    </div>
    <!-- /#wrapper -->
    <div class="modal fade" id="dialog" role="dialog">
        <div class="modal-dialog">        
          <!-- Modal content-->
          <div class="modal-content">
            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal">&times;</button>
              <h4 class="modal-title" id="dialogTitle">Remove Week</h4>
            </div>
            <div class="modal-body">
            <form id="submission" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
                <label for="Week" style="display:none">Week</label>
                <select class="form-control dialoginput" id="Week" form="submission" name="week" required>
                  <?php for($i=1; $i<=$weekNumResult->WeekNum; $i++) { ?>                  
                  <option value="<?php echo $i; ?>"><?php echo $i; ?></option>
                  <?php } ?>
                </select>                
            </form>
            </div>
            <div class="alert alert-danger">
                <p><strong>Reminder</strong>: If you remove one week, all the <strong>quizzes</strong> linked to this week will still exist, but their "week" attribute will be set to "null" and you should assign them to another week if you need via <a href="quiz.php">Quiz Overview</a>.</p>
            </div>
            <div class="modal-footer">            
              <button type="button" id="btnConfirm" class="btn btn-default">Confirm</button>
              <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
          </div>          
        </div>
      </div>
    <!-- jQuery -->
    <script src="../bower_components/jquery/dist/jquery.min.js"></script>

    <!-- Bootstrap Core JavaScript -->
    <script src="../bower_components/bootstrap/dist/js/bootstrap.min.js"></script>

    <!-- Metis Menu Plugin JavaScript -->
    <script src="../bower_components/metisMenu/dist/metisMenu.min.js"></script>

    <!-- DataTables JavaScript -->
    <script src="../bower_components/datatables/media/js/jquery.dataTables.min.js"></script>
    <script src="../bower_components/datatables-plugins/integration/bootstrap/3/dataTables.bootstrap.min.js"></script>

    <!-- Custom Theme JavaScript -->
    <script src="../dist/js/sb-admin-2.js"></script>    
    
    <!--jQuery Validate plugin-->
    <script src="http://ajax.aspnetcdn.com/ajax/jquery.validate/1.15.0/jquery.validate.min.js"></script>

    <!-- Page-Level Scripts -->
    <script>    
    $(document).ready(function() {
        $('#dataTables-example').DataTable({
                responsive: true
        });            
    });    
    $('.glyphicon-remove').on('click', function (){
        for(i=0;i<$('.dialoginput').length;i++){
            console.log($(this).parent().parent().children('span'));
            $('.dialoginput').eq(i).val($(this).parent().parent().children('td').eq(i).children('a').attr("id"));
        }                  
    });
    $('#btnConfirm').on('click', function (){
        $('#submission').validate();
        $('#submission').submit();
    });
    //include html
    w3IncludeHTML();
    </script>
</body>

</html>
