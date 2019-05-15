<?php

use BladeBTC\GUI\Helpers\Path;
use BladeBTC\GUI\Helpers\Session;
use BladeBTC\GUI\Models\AccountModel;
use BladeBTC\GUI\Models\MenuModel;

?>
<aside class="main-sidebar">

    <section class="sidebar">

        <!-- Sidebar user panel (optional) -->
        <div class="user-panel">
            <div class="pull-left image">
                <img src="<?php echo Path::profileImg(); ?>/<?php echo AccountModel::getProfileImg(Session::get("account_id")); ?>"
                     class="img-circle" alt="User Image"/>
            </div>
            <div class="pull-left info">
                <p><?php
					echo AccountModel::getFullName(Session::get("account_id")) ?></p>
                <!-- Status -->
                <a href="#"><i class="fa fa-circle text-success"></i> Online</a>
            </div>
        </div>

        <!-- Sidebar Menu -->
        <ul class="sidebar-menu" data-widget="tree">
            <li class="header">MENU</li>
            <!-- Optionally, you can add icons to the links -->
            <li class="active"><a href="<?php echo Path::root(); ?>"><i class='fa fa-home'></i> <span>Dashboard</span></a>
            </li>
			<?php
			/**
			 * Top level menu
			 */
			$items = MenuModel::getAll();
			while ($item = $items->fetchObject()) {

				/**
				 * Display top level menu if childs they have childs items
				 */
				if (MenuModel::getChildsCount($item->menu_id, Session::get("account_group")) > 0) {

					?>
                    <li class="treeview">
                        <a href="#"><i class="fa <?php echo $item->icon; ?> fa-fw"></i>
                            <span><?php echo $item->title; ?></span> <i
                                    class="fa fa-angle-left pull-right"></i></a>
                        <ul class="treeview-menu">
							<?php
							/**
							 * Childs menu
							 */
							$childs = MenuModel::getChilds($item->menu_id, Session::get("account_group"));
							foreach ($childs as $child) {
								?>
                                <li>
                                    <a href="<?php echo Path::module() . '/' . $child->name . '.php'; ?>"><?php echo $child->description; ?></a>
                                </li>
								<?php
							}
							?>
                        </ul>
                    </li>
					<?php
				}
			}
			?>
        </ul><!-- /.sidebar-menu -->
    </section>
    <!-- /.sidebar -->
</aside><!-- Left side column. contains the logo and sidebar -->