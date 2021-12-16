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
                <table id="example23" class="display nowrap table table-hover table-striped" cellspacing="0" width="100%">
                    <thead>
                    <tr>
                        <th>Date</th>
                        <th>Pickup Time</th>
                        <th>Drop Time</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($attendance as $a){ ?>
                        <tr>
                            <td><?= date("d M Y",strtotime($a['date'])) ?></td>
                            <td><?= $a['pick_time'] ?></td>
                            <td><?= $a['drop_time'] ?></td>
                        </tr>
                    <?php } ?>
                    </tbody>
                    <tfoot>
                    <tr>
                        <th>Date</th>
                        <th>Pickup Time</th>
                        <th>Drop Time</th>
                    </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>

</div>