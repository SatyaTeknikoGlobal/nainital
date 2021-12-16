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

<!-- /.container-fluid -->
<footer class="footer text-center"> 2018-19 &copy;Nainital ERP </footer>
</div>
<!-- ============================================================== -->
<!-- End Page Content -->
<!-- ============================================================== -->
</div>
<!-- ============================================================== -->
<!-- End Wrapper -->
<!-- ============================================================== -->
<!-- ============================================================== -->

<!-- Bootstrap Core JavaScript -->
<script src="<?=base_url("assets")?>/bootstrap/dist/js/bootstrap.min.js"></script>
<!-- Menu Plugin JavaScript -->
<script src="<?=base_url("assets")?>/plugins/bower_components/sidebar-nav/dist/sidebar-nav.min.js"></script>
<!--slimscroll JavaScript -->
<script src="<?=base_url("assets")?>/js/jquery.slimscroll.js"></script>
<!--Wave Effects -->
<script src="<?=base_url("assets")?>/js/waves.js"></script>
<!--Morris JavaScript -->
<script src="<?=base_url("assets")?>/plugins/bower_components/raphael/raphael-min.js"></script>
<script src="<?=base_url("assets")?>/plugins/bower_components/morrisjs/morris.js"></script>
<!-- chartist chart -->
<script src="<?=base_url("assets")?>/plugins/bower_components/chartist-js/dist/chartist.min.js"></script>
<script src="<?=base_url("assets")?>/plugins/bower_components/chartist-plugin-tooltip-master/dist/chartist-plugin-tooltip.min.js"></script>
<!-- Calendar JavaScript -->
<script src="<?=base_url("assets")?>/plugins/bower_components/moment/moment.js"></script>
<script src='<?=base_url("assets")?>/plugins/bower_components/calendar/dist/fullcalendar.min.js'></script>
<!--<script src="<?/*=base_url("assets")*/?>/plugins/bower_components/calendar/dist/cal-init.js"></script>-->
<!-- Custom Theme JavaScript -->
<script src="<?=base_url("assets")?>/js/custom.min.js"></script>
<script src="<?=base_url("assets")?>/js/dashboard1.js"></script>
<!-- Custom tab JavaScript -->
<script src="<?=base_url("assets")?>/js/cbpFWTabs.js"></script>
<script type="text/javascript">
    (function() {
        [].slice.call(document.querySelectorAll('.sttabs')).forEach(function(el) {
            new CBPFWTabs(el);
        });
    })();
</script>
<script src="<?=base_url("assets")?>/plugins/bower_components/toast-master/js/jquery.toast.js"></script>
<!--Style Switcher -->
<script src="<?=base_url("assets")?>/plugins/bower_components/styleswitcher/jQuery.style.switcher.js"></script>
<script src="<?=base_url("assets")?>/plugins/bower_components/datatables/datatables.min.js"></script>
<script src="<?=base_url("assets")?>/buttons/1.2.2/js/dataTables.buttons.min.js"></script>
<script src="<?=base_url("assets")?>/buttons/1.2.2/js/buttons.flash.min.js"></script>
<script src="<?=base_url("assets")?>/buttons/1.2.2/js/buttons.html5.min.js"></script>
<script src="<?=base_url("assets")?>/buttons/1.2.2/js/buttons.print.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/2.5.0/jszip.min.js"></script>
<script src="https://cdn.rawgit.com/bpampuch/pdfmake/0.1.18/build/pdfmake.min.js"></script>
<script src="https://cdn.rawgit.com/bpampuch/pdfmake/0.1.18/build/vfs_fonts.js"></script>
<script src="<?=base_url("assets")?>/plugins/bower_components/bootstrap-datepicker/bootstrap-datepicker.min.js"></script>
<!-- Date range Plugin JavaScript -->
<script src="<?=base_url("assets")?>/plugins/bower_components/timepicker/bootstrap-timepicker.min.js"></script>
<script src="<?=base_url("assets")?>/plugins/bower_components/bootstrap-daterangepicker/daterangepicker.js"></script>
<script src="<?=base_url("assets")?>/plugins/bower_components/bootstrap-daterangepicker/datetimepicker.js"></script>
<script src="<?=base_url("assets")?>/plugins/bower_components/clockpicker/dist/jquery-clockpicker.min.js"></script>
<script type="text/javascript" src="<?=base_url("assets")?>/plugins/bower_components/multiselect/js/jquery.multi-select.js"></script>
<script src="<?=base_url("assets")?>/plugins/bower_components/custom-select/dist/js/select2.full.min.js" type="text/javascript"></script>
<script src="<?=base_url("assets")?>/plugins/bower_components/bootstrap-select/bootstrap-select.min.js" type="text/javascript"></script>

<script>
    $(function() {
        // Switchery
        var elems = Array.prototype.slice.call(document.querySelectorAll('.js-switch'));
        $('.js-switch').each(function() {
            new Switchery($(this)[0], $(this).data());
        });
        // For select 2
        $(".select2").select2();
        $('.selectpicker').selectpicker();
        //Bootstrap-TouchSpin

        // For multiselect
        $('#pre-selected-options').multiSelect();
        $('#optgroup').multiSelect({
            selectableOptgroup: true
        });
        $('#public-methods').multiSelect();
        $('#select-all').click(function() {
            $('#public-methods').multiSelect('select_all');
            return false;
        });
        $('#deselect-all').click(function() {
            $('#public-methods').multiSelect('deselect_all');
            return false;
        });
        $('#refresh').on('click', function() {
            $('#public-methods').multiSelect('refresh');
            return false;
        });
        $('#add-option').on('click', function() {
            $('#public-methods').multiSelect('addOption', {
                value: 42,
                text: 'test 42',
                index: 0
            });
            return false;
        });
    });
</script>
<script>
    $(document).ready(function () {
        $('#example23').DataTable({
            dom: 'Bfrtip',
            buttons: [
                'copy', 'csv', 'excel', 'pdf', 'print'
            ]
        });
        $('.buttons-copy, .buttons-csv, .buttons-print, .buttons-pdf, .buttons-excel').addClass('btn btn-primary m-r-10');
    });
    $('#single-input').clockpicker({
        placement: 'bottom',
        align: 'left',
        autoclose: true,
        'default': 'now'
    });
    $('.clockpicker').clockpicker({
        donetext: 'Done',
    }).find('input').change(function() {
        console.log(this.value);
    });
    $('#check-minutes').click(function(e) {
        // Have to stop propagation here
        e.stopPropagation();
        input.clockpicker('show').clockpicker('toggleView', 'minutes');
    });
    $('.selectpicker').selectpicker();
</script>
<script>
    $('#calendar').fullCalendar({

        defaultView: 'month'


    });
</script>
<script>

</script>
