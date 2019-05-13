<?php
require $_SERVER['DOCUMENT_ROOT'] . '/views/partials/header.php';

use App\Controllers\Profile;
use App\Helpers\Messages;
use App\Helpers\Path;
use App\Helpers\Request;
use App\Helpers\Session;


?>

    <div class="content-wrapper">
        <section class="content-header">
            <h1 id="module">Mon compte</h1>
            <ol class="breadcrumb">
                <li><a href="<?php echo Path::module(); ?>/profile.php"><i class="fa fa-dashboard"></i> Mon
                        compte</a>
                </li>
            </ol>
        </section>

        <!-- Main content -->
        <section class="content container-fluid">


            <div class="row">
                <div class="col-md-12">
                    <div class="box">
                        <div class="box-header with-border">
                            <h3 class="box-title">Gestion du compte</h3>
                            <div class="box-tools pull-right">
                                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i
                                            class="fa fa-minus"></i>
                                </button>
                            </div>
                        </div>
                        <!-- /.box-header -->
                        <div class="box-body">

                            <div class="row">
                                <div class="col-md-12">
									<?php
									if (!is_null(Request::post('edit')) && Session::getFormId('edit') == Request::post('DBLP')) {
										try {
											if (Profile::update()) {
												Messages::success("Votre compte a bien été mis à jour!");
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
                                                <label>Mot de passe</label>
                                                <div class="input-group"><span class="input-group-addon"><i
                                                                class="fa fa-key"></i></span>
                                                    <input class="form-control" name="pwd" type="password"
                                                           placeholder="Laisser vide pour conserver votre mot de passe">
                                                </div>
                                            </div>
                                        </div>
                                        <br/>
                                        <div class="row">
                                            <div class="col-sm-6 col-md-6">
                                                <label>Photo</label>
                                                <div class="form-group">
                                                    <input type="file" name="img" class="file">
                                                    <div class="input-group col-xs-12">
                                                        <span class="input-group-addon"><i
                                                                    class="fa fa-image"></i></span>
                                                        <input type="text" class="form-control" disabled
                                                               placeholder="Téléverser une image">
                                                        <span class="input-group-btn">
                                                        <button class="browse btn btn-danger btn-flat" type="button"><i
                                                                    class="glyphicon glyphicon-search"></i> Parcourir</button></span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <input style="margin-top: 15px;" type="submit"
                                                       class="btn btn-success btn-block btn-flat"
                                                       name="edit" value="Enregistrer">
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
require $_SERVER['DOCUMENT_ROOT'] . '/views/partials/footer.php';