<?php
    session_start();
    require_once("../connection.php");
    require_once("../debug.php");
    require_once("/researcher-validation.php");
    $conn = db_connect();
    $overviewName = "mcq-option-editor";
    $columnName = array('OptionID','Content','Explanation','MCQID');
    try{
        //edit/delete option
        if($_SERVER["REQUEST_METHOD"] == "POST"){
            if(isset($_POST['update'])){                          
                $update = $_POST['update'];
                if($update == 1){
                    try{
                        $week = $_POST['week'];
                        $optionType = 'MCQ';
                        $topicName = $_POST['topicname'];
                        $points = $_POST['points'];
                        $questionnaires = $_POST['questionnaires'];
                        $conn->beginTransaction();              
                        
                        //get topicID
                        $topicSql = "SELECT TopicID
                                   FROM Topic WHERE TopicName = ?";
                        $topicQuery = $conn->prepare($topicSql);
                        $topicQuery->execute(array($topicName));
                        $topicResult = $topicQuery->fetch(PDO::FETCH_OBJ);
                        //insert quiz
                        $update_stmt = "INSERT INTO Quiz(Week, QuizType, TopicID)
                             VALUES (?,?,?);";			
                        $update_stmt = $conn->prepare($update_stmt);         
                        $update_stmt->execute(array($week, $optionType, $topicResult->TopicID)); 
                        //insert MCQ_Section
                        $optionID = $conn->lastInsertId(); 
                        $update_stmt = "INSERT INTO MCQ_Section(QuizID, Points, Questionnaires)
                                        VALUES (?,?,?) ON DUPLICATE KEY UPDATE Points = ?, Questionnaires = ?;";			
                        $update_stmt = $conn->prepare($update_stmt);                            
                        $update_stmt->execute(array($optionID, $points, $questionnaires, $points, $questionnaires)); 
                        //init empty Learning Material
                        $content='<p>Learning materials for this quiz has not been added.</p>';
                        $update_stmt = "INSERT INTO Learning_Material(Content,QuizID) VALUES (?,?);";
                        $update_stmt = $conn->prepare($update_stmt);                            
                        $update_stmt->execute(array($content, $optionID)); 
                        
                        $conn->commit();                    
                    } catch(PDOException $e) {
                        debug_pdo_err($overviewName, $e);
                        $conn->rollback();
                    } 
                }                       
                else if($update == 0){
                    
                }
                else if($update == -1){  
                    $optionID = $_POST['quizid'];
                    $update_stmt = "DELETE FROM Quiz WHERE QuizID = ?";			
                    $update_stmt = $conn->prepare($update_stmt);
                    if(! $update_stmt->execute(array($optionID))){
                        echo "<script language=\"javascript\">  alert(\"Error occurred to delete quiz. Contact with developers.\"); </script>";
                    } else{
                    } 
                }             
            }
        }
        
        //get mcq
        $mcqSql = "SELECT MCQID, Question, CorrectChoice
                   FROM MCQ_Question
                   WHERE  QuizID = ?";                                
        $mcqQuery = $conn->prepare($mcqSql);
        $mcqQuery->execute(array($_GET['mcqid']));
        $mcqResult = $mcqQuery->fetch(PDO::FETCH_OBJ); 
        
        // get options
        $optionSql = "SELECT ".join(',',$columnName)."
                   FROM MCQ_Question NATURAL JOIN `Option` WHERE MCQID = ? ";
        $optionQuery = $conn->prepare($optionSql);
        $optionQuery->execute(array($_GET['mcqid']));
        $optionResult = $optionQuery->fetchAll(PDO::FETCH_OBJ);    
    
    } catch(PDOException $e) {
        debug_pdo_err($overviewName, $e);
    }
    
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
                    <h1 class="page-header">Multiple Choice Question Editor</h1>
                </div>
                <!-- /.col-lg-12 -->
            </div>
            <!-- /.row -->
            <div class="row">
                <div class="col-lg-12">
                    <!-- MetaData -->
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            Quiz MetaData
                        </div>
                        <!-- /.panel-heading -->
                        <div class="panel-body">
                            <form id="metadatasubmission" method="post" action="<?php echo 'mcq-editor.php?quizid='.$quizID; ?>">
                                <!--if 0 update; else if -1 delete;-->
                                <input type=hidden name="metadataupdate" id="metadataupdate" value="1" required></input>
                                <label for="QuizID" style="display:none">QuizID</label>
                                <input type="text" class="form-control" id="QuizID" name="quizid" style="display:none" value="<?php echo $quizResult->QuizID; ?>"></input>
                                <br>
                                <label for="Week">Week</label>
                                <input type="text" class="form-control" id="Week" name="week" placeholder="Input Week Number" value="<?php echo $quizResult->Week; ?>"></input> 
                                <br>  
                                <label for='TopicName'>TopicName</label>
                                <select class="form-control" id="TopicName" form="metadatasubmission" name="topicname" required>
                                  <?php for($j=0; $j<count($topicResult); $j++) {?>                  
                                    <option value='<?php echo $topicResult[$j]->TopicName ?>' <?php if($topicResult[$j]->TopicName==$quizResult->TopicName) echo 'selected' ?> ><?php echo $topicResult[$j]->TopicName ?></option>
                                  <?php } ?>
                                </select>
                                <br>
                                <label for="Points">Points</label>
                                <input type="text" class="form-control" id="Points" name="points" placeholder="Input Points" value="<?php echo $quizResult->Points; ?>" required></input>
                                <br>
                                <label for="Questionnaires">Questionnaires</label>
                                <input type="hidden" class="form-control" id="Questionnaires" name="questionnaires" value="0"></input>
                                <input type="checkbox" class="form-control" id="Questionnaires" name="questionnaires" value="1" <?php if($quizResult->Questionnaires!=0) echo 'checked';?>></input>
                                <label for="Questions">Questions</label>
                                <input type="text" class="form-control" id="Questions" name="questions" value="<?php echo $quizResult->Questions; ?>" disabled></input>
                                <br>
                            </form>
                            <!--edit metadata-->
                            <span class="glyphicon glyphicon-remove pull-right" id="metadataremove" aria-hidden="true"></span><span class="pull-right" aria-hidden="true">&nbsp;</span><span class="glyphicon glyphicon-edit pull-right" id="metadataedit" aria-hidden="true"></span>    
                        </div>                            
                        <!-- /.panel-body -->
                    </div>
                    <!-- /.panel -->
                
                
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            Option Information Table <span class="glyphicon glyphicon-plus pull-right" data-toggle="modal" data-target="#dialog"></span>
                        </div>
                        <!-- /.panel-heading -->
                        <div class="panel-body">
                            <div class="dataTable_wrapper">
                                <table class="table table-striped table-bordered table-hover" id="datatables">
                                    <thead>
                                        <tr>
                                        <?php for($i=0; $i<count($columnName)-1; $i++){ ?>
                                            <th <?php if ($i==0){ echo 'style="display:none"';} ?>><?php echo $columnName[$i]; ?></th>
                                        <?php }?>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    <?php for($i=0; $i<count($optionResult); $i++) {?>
                                        <tr class="<?php if($i % 2 == 0){echo "odd";} else {echo "even";} ?>">
                                            <?php for($j=0; $j<count($columnName)-1; $j++){ ?>
                                                <td <?php if ($j==0){ echo 'style="display:none"';} ?>>
                                                    <?php echo $optionResult[$i]->$columnName[$j]; ?> 
                                                    <?php if($j==count($columnName)-1){?>
                                                        <span class="glyphicon glyphicon-remove pull-right" aria-hidden="true"></span>
                                                        <span class="pull-right" aria-hidden="true">&nbsp;</span>
                                                        <span class="glyphicon glyphicon-edit pull-right" aria-hidden="true"></span>
                                                    <?php } ?>
                                                </td>
                                            <?php }?>
                                        </tr>
                                    <?php } ?>    
                                    </tbody>
                                </table>
                            </div>
                            <!-- /.table-responsive -->
                            <div class="well row">
                                <h4>Multiple Choice Question Editor Notification</h4>
                                <div class="alert alert-info">
                                    <p>You can create/update/delete any options of this multiple choice question or the question itself.</p>
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
              <h4 class="modal-title" id="dialogTitle">Edit Quiz</h4>
            </div>
            <div class="modal-body">
            <form id="submission" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
                <!--if 1, insert; else if -1 delete;-->
                <input type=hidden name="update" id="update" value="1" required></input>
                <label for="QuizID" style="display:none">QuizID</label>
                <input type="text" class="form-control dialoginput" id="QuizID" name="quizid" style="display:none"></input>
                <label for="Week">Week</label>
                <input type="text" class="form-control dialoginput" id="Week" name="week"  placeholder="Input Week Number" required></input> 
                <br>  
                <label for='TopicName'>TopicName</label>
                <select class="form-control dialoginput" id="TopicName" form="submission" name="topicname" required>
                    <option value="" disabled selected>Select Topic</option>
                  <?php for($j=0; $j<count($topicResult); $j++) {?>                  
                    <option value='<?php echo $topicResult[$j]->TopicName ?>'><?php echo $topicResult[$j]->TopicName ?></option>
                  <?php } ?>
                </select>
                <br>
                <label for="Points">Points</label>
                <input type="text" class="form-control dialoginput" id="Points" name="points"  placeholder="Input Points" required></input>
                <br>
                <label for="Questionnaires">Questionnaires</label>
                <input type="checkbox" class="form-control" id="Questionnaires" name="questionnaires" value="1"></input>
            </form>
            </div>
            <div class="modal-footer">            
              <button type="button" id="btnSave" class="btn btn-default">Save</button>
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
    //DO NOT put them in $(document).ready() since the table has multi pages    
    $('.glyphicon-plus').on('click', function (){
        $('#dialogTitle').text("Add MCQ");
        $('#update').val(1);
        for(i=0;i<$('.dialoginput').length;i++){                
            $('.dialoginput').eq(i).val('');
        }   
    }); 
    $('.glyphicon-remove').on('click', function (){
        if (confirm('[WARNING] Are you sure to remove this quiz? If you remove one quiz. All the questions and submission of this quiz will also get deleted (not recoverable). It includes learning material, questions and options, their submissions and your grading/feedback, not only the quiz itself.')) {
            $('#update').val(-1);
            for(i=0;i<$('.dialoginput').length;i++){                
                $('.dialoginput').eq(i).val($(this).parent().parent().children('td').eq(i).text());
            }
            $('#submission').submit();
        }           
    });
    $('#btnSave').on('click', function (){
        $('#submission').validate({
          rules: {
            week: {
              required: true,
              digits: true
            },
            points: {
              required: true,
              digits: true
            }
          }
        });   
        $('#submission').submit();
    });
    //include html
    w3IncludeHTML();   
    $(document).ready(function() {
        var table = $('#datatables').DataTable({
                responsive: true,
                "order": [[ 1, "asc" ]],
                "pageLength":50,
                "aoColumnDefs": [
                  { "bSearchable": false, "aTargets": [ 0 ] }
                ]
        })   
    });        
    </script>
</body>

</html>
