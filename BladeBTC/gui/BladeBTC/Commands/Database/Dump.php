<?php
/**
 * Copyright (C) 2014 - 2017 CMS - All Rights Reserved
 * Unauthorized copying of this file, via any medium is strictly prohibited.
 * Proprietary and confidential.
 * Last edit : 17-03-07 16:38
 */

namespace BladeBTC\GUI\Commands\Database;

use BladeBTC\GUI\Helpers\Database;
use Exception;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class Dump extends Command
{
	protected function configure()
	{
		$this
			->setName('database:dump')
			->setDescription('Dump current database file to sql file.')
			->setHelp("This command allow you to to dump main database.");
	}

	protected function execute(InputInterface $input, OutputInterface $output)
	{

		try {

			/**
			 * Find MySql base dir
			 */
			$db = Database::get();
			$mysqldump = $db->query("SHOW VARIABLES LIKE 'basedir'")->fetchObject()->Value . "/bin";

			/**
			 * Command
			 */
			$dbhost = getenv("DB_HOST");
			$dbuser = getenv("DB_USER");
			$dbpass = getenv("DB_PASS");
			$dbname = getenv("DB_DB");
			$dumpFile = __DIR__ . "/Dump/" . uniqid() . ".sql";
			$command = "$mysqldump/mysqldump --host=$dbhost --user=$dbuser --password=$dbpass $dbname > $dumpFile";

			passthru($command, $return);
			if ($return) {
				$output->writeln("<error>An error occurred while exporting the database.</error>");
			} else {
				$output->writeln("<info>The database has been successfully exported to the file: $dumpFile.</info>");
			}

		} catch (Exception $e) {
			$output->writeln('<error>' . $e->getMessage() . '</error>');
		}
	}
}