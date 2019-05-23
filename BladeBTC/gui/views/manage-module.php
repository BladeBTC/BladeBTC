<?php
require $_SERVER['DOCUMENT_ROOT'] . '/gui/views/partials/header.php';

use BladeBTC\GUI\Controllers\ManageModule;
use BladeBTC\GUI\Helpers\FontAwesome;
use BladeBTC\GUI\Helpers\Form;
use BladeBTC\GUI\Helpers\Messages;
use BladeBTC\GUI\Helpers\Path;
use BladeBTC\GUI\Helpers\Request;
use BladeBTC\GUI\Helpers\Session;
use BladeBTC\GUI\Helpers\Utils;
use BladeBTC\GUI\Models\GroupModel;
use BladeBTC\GUI\Models\MenuModel;
use BladeBTC\GUI\Models\ModuleModel;

?>
    <div class="content-wrapper">
        <section class="content-header">
            <h1 id="module">Module Management</h1>
            <ol class="breadcrumb">
                <li><a href="<?php echo Path::root(); ?>"><i class="fa fa-dashboard"></i>Module Management</a>
                </li>

            </ol>
        </section>

        <!-- Main content -->
        <section class="content container-fluid">

            <div class="row">
                <div class="col-md-12">
					<?php
					if (Request::get('action') && Session::getFormId('mng-link') == Request::get('token')) {
						try {
							$message = ManageModule::action();
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
					if (!is_null(Request::post('save-module')) && Session::getFormId('mng-module') == Request::post('DBLP')) {
						try {

							if (Form::getReturn('edit_mode') == 1) {

								//EDIT
								if (ManageModule::edit()) {
									Messages::success("The module has been modified.");
								}
							} else {

								//ADD
								if (ManageModule::add()) {
									Messages::success("The module has been created.");
								}
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
                    <div class="box box-warning">
                        <div class="box-header with-border">
                            <h3 class="box-title">Management</h3>
                        </div>

                        <div class="box-body">

                            <div class="row">
                                <div class="col-md-12">
                                    <div class="alert bg-black-gradient"><i class="fa fa-check-circle fa-fw"></i> The options below are vital. Improper handling will cause a fatal GUI malfunction. Normally no changes should be made on this page unless you can understand how it works.</div>
                                </div>
                            </div>


                            <div class="row">
                                <div class="col-sm-6 col-md-6">
                                    <form action="<?php echo Path::module() ?>/manage-module.php"
                                          method="post">
                                        <input type="hidden" name="DBLP"
                                               value="<?php echo Session::setFormId('mng-module'); ?>">
                                        <label>Description</label>
                                        <div class="input-group"><span class="input-group-addon"><i
                                                        class="fa fa-quote-left"></i></span>
                                            <input class="form-control" name="description" type="text"
                                                   value="<?php Form::get('description') ?>">
                                        </div>
                                        <br/>
                                        <div class="row">
                                            <div class="col-sm-6 col-md-6">
                                                <label>Name</label>
                                                <div class="input-group"><span class="input-group-addon"><i
                                                                class="fa fa-terminal"></i></span>
                                                    <input class="form-control" name="name" type="text"
                                                           value="<?php Form::get('name') ?>">
                                                </div>
                                            </div>
                                            <div class="col-sm-6 col-md-6">
                                                <label>Icon</label>
                                                <div class="input-group"><span class="input-group-addon"><i
                                                                class="fa fa-file-text-o"></i></span>
                                                    <select name="icon" class="form-control selectpicker"
                                                            data-live-search="true">
                                                        <option value="-1" selected>Select an icon</option>
														<?php
														$icons = FontAwesome::getIcon();
														foreach ($icons as $key => $value) {
															echo '<option value="' . $key . '" data-Icon="fa ' . $key . '" ' . (Form::getReturn('icon') == $key ? 'selected' : null) . '>' . $key . '</option>';
														}
														?>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <br/>
                                        <div class="row">
                                            <div class="col-sm-6 col-md-6">
                                                <label>Parent</label>
                                                <div class="input-group"><span class="input-group-addon"><i
                                                                class="fa fa-sitemap"></i></span>
                                                    <select name="parent" class="form-control selectpicker">
                                                        <option value="0" selected>Choose a parent menu</option>
														<?php
														$parents = MenuModel::getAll();
														while ($parent = $parents->fetchObject()) {

															echo '<option value="' . $parent->menu_id . '" ' . (Form::getReturn('parent') == $parent->menu_id ? 'selected' : null) . '>' . $parent->title . '</option>';
														}
														?>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <br/>
                                        <div class="row">

                                            <div class="col-sm-6 col-md-6">
                                                <label>Active</label>
                                                <div class="input-group"><span class="input-group-addon"><i
                                                                class="fa fa-sitemap"></i></span>
                                                    <select name="active" class="form-control selectpicker">
                                                        <option value="-1" selected>Choose an option</option>
                                                        <option
                                                                value="0" <?php echo !is_null(Form::getReturn('active')) && Form::getReturn('active') == 0 ? 'selected' : null ?>>
                                                            Non
                                                        </option>
                                                        <option
                                                                value="1" <?php echo !is_null(Form::getReturn('active')) && Form::getReturn('active') == 1 ? 'selected' : null ?>>
                                                            Oui
                                                        </option>
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="col-sm-6 col-md-6">
                                                <label>Static&nbsp;&nbsp;<i
                                                            style="color: red;" class="fa fa-question-circle"
                                                            title="A static menu is a menu that should not be displayed in the main menu. This option allows you to create hidden modules or static pages. Example: 404, Access Denied ..."
                                                            data-toggle="tooltip"></i></label>
                                                <div class="input-group"><span class="input-group-addon"><i
                                                                class="fa fa-sitemap"></i></span>
                                                    <select name="static" class="form-control selectpicker">
                                                        <option value="-1" selected>Choose an option</option>
                                                        <option
                                                                value="0" <?php echo !is_null(Form::getReturn('static')) && Form::getReturn('static') == 0 ? 'selected' : null ?>>
                                                            Non
                                                        </option>
                                                        <option
                                                                value="1" <?php echo !is_null(Form::getReturn('static')) && Form::getReturn('static') == 1 ? 'selected' : null ?>>
                                                            Oui
                                                        </option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>

                                      <br/>

                                        <table class="table table-bordered table-striped table-condensed">
                                            <thead>
                                            <tr>
                                                <th>Group</th>
                                                <th>Active</th>
                                            </tr>
                                            </thead>
                                            <tbody>
											<?php
											$current_group = Form::getReturn('access_level');
											if (!is_array($current_group)) {
												$current_group = explode(";", $current_group);
											}

											$groups = GroupModel::getAll();
											while ($group = $groups->fetchObject()) {

												echo '<tr>';
												echo '<td>' . $group->group_name . '</td>';

												echo '<td>
                                                        <div class="checkbox icheck">
                                                            <label>
                                                                <input type="checkbox" name="access_level[]" value="' . $group->group_id . '" ' . (@in_array($group->group_id, $current_group) ? 'checked' : null) . '>
                                                            </label>
                                                        </div>
                                                       </td>';

											}
											?>
                                            </tbody>
                                        </table>

                                        <div class="row">
                                            <div class="col-md-6">
                                                <input class="btn btn-success btn-block" type="submit" name="save-module"
                                                       value="Save">
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>

                            <br/>

                            <div class="row">
                                <div class="col-md-12">
                                    <div class="alert bg-black-gradient"><i class="fa fa-check-circle fa-fw"></i> The options below are vital. Improper handling will cause a fatal GUI malfunction. Normally no changes should be made on this page unless you can understand how it works.</div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-sm-12">
                                    <!-- Tableau -->
                                    <div class="table-responsive">
                                        <table id="mng_module"
                                               class="table table-striped table-bordered table-responsive table-condensed">
                                            <thead>
                                            <tr>
                                                <th>Name</th>
                                                <th>Description</th>
                                                <th>Icon</th>
                                                <th>Parent</th>
                                                <th>Static</th>
                                                <th>Visits</th>
                                                <th>Last visit</th>
                                                <th>Action</th>
                                            </tr>
                                            </thead>

                                            <tbody>
											<?php

											$modules = ModuleModel::getAll();
											$token = Session::setFormId('mng-link');

											while ($module = $modules->fetchObject()) {

												$rm = '?action=delete&id=' . $module->id . '&token=' . $token;
												$vim = '?action=edit&id=' . $module->id . '&token=' . $token;

												echo '<tr ' . ($module->active == 0 ? "class='bg-red-gradient'" : null) . '>
                                                    <td>' . $module->name . '</td>
                                                    <td>' . $module->description . '</td>
                                                    <td><i class="fa ' . $module->icon . '"></i></td>
                                                    <td>' . ($module->static == 1 ? '<span class="label label-success">Static</span>' : MenuModel::getNameById($module->parent)) . '</td>
                                                    <td>' . ($module->static == 1 ? '<span class="label label-success">Yes</span>' : '<span class="label label-danger">No</span>') . '</td>
                                                    <td>' . $module->visits . '</td>
                                                    <td>' . Utils::dateFromTimeStamp('d-m-Y', $module->last_visit) . '</td>
                                                    <td>
                                                    <a  class="btn btn-success btn-xs" 
                                                        title="Edit this module" 
                                                        data-toggle="tooltip"
                                                        href="' . $vim . '">
                                                            <i class="fa fa-pencil"></i>
                                                    </a>
                                                    
                                                    <a  class="btn btn-danger btn-xs" 
                                                        title="Delete this module" 
                                                        data-toggle="tooltip"
                                                        onclick="return iconfirm(\'Attention!\',\'Are you wanting to delete this module? I do not think so!\', this.href)" 
                                                        href="' . $rm . '">
                                                            <i class="fa fa-trash-o"></i>
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


    <script>
        $(function () {
            $('#mng_module').DataTable({
                'paging': false,
                'lengthChange': false,
                'searching': true,
                'ordering': true,
                'info': false,
                'autoWidth': true,
            })
        })
    </script>
<?php
require $_SERVER['DOCUMENT_ROOT'] . '/gui/views/partials/footer.php';