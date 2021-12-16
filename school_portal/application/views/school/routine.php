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
            <h3 class="box-title m-b-0">SELECT CLASS AND SECTION</h3>
            <p class="text-muted m-b-30 font-13"> SELECT CLASS AND SECTION AND CLICK ON VIEW </p>
            <div class="form-inline">
                <div class="col-sm-5">
                    <label class="col-sm-3">Class : </label>
                    <div class="col-sm-8">
                        <select class="form-control" style="width: 100%" name="classID" id="classID" required>
                            <option value="">--SELECT--</option>
                            <?php foreach ($classes as $c){ ?>
                                <option value="<?=$c->classID?>"><?=$c->class?></option>
                            <?php } ?>
                        </select>
                    </div>
                </div>
                <div class="col-sm-5">
                    <label class="col-sm-3">SECTION : </label>
                    <div class="col-sm-8">
                        <select class="form-control" style="width: 100%" name="sectionID" id="sectionID" required>


                        </select>
                    </div>
                </div>
                <div class="col-sm-2">
                    <div class="col-md-12">
                        <input class="btn btn-success" type="button" id="view_button" onclick="routine_refresh()" value="VIEW"> </div>
                </div>
            </div>
            <h3>&nbsp;</h3>
        </div>
    </div>

</div>
<!-- /.row -->
<!-- .row -->
<div class="row">
    <div class="col-sm-12">
        <div class="white-box table-responsive">
            <h3 class="box-title m-b-0">ROUTINE</h3>
            <p class="text-muted m-b-30 font-13"> Manage Routine from here </p>
            <table id="routine_show" class="table table-bordered"></table>
        </div>
    </div>

</div>
<!-- /.row -->
<script>
    function routine_refresh() {
        var classID = $('#classID').val();
        var sectionID = $('#sectionID').val();
        var data = "classID="+classID+"&sectionID="+sectionID;
        if (classID == "" || sectionID == "")
        {
            alert("Please Select Class and Section");
        }else
        {
            $.ajax({
                url: "<?= base_url("configuration/routine_getbysectionclass")?>",
                method: "POST",
                data:data,
                success:function(res)
                {
                    var myObj = JSON.parse(res);
                    if (myObj[0]['response'] == "success")
                    {
                        if (myObj[0]['slots'] == '')
                        {
                            alert("Please Configure Slot First");
                        }else
                        {
                            var i,j,k,l,m,routine;
                            routine = '<thead>';
                            routine += '<tr>';
                            routine += '<th class="text-center" style="background-color: #ff7676; color: #ffffff">SLOTS<br>DAY</th>';
                            for(j in myObj[0]['slots']){

                                routine += '<th class="text-center" style="background-color: #087ccd; color: #ffffff">'+myObj[0]['slots'][j]['start_time']+' - '+myObj[0]['slots'][j]['end_time']+'</th>'
                            }
                            routine += '</tr>';
                            routine += '</thead>';
                            routine += '<tbody>';
                            i = 0;
                            for(i in myObj[0]['days']){
                                routine += '<tr><th class="text-center" style="background-color: #087ccd; color: #ffffff">'+myObj[0]['days'][i]+'</th>';
                                j = 0;
                                for(j in myObj[0]['slots']){
                                    k = 0;
                                    for(k in myObj[0]['routine']){
                                        //var routineID = '';
                                        var subject = 'undefined';
                                        var teacher = 'undefined';
                                        var subjectID = '';
                                        var teacherID = '';
                                        if ((myObj[0]['slots'][j]['lecture'] == myObj[0]['routine'][k]['lecture']) && (myObj[0]['days'][i] == myObj[0]['routine'][k]['day'])) {
                                            //routineID = myObj[0]['routine'][k]['routineID'];
                                            subject = myObj[0]['routine'][k]['subject_name'];
                                            teacher = myObj[0]['routine'][k]['teacher_name'];
                                            subjectID = myObj[0]['routine'][k]['subjectID'];
                                            teacherID = myObj[0]['routine'][k]['teacherID'];
                                            break;
                                        }
                                    }

                                    if (myObj[0]['slots'][j]['type'] == "interval"){
                                        routine += '<td class="text-center" style="background-color: #a95656; color: #ffffff">LUNCH</td>';
                                    }else {
                                        routine += '<td class="text-center" style="background-color: #bf8c8c; color: #ffffff"><a style="color: #ffffff" data-toggle="modal" data-target="#responsive-modal'+myObj[0]['days'][i]+myObj[0]['slots'][j]['lecture']+'">'+subject+'<br>'+teacher+'</a><div style = "text-align: left;color: #000;" id="responsive-modal'+myObj[0]['days'][i]+myObj[0]['slots'][j]['lecture']+'" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;"><div class="modal-dialog"><div class="modal-content"><div class="modal-header"><button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button><h4 class="modal-title">Update Routine</h4> </div><form id="update_form'+myObj[0]['days'][i]+myObj[0]['slots'][j]['lecture']+'" enctype="multipart/form-data" action="<?php echo base_url("/configuration/update_routine/");?>'+myObj[0]['days'][i]+'/'+myObj[0]['slots'][j]['lecture']+'/'+classID+'/'+sectionID+'" method="POST"><div class="modal-body"><div class="form-group"><label for="class" class="control-label">Subject: </label><SELECT class = "form-control" name="subjectID" id = "subjectID'+myObj[0]['days'][i]+myObj[0]['slots'][j]['lecture']+'"><option value = "">--SELECT--</option>';
                                        l = 0;
                                        for(l in myObj[0]['subjects']){
                                            if (myObj[0]['subjects'][l]['subjectID'] == subjectID) {
                                                routine += '<option selected value ="'+myObj[0]['subjects'][l]['subjectID']+'">'+myObj[0]['subjects'][l]['subject_name']+' ('+myObj[0]['subjects'][l]['subject_code']+')</option>';
                                            }else{
                                                routine += '<option value ="'+myObj[0]['subjects'][l]['subjectID']+'">'+myObj[0]['subjects'][l]['subject_name']+' ('+myObj[0]['subjects'][l]['subject_code']+')</option>';
                                            }
                                            
                                        }
                                        routine +='</SELECT></div>';

                                        routine += '<div class="form-group"><label for="class" class="control-label">Teacher: </label><SELECT name="teacherID" class = "form-control" id = "teacherID'+myObj[0]['days'][i]+myObj[0]['slots'][j]['lecture']+'"><option value = "">--SELECT--</option>';
                                        m=0;
                                        for(m in myObj[0]['teachers']){
                                            if (myObj[0]['teachers'][m]['teacherID'] == teacherID) {
                                                routine += '<option selected value ="'+myObj[0]['teachers'][m]['teacherID']+'">'+myObj[0]['teachers'][m]['name']+'</option>';
                                            }else{
                                                routine += '<option value ="'+myObj[0]['teachers'][m]['teacherID']+'">'+myObj[0]['teachers'][m]['name']+'</option>';
                                            }
                                            
                                        }
                                        routine +='</SELECT></div></div><div class="modal-footer"><button type="button" class="btn btn-default waves-effect" data-dismiss="modal">Close</button><input class="update_routine btn btn-danger waves-effect waves-light" type="button" value="update" att="'+myObj[0]['days'][i]+myObj[0]['slots'][j]['lecture']+'"></div></form></div></div></div></td>';
                                    }


                                }
                                routine += '</tr>';
                            }
                            routine += '</tbody>';
                            document.getElementById("routine_show").innerHTML = routine;
                        }
                    }

                }
            });
        }
    }
</script>
<script>
    $('document').ready(function () {
       $('#classID').change(function () {
          var classID = $(this).val();
           var data = "classID="+classID;
          if (classID != ''){
              $.ajax({
                  url: "<?= base_url("configuration/get_sectionbyclass")?>",
                  method: "POST",
                  data:data,
                  success:function(res)
                  {
                      var i, x = "";
                      var myObj = JSON.parse(res);
                      x += "<option value=''>--select--</option>";
                      for (i in myObj) {

                          x += "<option value='" + myObj[i].sectionID + "'>" + myObj[i].section + "</option>";

                      }

                      document.getElementById("sectionID").innerHTML = x;
                  }
              });
          }
       });
    });
</script>
<script>
    $(document).on("click",".update_routine",function(e){
        var id = $(this).attr("att");
        e.preventDefault();
        var formData =$("#update_form"+id).submit(function(e){
                return ;
            });
        var formData = new FormData(formData[0]);
        $.ajax({
            url: $("#update_form"+id).attr('action'),
            type: 'POST',
            data: formData,
            success: function(res){
                if(res.toUpperCase() == "SUCCESS"){
                    $.toast({
                        heading: res.toUpperCase(),
                        text: 'Updated Successfully.',
                        position: 'top-right',
                        loaderBg: '#ff6849',
                        icon: 'success',
                        hideAfter: 1500,
                        stack: 6
                    });
                }else{
                    $.toast({
                        heading: res.toUpperCase(),
                        text: 'Failed to Update.',
                        position: 'top-right',
                        loaderBg: '#ff6849',
                        icon: 'error',
                        hideAfter: 1500,
                        stack: 6
                    });
                }
                $("#responsive-modal"+id).modal('hide');
                routine_refresh();
            },
            contentType: false,
            processData: false,
            cache: false
        });
    });
</script>

