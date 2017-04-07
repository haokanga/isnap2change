<?php

    //check login status
    require_once('./student-validation.php');

    require_once("../mysql-lib.php");
    require_once("../debug.php");

    $conn = null;

    try{
        $conn = db_connect();

        $messages = getStudentQuestion($conn, $studentID);

        //get quiz viewed attribute
        $quizViewedAttrs = getQuizViewdAttr($conn, $studentID);

        //get student question viewed attribute
        $studentQuesViewedAttrs = getStudentQuesViewedAttr($conn, $studentID);
        
    }catch(Exception $e) {
        if($conn != null) {
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
    <title>Document</title>
    <link rel="stylesheet" href="./css/common.css">
    <link href='https://fonts.googleapis.com/css?family=Maitree|Lato:400,900' rel='stylesheet' type='text/css'>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
    <script src="./js/snap.js"></script>
    <style>
        .inbox-container {
            max-width: 1000px;
            margin: 20px auto 20px;
        }
        .inbox-header {
            text-align: center;
        }
        .inbox-icon {
            width: 100px;
            height: 80px;
            background-size: 100% 100%;
            background-image: url("./img/inbox_icon.png");
            margin: 0 auto 20px;
        }
        .inbox-content {
            margin-top: 20px;
        }
        .inbox-item {
            border-top: 2px solid rgb(160, 160, 160);
            padding: 10px 20px;
            overflow: hidden;
            font-size: 18px;
        }
        .inbox-item-unread {
            background-color: rgb(61, 61, 61);
            font-weight: bold;
        }

        .inbox-item span {
            float: left;
        }
        .inbox-date {
            width: 100px;
            margin-right: 20px;
            font-size: 18px;
        }
        .inbox-time {
            width: 80px;
            font-size: 18px;
            margin-right: 30px;
        }
        .inbox-subject {
            width:  330px;
            cursor: pointer;
        }
        .inbox-message {
            width: 330px;
            overflow: hidden;
            white-space: nowrap;
            text-overflow: ellipsis;
            color: rgb(160, 160, 160);
        }
        .inbox-item .inbox-delete {
            width: 16px;
            height: 16px;
            background-size: 100% 100%;
            background-image: url("./img/trash_icon.png");
            float: right;
            cursor: pointer;
        }

        .new-message-title {
            text-align: center;
            margin-top: 20px;
        }
        .new-message-icon {
            width: 120px;
            height: 120px;
            margin: 10px auto 40px;
            background-size: 100% 100%;
            background-image: url("./img/send_icon.png");
            cursor: pointer;
        }
        .detail-container {
            position: fixed;
            top: 0;
            bottom: 0;
            left: 0;
            right: 0;
            z-index: 99999;
            display: none;
        }
        .detail-mask {
            position: absolute;
            top: 0;
            bottom: 0;
            left: 0;
            right: 0;
            background-color: rgba(0, 0, 0, 0.6);
        }
        .detail-wrapper {
            width: 600px;
            height: 640px;
            position: absolute;
            top: 0;
            bottom: 0;
            left: 0;
            right: 0;
            margin: auto;
            border: 2px solid #ddd;
            padding: 20px;
            background-color: #000;
        }
        .detail-box {
            display: none;
            height: 560px;
        }
        .detail-box-active {
            display: block;
        }
        .snap-logo {
            width: 60px;
            height: 20px;
            background-size: 100% 100%;
            margin: 5px auto 10px;
            background-image: url("./img/snap_single_wordform_white.png");
        }
        .detail-prompt{
            text-align: center;
        }
        .detail-title {
            height: 30px;
            line-height: 30px;
            border-radius: 15px;
            margin: 20px 0;
            background-color: #dddede;
            color: #5b5b5b;
            padding: 0 10px;
        }
        .detail-content {
            margin-bottom: 20px;
            border-radius: 20px;
            background-color: #dddede;
            color: #5b5b5b;
            padding: 10px 20px;
            height: 200px;
            overflow-y: auto;
        }
        .detail-sub-prompt {
            margin-bottom: 20px;
        }
        .detail-close {
            display: block;
            width: 110px;
            height: 36px;
            line-height: 36px;
            background-color: #090909;
            border-radius: 10px;
            margin: 0 auto 0;
            position: absolute;
            left: 0;
            text-align: center;
            right: 0;
            bottom: 10px;
            cursor: pointer;
        }
    </style>
</head>
<body>

<div class="page-wrapper">
    <div class="header-wrapper">
        <div class="header">
            <a class="home-link">SNAP</a>
            <ul class="nav-list">
                <li class="nav-item"><a  class="nav-link" href="game-home.php">Snap Change</a></li>
                <li class="nav-item"><a  class="nav-link" href="snap-facts.php">Snap Facts</a></li>
                <li class="nav-item"><a  class="nav-link" href="#">Resources</a></li>
            </ul>
            <div class="settings">
                <div class="info-item info-notification">
                    <a class="info-icon" href="javascript:;"></a>
                    <?php           if (count($quizViewedAttrs) != 0) { ?>
                        <span class="info-number"><?php echo count($quizViewedAttrs) ?></span>
                    <?php           } ?>
                    <ul class="info-message-list">
                        <?php           for ($i = 0; $i < count($quizViewedAttrs); $i++) {
                            if ($quizViewedAttrs[$i]["extraQuiz"] == 0) {
                                $url = "weekly-task.php?week=".$quizViewedAttrs[$i]["week"];
                            } else {
                                $url = "extra-activities.php?week=".$quizViewedAttrs[$i]["week"];
                            }?>
                            <li class="info-message-item">
                                <a href="<?php echo $url ?>">
                                    <?php
                                    $message = "A ";

                                    switch($quizViewedAttrs[$i]["quizType"]) {
                                        case "Video":
                                            $message = $message."Video task";
                                            break;
                                        case "Image":
                                            $message = $message."Image task";
                                            break;
                                        case "SAQ":
                                            $message = $message."Short Answer Question task";
                                            break;
                                        case "Poster":
                                            $message = $message."Poster task";
                                            break;
                                    }

                                    $message = $message." in Week ".$quizViewedAttrs[$i]["week"]." has feedback for you.";
                                    echo $message;
                                    ?>
                                </a>
                            </li>
                        <?php           } ?>
                    </ul>
                </div>
                <div class="info-item info-message">
                    <a class="info-icon" href="javascript:;"></a>
                    <?php           if (count($studentQuesViewedAttrs) != 0) { ?>
                        <span class="info-number"><?php echo count($studentQuesViewedAttrs) ?></span>
                    <?php           } ?>
                    <ul class="info-message-list">
                        <li class="info-message-item">
                            <?php
                            for ($i = 0; $i < count($studentQuesViewedAttrs); $i++) { ?>
                                <a href="messages.php">
                                    You message about <?php echo $studentQuesViewedAttrs[$i]->Subject ?> has been replied.
                                </a>
                            <?php               } ?>
                        </li>
                    </ul>
                </div>


                <div class="setting-icon dropdown">
                    <ul class="dropdown-menu">
                        <li class="dropdown-item"><a href="settings.php">Settings</a></li>
                        <li class="dropdown-item"><a href="#">Log out</a></li>
                    </ul>
                </div>
                <a class="setting-text"><?php echo $_SESSION["studentUsername"]?></a>
            </div>
        </div>
    </div>
    <div class="content-wrapper">

        <div class="inbox-container">
            <div class="inbox-header">
                <div class="inbox-icon"></div>
                <h2 class="h2 inbox-title">Messages</h2>
                <div class="p1 inbox-prompt">View your messages and replies.</div>
            </div>
            <div class="inbox-content">
                <ul class="inbox-list">
<?php           for ($i = 0; $i < count($messages); $i++) {
                    if ($messages[$i]->Viewed == 0 ) {
                        $unreadClass = "inbox-item-unread";
                    } else {
                        $unreadClass = "";
                    }?>
                    <li class="inbox-item <?php echo $unreadClass ?>" data-id="<?php echo $messages[$i]->QuestionID ?>">
                        <span class="inbox-date"><?php echo date('j/n/Y', strtotime($messages[$i]->SendTime))?></span>
                        <span class="inbox-time"><?php echo date('g:i a', strtotime($messages[$i]->SendTime))?></span>
                        <span class="inbox-subject"><?php echo $messages[$i]->Subject ?></span>
                        <span class="inbox-message"><?php if ($messages[$i]->Replied == 1) echo "Replied"; else echo "Unreplied"  ?></span>
                        <span class="inbox-delete"></span>
                    </li>
<?php           } ?>
                </ul>

                <div class="new-message-container">
                    <h2 class="h2 new-message-title">New Message</h2>
                    <div class="new-message-icon"></div>
                </div>
            </div>

            <div class="detail-container">
                <div class="detail-mask"></div>
                <div class="detail-wrapper">

                    <div class="snap-logo"></div>
                    <div class="h4 detail-prompt">Send us a message.</div>
                    <div class="detail-box-list">
<?php           for ($i = 0; $i < count($messages); $i++) { ?>
                        <div class="detail-box" data-id="<?php echo $messages[$i]->QuestionID ?>">
                            <div class="p1 detail-title">Subject: <?php echo $messages[$i]->Subject ?></div>
                            <div class="detail-content p1">
                                <div class="detail-sub-prompt">What you sent to the researchers.</div>
                                <?php echo $messages[$i]->Content ?>
                            </div>
                            <div class="detail-content p1">
                                <div class="detail-sub-prompt">The researchers reply.</div>
                                <?php echo $messages[$i]->Feedback ?>
                            </div>
                        </div>
<?php           } ?>
                    </div>
                    <div class="detail-close">Close</div>
                </div>
            </div>

        </div>
    </div>

    <ul class="sitenav">
        <li class="sitenav-item sitenav-healthy-recipes"><a href="#"></a></li>
        <li class="sitenav-item sitenav-game-home"><a href="#"></a></li>
        <li class="sitenav-item sitenav-extra-activities"><a href="extra-activities.php"></a></li>
        <li class="sitenav-item sitenav-progress"><a href="progress.php"></a></li>
        <li class="sitenav-item sitenav-reading-material"><a href="reading-material.php"></a></li>
    </ul>

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
    function onItemDelete(itemId) {
        $.ajax({
            url: "student-question-feedback.php",
            data: {
                question_id: itemId,
                action: 'DELETE'
            },
            type: "POST",
            dataType : "json"
        })

            .done(function(feedback) {
                parseDeleteFeedback(feedback);
            })

            .fail(function( xhr, status, errorThrown ) {
                alert( "Sorry, there was a problem!" );
                console.log( "Error: " + errorThrown );
                console.log( "Status: " + status );
                console.dir( xhr );
            });
    }

    var $body = $('body');

    $body.on('click', '.inbox-subject', function (e) {
        var $item = $(e.currentTarget).closest('.inbox-item')
        DetailCtrl.show($item.data('id'))
    })
    $body.on('click', '.inbox-delete', function (e) {
        var $item = $(e.currentTarget).closest('.inbox-item')
        var itemId = $item.data('id')
        snap.confirm({
            title: 'Delete this message?',
            content: 'are you sure you want to delete this message? This action cannot be undo!',
            confirm: 'Delete',
            cancel: 'Cancel',
            onConfirm: function () {
                onItemDelete(itemId)
            }
        })
    })

    var DetailCtrl = {
        init: function () {
            this.cacheElements()
            this.addListeners()
        },

        cacheElements: function () {
            var $main = $('.detail-container')
            this.$main = $main
            this.$detailBoxes = $main.find('.detail-box')

        },
        addListeners: function () {
            var that = this
            this.$main.on('click', '.detail-close', function () {
                that.hide()
            })
        },
        hide: function () {
            this.$main.hide()
        },
        show: function (targetId) {
            this.$detailBoxes
                .removeClass('detail-box-active')
                .filter('[data-id=' + targetId+ ']')
                .addClass('detail-box-active')

            this.$main.show();

            $.ajax({
                url: "student-question-feedback.php",
                data: {
                    question_id: targetId,
                    action: 'VIEW'
                },
                type: "POST",
                dataType : "json"
            })

                .done(function(feedback) {
                    parseViewFeedback(feedback);
                })

                .fail(function( xhr, status, errorThrown ) {
                    alert( "Sorry, there was a problem!" );
                    console.log( "Error: " + errorThrown );
                    console.log( "Status: " + status );
                    console.dir( xhr );
                });
        }
    }
    DetailCtrl.init();

    function onMessageSend(data) {
        console.log(data);

        var sendTime = new Date();

        var dd = sendTime.getDate();
        var mm = sendTime.getMonth() + 1;
        var yyyy = sendTime.getFullYear();

        if(dd<10) {
            dd="0"+dd;
        }

        if(mm<10) {
            mm="0"+mm;
        }

        sendTime = yyyy+"-"+mm+"-"+dd+ " " +sendTime.getHours() + ":" + sendTime.getMinutes()+":" + sendTime.getSeconds();

        $.ajax({
            url: "student-question-feedback.php",
            data: {
                student_id: <?php echo $studentID?>,
                subject: data.title,
                content: data.content,
                send_time: sendTime,
                action: 'UPDATE'
            },
            type: "POST",
            dataType : "json"
        })

            .done(function(feedback) {
                parseUpdateFeedback(feedback);
            })

            .fail(function( xhr, status, errorThrown ) {
                alert( "Sorry, there was a problem!" );
                console.log( "Error: " + errorThrown );
                console.log( "Status: " + status );
                console.dir( xhr );
            });
    }

    function parseDeleteFeedback(feedback) {
        if(feedback.message != "success"){
            snap.alert({
                content: 'Sorry. Please try again',
                onClose: function () { }
            });
        } else if(feedback.message == "success"){
            snap.alert({
                content: 'You have deleted this message',
                onClose: function () {deleteMessage(feedback.questionID)}
            });
        }
    }

    function parseUpdateFeedback(feedback) {
        if(feedback.message != "success"){
            snap.alert({
                content: 'Sorry. Please try again',
                onClose: function () { }
            });
        } else if(feedback.message == "success"){
            snap.alert({
                content: 'You have sent a message to the researcher. Please wait for reply',
                onClose: function () {window.location.href = "messages.php";}
            });
        }
    }

    function parseViewFeedback(feedback) {
        if(feedback.message != "success"){
            snap.alert({
                content: 'Sorry. Please try again',
                onClose: function () { }
            });
        }
    }

    function deleteMessage(id) {
        $('.inbox-item').filter('[data-id=' + id + ']')
            .remove()
    }

    $body.on('click', '.new-message-icon', function () {
        snap.showSendDialog({
            onConfirm: onMessageSend
        })
    })
</script>
</body>
</html>

