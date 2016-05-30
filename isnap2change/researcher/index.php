<?php
    //if true, echo debug output in dev mode, else production mode
	$DEBUG_MODE = true;    
	session_start();
    require_once("../connection.php");	  
    $conn = db_connect();
    
    //set userid    
    if(isset($_SESSION['researcherid'])){
        $researcherid = $_SESSION['researcherid'];
        if($DEBUG_MODE){
            echo "<script language=\"javascript\">  console.log(\"This is DEBUG_MODE with SESSION ResearcherID = ".$researcherid.".\"); </script>";
        }
    }else{
        if($DEBUG_MODE){
            echo "<script language=\"javascript\">  console.log(\"This is DEBUG_MODE with hard-code ResearcherID = 1.\"); </script>";
            $researcherid = 1;
        }
    } 
    // get class
    $classSql = "SELECT ClassID, ClassName, SchoolName
               FROM Class NATURAL JOIN School";
    $classQuery = $conn->prepare($classSql);
    $classQuery->execute();
    $classResult = $classQuery->fetchAll(PDO::FETCH_OBJ);
    
    // get token
    $tokenSql = "SELECT ClassID, `Type`, TokenString
               FROM Token NATURAL JOIN Class";
    $tokenQuery = $conn->prepare($tokenSql);
    $tokenQuery->execute();
    $tokenResult = $tokenQuery->fetchAll(PDO::FETCH_OBJ);
    
    
    // get students number
    $studentNumSql = "SELECT count(*) as Count, ClassID
               FROM   Student NATURAL JOIN Class
               GROUP BY ClassID";
    $studentNumQuery = $conn->prepare($studentNumSql);
    $studentNumQuery->execute();
    $studentNumResult = $studentNumQuery->fetchAll(PDO::FETCH_OBJ);    
    
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

    <title>Content Editor</title>

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
    .glyphicon-remove:hover {
        background-color: rgb(153, 153, 102);
    }
    .glyphicon-edit:hover {
        background-color: rgb(153, 153, 102);
    }
    .glyphicon-plus:hover {
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
                    <h1 class="page-header">Class Overview</h1>
                </div>
                <!-- /.col-lg-12 -->
            </div>
            <!-- /.row -->
            <div class="row">
                <div class="col-lg-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            Class Information Table <span class="glyphicon glyphicon-plus pull-right"></span>
                        </div>
                        <!-- /.panel-heading -->
                        <div class="panel-body">
                            <div class="dataTable_wrapper">
                                <table class="table table-striped table-bordered table-hover" id="dataTables-example">
                                    <thead>
                                        <tr>
                                            <th>ClassID</th>
                                            <th>ClassName</th>
                                            <th>SchoolName</th>
                                            <th>TeacherToken</th>                                            
                                            <th>StudentToken</th>
                                            <th>EnrolledStudents</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    <?php for($i=0; $i<count($classResult); $i++) {?>
                                        <tr class="<?php if($i % 2 == 0){echo "odd";} else {echo "even";} ?>">
                                            <td><?php echo $classResult[$i]->ClassID ?></td>
                                            <td><?php echo $classResult[$i]->ClassName ?></td>
                                            <td><?php echo $classResult[$i]->SchoolName ?></td>
                                            <td class="center"><?php for($j=0; $j<count($tokenResult); $j++){ if ($tokenResult[$j]->ClassID == $classResult[$i]->ClassID && $tokenResult[$j]->Type == 'TEACHER') echo $tokenResult[$j]->TokenString;} ?></td>
                                            <td class="center"><?php for($j=0; $j<count($tokenResult); $j++){ if ($tokenResult[$j]->ClassID == $classResult[$i]->ClassID && $tokenResult[$j]->Type == 'STUDENT') echo $tokenResult[$j]->TokenString;} ?></td>
                                            <td class="center"><?php $count=0; for($j=0; $j<count($studentNumResult); $j++){ if ($studentNumResult[$j]->ClassID == $classResult[$i]->ClassID) $count=$studentNumResult[$j]->Count; } echo $count; ?><span class="glyphicon glyphicon-remove pull-right" aria-hidden="true"></span><span class="pull-right" aria-hidden="true">&nbsp;</span><span class="glyphicon glyphicon-edit pull-right" aria-hidden="true"></span></td>
                                        </tr>
                                    <?php } ?>    
                                    </tbody>
                                </table>
                            </div>
                            <!-- /.table-responsive -->
                            <div class="well row">
                                <h4>Weekly Content Editor Notification</h4>
                                <p>Navigate weekly content by weeks or searching. You can create/update/delete any tasks/facts or even add/remove certain week.</a>.</p>
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

    <!-- Page-Level Demo Scripts - Tables - Use for reference -->
    <script>
    $(document).ready(function() {
        $('#dataTables-example').DataTable({
                responsive: true
        });
    });
    
    //include html
    w3IncludeHTML();
    </script>

</body>

</html>
