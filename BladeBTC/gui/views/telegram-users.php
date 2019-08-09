<?php
require $_SERVER['DOCUMENT_ROOT'] . '/gui/views/partials/header.php';

use BladeBTC\GUI\Helpers\Path;

?>
    <div class="content-wrapper">
        <section class="content-header">
            <h1 id="module">Users (bot)</h1>
            <ol class="breadcrumb">
                <li><a href="<?php echo Path::module(); ?>/telegram-users.php"><i class="fa fa-dashboard"></i>Users (bot)</a>
                </li>

            </ol>
        </section>

        <!-- Main content -->
        <section class="content container-fluid">

            <div class="row">
                <div class="col-md-12">
                    <div class="box box-warning">
                        <div class="box-header with-border">
                            <h3 class="box-title">Users</h3>
                        </div>
                     
                        <div class="box-body">
                            <div class="row">
                                <div class="col-md-12">

                                    <!-- CONTENT HERE -->
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