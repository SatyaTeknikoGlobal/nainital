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

        <!-- /.row -->
        <!-- ============================================================== -->
        <!-- Different data widgets -->
        <!-- ============================================================== -->
        <div class="row">
            <div class="col-sm-12">
                <div class="white-box">
                    <div class="row row-in">
                        <div class="col-lg-3 col-sm-6 row-in-br">
                            <ul class="col-in">
                                <li>
                                    <span class="circle circle-md bg-danger"><i class="fa fa-clipboard"></i></span>
                                </li>
                                <li class="col-last">
                                    <h3 class="counter text-right m-t-15"><?=$schools->school_count?></h3>
                                </li>
                                <?php
                                if ($schools->school_count > 0 && $active_schools->school_count > 0) {
                                    $pSchool =  ($active_schools->school_count / $schools->school_count)*100 ;
                                }else{
                                    $pSchool = 0;
                                }
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
                                    <h4>Registered<br>Schools</h4>
                                    <div class="progress">
                                        <div class="progress-bar progress-bar-danger" role="progressbar" aria-valuenow="" aria-valuemin="0" aria-valuemax="100" style="width: <?=$pSchool?>%">
                                            <span class="sr-only"><?=$pSchool?>% Active (success)</span>
                                        </div>
                                    </div>
                                </li>
                            </ul>
                        </div>
                        <div class="col-lg-3 col-sm-6 row-in-br  b-r-none">
                            <ul class="col-in">
                                <li>
                                    <span class="circle circle-md bg-info"><i class="ti-clipboard"></i></span>
                                </li>
                                <li class="col-last">
                                    <h3 class="counter text-right m-t-15"><?=$student->count?></h3>
                                </li>
                                <li class="col-middle">
                                    <h4>Registered<br>Students</h4>
                                    <div class="progress">
                                        <div class="progress-bar progress-bar-info" role="progressbar" aria-valuenow="" aria-valuemin="0" aria-valuemax="100" style="width: <?=$pStudent?>%">
                                            <span class="sr-only"><?=$pStudent?>% Approved (success)</span>
                                        </div>
                                    </div>
                                </li>
                            </ul>
                        </div>
                        <div class="col-lg-3 col-sm-6 row-in-br">
                            <ul class="col-in">
                                <li>
                                    <span class="circle circle-md bg-success"><i class=" ti-clipboard"></i></span>
                                </li>
                                <li class="col-last">
                                    <h3 class="counter text-right m-t-15"><?=$teacher->count?></h3>
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
                        <div class="col-lg-3 col-sm-6  b-0">
                            <ul class="col-in">
                                <li>
                                    <span class="circle circle-md bg-warning"><i class="ti-clipboard"></i></span>
                                </li>
                                <li class="col-last">
                                    <h3 class="counter text-right m-t-15"><?=$parent->count?></h3>
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
            </div>
        </div>
        <!--row -->
    </div>
