<?php

namespace BladeBTC\GUI\Commands\Database;

use BladeBTC\GUI\Helpers\Database;
use Exception;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class Import extends Command
{
	protected function configure()
	{
		$this
			->setName('database:import')
			->setDescription('Replace current database with the latest database dump available.')
			->setHelp("This command allow you to replace current database with the latest database dump available..");
	}

	protected function execute(InputInterface $input, OutputInterface $output)
	{

		try {


			/**
			 * Find MySql base dir
			 */
			$db = Database::get();
			$mysql = $db->query("SHOW VARIABLES LIKE 'basedir'")->fetchObject()->Value . "/bin";


			/**
			 * Credentials
			 */
			$dbhost = getenv('HOST');
			$dbuser = getenv("USER");
			$dbpass = getenv("PASS");
			$dbname = getenv("BDD_NAME");


			/**
			 * Find last dump
			 */
			$files = glob(__DIR__ . "/Dump/*.sql");
			$fileNames = [];
			foreach ($files as $file) {
				$fileNames[] = basename($file, ".sql");
			}
			$last_dump = __DIR__ . "/Dump/" . max($fileNames) . ".sql";


			/**
			 * Import
			 */
			$command = "$mysql/mysql --host=$dbhost --user=$dbuser --password=$dbpass $dbname < $last_dump";

			passthru($command, $return);
			if ($return) {
				$output->writeln("<error>Une erreur s'est produite avec l'importation de la base de donnée.</error>");
			} else {
				$output->writeln("<info>La base de données a correctement été importer à partir de : $last_dump.</info>");
			}

		} catch (Exception $e) {
			$output->writeln('<error>' . $e->getMessage() . '</error>');
		}
	}
}