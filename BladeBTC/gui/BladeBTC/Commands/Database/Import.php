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
			$dbhost = getenv("DB_HOST");
			$dbuser = getenv("DB_USER");
			$dbpass = getenv("DB_PASS");
			$dbname = getenv("DB_DB");


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
				$output->writeln("<error>An error occurred while importing the database.</error>");
			} else {
				$output->writeln("<info>The database was successfully imported from: $last_dump.</info>");
			}

		} catch (Exception $e) {
			$output->writeln('<error>' . $e->getMessage() . '</error>');
		}
	}
}