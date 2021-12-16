
<div class="box">
    <div class="box-header">
        <h3 class="box-title"><i class="fa fa-leaf"></i> <?=$this->lang->line('panel_title')?></h3>

        <ol class="breadcrumb">
            <li><a href="<?=base_url("dashboard/index")?>"><i class="fa fa-laptop"></i> <?=$this->lang->line('menu_dashboard')?></a></li>
            <li><a href="<?=base_url("poll/index")?>"><?=$this->lang->line('menu_poll')?></a></li>
            <li class="active"><?=$this->lang->line('menu_add')?> <?=$this->lang->line('menu_poll')?></li>
        </ol>
    </div><!-- /.box-header -->
    <!-- form start -->
    <div class="box-body">
        <div class="row">
            <div class="col-sm-8">
                <form class="form-horizontal" role="form" method="post">

                    <?php 
                        if(form_error('question')) 
                            echo "<div class='form-group has-error' >";
                        else     
                            echo "<div class='form-group' >";
                    ?>
                        <label for="note" class="col-sm-2 control-label">
                            <?=$this->lang->line("question")?>
                        </label>
                        <div class="col-sm-6">
                            <textarea class="form-control" id="question" name="question"><?=set_value('question',$poll->question)?></textarea>
                        </div>
                         <span class="col-sm-4 control-label">
                            <?php echo form_error('question'); ?>
                        </span>
                    </div>

                    <?php 
                        if(form_error('ques_type')) 
                            echo "<div class='form-group has-error' >";
                        else     
                            echo "<div class='form-group' >";
                    ?>
                        <label for="class_type" class="col-sm-2 control-label">
                            <?=$this->lang->line("ques_type")?>
                        </label>
                        <div class="col-sm-6">
                              <?php
                                $array = array();
                                $array['open'] = 'open';
                                $array['option'] = 'option';
                                $array['voting'] = 'voting';
                                $array['scale'] = 'scale';
                                echo form_dropdown("ques_type", $array, set_value("ques_type",$poll->ques_type), "id='ques_type' class='form-control'");
                            ?>
                        </div>
                        <span class="col-sm-4 control-label">
                            <?php echo form_error('ques_type'); ?>
                        </span>
                    </div>
                     <?php 
                        if(form_error('start_date')) 
                            echo "<div class='form-group has-error' >";
                        else     
                            echo "<div class='form-group' >";
                    ?>
                        <label for="start_date" class="col-sm-2 control-label">
                            <?=$this->lang->line("start_date")?>
                        </label>
                        <div class="col-sm-6">
                            <input type="text" class="form-control" id="start_date" name="start_date" value="<?=set_value('start_date',date('d-m-Y',strtotime($poll->start_date)))?>" >
                        </div>
                        <span class="col-sm-4 control-label">
                            <?php echo form_error('start_date'); ?>
                        </span>
                    </div>

                     <?php 
                        if(form_error('end_date')) 
                            echo "<div class='form-group has-error' >";
                        else     
                            echo "<div class='form-group' >";
                    ?>
                        <label for="end_date" class="col-sm-2 control-label">
                            <?=$this->lang->line("end_date")?>
                        </label>
                        <div class="col-sm-6">
                            <input type="text" class="form-control" id="end_date" name="end_date" value="<?=set_value('end_date',date('d-m-Y',strtotime($poll->end_date)))?>" >
                        </div>
                        <span class="col-sm-4 control-label">
                            <?php echo form_error('end_date'); ?>
                        </span>
                    </div>

                    <?php 
                        if(form_error('status')) 
                            echo "<div class='form-group has-error' >";
                        else     
                            echo "<div class='form-group' >";
                    ?>
                        <label for="class_type" class="col-sm-2 control-label">
                            <?=$this->lang->line("status")?>
                        </label>
                        <div class="col-sm-6">
                              <?php
                                $array = array();
                                $array['Y'] = 'Active';
                                $array['N'] = 'Inactive';
                                echo form_dropdown("status", $array, set_value("status",$poll->status), "id='status' class='form-control'");
                            ?>
                        </div>
                        <span class="col-sm-4 control-label">
                            <?php echo form_error('ques_type'); ?>
                        </span>
                    </div>
                    

                    <div class="form-group">
                        <div class="col-sm-offset-2 col-sm-8">
                            <input type="submit" class="btn btn-success" value="<?=$this->lang->line("add_poll")?>" >
                        </div>
                    </div>

                </form>

            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    $("#start_date").datepicker();
    $("#end_date").datepicker();
</script>