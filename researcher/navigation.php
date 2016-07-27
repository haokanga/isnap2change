<!-- Navigation -->
<nav class="navbar navbar-default navbar-static-top" role="navigation" style="margin-bottom: 0">
    <div class="navbar-header">
        <a class="navbar-brand" href="index.php">iSNAP2Change Administration System</a>
    </div>
    <!-- /.navbar-header -->

    <ul class="nav navbar-top-links navbar-right">
        <li class="dropdown">
            <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                <i class="fa fa-envelope fa-fw"></i> <i class="fa fa-caret-down"></i>
            </a>
            <ul class="dropdown-menu dropdown-messages">
                <li>
                    <form id="submission" method="post" action="<?php echo $phpSelf; ?>">
                        <input type=hidden name="update" id="update" value="1" required>
                        <label for="studentID" style="display:none">StudentID</label>
                        <input type="text" id="studentID" name="studentID" style="display:none">
                        <label for="question">Question</label>
                        <br>
                        <textarea id="question" name="question"
                                  placeholder="Any question? Send our researchers a quick message and they will reply soon!"
                                  rows="8" required></textarea>
                        <br>
                    </form>
                </li>
                <li class="divider"></li>
                <li>
                    <div class="modal-footer">
                        <button type="button" id="btnSend" class="btn btn-default">Send</button>
                    </div>
                </li>
                <!--
                <li>
                    <a href="#">
                        <div>
                            <strong>John Smith</strong>
                            <span class="pull-right text-muted">
                                <em>Yesterday</em>
                            </span>
                        </div>
                        <div>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Pellentesque eleifend...</div>
                    </a>
                </li>
                <li class="divider"></li>
                <li>
                    <a href="#">
                        <div>
                            <strong>John Smith</strong>
                            <span class="pull-right text-muted">
                                <em>Yesterday</em>
                            </span>
                        </div>
                        <div>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Pellentesque eleifend...</div>
                    </a>
                </li>
                <li class="divider"></li>
                <li>
                    <a href="#">
                        <div>
                            <strong>John Smith</strong>
                            <span class="pull-right text-muted">
                                <em>Yesterday</em>
                            </span>
                        </div>
                        <div>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Pellentesque eleifend...</div>
                    </a>
                </li>
                <li class="divider"></li>
                <li>
                    <a class="text-center" href="#">
                        <strong>Read All Messages</strong>
                        <i class="fa fa-angle-right"></i>
                    </a>
                </li>
                -->
            </ul>
            <!-- /.dropdown-messages -->
        </li>
        <!-- /.dropdown -->
        <li class="dropdown">
            <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                <i class="fa fa-tasks fa-fw"></i> <i class="fa fa-caret-down"></i>
            </a>
            <ul class="dropdown-menu dropdown-tasks">
                <li>
                    <a href="#">
                        <div>
                            <p>
                                <strong>Task 1</strong>
                                <span class="pull-right text-muted">40% Complete</span>
                            </p>
                            <div class="progress progress-striped active">
                                <div class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="40"
                                     aria-valuemin="0" aria-valuemax="100" style="width: 40%">
                                    <span class="sr-only">40% Complete (success)</span>
                                </div>
                            </div>
                        </div>
                    </a>
                </li>
                <li class="divider"></li>
                <li>
                    <a href="#">
                        <div>
                            <p>
                                <strong>Task 2</strong>
                                <span class="pull-right text-muted">20% Complete</span>
                            </p>
                            <div class="progress progress-striped active">
                                <div class="progress-bar progress-bar-info" role="progressbar" aria-valuenow="20"
                                     aria-valuemin="0" aria-valuemax="100" style="width: 20%">
                                    <span class="sr-only">20% Complete</span>
                                </div>
                            </div>
                        </div>
                    </a>
                </li>
                <li class="divider"></li>
                <li>
                    <a href="#">
                        <div>
                            <p>
                                <strong>Task 3</strong>
                                <span class="pull-right text-muted">60% Complete</span>
                            </p>
                            <div class="progress progress-striped active">
                                <div class="progress-bar progress-bar-warning" role="progressbar" aria-valuenow="60"
                                     aria-valuemin="0" aria-valuemax="100" style="width: 60%">
                                    <span class="sr-only">60% Complete (warning)</span>
                                </div>
                            </div>
                        </div>
                    </a>
                </li>
                <li class="divider"></li>
                <li>
                    <a href="#">
                        <div>
                            <p>
                                <strong>Task 4</strong>
                                <span class="pull-right text-muted">80% Complete</span>
                            </p>
                            <div class="progress progress-striped active">
                                <div class="progress-bar progress-bar-danger" role="progressbar" aria-valuenow="80"
                                     aria-valuemin="0" aria-valuemax="100" style="width: 80%">
                                    <span class="sr-only">80% Complete (danger)</span>
                                </div>
                            </div>
                        </div>
                    </a>
                </li>
                <li class="divider"></li>
                <li>
                    <a class="text-center" href="#">
                        <strong>See All Tasks</strong>
                        <i class="fa fa-angle-right"></i>
                    </a>
                </li>
            </ul>
            <!-- /.dropdown-tasks -->
        </li>
        <!-- /.dropdown -->
        <li class="dropdown">
            <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                <i class="fa fa-bell fa-fw"></i> <i class="fa fa-caret-down"></i>
            </a>
            <ul class="dropdown-menu dropdown-alerts">
                <li>
                    <a href="#">
                        <div>
                            <i class="fa fa-comment fa-fw"></i> Researchers have replied to your question!
                            <span class="pull-right text-muted small">View it</span>
                        </div>
                    </a>
                </li>
                <li class="divider"></li>
                <li>
                    <a href="#">
                        <div>
                            <i class="fa fa-check fa-fw"></i> Your short answer quiz has been graded!
                            <span class="pull-right text-muted small">View it</span>
                        </div>
                    </a>
                </li>
                <li class="divider"></li>
                <li>
                    <a href="#">
                        <div>
                            <i class="fa fa-check fa-fw"></i> Your infograph quiz has been graded!
                            <span class="pull-right text-muted small">View it</span>
                        </div>
                    </a>
                </li>

                <!--
                <li>
                    <a href="#">
                        <div>
                            <i class="fa fa-comment fa-fw"></i> New Comment
                            <span class="pull-right text-muted small">4 minutes ago</span>
                        </div>
                    </a>
                </li>
                <li class="divider"></li>
                <li>
                    <a href="#">
                        <div>
                            <i class="fa fa-twitter fa-fw"></i> 3 New Followers
                            <span class="pull-right text-muted small">12 minutes ago</span>
                        </div>
                    </a>
                </li>
                <li class="divider"></li>
                <li>
                    <a href="#">
                        <div>
                            <i class="fa fa-envelope fa-fw"></i> Message Sent
                            <span class="pull-right text-muted small">4 minutes ago</span>
                        </div>
                    </a>
                </li>
                <li class="divider"></li>
                <li>
                    <a href="#">
                        <div>
                            <i class="fa fa-tasks fa-fw"></i> New Task
                            <span class="pull-right text-muted small">4 minutes ago</span>
                        </div>
                    </a>
                </li>
                <li class="divider"></li>
                <li>
                    <a href="#">
                        <div>
                            <i class="fa fa-upload fa-fw"></i> Server Rebooted
                            <span class="pull-right text-muted small">4 minutes ago</span>
                        </div>
                    </a>
                </li>
                <li class="divider"></li>
                <li>
                    <a class="text-center" href="#">
                        <strong>See All Alerts</strong>
                        <i class="fa fa-angle-right"></i>
                    </a>
                </li>
                -->
            </ul>
            <!-- /.dropdown-alerts -->
        </li>
        <!-- /.dropdown -->
        <li class="dropdown">
            <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                <i class="fa fa-user fa-fw"></i> <i class="fa fa-caret-down"></i>
            </a>
            <ul class="dropdown-menu dropdown-user">
                <li><a href="#"><i class="fa fa-user fa-fw"></i> User Profile</a>
                </li>
                <li><a href="#"><i class="fa fa-gear fa-fw"></i> Settings</a>
                </li>
                <li class="divider"></li>
                <li><a href="login.html"><i class="fa fa-sign-out fa-fw"></i> Logout</a>
                </li>
            </ul>
            <!-- /.dropdown-user -->
        </li>
        <!-- /.dropdown -->
    </ul>
    <!-- /.navbar-top-links -->

    <div class="navbar-default sidebar" role="navigation">
        <div class="sidebar-nav navbar-collapse">
            <ul class="nav" id="side-menu">
                <li>
                    <a href="index.php"><i class="fa fa-desktop fa-fw"></i> Dashboard<span class="fa arrow"></span></a>
                </li>
                <li>
                    <a href="javascript:;" data-toggle="collapse" data-target="#userAdmin" class=""
                       aria-expanded="true"><i class="fa fa-fw fa-wrench"></i> User Administration <i
                            class="fa fa-fw fa-caret-down"></i></a>
                    <ul id="userAdmin" class="collapse in nav nav-second-level" aria-expanded="true">

                        <?php for ($i = 0; $i < count($userAdminPageArr); $i++) { ?>
                            <li>
                                <a href="<?php echo strtolower($userAdminPageArr[$i]); ?>.php"><i
                                        class="fa fa-fw fa-<?php echo $userAdminIconArr[$i]; ?>"></i>&nbsp;<?php echo $userAdminPageArr[$i]; ?>
                                    Overview</a>
                            </li>
                        <?php } ?>
                    </ul>
                    <!--/.nav-second-level -->
                </li>
                <li>
                    <a href="javascript:;" data-toggle="collapse" data-target="#contentAdmin" class=""
                       aria-expanded="true"><i class="fa fa-fw fa-wrench"></i> Content Administration <i
                            class="fa fa-fw fa-caret-down"></i></a>
                    <ul id="contentAdmin" class="collapse in nav nav-second-level" aria-expanded="true">
                        <?php for ($i = 0; $i < count($contentAdminPageArr); $i++) { ?>
                            <li>
                                <a href="<?php echo str_replace(" ", "-", strtolower($contentAdminPageArr[$i])); ?>.php">
                                    <!--overview icon-->
                                    <i class="fa fa-fw fa-<?php echo $contentAdminIconArr[$i]; ?>"></i>
                                    <!--overview name-->
                                    &nbsp;<?php echo $contentAdminPageArr[$i];
                                    if (in_array($contentAdminPageArr[$i], $quizTypeArr)) echo " Quiz" ?> Overview</a>
                            </li>
                        <?php } ?>
                    </ul>
                    <!--/.nav-second-level -->
                </li>
                <li>
                    <a href="javascript:;" data-toggle="collapse" data-target="#gradingPage" class=""
                       aria-expanded="true"><i class="fa fa-fw fa-comment"></i> Grading & Feedback <i
                            class="fa fa-fw fa-caret-down"></i></a>
                    <ul id="gradingPage" class="collapse in nav nav-second-level" aria-expanded="true">
                        <?php for ($i = 0; $i < count($gradingPageArr); $i++) { ?>
                            <li>
                                <a href="<?php echo str_replace(" ", "-", strtolower($gradingPageArr[$i])); ?>.php"><i
                                        class="fa fa-fw fa-<?php echo $gradingIconArr[$i]; ?>"></i>&nbsp;<?php echo $gradingPageArr[$i]; ?>
                                </a>
                            </li>
                        <?php } ?>
                    </ul>
                    <!--/.nav-second-level -->
                </li>
            </ul>
        </div>
        <!-- /.sidebar-collapse -->
    </div>
    <!-- /.navbar-static-side -->
</nav>