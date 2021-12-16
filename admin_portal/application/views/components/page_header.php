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
                <a  href="<?=base_url()?>">
                    <img style="padding-top: 10px;" src="<?=base_url1("assets")?>/plugins/images/erplogo.png" alt="home" class="dark-logo" /><!--This is light logo icon--><img height="100px" src="<?=base_url1("assets")?>/plugins/images/logo_dark.png" alt="home" class="light-logo" />
                    </b>
                </a>
            </div>
            <!-- /Logo -->
            <!-- Search input and Toggle icon -->

            <ul class="nav navbar-top-links navbar-right pull-right">

                <li class="dropdown">
                    <a class="dropdown-toggle profile-pic" data-toggle="dropdown" href="javascript:void(0)"> <img src="<?=base_url1("uploads/images/")?>/<?=$_SESSION['image'];?>" alt="<?=$_SESSION['name'];?>" width="70px" height="80px" class="img-circle"><b class="hidden-xs"><?=$_SESSION['name'];?></b><span class="caret"></span> </a>
                    <ul class="dropdown-menu dropdown-user animated flipInY">
                        <li>
                            <div class="dw-user-box">
                                <div class="u-img"><img class="img-rounded" height="80px" width="70px" src="<?=base_url1("uploads/images/")?>/<?=$_SESSION['image'];?>" alt="<?=$_SESSION['name'];?>" /></div>
                                <div class="u-text">
                                    <h4><?=$_SESSION['name']?></h4>
                                    <p class="text-muted"><?=$_SESSION['email']?></p>
                                </div>
                            </div>
                        </li>
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
                    <a href="javascript:void(0)" class="waves-effect"><img height="50px" width="50px" src="<?=base_url1("uploads/images/")?>/<?=$_SESSION['image'];?>" alt="<?=$_SESSION['name']?>" class="img-circle"> <span class="hide-menu"><?=$_SESSION['name']?><span class="fa arrow"></span></span>
                    </a>
                    <ul class="nav nav-second-level collapse" aria-expanded="false" style="height: 0px;">
                        <li><a href="<?= base_url('dashboard/my_profile')?>"><i class="ti-user"></i> <span class="hide-menu">My Profile</span></a></li>
                        <li><a href="<?= base_url('login/logout')?>"><i class="fa fa-power-off"></i> <span class="hide-menu">Logout</span></a></li>
                    </ul>
                </li>
                <li> <a href="<?=base_url("dashboard/index")?>" class="waves-effect"><i class="mdi mdi-av-timer fa-fw" data-icon="v"></i> <span class="hide-menu"> Dashboard </span></a>

                </li>
                <li> <a href="<?=base_url("school")?>" class="waves-effect"><i class="mdi mdi-av-timer fa-fw" data-icon="v"></i> <span class="hide-menu"> School </span></a>

                </li>
                <li> <a href="<?=base_url("student")?>" class="waves-effect"><i class="mdi mdi-av-timer fa-fw" data-icon="v"></i> <span class="hide-menu"> Student </span></a>

                </li>
                <li> <a href="<?=base_url("teacher")?>" class="waves-effect"><i class="mdi mdi-av-timer fa-fw" data-icon="v"></i> <span class="hide-menu"> Teacher </span></a>

                </li>
                <li> <a href="<?=base_url()?>quiz" onclick="return false" class="waves-effect non_clickable"><i class="mdi mdi-clipboard-check fa-fw"></i> <span class="hide-menu">Quiz<span class="fa arrow"></span></span></a>
                    <ul class="nav nav-second-level">
                        <li><a href="<?=base_url()?>quiz/index"><i class="fab fa-simplybuilt m-r-5"></i><span class="hide-menu">Classes</span></a></li>
                        <li><a href="<?=base_url()?>quiz/subject"><i class="fa fa-book m-r-5"></i><span class="hide-menu">Subjects</span></a></li>
                        <li><a href="<?=base_url()?>quiz/topics"><i class="fa fa-database m-r-5"></i><span class="hide-menu">Topics</span></a></li>
                    </ul>
                </li>

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
                    <h4 class="page-title"><?=$title?></h4> </div>

                <!-- /.col-lg-12 -->
            </div>
