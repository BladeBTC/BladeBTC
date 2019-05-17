<?php
require $_SERVER['DOCUMENT_ROOT'] . '/gui/views/partials/header.php';

use BladeBTC\GUI\Controllers\ManageMenu;
use BladeBTC\GUI\Helpers\FontAwesome;
use BladeBTC\GUI\Helpers\Form;
use BladeBTC\GUI\Helpers\Messages;
use BladeBTC\GUI\Helpers\Path;
use BladeBTC\GUI\Helpers\Request;
use BladeBTC\GUI\Helpers\Session;
use BladeBTC\GUI\Models\MenuModel;

?>
    <div class="content-wrapper">
        <section class="content-header">
            <h1 id="module">Menu Management</h1>
            <ol class="breadcrumb">
                <li><a href="<?php echo Path::root(); ?>"><i class="fa fa-dashboard"></i>Menu Management</a>
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
                                Messages::success("The menu has been created.");
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
                                    <div class="alert bg-black-gradient"><i class="fa fa-check-circle fa-fw"></i> The
                                        options below are vital. Improper handling will cause a fatal GUI malfunction.
                                        Normally no changes should be made on this page unless you can understand how it
                                        works.
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-sm-6 col-md-6">
                                    <form action="<?php echo Path::module() ?>/manage-menu.php" method="post">
                                        <input type="hidden" name="DBLP"
                                               value="<?php echo Session::setFormId('add-menu'); ?>">
                                        <div class="row">
                                            <div class="col-sm-6 col-md-6">
                                                <label>Title</label>
                                                <div class="input-group"><span class="input-group-addon"><i
                                                                class="fa fa-quote-left"></i></span>
                                                    <input class="form-control" name="title" type="text"
                                                           value="<?php Form::get('title') ?>">
                                                </div>
                                            </div>
                                            <div class="col-sm-6 col-md-6">
                                                <label>Icon</label>
                                                <div class="input-group"><span class="input-group-addon"><i
                                                                class="fa fa-file-text-o"></i></span>
                                                    <select name="icon" class="form-control selectpicker"
                                                            data-live-search="true">
                                                        <option value="-1" selected>Choose an icon</option>
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
                                        <div class="row">
                                            <div class="col-md-6">
                                                <input class="btn btn-success btn-block" type="submit" value="Save"
                                                       name="add-menu">
                                            </div>
                                        </div>
                                    </form>

                                    <!-- Tableau -->
                                    <br/>
                                    <div class="table-responsive">
                                        <table class="table table-bordered table-striped table-bordered">
                                            <thead>
                                            <tr>
                                                <th>Title</th>
                                                <th>Icon</th>
                                                <th>Order</th>
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
                                                
                                                <a  class="btn btn-danger btn-xs" 
                                                    data-toggle="tooltip" 
                                                    title="Delete menu" 
                                                    onclick="return iconfirm(\'Attention!\',\'Are you sure you want to delete this menu? I dont think so!\', this.href)" 
                                                    href="' . $rm . '">
                                                    <i class="fa fa-trash-o"></i>
                                                </a>
                                                
                                                <a  class="btn btn-default btn-xs"
                                                    data-toggle="tooltip"
                                                    title="Move Up"
                                                    href="' . $up . '">
                                                    <i class="fa fa-level-up"></i>
                                                </a>
                                                
                                                <a  class="btn btn-default btn-xs"
                                                    data-toggle="tooltip"
                                                    title="Move Down"
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
require $_SERVER['DOCUMENT_ROOT'] . '/gui/views/partials/footer.php';