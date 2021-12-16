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
                <table id="example23" class="table table-striped table-bordered table-hover dataTable">
                    <thead>
                    <tr>
                        <th class="col-sm-1">#</th>
                        <th class="col-sm-2">Name</th>
                        <th class="col-sm-3">Usertype</th>
                        <th class="col-sm-2">Username</th>
                        <th class="col-sm-1">Answer</th>
                        <th class="col-sm-1">Added On</th>
                    </tr>
                    </thead>
                    <tfoot>
                    <tr>
                        <th class="col-sm-1">#</th>
                        <th class="col-sm-2">Name</th>
                        <th class="col-sm-3">Usertype</th>
                        <th class="col-sm-2">Username</th>
                        <th class="col-sm-1">Answer</th>
                        <th class="col-sm-1">Added On</th>
                    </tr>
                    </tfoot>
                    <tbody>
                    <!-- Teacher -->

                    <?php if(count($opinion)) {$i = 1; foreach($opinion as $o) {
                        if ($o->usertype == 'school' ){
                            $table = 'school_registration';
                            $primary = 'schoolID';
                        }else{
                            $table = $o->usertype;
                            $primary = $table.'ID';
                        }
                        $user = $this->db->get_where($table,array($primary=>$o->userID))->row();
                        ?>
                        <tr>
                            <td data-title="<?=$this->lang->line('slno')?>">
                                <?php echo $i; ?>
                            </td>
                            <td data-title="Name">
                                <?php
                                echo $user->name;
                                ?>
                            </td>
                            <td data-title="Usertype">
                                <?php echo $o->usertype; ?>
                            </td>
                            <td data-title="Username">
                                <?php
                                echo strtoupper($user->username);
                                ?>
                            </td>
                            <td data-title="Answer">
                                <?php
                                if($poll->ques_type == 'open')
                                {
                                    echo $o->answer;
                                }
                                if($poll->ques_type == 'voting')
                                {
                                    echo $o->vote;
                                }
                                if($poll->ques_type == 'scale')
                                {
                                    echo $o->answer;
                                }
                                if($poll->ques_type == 'option')
                                {
                                    $CI = &get_instance();
                                    $options  = $CI->poll_m->get_options($o->pollID);
                                    foreach ($options as $op) {
                                    }
                                    $o_ans = '';
                                    if($o->answer == '1')
                                    {

                                        $o_ans = $op->option1;
                                    }
                                    if($o->answer == '2')
                                    {
                                        $o_ans = $op->option2;

                                    }
                                    if($o->answer == '3')
                                    {
                                        $o_ans = $op->option3;

                                    }
                                    if($o->answer == '4')
                                    {
                                        $o_ans = $op->option4;

                                    }
                                    echo $o_ans;
                                }
                                ?>
                            </td>

                            <td data-title="Added On">
                                <?php echo date('d M Y',strtotime($o->added_on)); ?>
                            </td>



                        </tr>

                        <?php $i++; } } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>