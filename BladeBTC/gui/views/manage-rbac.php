<?php
require $_SERVER['DOCUMENT_ROOT'] . '/gui/views/partials/header.php';

use BladeBTC\GUI\Helpers\Path;
use BladeBTC\GUI\Helpers\Security;
use BladeBTC\GUI\Helpers\Session;
use BladeBTC\GUI\Models\GroupModel;
use BladeBTC\GUI\Models\RbacModel;

?>
    <div class="content-wrapper">
        <section class="content-header">
            <h1 id="module">Permissions management (RBAC)</h1>
            <ol class="breadcrumb">
                <li><a href="<?php echo Path::module(); ?>/manage-rbac.php"><i class="fa fa-dashboard"></i>Permissions management (RBAC)</a>
                </li>

            </ol>
        </section>

        <!-- Main content -->
        <section class="content container-fluid">

            <div id="good-box" class="row hidden">
                <div class="col-md-12">
                    <div class="alert alert-success"><i class="fa fa-check-circle fa-fw"></i><span id="good-msg"></span>
                    </div>
                </div>
            </div>

            <div id="bad-box" class="row hidden">
                <div class="col-md-12">
                    <div class="alert alert-danger"><i class="fa fa-exclamation-circle fa-fw"></i><span
                                id="bad-msg"></span></div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12">
                    <div class="box box-warning">
                        <div class="box-header with-border">
                            <h3 class="box-title">Permissions management (RBAC)</h3>
                        </div>

                        <div class="box-body">
                            <div class="row">
                                <div class="col-md-12">
                                    <form id="permissions">
										<?php
										$groups = GroupModel::getAll();
										while ($group = $groups->fetchObject()) {
											?>
                                            <h4>Group <strong><?php echo $group->group_name; ?></strong></h4>
                                            <table class="table">
                                                <tr>
													<?php
													$i = 1;
													$rbacs = Security::getItems();
													while ($rbac = $rbacs->fetchObject()) {
														?>

                                                        <td>
                                                            <div class="checkbox icheck">
                                                                <label>
                                                                    <input type="checkbox"
                                                                           name="<?php echo $group->group_id; ?>"
                                                                           value="<?php echo $rbac->id; ?>"
																		<?php echo RbacModel::can($rbac->id, $group->group_id) ? 'checked' : null; ?>>
                                                                    <label><?php echo $rbac->description; ?></label>
                                                                </label>
                                                            </div>
                                                        </td>

														<?php
														if ($i % 4 == 0) {
															echo '</tr><tr>';
														}
														$i++;
													}
													?>
                                                </tr>
                                            </table>
											<?php
										}
										?>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!-- /.content -->
    </div>

    <script type="text/javascript">
        $(document).on("ifChanged", "input[type='checkbox']", function () {

            if (this.checked) {
                save('add', this.name, this.value);
            } else {
                save('remove', this.name, this.value);
            }

            function save(mode, group, rbac) {
                $.ajax({
                    url: '<?php echo Path::root();?>/BladeBTC/REST/ManageRBAC.php',
                    type: 'POST',
                    data: {
                        group_id: group,
                        rbac_id: rbac,
                        mode: mode,
                        jwt: '<?php echo Session::get('account_jwt');?>'
                    },

                    success: function (msg) {

                        var modeText = mode === "add" ? "added to" : "deleted from";

                        document.querySelector('#good-box').classList.remove("hidden");
                        document.querySelector('#bad-box').classList.add("hidden");
                        document.querySelector('#good-msg').innerText = "Permission #" + rbac + " has been " + modeText + " group #" + group;

                        if (msg.status !== 200) {
                            document.querySelector('#good-box').classList.add("hidden");
                            document.querySelector('#bad-box').classList.remove("hidden");
                            document.querySelector('#bad-msg').innerText = msg.msg;
                        }
                    },
                    error: function () {
                        document.querySelector('#good-box').classList.add("hidden");
                        document.querySelector('#bad-box').classList.remove("hidden");
                        document.querySelector('#bad-msg').innerText = "An unexpected error has occurred.";
                    }
                });
            }
        });

    </script>

<?php
require $_SERVER['DOCUMENT_ROOT'] . '/gui/views/partials/footer.php';