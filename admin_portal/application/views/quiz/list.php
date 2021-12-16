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
            <h3 class="box-title m-b-0"><?=$title?><a data-toggle="modal" data-target="#responsive-modal-quiz" class="col-sm-offset-8 btn btn-primary model_img img-responsive"><i class="fa fa-plus"></i> ADD Quiz</a>  <a data-toggle="modal" data-target="#responsive-modal-quiz-import" class="btn btn-success model_img img-responsive"><i class="fa fa-plus"></i> Bulk Import</a></h3>
            <div class="table-responsive">
                <table id="example23" class="display table table-hover table-striped" cellspacing="0" width="100%">
                    <thead>
                    <tr>
                        <th>#</th>
                        <th>Question</th>
                        <th>OptionA</th>
                        <th>OptionB</th>
                        <th>OptionC</th>
                        <th>OptionD</th>
                        <th>Answer</th>
                        <th>Marks</th>
                        <th>Action</th>
                    </tr>
                    </thead>
                    <tfoot>
                    <tr>
                        <th>#</th>
                        <th>Question</th>
                        <th>OptionA</th>
                        <th>OptionB</th>
                        <th>OptionC</th>
                        <th>OptionD</th>
                        <th>Answer</th>
                        <th>Marks</th>
                        <th>Action</th>
                    </tr>
                    </tfoot>
                    <tbody>
                    <?php
                    $count = 0;
                    foreach ($quiz as $c){
                        $count++;
                        ?>
                        <tr>
                            <td><?=$count?></td>
                            <td><?=$c->Question?></td>
                            <td><?=$c->OptionA?></td>
                            <td><?=$c->OptionB?></td>
                            <td><?=$c->OptionC?></td>
                            <td><?=$c->OptionD?></td>
                            <td><?=$c->RightAns?></td>
                            <td><?=$c->marks?></td>
                            <td>
                                <a data-toggle="modal" data-target="#responsive-modal<?=$c->Que_ID?>" class="btn btn-danger model_img img-responsive"><i class="fa fa-edit"></i></a>
                                <a href="<?=base_url("quiz/delete_question/").$c->Que_ID ?>" onclick="return confirm('Are you sure you want to delete this?');" class="btn btn-success"><i class="fa fa-trash"></i></a>
                            </td>
                        </tr>

                        <div id="responsive-modal<?=$c->Que_ID?>" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                                        <h4 class="modal-title">Update Question</h4> </div>
                                    <form action="<?=base_url('quiz/edit_question/').$c->Que_ID.'/'.$c->quiz_level?>" method="POST"">
                                        <div class="modal-body">
                                            <div class="form-group">
                                                <label for="class" class="control-label">Question:</label>
                                                <textarea required class="form-control" name="Question" id="Question"><?=$c->Question?></textarea>
                                            </div>
                                            <div class="form-group">
                                                <label for="OptionA" class="control-label">OptionA:</label>
                                                <input type="text" required class="form-control" name="OptionA" id="OptionA" value="<?=$c->OptionA?>">
                                            </div>
                                            <div class="form-group">
                                                <label for="OptionB" class="control-label">OptionB:</label>
                                                <input type="text" required class="form-control" name="OptionB" id="OptionB" value="<?=$c->OptionB?>">
                                            </div>
                                            <div class="form-group">
                                                <label for="OptionC" class="control-label">OptionC:</label>
                                                <input type="text" required class="form-control" name="OptionC" id="OptionC" value="<?=$c->OptionC?>">
                                            </div>
                                            <div class="form-group">
                                                <label for="OptionD" class="control-label">OptionD:</label>
                                                <input type="text" required class="form-control" name="OptionD" id="OptionD" value="<?=$c->OptionD?>">
                                            </div>
                                            <div class="form-group">
                                                <label for="RightAns" class="control-label">Answer:</label>
                                                <select class="form-control" name="RightAns" id="RightAns" required>
                                                    <option <?php if ($c->RightAns == "A") echo "selected"?> value="A">A</option>
                                                    <option <?php if ($c->RightAns == "B") echo "selected"?> value="B">B</option>
                                                    <option <?php if ($c->RightAns == "C") echo "selected"?> value="C">C</option>
                                                    <option <?php if ($c->RightAns == "D") echo "selected"?> value="D">D</option>
                                                </select>
                                            </div>
                                            <div class="form-group">
                                                <label for="marks" class="control-label">Marks:</label>
                                                <input type="number" required class="form-control" name="marks" id="marks" value="<?=$c->marks?>">
                                            </div>
                                            <div class="form-group">
                                                <label for="explanation" class="control-label">Explanation:</label>
                                                <textarea rows="4" class="form-control" name="explanation" id="explanation"><?=$c->explanation?></textarea>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-default waves-effect" data-dismiss="modal">Close</button>
                                            <button type="submit" class="btn btn-danger waves-effect waves-light">Save changes</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>

                    <?php }?>
                    </tbody>
                </table>
                <div id="responsive-modal-quiz" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                                <h4 class="modal-title">ADD Question</h4> </div>
                            <form action="<?=base_url('quiz/add_question/').$quiz_level ?>" method="POST"">
                            <div class="modal-body">
                                <div class="form-group">
                                    <label for="class" class="control-label">Question:</label>
                                    <textarea required class="form-control" name="Question" id="Question"></textarea>
                                </div>
                                <div class="form-group">
                                    <label for="OptionA" class="control-label">OptionA:</label>
                                    <input type="text" required class="form-control" name="OptionA" id="OptionA">
                                </div>
                                <div class="form-group">
                                    <label for="OptionB" class="control-label">OptionB:</label>
                                    <input type="text" required class="form-control" name="OptionB" id="OptionB">
                                </div>
                                <div class="form-group">
                                    <label for="OptionC" class="control-label">OptionC:</label>
                                    <input type="text" required class="form-control" name="OptionC" id="OptionC">
                                </div>
                                <div class="form-group">
                                    <label for="OptionD" class="control-label">OptionD:</label>
                                    <input type="text" required class="form-control" name="OptionD" id="OptionD">
                                </div>
                                <div class="form-group">
                                    <label for="RightAns" class="control-label">Answer:</label>
                                    <select class="form-control" name="RightAns" id="RightAns" required>
                                        <option value="A">A</option>
                                        <option value="B">B</option>
                                        <option value="C">C</option>
                                        <option value="D">D</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="marks" class="control-label">Marks:</label>
                                    <input type="number" required class="form-control" name="marks" id="marks">
                                </div>
                                <div class="form-group">
                                    <label for="explanation" class="control-label">Explanation:</label>
                                    <textarea rows="4" class="form-control" name="explanation" id="explanation"></textarea>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-default waves-effect" data-dismiss="modal">Close</button>
                                <button type="submit" class="btn btn-danger waves-effect waves-light">Save changes</button>
                            </div>
                            </form>
                        </div>
                    </div>
                </div>
                <div id="responsive-modal-quiz-import" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                                <h4 class="modal-title">Import CSV File  <a class="btn btn-primary" href="<?=base_url("sample/question_upload_format.csv")?>">Sample Download</a></h4> </div>
                            <form action="<?=base_url('quiz/bulk_add_question/').$quiz_level ?>" method="POST" enctype="multipart/form-data">
                            <div class="modal-body">
                                <div class="form-group">
                                    <label for="imported_file" class="control-label">Select File:</label>
                                    <input type="file" required class="form-control" accept=".csv" name="imported_file" id="imported_file">
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-default waves-effect" data-dismiss="modal">Close</button>
                                <button type="submit" class="btn btn-danger waves-effect waves-light">Save changes</button>
                            </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- /.row -->
