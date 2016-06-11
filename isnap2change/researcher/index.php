<?php
	session_start();
    require_once("../mysql-lib.php");
    require_once("../debug.php");
    require_once("/researcher-validation.php");
    $conn = db_connect();
    
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

        <?php require_once('/navigation.php'); ?> 

        <div id="page-wrapper">
            <div class="row">
                <div class="col-lg-12">
                    <h1 class="page-header">iSNAP2Change Dashboard</h1>
                </div>
                <!-- /.col-lg-12 -->
            </div>
            <!-- /.row -->
            <div class="row">
                <div class="col-lg-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            Dashboard <span class="glyphicon glyphicon-plus pull-right" data-toggle="modal" data-target="#dialog"></span>
                        </div>
                        <!-- /.panel-heading -->
                        <div class="panel-body">
                             <div class="row">
                                <div class="col-lg-3 col-md-6">
                                    <div class="panel panel-primary">
                                        <div class="panel-heading">
                                            <div class="row">
                                                <div class="col-xs-3">
                                                    <i class="fa fa-comments fa-5x"></i>
                                                </div>
                                                <div class="col-xs-9 text-right">
                                                    <div class="huge">26</div>
                                                    <div>New Submissions!</div>
                                                </div>
                                            </div>
                                        </div>
                                        <a href="#">
                                            <div class="panel-footer">
                                                <span class="pull-left">View Details</span>
                                                <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                                                <div class="clearfix"></div>
                                            </div>
                                        </a>
                                    </div>
                                </div>
                                <div class="col-lg-3 col-md-6">
                                    <div class="panel panel-green">
                                        <div class="panel-heading">
                                            <div class="row">
                                                <div class="col-xs-3">
                                                    <i class="fa fa-tasks fa-5x"></i>
                                                </div>
                                                <div class="col-xs-9 text-right">
                                                    <div class="huge">12</div>
                                                    <div>New Public Questions!</div>
                                                </div>
                                            </div>
                                        </div>
                                        <a href="#">
                                            <div class="panel-footer">
                                                <span class="pull-left">View Details</span>
                                                <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                                                <div class="clearfix"></div>
                                            </div>
                                        </a>
                                    </div>
                                </div>                    
                            </div>
                            <div class="row">                   
                                <div class="col-lg-3 col-md-6">
                                    <div class="panel panel-yellow">
                                        <div class="panel-heading">
                                            <div class="row">
                                                <div class="col-xs-3">
                                                    <i class="fa fa-shopping-cart fa-5x"></i>
                                                </div>
                                                <div class="col-xs-9 text-right">
                                                    <div class="huge">124</div>
                                                    <div>New Student Questions!</div>
                                                </div>
                                            </div>
                                        </div>
                                        <a href="#">
                                            <div class="panel-footer">
                                                <span class="pull-left">View Details</span>
                                                <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                                                <div class="clearfix"></div>
                                            </div>
                                        </a>
                                    </div>
                                </div>
                                <div class="col-lg-3 col-md-6">
                                    <div class="panel panel-red">
                                        <div class="panel-heading">
                                            <div class="row">
                                                <div class="col-xs-3">
                                                    <i class="fa fa-support fa-5x"></i>
                                                </div>
                                                <div class="col-xs-9 text-right">
                                                    <div class="huge">13</div>
                                                    <div>New Tech Report!</div>
                                                </div>
                                            </div>
                                        </div>
                                        <a href="#">
                                            <div class="panel-footer">
                                                <span class="pull-left">View Details</span>
                                                <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                                                <div class="clearfix"></div>
                                            </div>
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <div class="well row">
                                <h4>Dashboard Notification</h4>
                                <div class="alert alert-info">
                                    <p>Grade student submission, answer public or student questions/comments, view tech bug report to contact with developers.</p>
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
      
    <!-- SB Admin Library -->  
    <?php include('/sb-admin-lib.php'); ?>
    
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
            $('.dialoginput').eq(i).val($(this).parent().parent().children('td').eq(i).text().trim());
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
        if (confirm('[WARNING] Are you sure to remove this class? All the student data in this class will also get deleted (not recoverable). It includes student information, their submissions of every task and your grading/feedback, not only the class itself.')) {
            $('#update').val(-1);
            //fill required input
            $('.dialoginput').eq(0).prop('disabled',false);
            for(i=0;i<$('.dialoginput').length;i++){                
                $('.dialoginput').eq(i).val($(this).parent().parent().children('td').eq(i).text().trim());
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
   
    $(document).ready(function() {
        var table = $('#datatables').DataTable({
                responsive: true,
                "initComplete": function(settings, json) {
                    
                    $('.input-sm').eq(1).val($("#keyword").val().trim());                    
                },
                "aoColumnDefs": [
                  { "bSearchable": false, "aTargets": [ 0 ] }
                ]
        })
        //search keyword, exact match
        table.search(
            $("#keyword").val().trim(), true, false, true
        ).draw();     
    });        
    </script>
</body>

</html>
