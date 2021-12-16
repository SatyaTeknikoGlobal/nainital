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
    <div class="col-sm-12">
        <div class="white-box">
            <h3 class="box-title m-b-0"><?=$title?></h3>
            <div class="table-responsive">
                <table id="example23" class="display table table-bordered table-hover table-striped" cellspacing="0" width="100%">
                    <thead>
                    <tr>
                        <th>#</th>
                        <th>Question</th>
                        <th>Question Type</th>
                        <th>Start Date</th>
                        <th>End Date</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                    </thead>
                    <tfoot>
                    <tr>
                        <th>#</th>
                        <th>Question</th>
                        <th>Question Type</th>
                        <th>Start Date</th>
                        <th>End Date</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                    </tfoot>
                    <tbody>
                    <?php $count = 0; foreach ($polls as $poll){ $count++; ?>
                        <tr>
                            <td><?= $count ?></td>
                            <td><?= $poll->question ?></td>
                            <td><?php if($poll->ques_type == 'option' || $poll->ques_type == 'scale'){ echo "<a class='btn btn-default btn-xs' href='add_option/".$poll->ques_type."/".$poll->pollID."'>".$poll->ques_type."</a>";} else echo $poll->ques_type; ?></td>
                            <td><?= date("d M Y",strtotime($poll->start_date))?></td>
                            <td><?= date("d M Y",strtotime($poll->end_date))?></td>
                            <td>
                                <?php
                                if($poll->status == 'N') {
                                    echo "<button class='status_btn btn btn-danger btn-xs' att = 'N'> Unavailable </button>";
                                } else {
                                    echo "<button class='status_btn btn btn-success btn-xs' att = 'Y'> Available </button>";
                                }
                                ?>
                            </td>
                            <td>
                                <?php echo "<a class='btn btn-primary btn-xs' title='Opinions' href= 'view_opinion/".$poll->pollID."'><i class='fa fa-eye'></i></a>" ?>
                                <?php echo "<a class='btn btn-success btn-xs' title='Delete' href= 'delete/".$poll->pollID."'><i class='fa fa-trash'></i></a>" ?>
                                <?php if($poll->ques_type == 'option' || $poll->ques_type == 'voting' || $poll->ques_type == 'scale'){ echo "<a class='btn btn-info btn-xs' href='statistics/".$poll->ques_type."/".$poll->pollID."'>Statistics</a>";} ?>
                            </td>
                        </tr>
                    <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>
