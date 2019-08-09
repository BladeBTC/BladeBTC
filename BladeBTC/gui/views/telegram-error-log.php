<?php
require $_SERVER['DOCUMENT_ROOT'] . '/gui/views/partials/header.php';

use BladeBTC\GUI\Controllers\ManageErrorLog;
use BladeBTC\GUI\Helpers\Messages;
use BladeBTC\GUI\Helpers\Path;
use BladeBTC\GUI\Helpers\Request;
use BladeBTC\GUI\Helpers\Session;
use BladeBTC\GUI\Models\ErrorLogs;

?>
    <div class="content-wrapper">
        <section class="content-header">
            <h1 id="module">Error Logs</h1>
            <ol class="breadcrumb">
                <li><a href="<?php echo Path::module(); ?>/telegram-error-log.php"><i class="fa fa-dashboard"></i>Error
                        Log</a>
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
                            $message = ManageErrorLog::action();
                            Messages::success($message);
                        } catch (Exception $e) {
                            Messages::error($e->getMessage());
                        }
                    }
                    ?>
                </div>
            </div>

            <div class="box-body">
                <div class="row">
                    <div class="col-md-12">

                        <div class="row">
                            <div class="col-md-12">
                                <div class="box box-warning">
                                    <div class="box-header with-border">
                                        <h3 class="box-title">Fatal Errors</h3>
                                    </div>

                                    <div class="box-body">

                                        <div class="row">
                                            <div class="col-md-12">

                                                <?php
                                                $token = Session::setFormId('manage-link');
                                                ?>

                                                <a class="btn btn-danger pull-right"
                                                   title="Clear error logs"
                                                   data-toggle="tooltip"
                                                   onclick="return iconfirm('Attention!','Are you sure you want to clear error logs?',this.href)"
                                                   href="?action=clear&token=<?php echo $token; ?>">
                                                    <i class="fa fa-trash-o fa-fw"></i>
                                                    Clear Logs
                                                </a>
                                                <div class="clearfix"></div>
                                                <br/>
                                                <!-- Tableau -->
                                                <div class="table-responsive">
                                                    <table id="mng_investment_plan"
                                                           class="table table-striped table-bordered">
                                                        <thead>
                                                        <tr>
                                                            <th>Error Code</th>
                                                            <th>Error</th>
                                                            <th>File</th>
                                                            <th>Line</th>
                                                            <th>Source</th>
                                                            <th>Date</th>
                                                            <th>Action</th>
                                                        </tr>
                                                        </thead>
                                                        <tbody>
                                                        <?php

                                                        $errors = ErrorLogs::getAll();
                                                        while ($error = $errors->fetchObject()) {

                                                            $rm = '?action=delete&id=' . $error->id . '&token=' . $token;

                                                            echo '<tr>
                                                        <td>' . $error->error_number . '</td>
                                                        <td>' . $error->error . '</td>
                                                        <td>' . $error->file . '</td>
                                                        <td>' . $error->line . '</td>
                                                        <td>' . $error->source . '</td>
                                                        <td>' . $error->date . '</td>
                                                        <td>
                                                            <a  class="btn btn-danger btn-xs" 
                                                                title="Delete Investment Plan" 
                                                                data-toggle="tooltip"
                                                                onclick="return iconfirm(\'Attention!\',\'Are you sure you want to delete this line?\',this.href)" 
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
                    </div>
                </div>
            </div>


        </section>
        <!-- /.content -->
    </div>

    <script>
        $(function () {
            $('#mng_investment_plan').DataTable({
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