<!--Learning Material-->
<div class="panel panel-default">
    <div class="panel-heading">
        Learning Material Editor
    </div>
    <!-- /.panel-heading -->
    <div class="panel-body">
        <div class="heading" style="color: black; max-height:10vh; text-align:center; border-bottom: 1px solid #eee;">
            <!-- Topic -->
            <h1 style='padding: 0px;'>
                <i>    <?php echo $materialRes->TopicName; ?> </i>
            </h1>
        </div>
        <iframe id="learning-material-editor" src="learning-material-editor.php?quizID=<?php echo $quizID; ?>"
                scrolling="no" frameborder="0" allowtransparency="true" allowfullscreen="true"
                name="Learning Material Editor" title="Learning Material Editor"
                style="width: 100%; height:100%;"></iframe>
    </div>
    <!-- /.panel-body -->
</div>
<!-- /.panel -->