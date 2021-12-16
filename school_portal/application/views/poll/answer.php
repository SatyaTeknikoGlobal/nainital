<?php
foreach ($check_answer as $value) {
    
}
?>
<div class="box">
    <div class="box-header">
        <h3 class="box-title"><i class="fa fa-leaf"></i> <?=$this->lang->line('panel_title')?></h3>

        <ol class="breadcrumb">
            <li><a href="<?=base_url("dashboard/index")?>"><i class="fa fa-laptop"></i> <?=$this->lang->line('menu_dashboard')?></a></li>
            <li><a href="<?=base_url("poll/index")?>"><?=$this->lang->line('menu_answer')?></a></li>
            <li class="active"><?=$this->lang->line('menu_add')?> <?=$this->lang->line('menu_answer')?></li>
        </ol>
    </div><!-- /.box-header -->
    <!-- form start -->
    <div class="box-body">
        <div class="row">
            <div class="col-sm-8">
                <h3>Ques : <strong><?=$poll->question?></strong></h3>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-8">
            <?php if($poll->ques_type=='option')
            {
                  $CI = &get_instance();
                  $options  = $CI->poll_m->get_options($poll_id);
                if(count($check_answer)>0)
                {
                     foreach ($options as $o) {
                            } 
                    $o_ans = '';
                    if($value->answer == '1')
                    {

                    $o_ans = $o->option1;
                    }
                     if($value->answer == '2')
                    {
                         $o_ans = $o->option2;
 
                    }
                     if($value->answer == '3')
                    {
                         $o_ans = $o->option3;

                    }
                     if($value->answer == '4')
                    {
                         $o_ans = $o->option4;

                    }


                ?>   

                         <div class='form-group' >
 <label for="note" class="col-sm-2 control-label">
                            <?=$this->lang->line("answer")?>:
                        </label>
                        <div class="col-sm-6">
                       <h5><?=$o_ans?> </h5>
                        </div>
                       
                    </div>

                    <?php
                }else
                {
                    ?>

                
            <form class="form-horizontal" role="form" method="post">
             <input type="hidden" name="qtype" id="qtype" value="option">
                 <?php 
                        if(form_error('answer')) 
                            echo "<div class='form-group has-error' >";
                        else     
                            echo "<div class='form-group' >";
                    ?>
                        <label for="note" class="col-sm-2 control-label">
                            <?=$this->lang->line("answer")?>
                        </label>
                        <div class="col-sm-6">
                         <?php
                         foreach ($options as $o) {
                            } 
                            ?>
                            <input type="radio"  name="answer" id="answer" value="1" required><?=$o->option1?><br>
                             <input type="radio"  name="answer" id="answer" value="2"><?=$o->option2?><br>
                              <input type="radio"  name="answer" id="answer" value="3"><?=$o->option3?><br>
                               <input type="radio"  name="answer" id="answer" value="4"><?=$o->option4?><br>
                            
                        </div>
                         <span class="col-sm-4 control-label">
                            <?php echo form_error('answer'); ?>
                        </span>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-offset-2 col-sm-8">
                            <input type="submit" class="btn btn-success" value="<?=$this->lang->line("add_answer")?>" >
                        </div>
                    </div>
                  
            
            </form>
            <?php
        }
            }
            elseif($poll->ques_type=='scale')
            {
                $CI = &get_instance();
                  $scale  = $CI->poll_m->get_options($poll_id);
                   foreach ($scale as $s) {
                            } 
                   if(count($check_answer)>0)
                {
                   ?>

                                            <div class='form-group' >
 <label for="note" class="col-sm-2 control-label">
                            <?=$this->lang->line("answer")?>:
                        </label>
                        <div class="col-sm-6">
                       <h5>Scale Value : <?=$value->answer?> </h5>
                        </div>
                       
                    </div>

            <?php
                }
                else
                {
                    ?>
       <form class="form-horizontal" role="form" method="post">
            <input type="hidden" name="qtype" id="qtype" value="scale">

                    <?php 
                        if(form_error('answer')) 
                            echo "<div class='form-group has-error' >";
                        else     
                            echo "<div class='form-group' >";
                    ?>
                        <label for="note" class="col-sm-2 control-label">
                            <?=$this->lang->line("answer")?>
                        </label>
                        <div class="col-sm-6">
                         <input type="range"  min="<?=$s->scale1?>" max="<?=$s->scale2?>" name="answer" value="0"  onchange="showValue(this.value)" />
                         <br><br>
                         Select Value
                         <span id="range">0</span>
                        </div>
                         <span class="col-sm-4 control-label">
                            <?php echo form_error('answer'); ?>
                        </span>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-offset-2 col-sm-8">
                            <input type="submit" class="btn btn-success" value="<?=$this->lang->line("add_answer")?>" >
                        </div>
                    </div>

            </form>
                    <?php
                }

            }
            elseif($poll->ques_type=='voting')
            {
                 if(count($check_answer)>0)
                {
                ?>
                <div class='form-group' >
 <label for="note" class="col-sm-2 control-label">
                            <?=$this->lang->line("answer")?>:
                        </label>
                        <div class="col-sm-6">
                       <h5>  <?php if($value->vote == 'Y'){ echo "Yes"; } else{ echo "No"; }?> </h5>
                        </div>
                       
                    </div>


                <?php
            }
            else
            {
                 ?>

                  <form class="form-horizontal" role="form" method="post">
            <input type="hidden" name="qtype" id="qtype" value="voting">

                    <?php 
                        if(form_error('answer')) 
                            echo "<div class='form-group has-error' >";
                        else     
                            echo "<div class='form-group' >";
                    ?>
                        <label for="note" class="col-sm-2 control-label">
                            <?=$this->lang->line("answer")?>
                        </label>
                        <div class="col-sm-6">
                          <select class="form-control" id="answer" name="answer" required>
                              <option value="">Select </option>
                              <option value="Y">Yes</option>
                              <option value="N">No</option>
                          </select>
                        </div>
                         <span class="col-sm-4 control-label">
                            <?php echo form_error('answer'); ?>
                        </span>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-offset-2 col-sm-8">
                            <input type="submit" class="btn btn-success" value="<?=$this->lang->line("add_answer")?>" >
                        </div>
                    </div>

            </form>

                 <?php

            }
                
            }
            else
            {
                if(count($check_answer)>0)
                {
                  
                  
                    ?>
<div class='form-group' >
 <label for="note" class="col-sm-2 control-label">
                            <?=$this->lang->line("answer")?>:
                        </label>
                        <div class="col-sm-6">
                       <h5>  <?=$value->answer?> </h5>
                        </div>
                       
                    </div>

                    <?php

                }else
                {

                
            ?>

            <form class="form-horizontal" role="form" method="post">
            <input type="hidden" name="qtype" id="qtype" value="open">

                    <?php 
                        if(form_error('answer')) 
                            echo "<div class='form-group has-error' >";
                        else     
                            echo "<div class='form-group' >";
                    ?>
                        <label for="note" class="col-sm-2 control-label">
                            <?=$this->lang->line("answer")?>
                        </label>
                        <div class="col-sm-6">
                            <textarea class="form-control" id="answer" name="answer"><?=set_value('answer')?></textarea>
                        </div>
                         <span class="col-sm-4 control-label">
                            <?php echo form_error('answer'); ?>
                        </span>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-offset-2 col-sm-8">
                            <input type="submit" class="btn btn-success" value="<?=$this->lang->line("add_answer")?>" >
                        </div>
                    </div>

            </form>
            <?php
        }
       }
            ?>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
function showValue(newValue)
{
    document.getElementById("range").innerHTML=newValue;
}
</script>