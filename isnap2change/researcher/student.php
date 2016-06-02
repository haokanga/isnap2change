<?php
    //if true, echo debug output in dev mode, else production mode
	$DEBUG_MODE = true;    
	session_start();
    require_once("../connection.php");	  
    $conn = db_connect();    
    $columnName = array('StudentID','ClassName','Username','FirstName','LastName','Email','Gender','DOB','Score','SubmissionDate');
    
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
    
    //if update/insert/remove class
    if($_SERVER["REQUEST_METHOD"] == "POST"){
        if(isset($_POST['update'])){                          
            $update = $_POST['update'];
            //update
            if($update == 0){
                $studentID = $_POST['classid'];
                $studentName = $_POST['classname'];
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
                if(! $update_stmt -> execute(array($studentName, $schoolResult->SchoolID, $studentID))){
                    echo "<script language=\"javascript\">  alert(\"Error occurred to update class. Contact with developers.\"); </script>";
                } else{
                }
                // update token                     
                $update_stmt = "UPDATE Token 
                    SET TokenString = ?
                    WHERE ClassID = ? AND `Type` = ?";			
                $update_stmt = $conn->prepare($update_stmt);                            
                if(! $update_stmt -> execute(array($teacherToken, $studentID, "TEACHER"))){
                    echo "<script language=\"javascript\">  alert(\"Error occurred to update teacherToken. Contact with developers.\"); </script>";
                } else{
                }
                $update_stmt = "UPDATE Token 
                    SET TokenString = ?
                    WHERE ClassID = ? AND `Type` = ?";			
                $update_stmt = $conn->prepare($update_stmt);                            
                if(! $update_stmt -> execute(array($studentToken, $studentID, "STUDENT"))){
                    echo "<script language=\"javascript\">  alert(\"Error occurred to update studentToken. Contact with developers.\"); </script>";
                } else{
                }
            }
            else if($update == 1){  
                $studentName = $_POST['classname'];
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
                $update_stmt -> execute(array($studentName, $schoolResult->SchoolID));
                $studentID = $conn -> lastInsertId();
                if($studentID <= 0){
                    echo "<script language=\"javascript\">  alert(\"Error occurred to insert class. Contact with developers.\"); </script>";
                } else{
                }
                // update token                     
                $update_stmt = "REPLACE INTO Token(ClassID, `Type`, TokenString)
                     VALUES (?,?,?);";			
                $update_stmt = $conn->prepare($update_stmt);                            
                if(! $update_stmt -> execute(array($studentID, "TEACHER", $teacherToken))){
                    echo "<script language=\"javascript\">  alert(\"Error occurred to insert teacherToken. Contact with developers.\"); </script>";
                } else{
                }
                $update_stmt = "REPLACE INTO Token(ClassID, `Type`, TokenString)
                     VALUES (?,?,?);";			
                $update_stmt = $conn->prepare($update_stmt);                            
                if(! $update_stmt -> execute(array($studentID, "STUDENT", $studentToken))){
                    echo "<script language=\"javascript\">  alert(\"Error occurred to insert studentToken. Contact with developers.\"); </script>";
                } else{
                }                
            }else if($update == -1){
                $studentID = $_POST['classid'];
                // remove class (with help of DELETE CASCADE) 
                $update_stmt = "DELETE FROM Class WHERE ClassID = ?";			
                $update_stmt = $conn->prepare($update_stmt);
                if(! $update_stmt -> execute(array($studentID))){
                    echo "<script language=\"javascript\">  alert(\"Error occurred to delete class/token. Contact with developers.\"); </script>";
                } else{
                } 
            }            
        }
    }   
    
    // get student
    $studentSql = "SELECT StudentID, ClassName, Username, FirstName, LastName, Email, Gender, DOB, Score, DATE(SubmissionTime) AS SubmissionDate FROM Student NATURAL JOIN Class
               ORDER BY ClassID";
    $studentQuery = $conn->prepare($studentSql);
    $studentQuery->execute();
    $studentResult = $studentQuery->fetchAll(PDO::FETCH_OBJ);    
    
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
                            Student Information Table <span class="glyphicon glyphicon-plus pull-right" data-toggle="modal" data-target="#dialog"></span>
                        </div>
                        <!-- /.panel-heading -->
                        <div class="panel-body">
                        <div>
                            Toggle column: 
                            <?php for($i=1; $i<count($columnName); $i++) {?>
                            <i class="fa fa-cog fa-fw"></i><a class="toggle-vis" data-column="<?php echo $i; ?>"><?php echo $columnName[$i]; ?></a>&nbsp;
                            <?php } ?>
                            <br>
                            <br>
                        </div>
                            <div class="dataTable_wrapper">
                                <table class="table table-striped table-bordered table-hover" id="datatables" >
                                    <thead>
                                        <tr>
                                        <?php for($i=0; $i<count($columnName); $i++) {
                                            if ($i==0){?>
                                            <th style="display:none"><?php echo $columnName[$i]; ?></th>
                                            <?php } else {?>                                            
                                            <th><?php echo $columnName[$i]; ?></th>
                                        <?php }
                                        }?>                                            
                                        </tr>
                                    </thead>
                                    <tbody>
                                    <?php for($i=0; $i<count($studentResult); $i++) {?>
                                        <tr class="<?php if($i % 2 == 0){echo "odd";} else {echo "even";} ?>">
                                        <?php for($j=0; $j<count($columnName); $j++) {?>
                                        <td <?php if ($j==0) echo 'style="display:none"'; ?>><?php echo $studentResult[$i]->$columnName[$j]; ?></td>
                                        <?php } ?>    
                                        </tr>
                                    <?php } ?> 
                                    </tbody>
                                </table>
                            </div>
                            <!-- /.table-responsive -->
                            <div class="well row">
                                <h4>Class Overview Notification</h4>
                                <div class="alert alert-info">
                                    <p>View students by filtering or searching. You can <strong>reset student password</strong> or delete students.</p>
                                </div>
                                <div class="alert alert-danger">
                                    <p><strong>Reminder</strong> : If you remove one student. All the data of this student will also get deleted (not recoverable).</p>
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
              <h4 class="modal-title" id="dialogTitle">Edit Class</h4>
            </div>
            <div class="modal-body">
            <form id="submission" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
                <!--if 1, insert; else if 0 update; else if -1 delete;-->
                <input type=hidden name="update" id="update" value="1"></input>
                <label for="ClassID" style="display:none">ClassID</label>
                <input type="text" class="form-control dialoginput" id="ClassID" name="classid" style="display:none">
                <br><label for="ClassName">ClassName</label>
                <input type="text" class="form-control dialoginput" id="ClassName" name="classname" required>
                <br><label for="SchoolName">SchoolName</label>
                <select class="form-control dialoginput" id="SchoolName" form="submission" name="schoolname" required>
                  <?php for($i=0; $i<count($schoolResult); $i++) {?>                  
                  <option value="<?php echo $schoolResult[$i]->SchoolName ?>"><?php echo $schoolResult[$i]->SchoolName ?></option>
                  <?php } ?>
                </select>                
                <br><label for="TeacherToken">TeacherToken</label><span class="glyphicon glyphicon-random pull-right"></span>
                <input type="text" class="form-control dialoginput" id="TeacherToken" name="teachertoken" required></input>
                <br><label for="StudentToken">StudentToken</label><span class="glyphicon glyphicon-random pull-right"></span>
                <input type="text" class="form-control dialoginput" id="StudentToken" name="studenttoken" required></input>
                <br><label for="EnrolledStudents">EnrolledStudents</label>
                <input type="text" class="form-control dialoginput" id="EnrolledStudents" name="EnrolledStudents">
                <br><label for="UnlockedProgress">UnlockedProgress</label>
                <input type="text" class="form-control dialoginput" id="UnlockedProgress" name="UnlockedProgress">
            </form>
            </div>
            <div class="modal-footer">            
              <button type="button" id="btnSave" class="btn btn-default">Save</button>
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
        $('#dialogTitle').text("Edit Class");
        $('#update').val(0);
        for(i=0;i<$('.dialoginput').length;i++){                
            $('.dialoginput').eq(i).val($(this).parent().parent().children('td').eq(i).text());
        }
        //disable ClassID, EnrolledStudents, UnlockedProgress
        $('.dialoginput').eq(0).attr('disabled','disabled');
        $('.dialoginput').eq(5).attr('disabled','disabled');
        $('.dialoginput').eq(6).attr('disabled','disabled');          
    });
    $('.glyphicon-plus').on('click', function (){
        $('#dialogTitle').text("Add Class");
        $('#update').val(1);
        for(i=0;i<$('.dialoginput').length;i++){                
            $('.dialoginput').eq(i).val('');
        }
        //disable ClassID, EnrolledStudents, UnlockedProgress
        $('.dialoginput').eq(0).attr('disabled','disabled');
        $('.dialoginput').eq(5).attr('disabled','disabled');    
        $('.dialoginput').eq(6).attr('disabled','disabled');         
    }); 
    $('.glyphicon-remove').on('click', function (){
        if (confirm('[WARNING] Are you sure to remove this class? All the student data in this class will also get deleted (not recoverable).')) {
            $('#update').val(-1);
            //fill required input
            $('.dialoginput').eq(0).prop('disabled',false);
            for(i=0;i<$('.dialoginput').length;i++){                
                $('.dialoginput').eq(i).val($(this).parent().parent().children('td').eq(i).text());
            }
            $('#submission').submit();
        }           
    });
     $('.glyphicon-random').on('click', function (){
        var index = $(this).index();
        if (index == $("#TeacherToken").index() - 1)
            $('#TeacherToken').val(randomString(16)); 
        else if (index == $("#StudentToken").index() - 1)
            $('#StudentToken').val(randomString(16));
    });
    $('#btnSave').on('click', function (){
        $('#submission').validate();        
        //enable ClassID and EnrolledStudents
        $('.dialoginput').eq(0).prop('disabled',false);
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

        $('a.toggle-vis').on( 'click', function (e) {
            e.preventDefault();
     
            // Get the column API object
            var column = table.column( $(this).attr('data-column') );
     
            // Toggle the visibility
            column.visible( ! column.visible() );
        } );
    });        
    </script>
</body>

</html>
