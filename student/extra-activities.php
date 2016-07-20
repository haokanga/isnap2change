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

        .extra-activities-detail {
            padding-top: 20px;
        }
        .extra-activities-header {
            text-align: center;
        }
        .extra-activities-logo {
            width: 128px;
            height: 128px;
            margin: 0 auto;
            background-size: 100% 100%;
            background-image: url("./img/extra_activites_icon.png");
        }
        .extra-activities-title {
            font-size: 28px;
        }
        .extra-activities-intro {
            width: 300px;
            font-family: "Maitree", serif;
            font-size: 18px;
            margin: 0 auto;
        }

        .extra-activities-tab {
            padding-top: 40px;
            padding-bottom: 30px;
            text-align: center;
        }
        .extra-activities-tab-title {
            font-size: 14px;
            margin: 10px 0;
        }
        .extra-activities-tab-item {
            width: 48px;
            height: 48px;
            line-height: 44px;
            margin: 0 10px;
            display: inline-block;
            border: 2px solid;
            border-radius: 50%;
            color:  #fcee2d;
            font-size: 32px;
            cursor: pointer;
        }
        .extra-activities-tab-item-active {
            color: #fff;
            background-color: #fcee2d;
            border-color: #fcee2d;
        }
        .extra-activities-tab-item-disabled {
            text-indent: -999em;
            border: 0;
            background-image: url("./img/locked_icon.png");
            cursor: not-allowed;
            background-size: 100% 100%;
        }

        .extra-activities-tab-content {
            max-width: 1000px;
            margin: 0 auto;
        }

        .extra-activities-week-detail {
            display: none;
        }
        .extra-activities-week-detail-active {
            display: block;
        }

        .extra-activities-item {
            width: 340px;
            margin: 0 auto 20px;
            text-align: center;
            cursor: pointer;
            display: block;
        }
        .extra-activities-item-logo {
            width: 110px;
            height: 110px;
            margin: 0 auto 10px;
            background-size: 100% 100%;
            display: block;
        }
        .extra-activities-item-game {
            color: #2fedc9;
        }
        .extra-activities-item-game .extra-activities-item-logo {
            background-image: url("./img/game_icon.png");
        }
        .extra-activities-item-quiz {
            color: #f7751e;
        }
        .extra-activities-item-quiz .extra-activities-item-logo {
            background-image: url("./img/quiz_icon.png");
        }
        .extra-activities-item-video {
            color: #93c;
        }
        .extra-activities-item-video .extra-activities-item-logo {
            background-image: url("./img/video_icon.png");
        }
        .extra-activities-item-title {
            font-size: 20px;
            display: block;
        }
        .extra-activities-item-divider {
            border-top: 2px solid;
            margin: 10px 0;
            display: block;
        }
        .extra-activities-item-desc {
            color: #fff;
            width: 200px;
            font-size: 18px;
            font-family: Maitree, serif;
            margin: 0 auto;
            display: block;
        }


    </style>
</head>
<body>

<div class="page-wrapper">
    <div class="header-wrapper">
        <div class="header">
            <a class="home-link" href="#">SNAP</a>
            <ul class="nav-list">
                <li class="nav-item"><a  class="nav-link" href="http://taobao.com">GAME HOME</a></li>
                <li class="nav-item"><a  class="nav-link" href="http://taobao.com">Snap Facts</a></li>
                <li class="nav-item"><a  class="nav-link" href="http://taobao.com">Resources</a></li>
            </ul>
            <a href="#" class="settings">
                <span class="setting-icon"></span>
                <span class="setting-text">NoButSrsly</span>
            </a>
        </div>
    </div>
    <div class="content-wrapper">

        <div class="extra-activities-detail">
            <div class="extra-activities-header">
                <div class="extra-activities-logo"></div>
                <div class="extra-activities-title">Extra Activities</div>
                <div class="extra-activities-intro">Complete these extra activities to receive more bonus points</div>
            </div>
            <div class="extra-activities-tab">
                <h2 class="extra-activities-tab-title">Select Your week</h2>
                <div class="extra-activities-tab-list">
                    <div class="extra-activities-tab-item">1</div>
                    <div class="extra-activities-tab-item">2</div>
                    <div class="extra-activities-tab-item">3</div>
                    <div class="extra-activities-tab-item">4</div>
                    <div class="extra-activities-tab-item">5</div>
                    <div class="extra-activities-tab-item">6</div>
                    <div class="extra-activities-tab-item extra-activities-tab-item-active">7</div>
                    <div class="extra-activities-tab-item extra-activities-tab-item-disabled">8</div>
                    <div class="extra-activities-tab-item extra-activities-tab-item-disabled">8</div>
                    <div class="extra-activities-tab-item extra-activities-tab-item-disabled">8</div>
                    <div class="extra-activities-tab-item extra-activities-tab-item-disabled">8</div>
                    <div class="extra-activities-tab-item extra-activities-tab-item-disabled">8</div>
                </div>
            </div>
            <div class="extra-activities-tab-content">

                <!-- index=0 -->
                <div class="extra-activities-week-detail extra-activities-week-detail-active mini-row">
                    <div class="col-6">
                        <a class="extra-activities-item extra-activities-item-game" href="">
                            <span class="extra-activities-item-logo"></span>
                            <span class="extra-activities-item-title">Nutrition</span>
                            <span class="extra-activities-item-divider"></span>
                            <span class="extra-activities-item-desc">Information on the types of food we eat</span>
                        </a>
                    </div>
                    <div class="col-6">
                        <a class="extra-activities-item extra-activities-item-quiz" href="">
                            <span class="extra-activities-item-logo"></span>
                            <span class="extra-activities-item-title">Nutrition</span>
                            <span class="extra-activities-item-divider"></span>
                            <span class="extra-activities-item-desc">Information on the types of food we eat</span>
                        </a>
                    </div>
                    <div class="col-6">
                        <a class="extra-activities-item extra-activities-item-video" href="">
                            <span class="extra-activities-item-logo"></span>
                            <span class="extra-activities-item-title">Nutrition</span>
                            <span class="extra-activities-item-divider"></span>
                            <span class="extra-activities-item-desc">Information on the types of food we eat</span>
                        </a>
                    </div>
                </div>


                <!-- index=1 -->
                <div class="extra-activities-week-detail mini-row">
                    <div class="col-6">
                        <a class="extra-activities-item extra-activities-item-nutrition" herf="">
                            <span class="extra-activities-item-logo"></span>
                            <span class="extra-activities-item-title">Nutrition</span>
                            <span class="extra-activities-item-divider"></span>
                            <span class="extra-activities-item-desc">Information on the types of food we eat</span>
                        </a>
                    </div>
                    <div class="col-6">
                        <a class="extra-activities-item extra-activities-item-nutrition" href="">
                            <span class="extra-activities-item-logo"></span>
                            <span class="extra-activities-item-title">Nutrition</span>
                            <span class="extra-activities-item-divider"></span>
                            <span class="extra-activities-item-desc">Information on the types of food we eat</span>
                        </a>
                    </div>

                </div>

            </div>






        </div>
    </div>

    <ul class="sitenav">
        <li class="sitenav-item sitenav-game-home"><a href="#"></a></li>
        <li class="sitenav-item sitenav-achievement"><a href="#"></a></li>
        <li class="sitenav-item sitenav-progress"><a href="#"></a></li>
        <li class="sitenav-item sitenav-extra-activities-material"><a href="#"></a></li>
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

    var TabCtrl = {
        cls: {
            tabActive: 'extra-activities-tab-item-active',
            tabDisabled: 'extra-activities-tab-item-disabled',
            tabContentActive: 'extra-activities-week-detail-active'
        },
        init: function (opt) {
            opt = opt || {
                    onTabChange: $.noop
                }
            this.onTabChange = opt.onTabChange
            this.cacheElements()
            this.addListeners()
        },
        cacheElements: function () {
            var $main = $('.extra-activities-tab')
            this.$main = $main
            this.$tabItems = $main.find('.extra-activities-tab-item')
            this.$tabContent = $('.extra-activities-tab-content')
            this.$tabContentItems = this.$tabContent.find('.extra-activities-week-detail')
        },
        addListeners: function () {
            var that = this

            this.$main.on('click', '.extra-activities-tab-item', function (e) {
                var $target = $(e.currentTarget)
                var cls = that.cls

                if (!$target.hasClass(cls.tabActive) && !$target.hasClass(cls.tabDisabled)) {
                    var index = that.$tabItems.index(e.currentTarget)
                    that.activeItem(index)
                    that.onTabChange(index)
                }
            })
        },
        activeItem: function (index) {
            this.$tabItems.removeClass(this.cls.tabActive)
                .eq(index)
                .addClass(this.cls.tabActive)
            this.$tabContentItems.removeClass(this.cls.tabContentActive)
                .eq(index)
                .addClass(this.cls.tabContentActive)
        }
    }
    TabCtrl.init({
        onTabChange: function (index) {
            MaterialCtrl.showNavPanel(index)
        }
    })


    var MaterialCtrl = {
        init: function () {
            this.cacheElements()
            this.addListeners()
        },
        cacheElements: function () {
            var $navMain = $('.extra-activities-tab-content')
            this.$navMain = $navMain
            this.$navPanels = $navMain.find('.extra-activities-week-detail')
            var $materialList = $('.material-list')
            this.$materialList = $materialList
            this.$materialItems = $materialList.find('.material-item')
        },
        addListeners: function () {
            var that = this
            that.$navMain.on('click', '.extra-activities-item', function (e) {
                var $target = $(e.currentTarget)
                var targetMaterialCls = $target.data('target')
                that.showMaterialDetail(targetMaterialCls)
            })
            that.$materialList.on('click', '.material-close', function (e) {
                that.hideMaterialDetail()
            })
        },
        showNavPanel: function (index) {
            this.$navMain.show()
            this.$navPanels.hide()
                .eq(index)
                .show()
            this.$materialItems.removeClass('material-item-active')
            this.$materialList.hide()
        },
        showMaterialDetail: function (targetMaterialCls) {
            this.$navMain.hide()
            this.$materialList.show()
            this.$materialItems.removeClass('material-item-active')
                .filter(targetMaterialCls)
                .addClass('material-item-active')
        },

        hideMaterialDetail: function () {
            this.$navMain.show()
            this.$materialList.hide()
            this.$materialItems.removeClass('material-item-active')
        }
    }
    // MaterialCtrl.init()


    snap.enableBackTop()
</script>
</body>
</html>
