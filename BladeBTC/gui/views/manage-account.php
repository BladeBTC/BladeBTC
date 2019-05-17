<?php
require $_SERVER['DOCUMENT_ROOT'] . '/gui/views/partials/header.php';

use BladeBTC\GUI\Controllers\ManageAccount;
use BladeBTC\GUI\Helpers\Form;
use BladeBTC\GUI\Helpers\Messages;
use BladeBTC\GUI\Helpers\Path;
use BladeBTC\GUI\Helpers\Request;
use BladeBTC\GUI\Helpers\Session;
use BladeBTC\GUI\Models\AccountModel;
use BladeBTC\GUI\Models\GroupModel;
use BladeBTC\GUI\Models\ModuleModel;

?>
    <div class="content-wrapper">
        <section class="content-header">
            <h1 id="module">CMS Account</h1>
            <ol class="breadcrumb">
                <li><a href="<?php echo Path::root(); ?>"><i class="fa fa-dashboard"></i>CMS Account</a>
                </li>

            </ol>
        </section>

        <!-- Main content -->
        <section class="content container-fluid">

            <div class="row">
                <div class="col-md-12">
					<?php
					if (Request::get('action') && Session::getFormId('manage-link') == Request::get('token')) {
						try {
							$message = ManageAccount::action();
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
					if (!is_null(Request::post('save-group')) && Session::getFormId('mng-group') == Request::post('DBLP')) {
						try {

							if (Form::getReturn('edit_mode') == 1) {

								//EDIT
								if (ManageAccount::editGroup()) {
									Messages::success("The group has been modified.");
								}
							} else {

								//ADD
								if (ManageAccount::addGroup()) {
									Messages::success("The group has been created.");
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
					<?php
					if (!is_null(Request::post('save-account')) && Session::getFormId('mng-account') == Request::post('DBLP')) {
						try {

							if (Form::getReturn('edit_mode') == 1) {

								//EDIT
								if (ManageAccount::editAccount()) {
									Messages::success("The account has been modified.");
								}
							} else {

								//ADD
								if (ManageAccount::addAccount()) {
									Messages::success("The account has been created.");
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
                            <h3 class="box-title">Groups</h3>
                        </div>

                        <div class="box-body">
                            <div class="row">
                                <div class="col-md-12">

                                    <div class="row">
                                        <div class="col-sm-6 col-md-6 col-lg-6">
                                            <form action="<?php echo Path::module() ?>/manage-account.php"
                                                  method="post">
                                                <input type="hidden" name="DBLP"
                                                       value="<?php echo Session::setFormId('mng-group'); ?>">
                                                <div class="row">
                                                    <div class="col-sm-6 col-md-6 col-lg-6">
                                                        <label>Group Name</label>
                                                        <div class="input-group"><span class="input-group-addon"><i
                                                                        class="fa fa-quote-left"></i></span>
                                                            <input class="form-control" name="group_name" type="text"
                                                                   value="<?php Form::get('group_name') ?>">
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-6 col-md-6 col-lg-6">
                                                        <label>Home Page</label>
                                                        <div class="input-group"><span class="input-group-addon"><i
                                                                        class="fa fa-tachometer"></i></span>
                                                            <select name="dashboard" class="form-control">
                                                                <option value="-1" selected>Home Page
                                                                </option>
																<?php
																$modules = ModuleModel::getAll();
																while ($module = $modules->fetchObject()) {
																	echo '<option value="' . $module->name . '" ' . (Form::getReturn('dashboard') == $module->name ? 'selected' : null) . '>' . $module->name . '</option>';
																}
																?>
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                                <br/>

                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <input class="btn btn-success btn-block"
                                                               type="submit" name="save-group" value="Save">
                                                    </div>
                                                </div>


                                            </form>

                                            <!-- Tableau -->
                                            <br/>

                                            <div class="table-responsive">
                                                <table id="mng_group" class="table table-striped table-bordered">
                                                    <thead>
                                                    <tr>
                                                        <th>Group Name</th>
                                                        <th>Home Page</th>
                                                        <th>Action</th>
                                                    </tr>
                                                    </thead>
                                                    <tbody>
													<?php
													$groups = GroupModel::getAll();
													$token = Session::setFormId('manage-link');
													while ($group = $groups->fetchObject()) {

														$rm = '?action=delete_group&group_id=' . $group->group_id . '&token=' . $token;
														$vim = '?action=edit_group&group_id=' . $group->group_id . '&token=' . $token;

														echo '<tr>
                                                            <td>' . $group->group_name . '</td>
                                                            <td>' . $group->dashboard . '</td>
                                                            <td>
                                                            <a  class="btn btn-success btn-xs" 
                                                                title="Edit group" 
                                                                data-toggle="tooltip"
                                                                href="' . $vim . '">
                                                                <i class="fa fa-pencil"></i>
                                                            </a>
                                                            <a  class="btn btn-danger btn-xs" 
                                                                title="Delete group" 
                                                                data-toggle="tooltip"
                                                                onclick="return iconfirm(\'Attention!\',\'Are you sure you want to delete this group?\', this.href)" 
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
                </div>
            </div>


            <div class="row">
                <div class="col-md-12">
                    <div class="box box-warning">
                        <div class="box-header with-border">
                            <h3 class="box-title">Accounts</h3>
                        </div>
                        <div class="box-body">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="row">
                                        <div class="col-sm-6 col-md-6 col-lg-6">
                                            <form action="<?php echo Path::module() ?>/manage-account.php"
                                                  method="post">
                                                <input type="hidden" name="DBLP"
                                                       value="<?php echo Session::setFormId('mng-account'); ?>">
                                                <div class="row">
                                                    <div class="col-sm-6 col-md-6 col-lg-6">
                                                        <label>First Name</label>
                                                        <div class="input-group"><span class="input-group-addon"><i
                                                                        class="fa fa-user"></i></span>
                                                            <input class="form-control" name="first_name" type="text"
                                                                   value="<?php Form::get('first_name') ?>">
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-6 col-md-6 col-lg-6">
                                                        <label>Last Name</label>
                                                        <div class="input-group"><span class="input-group-addon"><i
                                                                        class="fa fa-user"></i></span>
                                                            <input class="form-control pointer" name="last_name"
                                                                   type="text"
                                                                   value="<?php Form::get('last_name') ?>">
                                                        </div>
                                                    </div>
                                                </div>
                                                <br/>
                                                <div class="row">
                                                    <div class="col-sm-6 col-md-6 col-lg-6">
                                                        <label>Username</label>
                                                        <div class="input-group"><span class="input-group-addon"><i
                                                                        class="fa fa-tag"></i></span>
                                                            <input class="form-control" name="username" type="text"
                                                                   value="<?php Form::get('username') ?>">
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-6 col-md-6 col-lg-6">
                                                        <label>Group</label>
                                                        <div class="input-group"><span class="input-group-addon"><i
                                                                        class="fa fa-group"></i></span>
                                                            <select name="account_group" class="form-control">
                                                                <option value="0" selected>Select a Group
                                                                </option>
																<?php
																$groups = GroupModel::getAll();
																while ($group = $groups->fetchObject()) {
																	echo '<option value="' . $group->group_id . '" ' . (Form::getReturn('account_group') == $group->group_id ? 'selected' : null) . '>' . $group->group_name . '</option>';
																}
																?>
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                                <br/>
                                                <div class="row">
                                                    <div class="col-sm-6 col-md-6 col-lg-6">
                                                        <label>Password</label>
                                                        <div class="input-group"><span class="input-group-addon"><i
                                                                        class="fa fa-key"></i></span>
                                                            <input class="form-control" name="password" type="password"
                                                                   autocomplete="off">
                                                        </div>
                                                    </div>

                                                </div>
                                                <br/>
                                                <div class="row">
                                                    <div class="col-sm-12">
                                                        <label>Email</label>
                                                        <div class="input-group"><span class="input-group-addon"><i
                                                                        class="fa fa-envelope"></i></span>
                                                            <input id="email" class="form-control" name="email"
                                                                   type="text"
                                                                   value="<?php Form::get('email') ?>">
                                                        </div>
                                                    </div>
                                                </div>
                                                <br/>
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <input class="btn btn-success btn-block"
                                                               type="submit" name="save-account" value="Save">
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                    <!-- Tableau -->
                                    <br/>
                                    <div class="table-responsive">
                                        <table id="mng_user" class="table table-striped table-bordered">
                                            <thead>
                                            <tr>
                                                <th>First Name</th>
                                                <th>Last Name</th>
                                                <th>Username</th>
                                                <th>Group</th>
                                                <th>Email</th>
                                                <th>Action</th>
                                            </tr>
                                            </thead>
                                            <tbody>
											<?php

											$accounts = AccountModel::getAll();
											while ($account = $accounts->fetchObject()) {

												$rm = '?action=delete_account&id=' . $account->id . '&token=' . $token;
												$vim = '?action=edit_account&id=' . $account->id . '&token=' . $token;
												$rst = '?action=reset_pwd&id=' . $account->id . '&token=' . $token;
												$unl = '?action=unlock&id=' . $account->id . '&token=' . $token;

												echo '<tr>
                                                        <td>' . $account->first_name . '</td>
                                                        <td>' . $account->last_name . '</td>
                                                        <td>' . $account->username . '</td>
                                                        <td>' . GroupModel::getNameById($account->account_group) . '</td>
                                                        <td>' . $account->email . '</td>
                                                        <td>
                                                            <a  class="btn btn-success btn-xs" 
                                                                title="Edit account" 
                                                                data-toggle="tooltip"
                                                                href="' . $vim . '">
                                                                <i class="fa fa-pencil fa-fw"></i>
                                                            </a>
                                                            
                                                               <a  class="btn btn-primary btn-xs" 
                                                                title="Reset password to: bladebtc" 
                                                                data-toggle="tooltip"
                                                                onclick="return iconfirm(\'Attention!\',\'Are you sure you want to reset the password?\',this.href)" 
                                                                href="' . $rst . '">
                                                                <i class="fa fa-key fa-fw"></i>
                                                            </a>
                                                            
                                                            <a  class="btn btn-warning btn-xs" 
                                                                title="Unlock account" 
                                                                data-toggle="tooltip"
                                                                onclick="return iconfirm(\'Attention!\',\'Are you sure you want to unlock the account?\',this.href)" 
                                                                href="' . $unl . '">
                                                                <i class="fa fa-unlock-alt fa-fw"></i>
                                                            </a>
                                                            <a  class="btn btn-danger btn-xs" 
                                                                title="Delete account" 
                                                                data-toggle="tooltip"
                                                                onclick="return iconfirm(\'Attention!\',\'Are you sure you want to delete this user?\',this.href)" 
                                                                href="' . $rm . '">
                                                                <i class="fa fa-trash-o fa-fw"></i>
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
            $('#mng_user').DataTable({
                'paging': true,
                'lengthChange': true,
                'searching': true,
                'ordering': true,
                'info': false,
                'autoWidth': true,
                "lengthMenu": [[50, 100, -1], [50, 100, "All"]]
            })
        })
    </script>

<?php
require $_SERVER['DOCUMENT_ROOT'] . '/gui/views/partials/footer.php';