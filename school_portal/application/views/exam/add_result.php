<?php
/*
| -----------------------------------------------------
| PRODUCT NAME: 	MENTOR ERP
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
    <div class="col-sm-4">
        <div class="white-box">
            <div class="row" style="border: 1px solid black;margin-bottom: 10px">
                <div style="margin: 5px">
                    <h4 class="box-title m-b-0"> Exam Name : <?= $exam->exam_title ?></h4>
                    <h4 class="box-title m-b-0"> Exam Date : <?= date("Y-m-d",strtotime($exam->exam_date)) ?></h4>
                    <h4 class="box-title m-b-0"> Class : <?= $classes->class ?></h4>
                </div>
            </div>
            <div class="form-horizontal">
                <div class="form-group">
                    <label class="col-md-12">Section <span class="red_text">*</span></label>
                    <div class="col-md-12 input-group">
                        <select id="sectionID" class="form-control" name="sectionID" onchange="get_subject()">
                            <option value=""> -- SELECT -- </option>
                            <?php
                            foreach ($section as $sec){
                                $s = $this->db->get_where('section',array('sectionID'=>$sec))->row();
                                echo "<option value='".$s->sectionID."'>".$s->section."</option>>";
                            }
                            ?>
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-md-12">Subject <span class="red_text">*</span></label>
                    <div class="col-md-12 input-group">
                        <select id="subject" class="form-control" name="subject">

                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-md-12">
                        <input type="button" onclick="check_result()" class="btn btn-primary" value="Submit">
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-8">
        <div class="white-box">
            <div class="row" style="border: 1px solid black;margin-bottom: 10px">
                <div style="margin: 5px">
                    <h4 class="box-title m-b-0"><span style="float: left">Section : <span id="section_name"></span></span><span style="float: right">Subject : <span id="subject_name"></span></span></h4>
                </div>
            </div>
            <div class="table-responsive">
                <table id="result_table" class="display table table-hover table-bordered" cellspacing="0" width="100%">
                    <thead>
                    <tr>
                        <th>#</th>
                        <th>Student Name</th>
                        <th>Roll No</th>
                        <th>Obtained Marks</th>
                        <th>Max Marks</th>
                    </tr>
                    </thead>
                    <tbody id="result_display"></tbody>
                    <tfoot>
                    <tr>
                        <th>#</th>
                        <th>Student Name</th>
                        <th>Roll No</th>
                        <th>Obtained Marks</th>
                        <th>Max Marks</th>
                    </tr>
                    </tfoot>
                </table>
                <label id="total_marks_label" style="display: none">Max Marks : </label>
                <input style="display: none" id="total_marks" type="number">
                <input style="display: none" id="update_result" type="button" class="btn btn-success" value="UPDATE" onclick="update_result()">
            </div>
        </div>
    </div>

</div>
<!-- /.row -->
<script>
    function get_subject() {
        var sectionID = $("#sectionID").val();
        if (sectionID != ""){
            var section_name = $("#sectionID option:selected").text();
            $("#section_name").text(section_name);
            $.ajax({
                url : "<?= base_url("exam/get_subjects")?>",
                method : "post",
                data : "sectionID="+sectionID,
                success : function (res) {
                    res = JSON.parse(res);
                    if (res[0]['result'].toUpperCase() == "SUCCESS") {
                        var subject_option = "<option value = ''> -- SELECT -- </option>";
                        for (var i in res[0]['subject']) {
                            subject_option += "<option value='"+res[0]['subject'][i]['subject_name']+"'>"+res[0]['subject'][i]['subject_name']+" ( "+res[0]['subject'][i]['subject_code']+" ) </option>";
                        }
                        $("#subject").html(subject_option);
                    }

                }
            });
        }
    }
</script>
<script>
    function check_result() {
        var examID = "<?= $exam->examID ?>";
        var subject = $("#subject").val();
        var sectionID = $("#sectionID").val();
        if (examID == "" || subject == "" || sectionID == ""){
            alert("please fill all the mandatory fields");
        }else{
            $("#subject_name").text($("#subject").val());
            $.ajax({
               url : "<?= base_url("exam/check_result")?>",
               type : "post",
               data : "examID="+examID+"&subject="+subject+"&sectionID="+sectionID,
               success : function (res) {
                    res = JSON.parse(res);
                    if (res[0]['result'].toUpperCase() == "SUCCESS") {
                        var students = "";
                        var count = 1;
                        for (var i in res[0]['student']) {
                            if(res[0]['student'][i]['mark_obtain'] === null){
                                var obtained_marks = "";
                            }else{
                                var obtained_marks = res[0]['student'][i]['mark_obtain'];
                            }
                            if (res[0]['student'][i]['total_mark'] === null){
                                var total_mark = "";
                            }else{
                                var total_mark = res[0]['student'][i]['total_mark'];
                            }
                            students += "<tr><td>"+count+"</td><td>"+res[0]['student'][i]['name']+"</td><td>"+res[0]['student'][i]['roll_no']+"</td><td><input att='"+res[0]['student'][i]['studentID']+"' type='text' class='form-control obtained_marks' value='"+obtained_marks+"'></td><td>"+total_mark+"</td></tr>";
                            count += 1;
                        }
                        $("#result_display").html(students);
                        $("#total_marks_label").css('display','inline-block');
                        $("#total_marks").css('display','inline-block');
                        $("#update_result").css('display','inline-block');
                    }
               }
            });
        }
    }
</script>
<script>
    function update_result() {
        var inputs = "";
        var inputs_value = "";
        $(".obtained_marks").each(function (index, value) {
            inputs_value = $(this).val();
            if(inputs_value == '' || inputs_value == null) {
                inputs += $(this).attr("att") +":"+'0'+"$";
            } else {
                inputs += $(this).attr("att") +":"+inputs_value+"$";
            }
        });
        var subject = $("#subject_name").text();
        var total = $("#total_marks").val();
        $.ajax({
            type: 'POST',
            url: "<?=base_url("exam/add_student_result")?>",
            data: {"examID" : "<?=$exam->examID?>", "total_marks":total, "subject" : subject, "marks" : inputs},
            dataType: "html",
            success: function(data) {
                if (data.toUpperCase() == "SUCCESS"){
                    $.toast({
                        heading: data.toUpperCase(),
                        text: 'Updated Successfully.',
                        position: 'top-right',
                        loaderBg: '#ff6849',
                        icon: 'success',
                        hideAfter: 1500,
                        stack: 6
                    });
                }
            }
        });
    }
</script>
