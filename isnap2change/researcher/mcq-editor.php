<?php
    session_start();
    require_once("../mysql-lib.php");
    require_once("../debug.php");
    require_once("/researcher-validation.php");
    $pageName = "mcq-editor";
    $columnName = array('QuizID','Week','TopicName','Points','Questionnaires','Questions');   
    $mcqQuesColName = array('MCQID','Question','Option', 'Explanation','Edit');
    
    try { 	    
        $conn = db_connect();
        if($_SERVER["REQUEST_METHOD"] == "POST"){
            if(isset($_POST['metadataupdate'])){                          
                $metadataupdate = $_POST['metadataupdate'];
                //update
                if($metadataupdate == 0){
                    try{
                        $quizID = $_POST['quizid'];
                        $week = $_POST['week'];
                        $topicName = $_POST['topicname'];
                        $points = $_POST['points'];
                        $questionnaires = $_POST['questionnaires'];
                        $conn->beginTransaction();              
                        
                        $topicResult = getTopicByName($conn, $topicName);
                        updateQuiz($conn, $quizID, $topicResult->TopicID, $week);
                        updateMCQSection($conn, $quizID, $points, $questionnaires);
                        
                        $conn->commit();                    
                    } catch(Exception $e) {
                        debug_err($pageName, $e);
                        $conn->rollback();
                    } 
                }
                else if($metadataupdate == 1){  
                    $quizID = $_POST['quizid'];
                    deleteQuiz($conn, $quizID);
                }            
            }
            if(isset($_POST['update'])){                          
                $update = $_POST['update'];
                if($update == 1){
                    $quizID = $_POST['quizid'];
                    $question = $_POST['question'];
                    $mcqID = createMCQQuestion($conn, $quizID, $question);
                    header('Location: mcq-option-editor.php?quizid='.$quizID.'&mcqid='.$mcqID);
                } else if($update == -1){
                    $mcqID = $_POST['mcqid'];
                    deleteMCQQuestion($conn, $mcqID); 
                }            
            }
        }
    } catch(Exception $e) {
        debug_err($pageName, $e);
    }    
    
    try{
        if(isset($_GET['quizid'])){
            $quizID = $_GET['quizid'];
            $quizResult = getMCQQuiz($conn, $quizID);
            $topicResult = getTopics($conn);
            $materialRes = getLearningMaterial($conn, $quizID);
            $mcqQuesResult = getMCQQuestions($conn, $quizID);
            $phpself = $pageName.'.php?quizid='.$quizID;
        }
    } catch(Exception $e) {
        debug_err($pageName, $e);
    }
    
    db_close($conn); 
    
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Header Library -->
    <?php include('/header-lib.php'); ?>
</head>

<body>

    <div id="wrapper">

        <div w3-include-html="navigation.html"></div> 

        <div id="page-wrapper">
            <div class="row">
                <div class="col-lg-12">
                    <h1 class="page-header">Multiple Choice Quiz Editor</h1>
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
                            <form id="metadatasubmission" method="post" action="<?php echo $phpself; ?>">
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
                            <span class="glyphicon glyphicon-remove pull-right" id="metadataremove" aria-hidden="true"></span><span class="pull-right" aria-hidden="true">&nbsp;</span><span class="glyphicon glyphicon-floppy-saved pull-right" id="metadataedit" aria-hidden="true"></span>    
                        </div>                            
                        <!-- /.panel-body -->
                    </div>
                    <!-- /.panel -->
                    
                    
                    <!--Learning Material-->
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            Learning Material Editor
                        </div>
                        <!-- /.panel-heading -->
                        <div class="panel-body">
                            <div class="heading" style="color: black; max-height:10vh; text-align:center; border-bottom: 1px solid #eee;">
                                <h1 style='padding: 0px;'> 
								<i>	<?php echo $materialRes->TopicName; ?> </i>                          
                                </h1> 
                            </div>
                            <iframe id="cp_embed_YydQoj" src="learning-material-editor.php?quizid=<?php echo $quizID; ?>" scrolling="no" frameborder="0" allowtransparency="true" allowfullscreen="true" name="Learning Material Editor" title="Learning Material Editor" style="width: 100%; height:100%;" ></iframe>                          
                        </div>                            
                        <!-- /.panel-body -->
                    </div>
                    <!-- /.panel -->
                    
                    <!-- Options -->
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            Questions and Options
                            <span class="glyphicon glyphicon-plus pull-right" data-toggle="modal" data-target="#dialog"></span>
                        </div>
                        <!-- /.panel-heading -->
                        <div class="panel-body">
                            <div class="dataTable_wrapper">
                                <table class="table table-striped table-bordered table-hover" id="datatables">
                                    <thead>
                                        <tr>
                                        <?php for($i=0; $i<count($mcqQuesColName); $i++) {
                                            if ($i==0){?>
                                            <th style="display:none"><?php echo $mcqQuesColName[$i]; ?></th>
                                            <?php } else {?>                                            
                                            <th><?php echo $mcqQuesColName[$i]; ?></th>
                                        <?php }
                                        }?>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php for($i=0; $i<count($mcqQuesResult); $i++) {?>
                                        <tr class="<?php if($i % 2 == 0){echo "odd";} else {echo "even";} ?>">
                                            <td style="display:none"><?php echo $mcqQuesResult[$i]->$mcqQuesColName[0]; ?></td>
                                            <td><?php echo $mcqQuesResult[$i]->$mcqQuesColName[1] ?></td>
                                            <td class ="<?php if ($mcqQuesResult[$i]->Content == $mcqQuesResult[$i]->CorrectChoice && strlen($mcqQuesResult[$i]->Content) > 0 ) {echo 'bg-success';} else {echo 'bg-danger';} ?>">
                                                <?php echo $mcqQuesResult[$i]->Content; ?>
                                            <td><?php echo $mcqQuesResult[$i]->$mcqQuesColName[3] ?></td>
                                            <td>
                                                <span class="glyphicon glyphicon-remove pull-right " aria-hidden="true"></span>
                                                <span class="pull-right" aria-hidden="true">&nbsp;</span>
                                                <a href="mcq-option-editor.php?quizid=<?php echo $quizID ?>&mcqid=<?php echo $mcqQuesResult[$i]->$mcqQuesColName[0]; ?>">
                                                <span class="glyphicon glyphicon-edit pull-right" data-toggle="modal" data-target="#dialog" aria-hidden="true"></span></a>
                                            </td>            
                                        </tr>
                                    <?php } ?>
                                    </tbody>
                                </table>
                            </div>
                            <!-- /.table-responsive -->
                            <div class="well row">
                                <h4>Multiple Choice Quiz Overview Notification</h4>
                                <div class="alert alert-info">
                                    <p>View multiple choice questions in this quiz by filtering or searching. You can create/update/delete any question.</p>
                                </div>
                                <div class="alert alert-danger">
                                    <p><strong>Warning</strong> : If you remove one question. All the <strong>options and student answers</strong> of this question will also get deleted (not recoverable), not only the question itself.</p>
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
              <h4 class="modal-title" id="dialogTitle">Edit Question</h4>
            </div>
            <div class="modal-body">
            <form id="submission" method="post" action="<?php echo $phpself; ?>">
                <input type=hidden name="update" id="update" value="1" required></input>
                <label for="MCQID" style="display:none">MCQID</label>
                <input type="text" class="form-control dialoginput" id="MCQID" name="mcqid" style="display:none"></input>
                <label for="Question">Question</label>
                <input type="text" class="form-control dialoginput" id="Question" name="question" value="" required></input>
                <br>
                <label for="QuizID" style="display:none">QuizID</label>
                <input type="text" class="form-control" id="QuizID" name="quizid" style="display:none" value="<?php echo $quizID; ?>" required></input>
                <br>
            </form>
            </div>
            <div class="modal-footer">            
              <button type="button" id="btnSave" class="btn btn-default">Save</button>
              <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
          </div>          
        </div>
      </div>    
      
    <!-- SB Admin Library -->  
    <?php include('/sb-admin-lib.php'); ?>   
    <!-- Page-Level Scripts -->
    <script>
    //DO NOT put them in $(document).ready() since the table has multi pages
    
    $('.glyphicon-plus').on('click', function (){
        $('#dialogTitle').text("Add Question");
        $('#update').val(1);
        for(i=0;i<$('.dialoginput').length;i++){                
            $('.dialoginput').eq(i).val('');
        }   
    });
    $('td > .glyphicon-remove').on('click', function (){
        $('#update').val(-1);
        for(i=0;i<$('.dialoginput').length;i++){                
            $('.dialoginput').eq(i).val($(this).parent().parent().children('td').eq(i).text().trim());
        }
        $('#submission').submit();                           
    });    
    $('#btnSave').on('click', function (){
        $('#submission').validate();   
        $('#submission').submit();
    });
    
    //include html
    w3IncludeHTML();   
    $(document).ready(function() {
        var table = $('#datatables').DataTable({
            responsive: true,
            //rows group for MCQID, Question and edit box
            rowsGroup: [1,4],
            "pageLength":100,
            "aoColumnDefs": [
              { "bSearchable": false, "aTargets": [ 0 ] }
            ]
        })
        $('#metadataedit').on('click', function (){
            $('#metadataupdate').val(0);
            $('#metadatasubmission').validate({
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
            $('#metadatasubmission').submit();
        });        
    });    
    </script>    
</body>

</html>
