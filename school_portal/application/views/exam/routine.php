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

<!-- .row -->
<div class="row">
    <div class="col-sm-12">
        <div class="white-box">
            <h3 class="box-title m-b-0"><div class="col-xs-10"><p><?=$title?></p></div><div><p><a class="btn btn-sm btn-success text-right" data-toggle="modal" data-target="#responsive-modal-add-new"><i class="fa fa-plus"></i> ADD Exam Routine</a></p></div></h3>


            <div class="table-responsive">
                <table id="example23" class="display table-bordered table table-hover table-striped" cellspacing="0" width="100%">
                    <thead>
                    <tr>
                        <th>#</th>
                        <th>Subject</th>
                        <th>Subject Code</th>
                        <th>Start Time</th>
                        <th>End Time</th>
                        <th>Action</th>
                    </tr>
                    </thead>
                    <tfoot>
                    <tr>
                        <th>#</th>
                        <th>Subject</th>
                        <th>Subject Code</th>
                        <th>Start Time</th>
                        <th>End Time</th>
                        <th>Action</th>
                    </tr>
                    </tfoot>
                    <tbody>
                    <?php
                    $count = 0;
                    foreach ($routine as $f){
                        $count++;
                        ?>
                        <tr>
                            <td><?=$count?></td>
                            <td><?=$f->subject_name?></td>
                            <td><?=$f->subject_code?></td>
                            <td><?=date("d M Y H:i",strtotime($f->from_date))?></td>
                            <td><?=date("d M Y H:i",strtotime($f->to_date))?></td>
                            <td>
                                <a data-toggle="modal" data-target="#responsive-modal<?=$f->eroutineID?>" class="btn btn-danger model_img img-responsive"><i class="fa fa-edit"></i></a>
                                <a onclick="return window.confirm(this.title || 'Do You Want to Delete this record?');" class="btn btn-primary model_img img-responsive" href="<?=base_url("exam/exam_routine_action/delete/").$f->eroutineID.'/'.$exam->examID?>"><i class="fa fa-trash"></i></a>
                            </td>
                        </tr>

                        <div id="responsive-modal<?=$f->eroutineID?>" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                                        <h4 class="modal-title">Update Exam Routine</h4> </div>
                                    <form action="<?=base_url('exam/exam_routine_action/edit/').$f->eroutineID.'/'.$exam->examID?>" method="POST"">
                                    <div class="modal-body">

                                        <div class="form-group">
                                            <label for="title" class="control-label">Subject:</label>
                                            <select required class="form-control" name="subjectID">
                                                <?php foreach ($subjects as $s){ ?>
                                                    <option value="<?=$s->subjectID?>" <?php if ($s->subjectID == $f->subjectID){echo "selected";}?>><?=$s->subject_name." ( ".$s->subject_code." ) "?></option>
                                                <?php } ?>
                                            </select>
                                        </div>
                                        <div class="form-group col-sm-6">
                                            <label for="date" class="control-label">Start Time:</label>
                                            <input type="text" required autocomplete="off" class="form-control datepicker1" name="from_date" value="<?=$f->from_date?>">
                                        </div>
                                        <div class="form-group col-sm-6">
                                            <label for="date" class="control-label">End Time:</label>
                                            <input type="text" required autocomplete="off" class="form-control datepicker1" name="to_date" value="<?=$f->to_date?>">
                                        </div>
                                    </div>
                                    <p></p>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-default waves-effect" data-dismiss="modal">Close</button>
                                        <button type="submit" class="btn btn-danger waves-effect waves-light">Save changes</button>
                                    </div>
                                    </form>
                                </div>
                            </div>
                        </div>

                    <?php }?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div id="responsive-modal-add-new" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    <h4 class="modal-title">ADD Exam Routine</h4> </div>
                <form method="POST"">
                <div class="modal-body">

                    <div class="form-group">
                        <label for="title" class="control-label">Subject:</label>
                        <select required class="form-control" name="subjectID">
                            <?php foreach ($subjects as $s){ ?>
                                <option value="<?=$s->subjectID?>"><?=$s->subject_name." ( ".$s->subject_code." ) "?></option>
                            <?php } ?>
                        </select>
                    </div>
                    <div class="form-group col-sm-6">
                        <label for="date" class="control-label">Start Time:</label>
                        <input type="text" required autocomplete="off" class="form-control datepicker1" name="from_date" >
                    </div>
                    <div class="form-group col-sm-6">
                        <label for="date" class="control-label">End Time:</label>
                        <input type="text" required autocomplete="off" class="form-control datepicker1" name="to_date">
                    </div>
                </div>
                <p></p>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default waves-effect" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-danger waves-effect waves-light">ADD</button>
                </div>
                </form>
            </div>
        </div>
    </div>
</div>
<!-- /.row -->
<script>
    $(document).ready(function () {
        $('.datepicker1').datetimepicker({
            format : "YYYY-MM-DD HH:mm",
            inline : true
        });
    });
</script>

