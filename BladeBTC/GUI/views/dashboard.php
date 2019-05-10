<?php
require $_SERVER['DOCUMENT_ROOT'] . '/views/partials/header.php';

use App\Helpers\Path;

?>
    <div class="content-wrapper">
        <section class="content-header">
            <h1 id="module">Accueil</h1>
            <ol class="breadcrumb">
                <li><a href="<?php echo Path::root(); ?>"><i class="fa fa-dashboard"></i>Accueil</a>
                </li>

            </ol>
        </section>

        <!-- Main content -->
        <section class="content container-fluid">

            <div class="row">
                <div class="col-md-12">
                    <div class="box box-default">
                        <div class="box-header with-border">
                            <h3 class="box-title">Bienvenue!</h3>
                        </div>
                        <!-- /.box-header -->
                        <div class="box-body">
                            <div class="row">
                                <div class="col-md-12">
                                    <p>Bienvenue dans votre nouvelle plateforme pour Ogasys.<br/>
                                        Vous trouverez ici certains outils qui vous aideront dans votre travail durant
                                        la
                                        période
                                        d'implantation d'Ogasys.</p>
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