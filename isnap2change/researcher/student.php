<?php        
	session_start();
    require_once("../connection.php");
    require_once("../debug.php");
    require_once("/researcher_validation.php");
    $conn = db_connect();
    
    //if update/insert/remove class
    if($_SERVER["REQUEST_METHOD"] == "POST"){
        if(isset($_POST['update'])){                          
            $update = $_POST['update'];
            //update
            if($update == 0){
                $classID = $_POST['classid'];
                $className = $_POST['classname'];
                $schoolName = $_POST['schoolname'];
                $teacherToken = $_POST['teachertoken'];
                $studentToken = $_POST['studenttoken'];
                // get school
                $schoolSql = "SELECT SchoolID
                           FROM School WHERE SchoolName = ?";
                $schoolQuery = $conn->prepare($schoolSql);
                $schoolQuery->execute(array($schoolName));
                $schoolResult = $schoolQuery->fetch(PDO::FETCH_OBJ);
                // update class 
                $update_stmt = "UPDATE Class 
                    SET ClassName = ?, SchoolID = ?
                    WHERE ClassID = ?";			
                $update_stmt = $conn->prepare($update_stmt);                            
                if(! $update_stmt -> execute(array($className, $schoolResult->SchoolID, $classID))){
                    echo "<script language=\"javascript\">  alert(\"Error occurred to update class. Contact with developers.\"); </script>";
                } else{
                }
                // update token                     
                $update_stmt = "UPDATE Token 
                    SET TokenString = ?
                    WHERE ClassID = ? AND `Type` = ?";			
                $update_stmt = $conn->prepare($update_stmt);                            
                if(! $update_stmt -> execute(array($teacherToken, $classID, "TEACHER"))){
                    echo "<script language=\"javascript\">  alert(\"Error occurred to update teacherToken. Contact with developers.\"); </script>";
                } else{
                }
                $update_stmt = "UPDATE Token 
                    SET TokenString = ?
                    WHERE ClassID = ? AND `Type` = ?";			
                $update_stmt = $conn->prepare($update_stmt);                            
                if(! $update_stmt -> execute(array($studentToken, $classID, "STUDENT"))){
                    echo "<script language=\"javascript\">  alert(\"Error occurred to update studentToken. Contact with developers.\"); </script>";
                } else{
                }
            }
            else if($update == 1){  
                $className = $_POST['classname'];
                $schoolName = $_POST['schoolname'];
                $teacherToken = $_POST['teachertoken'];
                $studentToken = $_POST['studenttoken'];
                // get school
                $schoolSql = "SELECT SchoolID
                           FROM School WHERE SchoolName = ?";
                $schoolQuery = $conn->prepare($schoolSql);
                $schoolQuery->execute(array($schoolName));
                $schoolResult = $schoolQuery->fetch(PDO::FETCH_OBJ);
                // update class 
                $update_stmt = "INSERT INTO Class(ClassName, SchoolID)
                     VALUES (?,?);";			
                $update_stmt = $conn->prepare($update_stmt);         
                $update_stmt -> execute(array($className, $schoolResult->SchoolID));
                $classID = $conn -> lastInsertId();
                if($classID <= 0){
                    echo "<script language=\"javascript\">  alert(\"Error occurred to insert class. Contact with developers.\"); </script>";
                } else{
                }
                // update token                     
                $update_stmt = "REPLACE INTO Token(ClassID, `Type`, TokenString)
                     VALUES (?,?,?);";			
                $update_stmt = $conn->prepare($update_stmt);                            
                if(! $update_stmt -> execute(array($classID, "TEACHER", $teacherToken))){
                    echo "<script language=\"javascript\">  alert(\"Error occurred to insert teacherToken. Contact with developers.\"); </script>";
                } else{
                }
                $update_stmt = "REPLACE INTO Token(ClassID, `Type`, TokenString)
                     VALUES (?,?,?);";			
                $update_stmt = $conn->prepare($update_stmt);                            
                if(! $update_stmt -> execute(array($classID, "STUDENT", $studentToken))){
                    echo "<script language=\"javascript\">  alert(\"Error occurred to insert studentToken. Contact with developers.\"); </script>";
                } else{
                }                
            }else if($update == -1){
                $classID = $_POST['classid'];
                // remove class (with help of DELETE CASCADE) 
                $update_stmt = "DELETE FROM Class WHERE ClassID = ?";			
                $update_stmt = $conn->prepare($update_stmt);
                if(! $update_stmt -> execute(array($classID))){
                    echo "<script language=\"javascript\">  alert(\"Error occurred to delete class/token. Contact with developers.\"); </script>";
                } else{
                } 
            }            
        }
    }
    
    // get max week
    $weekSql = "SELECT MAX(Week) AS WeekNum FROM Quiz";
    $weekQuery = $conn->prepare($weekSql);
    $weekQuery->execute();
    $weekResult = $weekQuery->fetch(PDO::FETCH_OBJ);

    // get school
    $schoolSql = "SELECT SchoolName
               FROM School";
    $schoolQuery = $conn->prepare($schoolSql);
    $schoolQuery->execute();
    $schoolResult = $schoolQuery->fetchAll(PDO::FETCH_OBJ);
    
    // get class
    $classSql = "SELECT ClassID, ClassName, SchoolName, UnlockedProgress
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
                    <h1 class="page-header">Student Overview</h1>
                </div>
                <!-- /.col-lg-12 -->
            </div>
            <!-- /.row -->
            <div class="row">
                <div class="col-lg-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            Student Information Table
                        </div>
                        <!-- /.panel-heading -->
                        <div class="panel-body">
                            <?php for($i=1; $i<count($columnName); $i++) {
                            if($columnName[$i] != 'Username'){ ?>
                                <i class="fa fa-cog fa-fw"></i><a class="toggle-vis" data-column="<?php echo $i; ?>"><?php echo $columnName[$i]; ?></a>&nbsp;
                            <?php }
                            } ?>
                            <div class="dataTable_wrapper">
                                <table class="table table-striped table-bordered table-hover" id="datatables">
                                    <thead>
                                        <tr>
                                            <th style="display:none">StudentID</th>
                                            <th>UserName</th>
                                            <th>Password</th>
                                            <th>Nickname</th>                                            
                                            <th>StudentToken</th>
                                            <th>EnrolledStudents</th>
                                            <th>UnlockedProgress</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    <?php for($i=0; $i<count($classResult); $i++) {?>
                                        <tr class="<?php if($i % 2 == 0){echo "odd";} else {echo "even";} ?>">
                                            <td style="display:none"><?php echo $classResult[$i]->ClassID ?></td>
                                        <td <?php if ($j==0) echo 'style="display:none"'; ?>><?php echo $studentResult[$i]->$columnName[$j]; ?>
                                        <?php if ($j==2) echo '<span class="glyphicon glyphicon-remove pull-right" aria-hidden="true"></span><span class="pull-right" aria-hidden="true">&nbsp;</span><span class="glyphicon glyphicon-edit pull-right" data-toggle="modal" data-target="#dialog" aria-hidden="true"></span>'; ?>
                                        </td>
                                            <td><?php for($j=0; $j<count($tokenResult); $j++){ if ($tokenResult[$j]->ClassID == $classResult[$i]->ClassID && $tokenResult[$j]->Type == 'STUDENT') echo $tokenResult[$j]->TokenString;} ?></td>
                                            <td><?php $count=0; for($j=0; $j<count($studentNumResult); $j++){ if ($studentNumResult[$j]->ClassID == $classResult[$i]->ClassID) $count=$studentNumResult[$j]->Count; } echo $count; ?></td>
                                            <td><?php echo min($classResult[$i]->UnlockedProgress, $weekResult->WeekNum)."/".$weekResult->WeekNum ?><span class="glyphicon glyphicon-remove pull-right" aria-hidden="true"></span><span class="pull-right" aria-hidden="true">&nbsp;</span><span class="glyphicon glyphicon-edit pull-right" data-toggle="modal" data-target="#dialog" aria-hidden="true"></span></td>
                                        </tr>
                                    <?php } ?>    
                                    </tbody>
                                </table>
                            </div>
                            <!-- /.table-responsive -->
                            <div class="well row">
                                <h4>Class Overview Notification</h4>
                                <div class="alert alert-info">
                                    <p>Navigate classes by filtering or searching. You can create/update/delete any class.</p>
                                </div>
                                <div class="alert alert-danger">
                                    <p><strong>Reminder</strong> : If you remove one class. All the student data in this class will also get deleted (not recoverable).</p>
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
    <!-- Modal -->
      <div class="modal fade" id="dialog" role="dialog">
        <div class="modal-dialog">        
          <!-- Modal content-->
          <div class="modal-content">
            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal">&times;</button>
              <h4 class="modal-title" id="dialogTitle">Reset Password</h4>
            </div>
            <div class="modal-body">
            <form id="submission" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
                <!--if 0 update; else if -1 delete;-->
                <input type=hidden name="update" id="update" value="1"></input>                
                <?php for($i=0; $i<count($columnName); $i++) {
                    if($columnName[$i] == 'StudentID' || $columnName[$i] == 'Username'){?>
                    <label for="<?php echo $columnName[$i]; ?>" <?php if ($i==0){ echo 'style="display:none"';} ?>><?php echo $columnName[$i]; ?></label>
                    <input type="text" class="form-control dialoginput" id="<?php echo $columnName[$i]; ?>" name="<?php echo strtolower($columnName[$i]); ?>"  
                    <?php if ($i==0){ echo 'style="display:none"';} ?>>
                <?php } 
                }?>
            </form>
            </div>
            <div class="modal-footer">            
              <button type="button" id="btmResetPwd" class="btn btn-default">Reset Password</button>
              <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
          </div>          
        </div>
      </div>
      <input type=hidden name="keyword" id="keyword" value="<?php if(isset($_GET['schoolname'])){ echo $_GET['schoolname']; } ?>"></input>
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
    function randomString(length) {
        return Math.round((Math.pow(36, length + 1) - Math.random() * Math.pow(36, length))).toString(36).slice(1);
    }
    //DO NOT put them in $(document).ready() since the table has multi pages
    $('.glyphicon-edit').on('click', function (){
        $('#update').val(0);
        $('.dialoginput').eq(1).val($(this).parent().text());
    });
    $('.glyphicon-remove').on('click', function (){
        if (confirm('[WARNING] Are you sure to remove this class? All the student data in this class will also get deleted (not recoverable).')) {
            $('#update').val(-1);            
            //$('.dialoginput').eq(1).val($(this).parent().text());
            //$('#submission').submit();
        }           
    });
    $('#btmResetPwd').on('click', function (){
        $('#submission').validate();        
        //enable all the input
        $('.dialoginput').each(function() {
            $( this ).prop('disabled',false);
        });
        $('#submission').submit();
    });
    //include html
    w3IncludeHTML();   
    $(document).ready(function() {
        var table = $('#datatables').DataTable({
                responsive: true,
                "initComplete": function(settings, json) {
                    
                    $('.input-sm').eq(1).val($("#keyword").val());                    
                }
        })
        //search keyword (schoolname), exact match
        table.search(
            $("#keyword").val(), true, false, true
        ).draw();     
            e.preventDefault();     
            var column = table.column( $(this).attr('data-column') );     
        //hide FirstName LastName Email
        var hiddenColArray=['FirstName','LastName','Email'] 
        $('a.toggle-vis').each(function() {
            if(jQuery.inArray( $(this).text(), hiddenColArray )!= -1)
                $( this ).click();
        });        
    });        
    </script>
</body>

</html>
