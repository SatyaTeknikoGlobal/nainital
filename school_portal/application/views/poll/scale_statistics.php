<div class="row">
    <div class="col-sm-12">
        <div class="white-box">
            <h3 class="box-title m-b-0"><?=$title?></h3>
            <br>
            <div id="hide-table">
                <table id="example23" class="table table-striped table-bordered table-hover dataTable no-footer">
                    <thead>
                    <tr>

                        <th class="col-sm-4" width="50%">Scale Value</th>
                        <th class="col-sm-2" width="50%">No. of Answers</th>


                    </tr>
                    </thead>
                    <tbody>
                    <!-- Teacher -->

                    <?php
                    $i = 1;
                    foreach($scale as $s) {
                        $scale1 = $s->scale1;
                        $scale2 = $s->scale2;
                    }

                    for($j=$scale1;$j<=$scale2;$j++) {

                        ?>
                        <tr>


                            <td data-title="Scale Value" style="text-align: center;">
                                <?php echo $j; ?>
                            </td>

                            <td data-title="No. of Answers">
                                <?php
                                $CI =& get_instance();
                                $no_of_user = $CI->poll_m->get_ans_vote($qid,$j);

                                echo count($no_of_user);


                                ?>
                            </td>
                        </tr>
                        <?php $i++; }  ?>



                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>