<?php
    //check login status
    //require_once('student-validation.php');

    require_once("../mysql-lib.php");
    require_once("../debug.php");

    $pageName = "snap-facts";

    //check whether a request is GET or POST
    if($_SERVER["REQUEST_METHOD"] == "GET"){
        if(isset($_GET["topic_id"])){
            $topicID = $_GET["topic_id"];
        } else{

        }
    } else{

    }

    $conn = null;

    try{
        $conn = db_connect();

        //get snap facts by topic id
        $snapFacts = getSnapFactsByTopic($conn, $topicID);

        //get verbose facts by topic id
        $verboseFacts = getVerboseFactsStuByTopic($conn, $topicID);

        //count pages
        $snapFactsCount = count($snapFacts);
        $verboseFactsCount = count($verboseFacts);
        $totalFactsCount = $snapFactsCount + $verboseFactsCount;

        echo "<script>console.log('".($snapFactsCount)."')</script>";
        echo "<script>console.log(".$totalFactsCount.")</script>";

        if($totalFactsCount % 9 == 0){
            $pageCount = intval($totalFactsCount / 9);
        } else {
            $pageCount = intval($totalFactsCount / 9) + 1;
        }

        //count snap facts pages
        $snapFactsPageCount = intval($snapFactsCount / 9);
        echo "<script>console.log(".$snapFactsPageCount.")</script>";

        $snapFactsLeftCount = $snapFactsCount % 9;


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
    <title>Document</title>
    <link rel="stylesheet" href="./css/common.css">
    <link href='https://fonts.googleapis.com/css?family=Maitree|Lato:400,900' rel='stylesheet' type='text/css'>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
    <script src="./js/snap.js"></script>
    <style>
        .facts-detail-smoking {
            color: #fcee2d;
        }
        .facts-detail-smoking .item-trigger{
            background-image: url("./img/drop_down_icon.png");
        }
        .facts-detail-nutrition {
            color: #f7751e;
        }
        .facts-detail-nutrition .item-trigger{
            background-image: url("./img/drop_down_orange_icon.png");
        }
        .facts-detail-alcohol {
            color: #AF24D1;
        }
        .facts-detail-alcohol .item-trigger{
            background-image: url("./img/drop_down_purple_icon.png");
        }
        .facts-detail-physical-activity {
            color: #DB1B1B;
        }
        .facts-detail-physical-activity .item-trigger{
            background-image: url("./img/drop_down_red_icon.png");
        }
        .facts-detail-drugs {
            color: #00f8cd;
        }
        .facts-detail-drugs .item-trigger{
            background-image: url("./img/drop_down_blue_icon.png");
        }
        .facts-detail-sexual-health {
            color: #AF24D1;
        }
        .facts-detail-sexual-health .item-trigger{
            background-image: url("./img/drop_down_purple_icon.png");
        }
        .facts-detail-health-and-wellbeing {
            color: #DB1B1B;
        }
        .facts-detail-health-and-wellbeing .item-trigger {
            background-image: url("./img/drop_down_red_icon.png");
        }
        .facts-detail-header {
            max-width: 1000px;
            margin: 20px auto;
            text-align: center;
        }
        .facts-detail-panel {
            display: none;
        }
        .facts-detail-icon {
            width: 128px;
            height: 128px;
            background-size: 100% 100%;
            margin: 0 auto;
            display: block;
        }

        .facts-detail-item {
            border-bottom: 2px solid;
            max-width: 800px;
            margin: 10px auto;
            padding: 20px 10px;
        }
        .item-header {
            position: relative;
        }
        .item-trigger {
            width: 30px;
            height: 30px;
            background-size: 100% 100%;
            position: absolute;
            top: 5px;
            right: 20px;
            cursor: pointer;
        }

        .item-title {
            text-align: center;
            font-size: 28px;
        }
        .item-content {
            color: #fff;
            font-size: 20px;
            margin-top: 10px;
        }

        .facts-detail-item-collapsable .item-content {
            display: none;
        }
        .facts-detail-item-collapsable .item-title {
            color: #fff;
        }
        .facts-detail-item-collapsable.item-open .item-content {
            display: block;
        }
        .facts-detail-item-collapsable.item-open .item-trigger {
            transform: rotate(180deg);
        }

        .pagination {
            margin-top: 20px;
            margin-bottom: 20px;
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
<?php
switch ($topicID) {
    case 1: ?>
        <div class="facts-detail facts-detail-smoking">
            <div class="facts-detail-header">
                <span class="facts-detail-icon image-icon-smoking"></span>
                <span class="facts-detail-name h2">Smoking</span>
            </div>
<?php   break;
    case 2: ?>
        <div class="facts-detail facts-detail-nutrition">
            <div class="facts-detail-header">
                <span class="facts-detail-icon image-icon-nutrition"></span>
                <span class="facts-detail-name h2">Nutrition</span>
            </div>
<?php   break;
    case 3: ?>
        <div class="facts-detail facts-detail-alcohol">
            <div class="facts-detail-header">
                <span class="facts-detail-icon image-icon-alcohol"></span>
                <span class="facts-detail-name h2">Alcohol</span>
            </div>
<?php   break;
    case 4: ?>
        <div class="facts-detail facts-detail-physical-activity">
            <div class="facts-detail-header">
                <span class="facts-detail-icon image-icon-physical"></span>
                <span class="facts-detail-name h2">Physical Activity</span>
            </div>
<?php   break;
    case 6: ?>
        <div class="facts-detail facts-detail-drugs">
            <div class="facts-detail-header">
                <span class="facts-detail-icon image-icon-drugs"></span>
                <span class="facts-detail-name h2">Drugs</span>
            </div>
<?php   break;
    case 7: ?>
        <div class="facts-detail facts-detail-sexual-health">
            <div class="facts-detail-header">
                <span class="facts-detail-icon image-icon-sexual"></span>
                <span class="facts-detail-name h2">Sexual Health</span>
            </div>
<?php   break;
    case 8: ?>
        <div class="facts-detail facts-detail-health-and-wellbeing">
            <div class="facts-detail-header">
                <span class="facts-detail-icon image-icon-health"></span>
                <span class="facts-detail-name h2">Health and Wellbeing</span>
            </div>
<?php   break;
    } ?>
            <div class="facts-detail-content">
                <div class="facts-detail-panels">
<?php           for($i = 0; $i < $snapFactsPageCount; $i++) { ?>
                    <div class="facts-detail-panel">
<?php               for($j = 0; $j < 9; $j++) { ?>
                        <div class="facts-detail-item">
                            <div class="item-header">
                                <h2 class="h3 item-title">SNAP Fact</h2>
                            </div>
                            <div class="item-content"><?php echo $snapFacts[$i*9+$j]->Content ?></div>
                        </div>
<?php                } ?>
                    </div>
<?php           }   ?>

                    <div class="facts-detail-panel">
<?php               for($k = $snapFactsPageCount*9; $k < $snapFactsCount; $k++) { ?>
                        <div class="facts-detail-item">
                            <div class="item-header">
                                <h2 class="h3 item-title">SNAP Fact</h2>
                            </div>
                            <div class="item-content"><?php echo $snapFacts[$k]->Content ?></div>
                        </div>
<?php                } ?>

<?php               for($l = 0; $l < min($verboseFactsCount, (9 - $snapFactsLeftCount)); $l++) { ?>
                        <div class="facts-detail-item facts-detail-item-collapsable">
                            <div class="item-header">
                                <h2 class="h3 item-title"><?php echo $verboseFacts[$l]->Title ?></h2>
                                <span class="item-trigger"></span>
                            </div>
                            <div class="item-content"><?php echo $verboseFacts[$l]->Content ?></div>
                        </div>
<?php                }

                     $verboseFactsLeftCount =  $verboseFactsCount - $l;
                     $verboseFactsLeftPageCount = intval($verboseFactsLeftCount / 9);
                     $verboseFactsFinalPageCount = $verboseFactsLeftCount % 9;?>
                    </div>

<?php           for($m = 0; $m < $verboseFactsLeftPageCount; $m++) { ?>
                    <div class="facts-detail-panel">
<?php               for($n = 0; $n < 9; $n++) { ?>
                        <div class="facts-detail-item facts-detail-item-collapsable">
                            <div class="item-header">
                                <h2 class="h3 item-title"><?php echo $verboseFacts[$m*9+$n+$l]->Title ?></h2>
                                <span class="item-trigger"></span>
                            </div>
                            <div class="item-content"><?php echo $verboseFacts[$m*9+$n+$l]->Content ?></div>
                        </div>
<?php                } ?>
                    </div>
<?php           }   ?>

                    <div class="facts-detail-panel">
<?php               for($p = $verboseFactsLeftPageCount*9+$l; $p < $verboseFactsCount; $p++) { ?>
                        <div class="facts-detail-item facts-detail-item-collapsable">
                            <div class="item-header">
                                <h2 class="h3 item-title"><?php echo $verboseFacts[$p]->Title ?></h2>
                                <span class="item-trigger"></span>
                            </div>
                        <div class="item-content"><?php echo $verboseFacts[$p]->Content ?></div>
                    </div>
<?php                } ?>
                    </div>

                </div>
<?php
            if($pageCount > 1) { ?>
                <div class="pagination">
                    <span class="pagination-prev pagination-nav"></span>
                    <div class="pagination-nav-list">
                        <span class="pagination-nav-item active">1</span>
<?php
                    for($pageIndex = 2; $pageIndex <= $pageCount; $pageIndex++){ ?>
                        <span class="pagination-nav-item"><?php echo $pageIndex ?></span>
<?php               } ?>
                    </div>
                    <span class="pagination-next pagination-nav"></span>
                </div>
<?php        } ?>
            </div>
        </div>


    </div>

    <ul class="sitenav">
        <li class="sitenav-item sitenav-facts-home"><a href="#"></a></li>
        <li class="sitenav-item sitenav-smoking"><a href="#"></a></li>
        <li class="sitenav-item sitenav-nutrition"><a href="#"></a></li>
        <li class="sitenav-item sitenav-alcohol"><a href="#"></a></li>
        <li class="sitenav-item sitenav-physical"><a href="#"></a></li>
        <li class="sitenav-item sitenav-health"><a href="#"></a></li>
        <li class="sitenav-item sitenav-sexual"><a href="#"></a></li>
        <li class="sitenav-item sitenav-drgus"><a href="#"></a></li>
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
    var $items = $('.facts-detail-item-collapsable');
    $(document).on('click', '.item-trigger', function (e) {
        var $item = $(e.currentTarget).closest('.facts-detail-item-collapsable');
        var isOpen = $item.hasClass('item-open');
        $items.removeClass('item-open');
        if (!isOpen) {
            $item.addClass('item-open')
        }
    });

    var PanelCtrl = {
        init: function () {
            this.cacheElements()
        },
        cacheElements: function () {
            var $main = $('.facts-detail-panels')
            this.$main = $main
            this.$panels = $main.find('.facts-detail-panel')
        },
        activePanel: function (index) {
            this.$panels.removeClass('show')
                .eq(index)
                .addClass('show')
        }
    }
    PanelCtrl.init()
    PanelCtrl.activePanel(0)


    var pagination = new snap.Pagination({
        onChange: function (i) {
            PanelCtrl.activePanel(i)
            window.scrollTo(0, 0)
        }
    })
</script>
</body>
</html>

