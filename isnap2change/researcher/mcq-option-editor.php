<?php
    session_start();
    require_once("../mysql-lib.php");
    require_once("../debug.php");
    require_once("/researcher-validation.php");
    $pageName = "mcq-option-editor";
    $columnName = array('OptionID','Content','Explanation','Edit');
    
    try{
        $conn = db_connect();        
        if($_SERVER["REQUEST_METHOD"] == "POST"){
            if(isset($_POST['metadataupdate'])){                          
                $metadataupdate = $_POST['metadataupdate'];
                if($metadataupdate == 0){
                    $mcqID = $_POST['mcqid'];
                    $correctChoice = $_POST['correctchoice'];
                    $question = $_POST['question'];
                    updateMCQQuestion($conn, $mcqID, $correctChoice, $question);
                }
                else if($metadataupdate == -1){
                    $mcqID = $_POST['mcqid'];                    
                    deleteMCQQuestion($conn, $mcqID); 
                }             
            }            
            if(isset($_POST['update'])){                          
                $update = $_POST['update'];
                if($update == 1){
                    $mcqID = $_POST['mcqid'];
                    $content = $_POST['content'];
                    $explanation = $_POST['explanation'];
                    createOption($conn, $mcqID, $content, $explanation);
                }
                else if($update == 0){
                    $optionID = $_POST['optionid'];
                    $content = $_POST['content'];
                    $explanation = $_POST['explanation'];
                    updateOption($conn, $optionID, $content, $explanation);
                }
                else if($update == -1){  
                    $optionID = $_POST['optionid'];
                    deleteOption($conn, $optionID); 
                }             
            }
        }      
    } catch(Exception $e) {
        debug_err($pageName, $e);
    }
    
    try{
        if(isset($_GET['quizid']) && isset($_GET['mcqid'])){
            $quizID = $_GET['quizid'];
            $mcqID = $_GET['mcqid'];
            $mcqQuesResult = getMCQQuestion($conn, $mcqID); 
            $optionResult = getOptions($conn, $mcqID);
            $phpself = $pageName.'.php?quizid='.$quizID.'&mcqid='.$mcqID;
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
                    <h1 class="page-header">Multiple Choice Question Editor                    
                    <button type="button" class="btn btn-lg btn-info pull-right" onclick="location.href='<?php echo "mcq-editor.php?quizid=".$quizID; ?>'">GO BACK</button>
                    </h1>
                </div>
                <!-- /.col-lg-12 -->
            </div>
            <!-- /.row -->
            <div class="row">
                <div class="col-lg-12">
                    <!-- MetaData -->
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            Multiple Choice Question MetaData
                        </div>
                        <!-- /.panel-heading -->
                        <div class="panel-body">
                            <form id="metadatasubmission" method="post" action="<?php echo $phpself; ?>">
                                <!--if 0 update; else if -1 delete;-->
                                <input type=hidden name="metadataupdate" id="metadataupdate" value="1" required></input>
                                <label for="MCQID" style="display:none">MCQID</label>
                                <input type="text" class="form-control" id="MCQID" name="mcqid" style="display:none" value="<?php echo $mcqQuesResult->MCQID; ?>" required></input>
                                <br>
                                <label for="Question">Question</label>
                                <input type="text" class="form-control" id="Question" name="question" value="<?php echo $mcqQuesResult->Question; ?>" required></input>
                                <br>
                                <label for="CorrectChoice">CorrectChoice</label>
                                <select class="form-control" id="CorrectChoice" form="metadatasubmission" name="correctchoice">                                
                                <option value='' selected>No Correct Choice Selected</option> 
                                <?php for($i=0; $i<count($optionResult); $i++) { ?>                              
                                <option value="<?php echo $optionResult[$i]->Content; ?>" <?php if($mcqQuesResult->CorrectChoice==$optionResult[$i]->Content) {echo 'selected';} ?>><?php echo $optionResult[$i]->Content; ?></option>
                                <?php } ?>
                                </select>
                                <br>
                            </form>
                            <!--No CorrectChoice Reminder-->
                            <div class="alert alert-danger" id="noCorrectChoiceReminder">
                                <p><strong>Reminder</strong> : You have not chosen any correct choice for this question!
                            </div>
                            <!--edit metadata-->
                            <span class="glyphicon glyphicon-remove pull-right" id="metadataremove" aria-hidden="true"></span><span class="pull-right" aria-hidden="true">&nbsp;</span><span class="glyphicon glyphicon-floppy-saved pull-right" id="metadataedit" aria-hidden="true"></span>    
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
                                        <?php for($i=0; $i<count($columnName); $i++){ ?>
                                            <th <?php if ($i==0){ echo 'style="display:none"';} ?>><?php echo $columnName[$i]; ?></th>
                                        <?php }?>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    <?php for($i=0; $i<count($optionResult); $i++) {?>
                                        <tr class="<?php if($i % 2 == 0){echo "odd";} else {echo "even";} ?>">
                                            <?php for($j=0; $j<count($columnName); $j++){ ?>
                                                <td <?php if ($j==0){ echo 'style="display:none"';} ?>>
                                                    <?php 
                                                    if($j!=count($columnName)-1) 
                                                        echo $optionResult[$i]->$columnName[$j]; 
                                                    else { ?>
                                                        <span class="glyphicon glyphicon-remove pull-right" aria-hidden="true"></span>
                                                        <span class="pull-right" aria-hidden="true">&nbsp;</span>
                                                        <span class="glyphicon glyphicon-edit pull-right" data-toggle="modal" data-target="#dialog" aria-hidden="true"></span>
                                                    <?php } ?>
                                                </td>
                                            <?php } ?>                                            
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
              <h4 class="modal-title" id="dialogTitle">Edit Option</h4>
            </div>
            <div class="modal-body">
            <form id="submission" method="post" action="<?php echo $phpself; ?>">
                <!--if 1, insert; else if -1 delete;-->
                <input type=hidden name="update" id="update" value="1" required></input>
                <label for="OptionID" style="display:none">OptionID</label>                
                <input type="text" class="form-control dialoginput" id="OptionID" name="optionid" style="display:none"></input>
                <label for="Content">Content</label>
                <input type="text" class="form-control dialoginput" id="Content" name="content"  placeholder="Input Content" required></input> 
                <br>  
                <label for="Explanation">Explanation</label>
                <input type="text" class="form-control dialoginput" id="Explanation" name="explanation"  placeholder="Input Explanation" required></input> 
                <br>
                <label for="MCQID" style="display:none">MCQID</label>
                <input type="text" class="form-control dialoginput" id="MCQID" name="mcqid" style="display:none" value="<?php echo $mcqQuesResult->MCQID; ?>" required></input>
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
        $('#dialogTitle').text("Add Option");
        $('#update').val(1);
        for(i=0;i<$('.dialoginput').length-1;i++){                
            $('.dialoginput').eq(i).val('');
        }   
    });     
    $('td > .glyphicon-edit').on('click', function (){
        $('#dialogTitle').text("Edit Option");
        $('#update').val(0);
        for(i=0;i<$('.dialoginput').length-1;i++){                
            $('.dialoginput').eq(i).val($(this).parent().parent().children('td').eq(i).text().trim());
        }   
    });     
    $('td > .glyphicon-remove').on('click', function (){        
        $('#update').val(-1);
        for(i=0;i<$('.dialoginput').length-1;i++){                
            $('.dialoginput').eq(i).val($(this).parent().parent().children('td').eq(i).text().trim());
        }
        $('#submission').submit();                   
    });
    $('#btnSave').on('click', function (){
        $('#submission').validate();
        for(i=0;i<$('.dialoginput').length;i++){                
            console.log($('.dialoginput').eq(i).val());
        }        
        $('#submission').submit();
    });
    
    //include html
    w3IncludeHTML();   
    $(document).ready(function() {
        var table = $('#datatables').DataTable({
                responsive: true,
                "order": [[ 1, "asc" ]],
                "pageLength":10,
                "aoColumnDefs": [
                  { "bSearchable": false, "aTargets": [ 0 ] }
                ]
        })
        $('#metadataedit').on('click', function (){
            $('#metadataupdate').val(0);
            $('#metadatasubmission').validate();   
            $('#metadatasubmission').submit();
        }); 
        $('#metadataremove').on('click', function (){
            $('#metadataupdate').val(-1);   
            $('#metadatasubmission').submit();
        });
        showNoCorrectChoiceReminder();
        $( "#CorrectChoice" ).change(function() {
           showNoCorrectChoiceReminder();
        });
    });     
    
    function showNoCorrectChoiceReminder(){
        if($('#CorrectChoice').val()==''){
            $('#noCorrectChoiceReminder').show();
        } else {
            $('#noCorrectChoiceReminder').hide(); 
        }
    }
    
    </script>
</body>

</html>
