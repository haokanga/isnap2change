<?php



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
        .setting-content {
            max-width: 1000px;
            margin: 30px auto;
            text-align: center;
        }
        .setting-logo {
            width: 128px;
            height: 128px;
            margin: 0 auto 20px;
            background-size: 100% 100%;
            background-image: url("./img/settings_icon.png");
        }
        .setting-label {
            font-size: 28px;
        }
        .setting-prompt {
            font-size: 18px;
            font-family: Maitree, serif;
        }

        .account-info {
            max-width: 700px;
            margin: 0 auto;
        }
        .account-title {
            margin: 30px 0;
            font-size: 20px;

        }
        .account-item {
            border-bottom: 2px solid #fcec1b;
            padding: 20px 0 10px;
            overflow: hidden;
        }
        .account-field-name {
            width: 160px;
            float: left;
            color: #fcec1b;
            font-size: 18px;
        }
        .account-field {
            width: 400px;
            float: left;
            background-color: #000;
            color: #fff;
            border: 0;
            font-size: 18px;
            font-family: Maitree, serif;
            border-bottom: 1px solid #fff;
        }
        .account-field:focus {
            outline: 0;
        }
        .account-submit {
            display: block;
            width: 80px;
            text-align: center;
            margin: 20px auto;
            color: #fcec1b;
            height: 40px;
            line-height: 40px;

        }

        .addition-info {
            max-width: 600px;
            margin: 40px auto;
        }
        .addition-title {
            text-align: center;
            margin: 0 auto 20px;
            font-size: 18px;
        }
        .addition-field {
            display: block;
            width: 400px;
            margin: 0 auto;
            height: 350px;
            font-size: 18px;
        }
        .addition-footer {
            padding: 20px;
        }
        .addition-submit {
            width: 100px;
            height: 40px;
            margin: 0 auto;
            cursor: pointer;
            background-size: 100% 100%;
            background-color: #000;
            border: 0;
            background-image: url("./img/send_icon.png");
        }
        .addition-submit:focus {
            outline: 0;
        }
    </style>
</head>
<body>

<div class="page-wrapper">
    <div class="header-wrapper">
        <div class="header">
            <a href="#" class="header-back-link"></a>
            <a class="home-link" href="#">SNAP</a>
            <ul class="nav-list">
                <li class="nav-item"><a  class="nav-link" href="http://taobao.com">GAME HOME</a></li>
                <li class="nav-item"><a  class="nav-link" href="http://taobao.com">Snap Facts</a></li>
                <li class="nav-item"><a  class="nav-link" href="http://taobao.com">Resources</a></li>
            </ul>
            <div class="settings">
                <div class="setting-icon dropdown">
                    <ul class="dropdown-menu">
                        <li class="dropdown-item"><a href="#">Logout</a></li>
                    </ul>
                </div>
                <a href="#" class="setting-text">NoButSrsly</a>
            </div>
        </div>
    </div>


    <div class="content-wrapper">
        <div class="setting-content">
            <div class="setting-header">
                <div class="setting-logo"></div>
                <div class="setting-label">Settings</div>
                <div class="setting-prompt">Change your account settings</div>
            </div>
            <div class="account-info">
                <h2 class="account-title">Account Information</h2>
                <form class="account-form">
                    <div class="account-item">
                        <label>
                            <span class="account-field-name">Name</span>
                            <input type="text" name="name" class="account-field">
                        </label>
                    </div>
                    <div class="account-item">
                        <label>
                            <span class="account-field-name">Username</span>
                            <input type="text" name="username" class="account-field">
                        </label>
                    </div>
                    <div class="account-item">
                        <label>
                            <span class="account-field-name">Password</span>
                            <input type="password" name="password" class="account-field">
                        </label>
                    </div>
                    <div class="account-item">
                        <label>
                            <span class="account-field-name">rePassword</span>
                            <input type="password" name="repassword" class="account-field">
                        </label>
                    </div>
                    <div class="account-item">
                        <label>
                            <span class="account-field-name">Email</span>
                            <input type="text" name="email" class="account-field">
                        </label>
                    </div>
                    <div class="account-operation">
                        <button type="submit" class="account-submit">Update</button>
                    </div>
                </form>
            </div>

            <div class="addition-info">
                <div class="addition-title">Any issues of questions? Please send them through and we will get back to you quickly.</div>
                <textarea cols="30" rows="10" class="addition-field"></textarea>
                <div class="addition-footer">
                    <button type="submit" class="addition-submit"></button>
                </div>
            </div>
        </div>

    </div>

    <ul class="sitenav">
        <li class="sitenav-item sitenav-game-home"><a href="#"></a></li>
        <li class="sitenav-item sitenav-achievement"><a href="#"></a></li>
        <li class="sitenav-item sitenav-progress"><a href="#"></a></li>
        <li class="sitenav-item sitenav-reading-material"><a href="#"></a></li>
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
    var AccountCtrl = {
        init: function (opt) {
            opt = opt || {
                    onSubmit: $.noop
                }
            this.onSubmit = opt.onSubmit
            this.cacheElements()
            this.addListeners()
        },
        cacheElements: function () {
            this.$infoForm = $('.account-form')
        },
        addListeners: function () {
            var that = this

            this.$infoForm.on('submit', function (e) {
                e.preventDefault()
                var data = that.getInfoData()
                that.onSubmit(data)
            })
        },
        getInfoData: function () {
            var infoArray = this.$infoForm.serializeArray()
            var infoData = {}
            infoArray.forEach(function (item) {
                infoData[item.name] = item.value
            })
            return infoData
        }
    }
    AccountCtrl.init({
        onSubmit: function (data) {
            console.log(data)
        }
    })


    var $additionSubmit = $('.addition-submit')
    var $additionField = $('.addition-field')
    $additionSubmit.on('click', function (e) {
        e.preventDefault()
        console.log($additionField.val())
    })
</script>
</body>
</html>



