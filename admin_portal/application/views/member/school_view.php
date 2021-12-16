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
<div class="row">
    <!--profile picture-->
    <div class="col-sm-3">
        <div class="white-box">
            <div class="row">
                <img src="<?=$school->image?>" style="width: 100%" class="img-thumbnail">
            </div>
            <hr>
            <div class="row">
                <label class="col-xs-5 control-label">School : </label>
                <label class="col-xs-7 controls"><?=$school->school_name?></label>
            </div>
            <div class="row">
                <label class="col-xs-5 control-label">Admin : </label>
                <label class="col-xs-7 controls"><?=$school->admin_name?></label>
            </div>
        </div>
    </div>
    <!--User Information-->
    <div class="col-sm-9">
        <div class="white-box">
            <div class="row row-in">
                <div class="col-lg-4 col-sm-6 row-in-br">
                    <ul class="col-in">
                        <li>
                            <span class="circle circle-md bg-danger"><i class="ti-clipboard"></i></span>
                        </li>
                        <li class="col-last">
                            <h3 class="counter text-right m-t-15"><?=$active_student->count.'/'.$student->count?></h3>
                        </li>
                        <?php
                        if ($student->count > 0 && $active_student->count > 0) {
                            $pStudent =  ($active_student->count / $student->count)*100 ;
                        }else{
                            $pStudent = 0;
                        }
                        if ($teacher->count > 0 && $active_teacher->count > 0) {
                            $pTeacher =  ($active_teacher->count / $teacher->count)*100 ;
                        }else{
                            $pTeacher = 0;
                        }
                        if ($parent->count > 0 && $active_parent->count > 0) {
                            $pParent =  ($active_parent->count / $parent->count)*100 ;
                        }else{
                            $pParent = 0;
                        }
                        ?>
                        <li class="col-middle">
                            <h4>Registered<br>Students</h4>
                            <div class="progress">
                                <div class="progress-bar progress-bar-danger" role="progressbar" aria-valuenow="" aria-valuemin="0" aria-valuemax="100" style="width: <?=$pStudent?>%">
                                    <span class="sr-only"><?=$pStudent?>% Active (success)</span>
                                </div>
                            </div>
                        </li>
                    </ul>
                </div>
                <div class="col-lg-4 col-sm-6 row-in-br b-r-none">
                    <ul class="col-in">
                        <li>
                            <span class="circle circle-md bg-success"><i class=" ti-clipboard"></i></span>
                        </li>
                        <li class="col-last">
                            <h3 class="counter text-right m-t-15"><?=$active_teacher->count.'/'.$teacher->count?></h3>
                        </li>

                        <li class="col-middle">
                            <h4>Registered<br>Teachers</h4>
                            <div class="progress">
                                <div class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="" aria-valuemin="0" aria-valuemax="100" style="width: <?=$pTeacher?>%">
                                    <span class="sr-only"><?=$pTeacher?>% Approved (success)</span>
                                </div>
                            </div>
                        </li>
                    </ul>
                </div>
                <div class="col-lg-4 col-sm-6  b-0">
                    <ul class="col-in">
                        <li>
                            <span class="circle circle-md bg-warning"><i class="ti-clipboard"></i></span>
                        </li>
                        <li class="col-last">
                            <h3 class="counter text-right m-t-15"><?=$active_parent->count.'/'.$parent->count?></h3>
                        </li>
                        <li class="col-middle">
                            <h4>Registered<br>Parents</h4>
                            <div class="progress">
                                <div class="progress-bar progress-bar-warning" role="progressbar" aria-valuenow="" aria-valuemin="0" aria-valuemax="100" style="width: <?=$pParent?>%">
                                    <span class="sr-only"><?=$pParent?>% Approved (success)</span>
                                </div>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="white-box">
                <h2 style="margin-top: 10px !important;margin-bottom: 15px !important;font-weight: 500;"><i class="ti-user"></i>&nbsp;&nbsp;ABOUT ADMIN</h2>
                <div class="row">
                    <div class="col-sm-6">
                        <div class="row" style="margin-bottom: 5px !important;">
                            <label class="col-xs-5 control-label">Name : </label>
                            <div class="col-xs-7 controls"><?=$school->admin_name?></div>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="row" style="margin-bottom: 5px !important;">
                            <label class="col-xs-5 control-label">User Name : </label>
                            <div class="col-xs-7 controls"><?=$school->username?></div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-6">
                        <div class="row" style="margin-bottom: 5px !important;">
                            <label class="col-xs-5 control-label">Phone : </label>
                            <div class="col-xs-7 controls"><?=$school->admin_phone?></div>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="row" style="margin-bottom: 5px !important;">
                            <label class="col-xs-5 control-label">Email : </label>
                            <div class="col-xs-7 controls"><?=$school->admin_email?></div>
                        </div>
                    </div>
                </div>
                <hr>
                <h2 style="margin-top: 10px !important;margin-bottom: 15px !important;font-weight: 500;"><i class="ti-briefcase"></i>&nbsp;&nbsp; ABOUT SCHOOL</h2>
                <div class="row">
                    <div class="col-sm-6">
                        <div class="row" style="margin-bottom: 5px !important;">
                            <label class="col-xs-5 control-label">School Name : </label>
                            <div class="col-xs-7 controls"><?=$school->school_name?></div>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="row" style="margin-bottom: 5px !important;">
                            <label class="col-xs-5 control-label">School Code : </label>
                            <div class="col-xs-7 controls"><?=$school->school_code?></div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-6">
                        <div class="row" style="margin-bottom: 5px !important;">
                            <label class="col-xs-5 control-label">School Prefix : </label>
                            <div class="col-xs-7 controls"><?=$school->school_prefix?></div>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="row" style="margin-bottom: 5px !important;">
                            <label class="col-xs-5 control-label">Principal : </label>
                            <div class="col-xs-7 controls"><?=$school->principal_name?></div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-6">
                        <div class="row" style="margin-bottom: 5px !important;">
                            <label class="col-xs-5 control-label">Email : </label>
                            <div class="col-xs-7 controls"><?=$school->email?></div>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="row" style="margin-bottom: 5px !important;">
                            <label class="col-xs-5 control-label">Phone : </label>
                            <div class="col-xs-7 controls"><?=$school->phone?></div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-6">
                        <div class="row" style="margin-bottom: 5px !important;">
                            <label class="col-xs-5 control-label">Address : </label>
                            <div class="col-xs-7 controls"><?= str_replace(",",",<br>",$school->admin_address)?></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
