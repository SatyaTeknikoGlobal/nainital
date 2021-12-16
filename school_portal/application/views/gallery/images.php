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
    <?php $count = 0; $close = 0; foreach ($images as $img){?>
        <?php if ($count%3 === 0){$close = $count; ?>
            <div class="row">
        <?php }?>
                <div class="col-sm-4">
                    <div class="white-box">
                        <div class="row m-b-10">
                            <div style="float: right;"><span><a class="btn-success" onclick="return confirm('Are you sure want to delete this ?');" href="<?=base_url("gallery/delete_image/$img->galleryID/$img->folderID")?>"><i class="fa fa-times"></i></a></span></div>
                        </div>
                        <img src="<?=$img->image?>" width="100%" style="max-height: 200px;"  class="img img-thumbnail">
                        <hr>
                        <div class="row">
                            <div class="col-sm-6"><span>Student : <?php if ($img->student == "Y"){echo "Y";}else{echo "N";}?></span></div>
                            <div class="col-sm-6"><span>Teacher : <?php if ($img->teacher == "Y"){echo "Y";}else{echo "N";}?></span></div>
                        </div>
                    </div>
                </div>
        <?php if ($count === $close+2){ ?>
            </div>
         <?php }?>
    <?php $count++ ; } ?>
</div>
<!-- /.row -->
