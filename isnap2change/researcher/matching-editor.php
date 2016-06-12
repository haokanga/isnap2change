<?php
    session_start();
    require_once("../mysql-lib.php");
    require_once("../debug.php");
    require_once("researcher-validation.php");
    $pageName = "matching-editor";
    $columnName = array('QuizID','Week','TopicName','Description','MultipleChoice','Points');  
    $matchingQuesColName = array('MatchingID','Question','OptionID','Content');
    $matchingQuesVisualName = array('MatchingID','Terminology/Bucket','OptionID','Explanation/Item');
    
    try { 	    
        $conn = db_connect();
        if($_SERVER["REQUEST_METHOD"] == "POST"){
            if(isset($_POST['metadataupdate'])){                          
                $metadataupdate = $_POST['metadataupdate'];
                if($metadataupdate == 0){
                    try{
                        $quizID = $_POST['quizid'];
                        $week = $_POST['week'];
                        $topicName = $_POST['topicname'];
                        $description = $_POST['description'];
                        $points = $_POST['points'];
                        $conn->beginTransaction();              
                        
                        $topicResult = getTopicByName($conn, $topicName);
                        $topicID = $topicResult->TopicID;
                        updateQuiz($conn, $quizID, $topicID, $week);
                        updateMatchingSection($conn, $quizID, $description, $points);
                        
                        $conn->commit();                    
                    } catch(Exception $e) {
                        debug_err($pageName, $e);
                        $conn->rollback();
                    } 
                }
                else if($metadataupdate == -1){  
                    $quizID = $_POST['quizid'];
                    deleteQuiz($conn, $quizID);
                    header('Location: matching.php');
                }            
            }
            if(isset($_POST['bucketupdate'])){                          
                $bucketupdate = $_POST['bucketupdate'];
                if($bucketupdate == 1){
                    $quizID = $_POST['quizid'];
                    $question = $_POST['question'];                    
                    createMatchingQuestion($conn, $quizID, $question);
                } else if($bucketupdate == 0){
                    $matchingID = $_POST['matchingid'];
                    $question = $_POST['question'];
                    updateMatchingQuestion($conn, $matchingID, $question);
                }else if($bucketupdate == -1){
                    $matchingID = $_POST['matchingid'];
                    deleteMatchingQuestion($conn, $matchingID); 
                }            
            }
            if(isset($_POST['itemupdate'])){                          
                $itemupdate = $_POST['itemupdate'];
                if($itemupdate == 1){
                    $matchingID = $_POST['matchingid'];
                    $content = $_POST['content'];
                    createMatchingOption($conn, $matchingID, $content);
                } else if($itemupdate == 0){
                    $optionID = $_POST['optionid'];
                    $matchingID = $_POST['matchingid'];
                    $content = $_POST['content'];
                    updateMatchingOption($conn, $matchingID, $optionID, $content);
                }else if($itemupdate == -1){
                    $optionID = $_POST['optionid'];
                    deleteMatchingOption($conn, $optionID); 
                }            
            }
        }
    } catch(Exception $e) {
        debug_err($pageName, $e);
    }    
    
    try{
        if(isset($_GET['quizid'])){
            $quizID = $_GET['quizid'];
            $quizResult = getMatchingQuiz($conn, $quizID);
            $topicResult = getTopics($conn);
            $materialRes = getLearningMaterial($conn, $quizID);
            $matchingQuesResult = getMatchingQuestions($conn, $quizID);
            $bucketResult = getMatchingBuckets($conn, $quizID);
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
    <?php require_once('header-lib.php'); ?>
</head>

<body>

    <div id="wrapper">

        <?php require_once('navigation.php'); ?> 

        <div id="page-wrapper">
            <div class="row">
                <div class="col-lg-12">
                    <h1 class="page-header">Matching Quiz Editor</h1>
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
                            <form id="metadata-submission" method="post" action="<?php echo $phpself; ?>">
                                <!--if 0 bucketupdate; else if -1 delete;-->
                                <input type=hidden name="metadataupdate" id="metadataupdate" value="1" required></input>
                                <label for="QuizID" style="display:none">QuizID</label>
                                <input type="text" class="form-control" id="QuizID" name="quizid" style="display:none" value="<?php echo $quizResult->QuizID; ?>"></input>
                                <br>
                                <label for="Week">Week</label>
                                <input type="text" class="form-control" id="Week" name="week" placeholder="Input Week Number" value="<?php echo $quizResult->Week; ?>"></input> 
                                <br>  
                                <label for='TopicName'>TopicName</label>
                                <select class="form-control" id="TopicName" form="metadata-submission" name="topicname" required>
                                  <?php for($j=0; $j<count($topicResult); $j++) {?>                  
                                    <option value='<?php echo $topicResult[$j]->TopicName ?>' <?php if($topicResult[$j]->TopicName==$quizResult->TopicName) echo 'selected' ?> ><?php echo $topicResult[$j]->TopicName ?></option>
                                  <?php } ?>
                                </select>
                                <br>
                                <label for="Description">Description</label>
                                <input type="text" class="form-control" id="Description" name="description"  placeholder="Input Description" value="<?php echo $quizResult->Description; ?>" required></input>
                                <br>
                                <label for="Points">Points</label>
                                <input type="text" class="form-control" id="Points" name="points" placeholder="Input Points" value="<?php echo $quizResult->Points; ?>" required></input>
                                <br>
                                <label for="MultipleChoice">MultipleChoice</label>
                                <input type="hidden" class="form-control" id="MultipleChoice" name="multiplechoice" value="0"></input>
                                <input type="checkbox" class="form-control" id="MultipleChoice" name="multiplechoice" value="1" <?php if(getMaxMatchingOptionNum($conn, $quizID) > 1) echo 'checked';?> disabled></input>
                                <label for="Questions">Questions</label>
                                <input type="text" class="form-control" id="Questions" name="questions" value="<?php echo $quizResult->Questions; ?>" disabled></input>
                                <br>
                            </form>
                            <!--edit metadata-->
                            <span class="glyphicon glyphicon-remove pull-right" id="metadata-remove" aria-hidden="true"></span><span class="pull-right" aria-hidden="true">&nbsp;</span><span class="glyphicon glyphicon-floppy-saved pull-right" id="metadata-save" aria-hidden="true"></span>    
                        </div>                            
                        <!-- /.panel-body -->
                    </div>
                    <!-- /.panel -->
                                                       
                    <?php require_once('learning-material-editor-iframe.php'); ?>
                    
                    <!-- Options -->
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            Questions and Options<br>
                            <button type="button" class="btn btn-info bucket-plus" data-toggle="modal" data-target="#bucket-dialog">Add Bucket</button>
                            <button type="button" class="btn btn-info bucket-plus" data-toggle="modal" data-target="#item-dialog">Add Item</button>
                        </div>
                        <!-- /.panel-heading -->
                        <div class="panel-body">
                            <div class="dataTable_wrapper">                            
                                <table class="table table-striped table-bordered table-hover" id="datatables">
                                    <thead>
                                        <tr>
                                        <th style="display:none"><?php echo $matchingQuesVisualName[0]; ?></th>
                                        <th><?php echo $matchingQuesVisualName[1]; ?></th>
                                        <th style="display:none"><?php echo $matchingQuesVisualName[2]; ?></th>
                                        <th><?php echo $matchingQuesVisualName[3]; ?></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php for($i=0; $i<count($matchingQuesResult); $i++) {?>
                                        <tr class="<?php if($i % 2 == 0){echo "odd";} else {echo "even";} ?>">
                                            <td style="display:none"><?php echo $matchingQuesResult[$i]->$matchingQuesColName[0]; ?></td>
                                            <td><?php echo $matchingQuesResult[$i]->$matchingQuesColName[1] ?>
                                                <span class="glyphicon glyphicon-remove pull-right bucket-remove" aria-hidden="true"></span>
                                                <span class="pull-right" aria-hidden="true">&nbsp;</span>
                                                <span class="glyphicon glyphicon-edit pull-right bucket-edit" data-toggle="modal" data-target="#bucket-dialog" aria-hidden="true"></span>
                                            </td>
                                            <td style="display:none"><?php echo $matchingQuesResult[$i]->$matchingQuesColName[2]; ?></td>
                                            <td>
                                            <?php if (strlen($matchingQuesResult[$i]->$matchingQuesColName[3])>0) {
                                                echo $matchingQuesResult[$i]->$matchingQuesColName[3]; ?>
                                                <span class="glyphicon glyphicon-remove pull-right item-remove" aria-hidden="true"></span>
                                                <span class="pull-right" aria-hidden="true">&nbsp;</span>
                                                <span class="glyphicon glyphicon-edit pull-right item-edit" data-toggle="modal" data-target="#item-dialog" aria-hidden="true"></span>
                                            <?php } else { ?>
                                            <div class="alert alert-danger">
                                                <p><strong>Reminder</strong> : You have not added any item for this bucket!
                                            </div>    
                                            <?php } ?>
                                            </td>          
                                        </tr>
                                    <?php } ?>
                                    </tbody>
                                </table>
                            </div>
                            <!-- /.table-responsive -->
                            <div class="well row">
                                <h4>Matching Quiz Overview Notification</h4>
                                <div class="alert alert-info">
                                    <p>View matching questions in this quiz by filtering or searching. You can create/update/delete any bucket/item.</p>
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
    <!-- Bucket Modal -->
      <div class="modal fade" id="bucket-dialog" role="dialog">
        <div class="modal-dialog">        
          <!-- Modal content-->
          <div class="modal-content">
            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal">&times;</button>
              <h4 class="modal-title" id="bucket-dialogtitle">Edit Bucket</h4>
            </div>
            <div class="modal-body">
            <form id="bucket-submission" method="post" action="<?php echo $phpself; ?>">
                <input type=hidden name="bucketupdate" id="bucketupdate" value="1" required></input>
                <label for="MatchingID" style="display:none">MatchingID</label>
                <input type="text" class="form-control bucket-dialoginput" id="MatchingID" name="matchingid" style="display:none"></input>
                <label for="Question">Terminology/Bucket</label>
                <input type="text" class="form-control bucket-dialoginput" id="Question" name="question" value="" required></input>
                <br>
                <label for="QuizID" style="display:none">QuizID</label>
                <input type="text" class="form-control" id="QuizID" name="quizid" style="display:none" value="<?php echo $quizID; ?>" required></input>
                <br>
            </form>
            </div>
            <div class="modal-footer">            
              <button type="button" id="bucket-save" class="btn btn-default">Save</button>
              <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
          </div>          
        </div>
      </div>
    <!-- Item Modal -->
      <div class="modal fade" id="item-dialog" role="dialog">
        <div class="modal-dialog">        
          <!-- Modal content-->
          <div class="modal-content">
            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal">&times;</button>
              <h4 class="modal-title" id="item-dialogtitle">Edit Item</h4>
            </div>
            <div class="modal-body">
            <form id="item-submission" method="post" action="<?php echo $phpself; ?>">
                <input type=hidden name="itemupdate" id="itemupdate" value="1" required></input>                
                <label for='Bucket'>Terminology/Bucket</label>
                <select class="form-control item-dialoginput" id="Bucket" form="item-submission" name="matchingid" required>
                    <option value="" disabled selected>Select Bucket</option>
                  <?php for($j=0; $j<count($bucketResult); $j++) {?>                  
                    <option value='<?php echo $bucketResult[$j]->MatchingID ?>'><?php echo $bucketResult[$j]->Question ?></option>
                  <?php } ?>
                </select>
                <br>
                <label for="OptionID" style="display:none">OptionID</label>
                <input type="text" class="form-control item-dialoginput" id="OptionID" name="optionid" style="display:none"></input>
                <label for="Content">Explanation/Item</label>
                <input type="text" class="form-control item-dialoginput" id="Content" name="content" value="" required></input>
                <br>
            </form>
            </div>
            <div class="modal-footer">            
              <button type="button" id="item-save" class="btn btn-default">Save</button>
              <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
          </div>          
        </div>
      </div>  
      
    <!-- SB Admin Library -->  
    <?php require_once('sb-admin-lib.php'); ?>   
    <!-- Page-Level Scripts -->
    <script>
    //DO NOT put them in $(document).ready() since the table has multi pages    
    $('#metadata-save').on('click', function (){
        $('#metadataupdate').val(0);
        $('#metadata-submission').validate({
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
        $('#metadata-submission').submit();
    });  
    $('#metadata-remove').on('click', function (){
        if (confirm('[WARNING] Are you sure to remove this quiz? If you remove one quiz. All the questions and submission of this quiz will also get deleted (not recoverable). It includes learning material, questions and options, their submissions and your grading/feedback, not only the quiz itself.')) {
            $('#metadataupdate').val(-1);
            $('#metadata-submission').submit();
        }                            
    });
    $('.bucket-plus').on('click', function (){
        $('#bucket-dialogtitle').text("Add Bucket");
        $('#bucketupdate').val(1);
        for(i=0;i<$('.bucket-dialoginput').length;i++){                
            $('.bucket-dialoginput').eq(i).val('');
        }   
    });    
    $('.bucket-edit').on('click', function (){
        $('#bucket-dialogtitle').text("Edit Bucket");
        $('#bucketupdate').val(0);
        for(i=0;i<$('.bucket-dialoginput').length;i++){                
            $('.bucket-dialoginput').eq(i).val($(this).parent().parent().children('td').eq(i).text().trim());
        }                          
    });
    $('.bucket-remove').on('click', function (){
        $('#bucketupdate').val(-1);
        for(i=0;i<$('.bucket-dialoginput').length;i++){                
            $('.bucket-dialoginput').eq(i).val($(this).parent().parent().children('td').eq(i).text().trim());
        }
        $('#bucket-submission').submit();                           
    });
    $('#bucket-save').on('click', function (){
        $('#bucket-submission').validate();   
        $('#bucket-submission').submit();
    }); 
     $('.item-plus').on('click', function (){
        $('#item-dialogtitle').text("Add Item");
        $('#itemupdate').val(1);
        for(i=0;i<$('.item-dialoginput').length;i++){                
            $('.item-dialoginput').eq(i).val('');
        }   
    });    
    $('.item-edit').on('click', function (){
        $('#item-dialogtitle').text("Edit Item");
        $('#itemupdate').val(0);
        var bucketText = $(this).parent().parent().children('td').eq(1).text().trim();
        var index = $('#Bucket option').filter(function () { return $(this).html() == bucketText; }).val();        
        $('.item-dialoginput').eq(0).val(index);
        for(i=1;i<$('.item-dialoginput').length;i++){                
            $('.item-dialoginput').eq(i).val($(this).parent().parent().children('td').eq(i+1).text().trim());
        }                          
    });
    $('.item-remove').on('click', function (){
        $('#itemupdate').val(-1);
        for(i=1;i<$('.item-dialoginput').length;i++){                
            $('.item-dialoginput').eq(i).val($(this).parent().parent().children('td').eq(i+1).text().trim());
        }
        $('#item-submission').submit();                           
    });
    $('#item-save').on('click', function (){
        $('#item-submission').validate();   
        $('#item-submission').submit();
    });
   
    $(document).ready(function() {
        var table = $('#datatables').DataTable({
            responsive: true,
            //rows group for Question
            rowsGroup: [1],
            "pageLength":100,
            "aoColumnDefs": [
              { "bSearchable": false, "aTargets": [ 0 ] }
            ]
        })      
    });    
    </script>    
</body>

</html>
