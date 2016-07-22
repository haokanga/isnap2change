<?php

    //check login status
    require_once('student-validation.php');

    require_once("../mysql-lib.php");
    require_once("../debug.php");

    $pageName = "poster";

    //check whether a request is GET or POST
    if($_SERVER["REQUEST_METHOD"] == "GET"){
        if(isset($_GET["quiz_id"])){
            $quizID = $_GET["quiz_id"];
        } else{

        }
    } else{

    }

    $conn = null;

    try{
        $conn = db_connect();

        $week = getWeekByQuiz($conn, $quizID);

        //check whether the week is locked or not
        if ($week > getStudentWeek($conn, $studentID)) {
            echo '<script>alert("This is a locked quiz!")</script>';
            echo '<script>window.location="game-home.php"</script>';
        }

        //check quiz status
        $status = getQuizStatus($conn, $quizID, $studentID);
        //if quiz is answered, saved poster will be read from database.
        if($status != "UNANSWERED"){
            $posterRes = getPosterRecord($conn, $quizID, $studentID);
        }
    }catch(Exception $e){
        if($conn != null){
            db_close($conn);
        }

        debug_err($e);
        //to do: handle sql error
        //...
        exit;
    }

    db_close($conn);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="initial-scale=1.0, width=device-width, user-scalable=no">
    <title>Poster</title>
    <link rel="stylesheet" href="./css/common.css">
    <link href='https://fonts.googleapis.com/css?family=Maitree|Lato:400,900' rel='stylesheet' type='text/css'>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
    <script src="http://zwibbler.com/zwibbler2.js"></script>
    <script src="./js/snap.js"></script>
    <style>
        .canvas-title {
            padding-top: 10px;
            text-align: center;
        }
        .canvas-prompt {
            width: 530px;
            margin: 10px auto 20px;
        }
        .canvas-container {
            width: 1000px;
            margin: 20px auto;
        }
        .canvas-menu {
            float: left;
            width: 100px;
        }
        .canvas-menu-disabled .canvas-menu-item {
            cursor: not-allowed;
        }
        .canvas-menu-item {
            width: 40px;
            height: 40px;
            float: left;
            margin: 0 5px 20px;
            cursor: pointer;
            background-size: 100% 100%;
        }
        .canvas-menu-item-selected {
            background-color: #3D3D3D;
        }
        .icon-select {
            background-image: url("./img/art-board/select_icon.png");
        }
        .icon-rectangle {
            background-image: url("./img/art-board/rectangle_icon.png");
        }
        .icon-circle {
            background-image: url("./img/art-board/circle_icon.png");
        }
        .icon-brush {
            background-image: url("./img/art-board/brush_icon.png");
        }
        .icon-line {
            background-image: url("./img/art-board/line_icon.png");
        }
        .icon-arrow {
            background-image: url("./img/art-board/arrow_icon.png");
        }
        .icon-text {
            background-image: url("./img/art-board/type_icon.png");
        }
        .icon-insert {
            background-image: url("./img/art-board/insert_icon.png");
        }
        .icon-copy {
            background-image: url("./img/art-board/copy_icon.png");
        }
        .icon-paste {
            background-image: url("./img/art-board/paste_icon.png");
        }
        .icon-undo {
            background-image: url("./img/art-board/undo_icon.png");
        }
        .icon-redo {
            background-image: url("./img/art-board/redo_icon.png");
        }
        .icon-save {
            background-image: url("./img/art-board/save_icon.png");
        }
        .canvas-content {
            float: left;
            width: 900px;
        }

        .canvas-footer {
            clear: all;
        }
    </style>
</head>
<body>

<div class="page-wrapper">
    <div class="header-wrapper">
        <div class="header">
            <a class="home-link">SNAP</a>

            <div class="settings">
                <div class="setting-icon dropdown">
                    <ul class="dropdown-menu">
                        <li class="dropdown-item"><a href="setting.php">Setting</a></li>
                        <li class="dropdown-item"><a href="logout.php">Logout</a></li>
                    </ul>
                </div>
                <a class="setting-text"><?php echo $studentUsername?></a>
            </div>
        </div>
    </div>

    <div class="content-wrapper">
        <div class="canvas-header">
            <h2 class="h2 canvas-title">Create a Future Board</h2>
            <div class="canvas-prompt p1">What would you linke to achieve this school term? Make board with pictures of what you would like to achieve and the people and things that inspire you and whtat you aspire to be. You can also put down things about yourself that you would like to improve on. If you would feel more comvortable using words or pictures that only you know what they mean, you can . After all, some goals are personal.</div>
        </div>
        <div class="canvas-container clearfix">
            <div class="canvas-menu">
                <ul class="canvas-menu-list clearfix">
                    <li class="canvas-menu-item icon-select" data-name="select" title="Pick tool"></li>
                    <li class="canvas-menu-item icon-rectangle" data-name="rectangle" title="Rectangle"></li>
                    <li class="canvas-menu-item icon-circle" data-name="circle" title="Circle"></li>
                    <li class="canvas-menu-item icon-brush" data-name="brush" title="Pencil"></li>
                    <li class="canvas-menu-item icon-line" data-name="line" title="Line"></li>
                    <li class="canvas-menu-item icon-arrow" data-name="arrow" title="Arrow"></li>
                    <li class="canvas-menu-item icon-text" data-name="text" title="Insert text"></li>
                    <li class="canvas-menu-item icon-insert" data-name="insert" title="Insert image"></li>
                    <li class="canvas-menu-item icon-copy" data-name="copy" title="Copy"></li>
                    <li class="canvas-menu-item icon-paste" data-name="paste" title="Paste"></li>
                    <li class="canvas-menu-item icon-undo" data-name="undo" title="Undo"></li>
                    <li class="canvas-menu-item icon-redo" data-name="redo" title="Redo"></li>
                    <li class="canvas-menu-item icon-save" data-name="save" title="Save"></li>
                </ul>
            </div>
            <div class="canvas-content">
                <div id="zwibbler1" style="width:900px;height:600px"></div>
            </div>
            <form style="display:none" method=post enctype="multipart/form-data" action="upload-handler.php">
                <input type="file" name="file" id="file-input" accept="image/*">
                <input type="hidden" name="studentID" value=<?php echo $studentID;?>>
            </form>
        </div>
        <div class="canvas-footer">
            <button class="question-submit" type="button"></button>
        </div>
    </div>

    <a href="weekly-task.php?week=<?php echo $week?>" class="cancel-task">
        <span class="cancel-icon"></span>
        <span class="cancel-label">Cancel Task</span>
    </a>
    
    <div class="footer-wrapper">
        <div class="footer">
            <div class="footer-content">
                <a href="#" class="footer-logo"></a>
                <ul class="footer-nav">
                    <li class="footer-nav-item"><a href="#">Any Legal Stuff</a></li>
                    <li class="footer-nav-item"><a href="#">Acknowledgements</a></li>
                </ul>
            </div>
        </div>
    </div>
</div>

<script>
    var ctx = Zwibbler.create("zwibbler1", {
        showToolbar: false,
        background: "white",
        persistent: false
    });
</script>
<script>
    function onMenuItemClick(type) {
        switch (type) {
            case "select":
                ctx.usePickTool();
                break;
            case "rectangle":
                ctx.useSquareTool();
                break;
            case "circle":
                ctx.useCircleTool();
                break;
            case "brush":
                ctx.setConfig("defaultBrushColour", "#000000");
                ctx.useBrushTool();
                break;
            case "line":
                ctx.useLineTool();
                break;
            case "arrow":
                ctx.useArrowTool();
                break;
            case "text":
                ctx.useTextTool();
                break;
            case "insert":
                $('#file-input').click();
                ctx.usePickTool();
                break;
            case "copy":
                ctx.copy();
                break;
            case "paste":
                ctx.paste();
                break;
            case "undo":
                ctx.undo();
                break;
            case "redo":
                ctx.redo();
                break;
            case "save":
                onSaveClick();
                break;
        }
    }

    var ToolCtrl = {
        cls: {
            disabled: 'canvas-menu-disabled',
            itemSelected: 'canvas-menu-item-selected'
        },
        init: function (opt) {
            this.opt = $.extend({
                onMenuItemClick: $.noop
            }, opt)
            this.cacheElements();
            this.addListeners();
        },
        cacheElements: function () {
            var $main = $('.canvas-menu');
            this.$main = $main;
            this.$menuList = $main.find('.canvas-menu-list');
            this.$menuItems = $main.find('.canvas-menu-item')
        },
        addListeners: function () {
            var that = this;
            this.$main.on('click', '.canvas-menu-item', function (e) {
                if (that.$main.hasClass(that.cls.disabled)) {
                    return
                }
                that.$menuItems.removeClass(that.cls.itemSelected);
                e.currentTarget.classList.add(that.cls.itemSelected);
                that.opt.onMenuItemClick(e.currentTarget.getAttribute('data-name'))
            })
        },
        disableMenu: function () {
            this.$main.addClass(this.cls.disabled)
        }
    };

    ToolCtrl.init({
        onMenuItemClick: onMenuItemClick
    });

<?php
        //if quiz is answered, saved poster will be loaded to canvas.
        if($status != "UNANSWERED") { ?>
            var saved = "zwibbler3.";
            saved = saved + '<?php echo $posterRes->ZwibblerDoc ?>';
            ctx.load("zwibbler3", saved);
<?php	} ?>

<?php
        //if quiz is submitted, canvas and submit button will be disabled.
        if($status == "UNGRADED" || $status == "GRADED") { ?>
            ToolCtrl.disableMenu();
            ctx.setConfig("readOnly", true);
            $(".icon-save").attr("disabled","disabled");
            $(".question-submit").attr("disabled","disabled");
<?php	} ?>

    $('#file-input').on("change", function (e) {
        var form = this.parentNode;
        upload(form);
        form.reset();
    });

    function onSaveClick() {
        var zwibblerDoc = ctx.save("zwibbler3");

        $.ajax({
            url: "poster-feedback.php",
            data: {
                student_id: <?php echo $studentID?>,
                quiz_id: <?php echo $quizID?>,
                action: "SAVE",
                zwibbler_doc: zwibblerDoc.substr(10)
            },
            type: "POST",
            dataType : "json"
        })

            .done(function(feedback) {
                parseFeedback(feedback);
            })

            .fail(function( xhr, status, errorThrown ) {
                alert( "Sorry, there was a problem!" );
                console.log( "Error: " + errorThrown );
                console.log( "Status: " + status );
                console.dir( xhr );
            });
    }

    function onSubmitClick() {
        var zwibblerDoc = ctx.save("zwibbler3");
        var dataUrl = ctx.save("png");

        $.ajax({
            url: "poster-feedback.php",
            data: {
                student_id: <?php echo $studentID?>,
                quiz_id: <?php echo $quizID?>,
                action: "SUBMIT",
                zwibbler_doc: zwibblerDoc.substr(10),
                data_url: dataUrl
            },
            type: "POST",
            dataType : "json"
        })

            .done(function(feedback) {
                parseFeedback(feedback);
            })

            .fail(function( xhr, status, errorThrown ) {
                alert( "Sorry, there was a problem!" );
                console.log( "Error: " + errorThrown );
                console.log( "Status: " + status );
                console.dir( xhr );
            });
    }

    function parseFeedback(feedback){
        if(feedback.message != "success"){
            alert(feedback.message + ". Please try again!");
            return;
        }

        if(feedback.action == "SAVE"){
            snap.alert({
                content: 'You have successfully saved the poster.',
                onClose: function () { }
            });
        }

        if(feedback.action == "SUBMIT") {
            snap.alert({
                content: 'You have successfully submitted the poster.',
                onClose: function () { }
            });
            $('.canvas-menu-item').removeClass('canvas-menu-item-selected');
            ToolCtrl.disableMenu();
            ctx.setConfig("readOnly", true);
            $(".icon-save").attr("disabled", "disabled");
            $(".question-submit").attr("disabled", "disabled");
        }
    }

    function uploadDone(status, result) {
        if (status === "ok") {
            if(result.message == "success"){
                var url = <?php getURL("/tmp_poster_img/") ?> + result.fileid;

                ctx.beginTransaction();
                var nodeId = ctx.createNode("ImageNode", {
                    url: url
                });
                ctx.translateNode(nodeId, 100, 100);
                ctx.commitTransaction();
            } else{
                alert(result.message);
            }
        }
    }

    function upload(form) {
        var progress = new ProgressNotification("Reading file");
        var xhr = new XMLHttpRequest();

        var fd = new FormData(form);

        xhr.upload.addEventListener("progress",
            function( e ) {
                progress.update( e.loaded / e.total );
            }, false
        );

        xhr.addEventListener("load",
            function( e ) {
                progress.done();
                uploadDone("ok", $.parseJSON(xhr.response));
            }, false
        );

        xhr.addEventListener("error",
            function( e ) {
                progress.error("Error");
                uploadDone( "error", null );
            }, false
        );

        xhr.addEventListener("abort",
            function( e ) {
                progress.error("Aborted");
                uploadDone( "aborted", null );
            }, false
        );

        xhr.open(form.method, form.action);
        xhr.send(fd);
    }

    // Display multiple upload progress notifications
    function ProgressNotification(name){
        this.name = name;
        ProgressNotification.all.push(this);
        this.div = $("<div>");
        $("#progress").append(this.div).show();
        this.update(0);
    }

    ProgressNotification.all = [];
    ProgressNotification.prototype = {
        update: function(percent) {
            this.div.text(this.name + "... " + Math.round(percent * 100) +
                "%");
        },

        error: function(message) {
            var self = this;
            var input = $("<input>").
            attr("type", "button").
            val("OK");

            input.click(function(e) {
                self.done();
            });

            this.div.html(this.name + "... " +  message);
            this.div.append(input);
        },

        done: function() {
            this.div.remove();
            var all = ProgressNotification.all;
            for(var i = 0; i < all.length; i++) {
                if (all[i] === this) {
                    all.splice(i, 1);
                    break;
                }
            }

            if (all.length === 0) {
                $("#progress").hide();
            }
        }
    };

    $('.question-submit').on('click', onSubmitClick)
</script>
</body>
</html>





