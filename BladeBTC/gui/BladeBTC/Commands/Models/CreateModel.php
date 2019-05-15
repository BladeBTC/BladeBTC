<?php


namespace BladeBTC\GUI\Commands\Models;

use Exception;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class CreateModel extends Command
{
	protected function configure()
	{
		$this
			->setName('model:create')
			->setDescription('Create new model.')
			->addArgument('name', InputArgument::REQUIRED, 'The name of the model.')
			->setHelp("This command allows you to create new model.");
	}

	protected function execute(InputInterface $input, OutputInterface $output)
	{
		try {


			$ns = "BladeBTC\GUI\Models";
			$file = "gui/BladeBTC/Models/" . $input->getArgument('name') . "Model.php";


			/**
			 * Validate model name
			 */
			if (file_exists($file)) {
				throw new Exception("A model with the same name already exist.");
			}

			/**
			 * Prepare file content
			 */
			$content = "<?php

namespace " . $ns . ";

use BladeBTC\GUI\Helpers\Database;


/**
 * Class " . $input->getArgument('name') . "Model
 *
 * @package " . $ns . "
 */
class " . $input->getArgument('name') . "Model
{
	
	public static function getAll()
	{
		\$db = Database::get();
	}

	public static function add(\$data)
	{
		\$db = Database::get();

		\$query = \"INSERT INTO 
					  table (
						field
					  )
					VALUES (
						:field 
					)\";

		\$sth = \$db->prepare(\$query);

		\$sth->execute([
			\"field\"  => \$data['field'],
		]);
	}
	
	
	public static function update(\$data)
	{
		\$db = Database::get();

		\$query = \"UPDATE
					  table
					SET
					  field = :field
					WHERE
					  id = :id\";

		\$sth = \$db->prepare(\$query);

		\$sth->execute([
			\"id\"   => \$data[\"id\"],
			\"field\"  => \$data[\"field\"],
		]);
	}
	
	
	public static function delete(\$data)
	{
	
		\$db = Database::get();
	
		Database::transaction(\$db, [
		
			\"DELETE FROM table WHERE field = \$data\"
			
		]);
	}
}
";

			/**
			 * Create and write new model
			 */
			$model = fopen($file, "w");
			fwrite($model, $content);

			$output->writeln('<info>Model ' . $input->getArgument('name') . 'Model successfully created.</info>');

		} catch (Exception $e) {
			$output->writeln('<error>' . $e->getMessage() . '</error>');
		}
	}
}