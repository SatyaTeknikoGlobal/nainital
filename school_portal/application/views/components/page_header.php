<?php
/*
| -----------------------------------------------------
| PRODUCT NAME: 	Tekniko School
| -----------------------------------------------------
| AUTHOR:			Kshitij Kumar Singh
| -----------------------------------------------------
| EMAIL:			kshitij.singh@teknikoglobal.com
| -----------------------------------------------------
| COPYRIGHT:		RESERVED BY TEKNIKOGLOBAL
| -----------------------------------------------------
| WEBSITE:			https://www.teknikoglobal.com
| -----------------------------------------------------
*/
?>
<!-- ============================================================== -->
<!-- Preloader -->
<!-- ============================================================== -->
<div class="preloader">
    <svg class="circular" viewBox="25 25 50 50">
        <circle class="path" cx="50" cy="50" r="20" fill="none" stroke-width="2" stroke-miterlimit="10" />
    </svg>
</div>
<!-- ============================================================== -->
<!-- Wrapper -->
<!-- ============================================================== -->
<div id="wrapper">
    <!-- ============================================================== -->
    <!-- Topbar header - style you can find in pages.scss -->
    <!-- ============================================================== -->
    <nav class="navbar navbar-default navbar-static-top m-b-0">
        <div class="navbar-header">
            <div class="top-left-part" style="display:inline-flex">
                <ul class="nav navbar-top-links navbar-left">
                    <li><a href="javascript:void(0)" class="open-close waves-effect waves-light visible-xs"><i style="font-size: 60px;line-height: 100px" class="ti-close ti-menu"></i></a></li>
                </ul>
                <!-- Logo -->

                <!-- src="<?php // echo base_url("uploads/logo/").$_SESSION['logo']?>" <?php //base_url("uploads/logo/").$_SESSION['logo']?> -->
                <a  href="<?=base_url()?>">
                    <img src="<?=base_url("assets/plugins/images/erplogo.png")?>" style="padding-top: 10px;"   class="dark-logo" /><!--This is light logo icon--><img  src="" alt="home" class="light-logo" />
                    </b>
                </a>
            </div>
            <!-- /Logo -->
            <!-- Search input and Toggle icon -->

            <ul class="nav navbar-top-links navbar-right pull-right">

                <li class="dropdown">
                    <a class="dropdown-toggle profile-pic" data-toggle="dropdown" href="javascript:void(0)"> <img src="<?=base_url("uploads/images/")?>/<?=$_SESSION['image'];?>" alt="<?=$_SESSION['name'];?>" width="70px" height="80px" class="img-circle"><b class="hidden-xs"><?=$_SESSION['name'];?></b><span class="caret"></span> </a>
                    <ul class="dropdown-menu dropdown-user animated flipInY">
                        <li>
                            <div class="dw-user-box">
                                <div class="u-img"><img class="img-rounded" height="80px" width="70px" src="<?=base_url("uploads/images/")?>/<?=$_SESSION['image'];?>" alt="<?=$_SESSION['name'];?>" /></div>
                                <div class="u-text">
                                    <h4><?=$_SESSION['name']?></h4>
                                    <p class="text-muted"><?=$_SESSION['email']?></p></div>
                            </div>
                        </li>
                        <li role="separator" class="divider"></li>
                        <li><a href="javascript:void(0)" class="text-center"><span style="float: left">School Code</span>:<span style="float: right"><?=$_SESSION['code']?></span></a></li>
                        <li role="separator" class="divider"></li>
                        <li><a href="<?= base_url('dashboard/my_profile')?>"><i class="ti-user"></i> My Profile</a></li>
                        <li role="separator" class="divider"></li>
                        <li><a href="<?= base_url('login/logout')?>"><i class="fa fa-power-off"></i> Logout</a></li>
                    </ul>
                    <!-- /.dropdown-user -->
                </li>
                <!-- /.dropdown -->
            </ul>
        </div>
        <!-- /.navbar-header -->
        <!-- /.navbar-top-links -->
        <!-- /.navbar-static-side -->
    </nav>
    <!-- End Top Navigation -->
    <!-- ============================================================== -->
    <!-- Left Sidebar - style you can find in sidebar.scss  -->
    <!-- ============================================================== -->
    <div class="navbar-default sidebar" role="navigation">
        <div class="sidebar-nav">
            <div class="sidebar-head">
                <h3><span class="fa-fw open-close"><i class="ti-menu hidden-xs"></i><i class="ti-close visible-xs"></i></span> <span class="hide-menu">MENTOR</span></h3> </div>
            <ul class="nav" id="side-menu">
                <li class="user-pro">
                    <a href="javascript:void(0)" class="waves-effect"><img height="50px" width="50px" src="<?=base_url("uploads/images/")?>/<?=$_SESSION['image'];?>" alt="<?=$_SESSION['name']?>" class="img-circle"> <span class="hide-menu"><?=$_SESSION['name']?><span class="fa arrow"></span></span>
                    </a>
                    <ul class="nav nav-second-level collapse" aria-expanded="false" style="height: 0px;">
                        <li><a href="javascript:void(0)"><span class="hide-menu" >School Code</span>&nbsp;&nbsp;:&nbsp;&nbsp;<span class="hide-menu"><?=$_SESSION['code']?></span></a></li>
                        <li><a href="<?= base_url('dashboard/my_profile')?>"><i class="ti-user"></i> <span class="hide-menu">My Profile</span></a></li>
                        <li><a href="<?= base_url('login/logout')?>"><i class="fa fa-power-off"></i> <span class="hide-menu">Logout</span></a></li>
                    </ul>
                </li>
                <li> <a href="<?=base_url("dashboard/index")?>" class="waves-effect"><i class="mdi mdi-av-timer fa-fw" data-icon="v"></i> <span class="hide-menu"> Dashboard </span></a>

                </li>
                <?php if ($_SESSION['role'] == "school") { ?>
                <li> <a href="<?=base_url()?>configuration" class="waves-effect non_clickable" onclick="return false"><i class="mdi mdi-settings fa-fw"></i> <span class="hide-menu">Configuration<span class="fa arrow"></span></span></a>
                    <ul class="nav nav-second-level">
                        <li><a href="<?=base_url()?>configuration/index"><i class="fa fa-cogs m-r-5"></i><span class="hide-menu">School Setup</span></a></li>
                        <li><a href="<?=base_url()?>configuration/batch"><i class="fa fa-graduation-cap m-r-5"></i><span class="hide-menu">Batch</span></a></li>
                        <li><a href="<?=base_url()?>configuration/classes"><i class="fab fa-simplybuilt m-r-5"></i><span class="hide-menu">Classes</span></a></li>
                        <li><a href="<?=base_url()?>configuration/section"><i class="fa fa-database m-r-5"></i><span class="hide-menu">Section</span></a></li>
                        <li><a href="<?=base_url()?>configuration/subject"><i class="fa fa-book m-r-5"></i><span class="hide-menu">Subject</span></a></li>
                        <li><a href="<?=base_url()?>configuration/slots"><i class="fa fa-clock m-r-5"></i><span class="hide-menu">Slots</span></a></li>
                        <li><a href="<?=base_url()?>configuration/routine"><i class="fa fa-calendar m-r-5"></i><span class="hide-menu">Routine</span></a></li>
                        <li><a href="<?=base_url()?>configuration/contact_list"><i class="fa fa-address-book m-r-5"></i><span class="hide-menu">Contact Directory</span></a></li>
                        <li><a href="<?=base_url()?>configuration/holiday"><i class="fa fa-calendar m-r-5"></i><span class="hide-menu">Holidays</span></a></li>
                        <li><a href="<?=base_url()?>configuration/announcement"><i class="fa fa-bullhorn m-r-5"></i><span class="hide-menu">Announcement</span></a></li>
                    </ul>
                </li>
                <?php } ?>
                <li> <a href="<?=base_url()?>attendance" onclick="return false" class="waves-effect non_clickable"><i class="mdi mdi-clipboard-check fa-fw"></i> <span class="hide-menu">Attendance<span class="fa arrow"></span></span></a>
                    <ul class="nav nav-second-level">
                        <li><a href="<?=base_url()?>attendance/index"><i class="fa fa-user m-r-5"></i><span class="hide-menu">Student Attendance</span></a></li>
                        <li><a href="<?=base_url()?>attendance/teacher_attendance"><i class="fa fa-user-secret m-r-5"></i><span class="hide-menu">Teacher Attendance</span></a></li>
                        <?php if ($_SESSION['role']=="school"){?>
                        <!--<li><a href="<?/*=base_url()*/?>attendance/groups"><i class="fa fa-location-arrow m-r-5"></i><span class="hide-menu">Location Group</span></a></li>
                        <li><a href="<?/*=base_url()*/?>attendance/allocate_group"><i class="fa fa-object-group m-r-5"></i><span class="hide-menu">Allocate Group (Students)</span></a></li>
                        <li><a href="<?/*=base_url()*/?>attendance/teacher_allocate_group"><i class="fa fa-object-group m-r-5"></i><span class="hide-menu">Allocate Group (Teacher)</span></a></li>
                        <li><a href="<?/*=base_url()*/?>attendance/staff_allocate_group"><i class="fa fa-object-group m-r-5"></i><span class="hide-menu">Allocate Group (Non Teaching)</span></a></li>-->
                        <?php }?>
                    </ul>
                </li>
                <li> <a href="<?=base_url()?>members" onclick="return false" class="waves-effect non_clickable"><i class="mdi mdi-account-multiple fa-fw"></i> <span class="hide-menu">Members<span class="fa arrow"></span></span></a>
                    <ul class="nav nav-second-level">
                        <li><a href="<?=base_url()?>members/index"><i class="fa fa-users m-r-5"></i><span class="hide-menu">Students</span></a></li>
                        <li><a href="<?=base_url()?>members/teacher"><i class="fa fa-user-secret m-r-5"></i><span class="hide-menu">Teachers</span></a></li>
                        <?php if ($_SESSION['role']=="school"){?>
                            <li><a href="<?=base_url()?>members/student_bulk_import"><i class="fa fa-users m-r-5"></i><span class="hide-menu">Student Bulk Upload</span></a></li>
                            <li><a href="<?=base_url()?>members/teacher_bulk_import"><i class="fa fa-user-secret m-r-5"></i><span class="hide-menu">Teacher Bulk Upload</span></a></li>
                        <?php }?>
                        <?php if ($_SESSION['role']=="school"){?>
                        <li><a href="<?=base_url()?>members/role_non_teaching"><i class="fa fa-lock m-r-5"></i><span class="hide-menu">Non Teaching Roles</span></a></li>
                        <?php }?>
                        <li><a href="<?=base_url()?>members/non_teaching"><i class="fa fa-user m-r-5"></i><span class="hide-menu">Non Teaching</span></a></li>
                    </ul>
                </li>
                <?php if ($_SESSION['role'] == "school"){?>
                <li> <a href="<?=base_url()?>accounts" onclick="return false" class="waves-effect non_clickable"><i class="mdi mdi-shield-outline fa-fw"></i> <span class="hide-menu">Accounts<span class="fa arrow"></span></span></a>
                    <ul class="nav nav-second-level">
                        <li><a href="<?=base_url()?>accounts/fee_head"><i class="ti-hand-point-up fa-fw"></i><span class="hide-menu">Fee Head</span></a></li>
                        <li><a href="<?=base_url()?>accounts/fee_type"><i class="ti-hand-point-up fa-fw"></i><span class="hide-menu">Fee Type</span></a></li>
                        <li><a href="<?=base_url()?>accounts/fee_structure"><i class="ti-hand-point-up fa-fw"></i><span class="hide-menu">Fee Structure</span></a></li>
                        <li><a href="<?=base_url()?>accounts/concession"><i class="ti-hand-point-up fa-fw"></i><span class="hide-menu">Concession</span></a></li>
                        <li><a href="<?=base_url()?>accounts/allocate_concession"><i class="ti-hand-point-up fa-fw"></i><span class="hide-menu">Allocate Concession</span></a></li>
                        <li><a href="<?=base_url()?>accounts/generate_invoice"><i class="ti-hand-point-up fa-fw"></i><span class="hide-menu">Generate Invoices</span></a></li>
                        <li><a href="<?=base_url()?>accounts/invoice"><i class="ti-hand-point-up fa-fw"></i><span class="hide-menu">Invoices</span></a></li>
                        <li><a href="<?=base_url()?>accounts/payment"><i class="ti-hand-point-up fa-fw"></i><span class="hide-menu">Student Wise Invoice</span></a></li>
                        <li><a href="<?=base_url()?>accounts/expense"><i class="ti-hand-point-up fa-fw"></i><span class="hide-menu">Expense</span></a></li>
                        <li><a href="<?=base_url()?>accounts/reports"><i class="ti-hand-point-up fa-fw"></i><span class="hide-menu">Reports</span></a></li>
                    </ul>
                </li>
                <?php }?>
                <li> <a href="<?=base_url()?>exam" onclick="return false" class="waves-effect non_clickable"><i class="mdi mdi-note fa-fw"></i> <span class="hide-menu">Exams<span class="fa arrow"></span></span></a>
                    <ul class="nav nav-second-level">
                        <?php if (strtolower($_SESSION['role'])=='school'){?>
                        <li><a href="<?=base_url()?>exam/index"><i class="ti-hand-point-up fa-fw"></i><span class="hide-menu">Exam</span></a></li>
                        <?php }?>
                        <li><a href="<?=base_url()?>exam/result"><i class="ti-hand-point-up fa-fw"></i><span class="hide-menu">Result</span></a></li>
                    </ul>
                </li>
                <?php if ($_SESSION['role'] == "school") { ?>
                    <li> <a href="<?=base_url("gallery")?>" class="waves-effect"><i class="mdi mdi-google-photos" data-icon="v"></i> <span class="hide-menu"> Gallery </span></a>

                    </li>
                    <!-- <li> <a href="<?=base_url()?>transport" onclick="return false" class="waves-effect non_clickable"><i class="mdi mdi-bus fa-fw"></i> <span class="hide-menu">Transport<span class="fa arrow"></span></span></a>
                        <ul class="nav nav-second-level">
                            <?php if (strtolower($_SESSION['role'])=='school'){?>
                                <li><a href="<?=base_url()?>transport/index"><i class="ti-angle-double-right fa-fw"></i><span class="hide-menu">Routes</span></a></li>
                                <li><a href="<?=base_url()?>transport/transport_allocation"><i class="ti-car fa-fw"></i><span class="hide-menu">Transport Allocation</span></a></li>
                                <li><a href="<?=base_url()?>transport/attendance"><i class="ti-calendar fa-fw"></i><span class="hide-menu">Transport Attendance</span></a></li>
                                <li><a href="<?=base_url()?>transport/drivers"><i class="ti-user fa-fw"></i><span class="hide-menu">Drivers</span></a></li>
                            <?php }?>
                        </ul>
                    </li> -->
                    <!--<li> <a href="<?/*=base_url()*/?>library" onclick="return false" class="waves-effect non_clickable"><i class="mdi mdi-book-open-page-variant fa-fw"></i> <span class="hide-menu">Library<span class="fa arrow"></span></span></a>
                        <ul class="nav nav-second-level">
                            <?php /*if (strtolower($_SESSION['role'])=='school'){*/?>
                                <li><a href="<?/*=base_url()*/?>library/index"><i class="ti-wallet fa-fw"></i><span class="hide-menu">Book Category</span></a></li>
                                <li><a href="<?/*=base_url()*/?>library/books"><i class="ti-book fa-fw"></i><span class="hide-menu">Books</span></a></li>
                            <?php /*}*/?>
                        </ul>
                    </li>-->
                   <!--  <li> <a href="<?=base_url()?>poll" onclick="return false" class="waves-effect non_clickable"><i class="mdi mdi-poll-box fa-fw"></i> <span class="hide-menu">Polls<span class="fa arrow"></span></span></a>
                        <ul class="nav nav-second-level">
                            <?php if (strtolower($_SESSION['role'])=='school'){?>
                                <li><a href="<?=base_url()?>poll/index"><i class="ti-bar-chart fa-fw"></i><span class="hide-menu">Polls</span></a></li>
                                <li><a href="<?=base_url()?>poll/add"><i class="ti-plus fa-fw"></i><span class="hide-menu">Add Poll</span></a></li>
                            <?php }?>
                        </ul>
                    </li> -->
                <?php } ?>
            </ul>
        </div>
    </div>
    <!-- ============================================================== -->
    <!-- End Left Sidebar -->
    <!-- ============================================================== -->
    <!-- ============================================================== -->
    <!-- Page Content -->
    <!-- ============================================================== -->
    <div id="page-wrapper">
        <div class="container-fluid">
            <div class="row bg-title" style="margin-top: 40px">
                <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
                    <h4 class="page-title"><?=$title?>&nbsp;&nbsp;<?php if (isset($title1)){echo $title1;}?></h4>
                </div>

                <!-- /.col-lg-12 -->
            </div>
