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
            <div class="form-inline">
                <div class="col-sm-2">
                    <label class="col-sm-12">Batch (optional) : </label>
                    <div class="col-sm-12">
                        <select class="form-control" style="width: 100%" name="batchID" id="batchID">
                            <option value="">--SELECT--</option>
                            <?php foreach ($batch as $c){ ?>
                                <option value="<?=$c->batchID?>"><?=$c->batch?></option>
                            <?php } ?>
                        </select>
                    </div>
                </div>
                <div class="col-sm-2">
                    <label class="col-sm-12">Session : </label>
                    <div class="col-sm-12">
                        <select class="form-control" style="width: 100%" name="session" id="session" required>
                            <?php foreach ($session as $c){ ?>
                                <option value="<?=$c->session?>"><?=$c->session?></option>
                            <?php } ?>
                        </select>
                    </div>
                </div>
                <div class="col-sm-2">
                    <label class="col-sm-12">Class : </label>
                    <div class="col-sm-12">
                        <select class="form-control" style="width: 100%" name="classID" id="classID" required>
                            <option value="">--SELECT--</option>
                            <?php foreach ($classes as $c){ ?>
                                <option value="<?=$c->classID?>"><?=$c->class?></option>
                            <?php } ?>
                        </select>
                    </div>
                </div>
                <div class="col-sm-2">
                    <label class="col-sm-12">Section: </label>
                    <div class="col-sm-12">
                        <select class="form-control" style="width: 100%" name="sectionID" id="sectionID" required>


                        </select>
                    </div>
                </div>
                 <div class="col-sm-2">
                    <label class="col-sm-12">Period : </label>
                    <div class="col-sm-12">
                        <select class="form-control" style="width: 100%" name="month-year" id="month-year1" required>


                        </select>
                    </div>
                </div>
                <div class="col-sm-1">
                    <div class="col-md-12">
                        <button class="btn btn-success" onclick="viewinvoices()"> View </button>
                    </div>
                    <p id="demo"></p>
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
        <div class="white-box">
            <h3 class="box-title m-b-0"><?=$title?></h3>


            <div class="table-responsive">
                <table id="invoice_table" class="display nowrap table table-hover table-striped" cellspacing="0" width="100%">
                    
                </table>
            </div>
        </div>
    </div>
</div>
<!-- /.row -->
<script>
    function viewinvoices() {
        var batchID = $('#batchID').val();
        var classID = $('#classID').val();
        var sectionID = $('#sectionID').val();
        var monthyear = $('#month-year1').val();
        if (batchID == ""){
            batchID = null;
        }
        var data = "batchID="+batchID+"&classID="+classID+"&sectionID="+sectionID+"&monthyear="+monthyear;
        if (classID == "" || sectionID == "" || monthyear == "" || monthyear == null)
        {
            alert("Please Select Class, Section and Period");
        }else {
            $.ajax({
                url: "<?= base_url("accounts/get_invoices")?>",
                method: "POST",
                data:data,
                success:function(res)
                {
                    var myObj = JSON.parse(res);
                    var i;
                    var invoices = '<thead><tr><th>Session</th><th>Name</th><th>Class</th><th>Section</th><th>Roll</th><th>Total</th><th>Concession</th><th>Payable</th><th>Paid</th><th>Due Amount</th><th>Due Date</th><th>Action</th></tr></thead><tfoot><tr><th>Session</th><th>Name</th><th>Class</th><th>Section</th><th>Roll</th><th>Total</th><th>Concession</th><th>Payable</th><th>Paid</th><th>Due Amount</th><th>Due Date</th><th>Action</th></tr></tfoot><tbody>';
                    for (i in myObj)
                    {
                        var session = myObj[i]['session'];
                        var paid = myObj[i]['paid_amount'];
                        var section = myObj[i]['section'];
                        var classes = myObj[i]['class'];
                        var roll = myObj[i]['roll_no'];
                        var name = myObj[i]['name'];
                        var total = myObj[i]['invoice_total'];
                        var concession = myObj[i]['concession'];
                        var payable_amount = myObj[i]['payable_amount'];
                        var due_amount = myObj[i]['due_amount'];
                        var due_date = myObj[i]['due_date'];
                        var secret = '/'+myObj[i]['secret'];
                        var view_invoice = "<a class='btn btn-success text-center' target='_blank' href='<?=base_url("accounts/view_slip/")?>"+myObj[i]['invoiceID']+secret+"'><i class='fa fa-eye'></i></a>";
                        var pay = "<a class='btn btn-primary text-center' target='_blank' href='<?=base_url("accounts/pay/")?>"+myObj[i]['invoiceID']+secret+"'>Pay</a>";
                        if (due_amount > 0){
                            invoices += '<tr><td>'+session+'</td><td>'+name+'</td><td>'+classes+'</td><td>'+section+'</td><td>'+roll+'</td><td>'+total+'</td><td>'+concession+'</td><td>'+payable_amount+'</td><td>'+paid+'</td><td>'+due_amount+'</td><td>'+due_date+'</td><td>'+view_invoice+"&nbsp;&nbsp;"+pay+'</td></tr>';
                        } else {
                            invoices += '<tr><td>'+session+'</td><td>'+name+'</td><td>'+classes+'</td><td>'+section+'</td><td>'+roll+'</td><td>'+total+'</td><td>'+concession+'</td><td>'+payable_amount+'</td><td>'+paid+'</td><td>'+due_amount+'</td><td>'+due_date+'</td><td>'+view_invoice+'</td></tr>';
                        }
                    }
                    invoices += '</tbody>';
                    $("#invoice_table").html(invoices);
                    $("#invoice_table").DataTable().destroy();
                    $("#invoice_table").DataTable();
                }
            });
        }
    }
</script>
<script>
    $('document').ready(function () {
        $('#classID').change(function () {
            var classID = $(this).val();
            var session = $('#session').val();
            var data = "classID="+classID+"&session="+session;
            if (classID != ''){
                $.ajax({
                    url: "<?= base_url("accounts/get_section")?>",
                    method: "POST",
                    data:data,
                    success:function(res)
                    {
                        var i, x, j, y = "";
                        var myObj = JSON.parse(res);
                        if (myObj['result'] == 'success'){
                            var section = myObj['section'];
                            var period = myObj['period'];
                            for (i in section) {

                                x += "<option value='" + section[i].sectionID + "'>" + section[i].section + "</option>";

                            }
                            document.getElementById("sectionID").innerHTML = x;
                            for (j in period) {

                                y += "<option value='" + period[j]['date'] + "'>" + period[j]['value'] + "</option>";

                            }
                            document.getElementById("month-year1").innerHTML = y;
                        }
                    }
                });
            }
        });
    });
</script>
<script>
    $(document).ready(function(){
        $('.month-year').datepicker({
             format: "MM yyyy",
            startView: "year", 
            minViewMode: "months"
        }).on('changeDate', function(e){
            $(this).datepicker('hide');
        });
    });
</script>

