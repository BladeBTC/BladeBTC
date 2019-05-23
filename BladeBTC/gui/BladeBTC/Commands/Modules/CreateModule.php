<?php


namespace BladeBTC\GUI\Commands\Modules;

use Exception;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class CreateModule extends Command
{
	protected function configure()
	{
		$this
			->setName('module:create')
			->setDescription('Create new module.')
			->addArgument('name', InputArgument::REQUIRED, 'The name of the module.')
			->setHelp("This command allows you to create new module.");
	}

	protected function execute(InputInterface $input, OutputInterface $output)
	{

		try {

			/**
			 * Validate model name
			 */
			if (file_exists("views/" . $input->getArgument('name') . ".php")) {
				throw new Exception("A module with the same name already exist.");
			}

			/**
			 * Prepare file content
			 */
			$content = "<?php
require \$_SERVER['DOCUMENT_ROOT'] . '/gui/views/partials/header.php';

use BladeBTC\GUI\Helpers\Path;

?>
    <div class=\"content-wrapper\">
        <section class=\"content-header\">
            <h1 id=\"module\"><!-- PAGE TITLE HERE --></h1>
            <ol class=\"breadcrumb\">
                <li><a href=\"<?php echo Path::module(); ?>/" . $input->getArgument('name') . ".php\"><i class=\"fa fa-dashboard\"></i><!-- PAGE TITLE HERE --></a>
                </li>

            </ol>
        </section>

        <!-- Main content -->
        <section class=\"content container-fluid\">

            <div class=\"row\">
                <div class=\"col-md-12\">
                    <div class=\"box box-warning\">
                        <div class=\"box-header with-border\">
                            <h3 class=\"box-title\"><!-- BOX TITLE HERE --></h3>
                        </div>
                     
                        <div class=\"box-body\">
                            <div class=\"row\">
                                <div class=\"col-md-12\">

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
require \$_SERVER['DOCUMENT_ROOT'] . '/gui/views/partials/footer.php';";

			/**
			 * Create and write new module
			 */
			$model = fopen("views/" . $input->getArgument('name') . ".php", "w");
			fwrite($model, $content);

			$output->writeln('Module ' . $input->getArgument('name') . ' successfully created.');

		} catch (Exception $e) {
			$output->writeln($e->getMessage());
		}
	}
}