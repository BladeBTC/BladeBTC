<?php
require $_SERVER['DOCUMENT_ROOT'] . '/views/partials/header.php';

use App\Controllers\ManageMenu;
use App\Helpers\FontAwesome;
use App\Helpers\Form;
use App\Helpers\Messages;
use App\Helpers\Path;
use App\Helpers\Request;
use App\Helpers\Session;
use App\Models\MenuModel;

?>
    <div class="content-wrapper">
        <section class="content-header">
            <h1 id="module">Gestion du menu</h1>
            <ol class="breadcrumb">
                <li><a href="<?php echo Path::root(); ?>"><i class="fa fa-dashboard"></i>Gestion du menu</a>
                </li>

            </ol>
        </section>

        <!-- Main content -->
        <section class="content container-fluid">

            <div class="row">
                <div class="col-md-12">
					<?php
					if (Request::get('action') && Session::getFormId('manage-menu-link') == Request::get('token')) {
						try {
							$message = ManageMenu::action();
							Messages::success($message);
						} catch (Exception $e) {
							Messages::error($e->getMessage());
						}
					}
					?>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12">
					<?php
					if (!is_null(Request::post('add-menu')) && Session::getFormId('add-menu') == Request::post('DBLP')) {
						try {
							if (ManageMenu::add()) {
								Messages::success("Le menu à bien été créé.");
							}
						} catch (Exception $e) {
							Messages::error($e->getMessage());
						}
					}
					?>
                </div>
            </div>


            <div class="row">
                <div class="col-md-12">
                    <div class="box box-default">
                        <div class="box-header with-border">
                            <h3 class="box-title">Édition</h3>
                        </div>

                        <div class="box-body">
                            <div class="row">
                                <div class="col-sm-6 col-md-6">
                                    <form action="<?php echo Path::module() ?>/manage-menu.php" method="post">
                                        <input type="hidden" name="DBLP"
                                               value="<?php echo Session::setFormId('add-menu'); ?>">
                                        <div class="row">
                                            <div class="col-sm-6 col-md-6">
                                                <label>Titre du menu</label>
                                                <div class="input-group"><span class="input-group-addon"><i
                                                                class="fa fa-quote-left"></i></span>
                                                    <input class="form-control" name="title" type="text"
                                                           value="<?php Form::get('title') ?>">
                                                </div>
                                            </div>
                                            <div class="col-sm-6 col-md-6">
                                                <label>Icône</label>
                                                <div class="input-group"><span class="input-group-addon"><i
                                                                class="fa fa-file-text-o"></i></span>
                                                    <select name="icon" class="form-control selectpicker"
                                                            data-live-search="true">
                                                        <option value="-1" selected>Choisir une icône</option>
														<?php
														$icons = FontAwesome::getIcon();
														foreach ($icons as $key => $value) {
															echo '<option value="' . $key . '" data-Icon="fa ' . $key . '" ' . (Form::getReturn('icon') == $key ? 'selected' : null) . '>' . $key . '</option>' . "n";
														}
														?>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <br/>
                                        <div class="input-group">
                                            <input class="btn btn-danger" type="submit" value="Ajouter" name="add-menu">
                                        </div>
                                    </form>

                                    <!-- Tableau -->
                                    <br/>
                                    <div class="table-responsive">
                                        <table class="table table-bordered table-striped table-bordered">
                                            <thead>
                                            <tr>
                                                <th>Titre</th>
                                                <th>Icône</th>
                                                <th>Ordre</th>
                                                <th>Action</th>
                                            </tr>
                                            </thead>
                                            <tbody>
											<?php

											$menus = MenuModel::getAll();
											$token = Session::setFormId('manage-menu-link');

											while ($menu = $menus->fetchObject()) {

												$rm = '?action=delete&menu_id=' . $menu->menu_id . '&token=' . $token;
												$up = '?action=up&menu_id=' . $menu->menu_id . '&token=' . $token;
												$down = '?action=down&menu_id=' . $menu->menu_id . '&token=' . $token;

												echo '<tr>
                                                <td>' . $menu->title . '</td>
                                                <td><i class="fa ' . $menu->icon . '"></i></td>
                                                <td>' . $menu->display_order . '</td>
                                                <td>
                                                
                                                <a  class="btn btn-danger btn-flat btn-xs" 
                                                    data-toggle="tooltip" 
                                                    title="Supprimer le menu" 
                                                    onclick="return iconfirm(\'Attention!\',\'Êtes-vous de vouloir supprimer ce menu\', this.href)" 
                                                    href="' . $rm . '">
                                                    <i class="fa fa-trash-o"></i>
                                                </a>
                                                
                                                <a  class="btn btn-default btn-flat btn-xs"
                                                    data-toggle="tooltip"
                                                    title="Monter"
                                                    href="' . $up . '">
                                                    <i class="fa fa-level-up"></i>
                                                </a>
                                                
                                                <a  class="btn btn-default btn-flat btn-xs"
                                                    data-toggle="tooltip"
                                                    title="Descendre"
                                                    href="' . $down . '">
                                                    <i class="fa fa-level-down"></i>
                                                </a>
                                                </td>
                                                </tr>';
											}
											?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>


        <!-- /.content -->
    </div>
<?php
require $_SERVER['DOCUMENT_ROOT'] . '/views/partials/footer.php';