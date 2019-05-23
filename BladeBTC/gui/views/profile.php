<?php
require $_SERVER['DOCUMENT_ROOT'] . '/gui/views/partials/header.php';

use BladeBTC\GUI\Controllers\Profile;
use BladeBTC\GUI\Helpers\Messages;
use BladeBTC\GUI\Helpers\Path;
use BladeBTC\GUI\Helpers\Request;
use BladeBTC\GUI\Helpers\Session;


?>

    <div class="content-wrapper">
        <section class="content-header">
            <h1 id="module">My account</h1>
            <ol class="breadcrumb">
                <li><a href="<?php echo Path::module(); ?>/profile.php"><i class="fa fa-dashboard"></i> My account</a>
                </li>
            </ol>
        </section>

        <!-- Main content -->
        <section class="content container-fluid">


            <div class="row">
                <div class="col-md-12">
                    <div class="box">
                        <div class="box-header with-border">
                            <h3 class="box-title">Edit</h3>
                        </div>
                        <!-- /.box-header -->
                        <div class="box-body">

                            <div class="row">
                                <div class="col-md-12">
									<?php
									if (!is_null(Request::post('edit')) && Session::getFormId('edit') == Request::post('DBLP')) {
										try {
											if (Profile::update()) {
												Messages::success("Your account has been updated!");
											}
										} catch (Exception $e) {
											Messages::error($e->getMessage());
										}
									}
									?>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-sm-12 col-md-12">
                                    <form action="<?php echo Path::module(); ?>/profile.php" method="post"
                                          enctype="multipart/form-data">
                                        <input type="hidden" name="DBLP"
                                               value="<?php echo Session::setFormId('edit'); ?>">
                                        <div class="row">
                                            <div class="col-sm-6 col-md-6">
                                                <label>Password</label>
                                                <div class="input-group"><span class="input-group-addon"><i
                                                                class="fa fa-key"></i></span>
                                                    <input class="form-control" name="pwd" type="password"
                                                           placeholder="Leave this field empty to keep your current password.">
                                                </div>
                                            </div>
                                        </div>
                                        <br/>
                                        <div class="row">
                                            <div class="col-sm-6 col-md-6">
                                                <label>Picture</label>
                                                <div class="form-group">
                                                    <input type="file" name="img" class="file">
                                                    <div class="input-group col-xs-12">
                                                        <span class="input-group-addon"><i
                                                                    class="fa fa-image"></i></span>
                                                        <input type="text" class="form-control" disabled
                                                               placeholder="Browse a picture">
                                                        <span class="input-group-btn">
                                                        <button class="browse btn btn-danger" type="button"><i
                                                                    class="glyphicon glyphicon-search"></i> Browse</button></span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <input style="margin-top: 15px;" type="submit"
                                                       class="btn btn-success btn-block"
                                                       name="edit" value="Save">
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                            <!-- /.row -->
                        </div>

                    </div>
                    <!-- /.box -->
                </div>
                <!-- /.col -->
            </div>
            <!-- /.row -->

        </section>
        <!-- /.content -->
    </div>

    <style>
        .file {
            visibility: hidden;
            position: absolute;
        }

    </style>

    <script type="text/javascript">
        $(document).on('click', '.browse', function () {
            var file = $(this).parent().parent().parent().find('.file');
            file.trigger('click');
        });
        $(document).on('change', '.file', function () {
            $(this).parent().find('.form-control').val($(this).val().replace(/C:\\fakepath\\/i, ''));
        });

    </script>

<?php
require $_SERVER['DOCUMENT_ROOT'] . '/gui/views/partials/footer.php';