<?php
    session_start();
    require_once("../connection.php");
    require_once("../debug.php");
    require_once("/researcher-validation.php");
    require_once("/get-quiz-points.php");	    
    $conn = db_connect();
    $overviewName = "quiz";
    $columnName = array('QuizID','Week','TopicName','Points', 'Questions');    
    //edit/delete quiz
    if($_SERVER["REQUEST_METHOD"] == "POST"){
        if(isset($_POST['update'])){                          
            $update = $_POST['update'];
            //update
            if($update == 0){
                
            }
            else if($update == 1){  
                $quizID = $_POST['quizid'];
                $update_stmt = "DELETE FROM Quiz WHERE QuizID = ?";			
                $update_stmt = $conn->prepare($update_stmt);
                if(! $update_stmt -> execute(array($quizID))){
                    echo "<script language=\"javascript\">  alert(\"Error occurred to delete quiz. Contact with developers.\"); </script>";
                } else{
                } 
            }            
        }
    }
    
    // get quiz and topic
    $quizSql = "SELECT QuizID, Week, TopicName, COUNT(*) AS Questions
               FROM Quiz NATURAL JOIN Topic NATURAL JOIN MCQ_Section NATURAL JOIN MCQ_Question WHERE QuizType = 'MCQ' GROUP BY QuizID";
    $quizQuery = $conn->prepare($quizSql);
    $quizQuery->execute();
    $quizResult = $quizQuery->fetchAll(PDO::FETCH_OBJ); 
    
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
                    <h1 class="page-header">Multiple Choice Quiz Overview</h1>
                </div>
                <!-- /.col-lg-12 -->
            </div>
            <!-- /.row -->
            <div class="row">
                <div class="col-lg-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            Multiple Choice Quiz Information Table <span class="glyphicon glyphicon-plus pull-right" data-toggle="modal" data-target="#dialog"></span>
                        </div>
                        <!-- /.panel-heading -->
                        <div class="panel-body">
                            <div class="dataTable_wrapper">
                                <table class="table table-striped table-bordered table-hover" id="datatables">
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
                                    <?php for($i=0; $i<count($quizResult); $i++) {?>
                                        <tr class="<?php if($i % 2 == 0){echo "odd";} else {echo "even";} ?>">
                                            <td style="display:none"><?php echo $quizResult[$i]->QuizID ?></td>
                                            <td><?php echo $quizResult[$i]->Week ?></td>
                                            <td><?php echo $quizResult[$i]->TopicName ?></td>
                                            <td><?php echo getQuizPoints($quizResult[$i]->QuizID); ?></td>                                            
                                            <td><?php echo $quizResult[$i]->Questions ?><span class="glyphicon glyphicon-remove pull-right" aria-hidden="true"></span><span class="pull-right" aria-hidden="true">&nbsp;</span><a href="mcq-viewer.php?quizid=<?php echo $quizResult[$i]->QuizID ?>"><span class="glyphicon glyphicon-edit pull-right" data-toggle="modal" data-target="#dialog" aria-hidden="true"></span></a></td>
                                        </tr>
                                    <?php } ?>    
                                    </tbody>
                                </table>
                            </div>
                            <!-- /.table-responsive -->
                            <div class="well row">
                                <h4>Multiple Choice Quiz Overview Notification</h4>
                                <div class="alert alert-info">
                                    <p>View quizzes by filtering or searching. You can create/update/delete any quiz.</p>
                                </div>
                                <div class="alert alert-danger">
                                    <p><strong>Warning</strong> : If you remove one quiz. All the <strong>questions and submission</strong> of this quiz will also get deleted (not recoverable).</p> It includes <strong>learning material, questions and options, their submissions and your grading/feedback</strong>, not only the quiz itself.
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
                  <?php for($i=0; $i<count($quizResult); $i++) {?>                  
                  <option value="<?php echo $quizResult[$i]->SchoolName ?>"><?php echo $quizResult[$i]->SchoolName ?></option>
                  <?php } ?>
                </select>                
                <br><label for="TeacherToken">TeacherToken</label><span class="glyphicon glyphicon-random pull-right"></span>
                <input type="text" class="form-control dialoginput" id="TeacherToken" name="teachertoken" required></input>
                <br><label for="QuizToken">QuizToken</label><span class="glyphicon glyphicon-random pull-right"></span>
                <input type="text" class="form-control dialoginput" id="QuizToken" name="quiztoken" required></input>
                <br><label for="EnrolledQuizs">EnrolledQuizs</label>
                <input type="text" class="form-control dialoginput" id="EnrolledQuizs" name="EnrolledQuizs">
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
      <input type=hidden name="keyword" id="keyword" value="<?php if(isset($_GET['week'])){ echo $_GET['week']; } ?>"></input>
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
        //disable ClassID, EnrolledQuizs, UnlockedProgress
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
        //disable ClassID, EnrolledQuizs, UnlockedProgress
        $('.dialoginput').eq(0).attr('disabled','disabled');
        $('.dialoginput').eq(5).attr('disabled','disabled');    
        $('.dialoginput').eq(6).attr('disabled','disabled');         
    }); 
    $('.glyphicon-remove').on('click', function (){
        if (confirm('[WARNING] Are you sure to remove this class? All the quiz data in this class will also get deleted (not recoverable). It includes quiz information, their submissions of every task and your grading/feedback, not only the class itself.')) {
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
        else if (index == $("#QuizToken").index() - 1)
            $('#QuizToken').val(randomString(16));
    });
    $('#btnSave').on('click', function (){
        $('#submission').validate();        
        //enable ClassID and EnrolledQuizs
        $('.dialoginput').eq(0).prop('disabled',false);
        $('#submission').submit();
    });
    //include html
    w3IncludeHTML();   
    $(document).ready(function() {
        var table = $('#datatables').DataTable({
                responsive: true,
                "initComplete": function(settings, json) {
                    
                    $('.input-sm').eq(1).val($("#keyword").val().trim());                    
                }
        })
        //search keyword (schoolname), exact match
        table.search(
            $("#keyword").val().trim(), true, false, true
        ).draw();     
    });        
    </script>
</body>

</html>
