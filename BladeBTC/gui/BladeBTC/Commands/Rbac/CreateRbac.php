<?php

namespace BladeBTC\GUI\Commands\Rbac;

use BladeBTC\GUI\Helpers\Security;
use Exception;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;


class CreateRbac extends Command
{
	protected function configure()
	{
		$this
			->setName('rbac:create')
			->setDescription('Create new RBAC item.')
			->addArgument('description', InputArgument::REQUIRED, 'The description of the RBAC.')
			->setHelp("This command allows you to create new RBAC item.");
	}

	protected function execute(InputInterface $input, OutputInterface $output)
	{
		try {

			/**
			 * Get param
			 */
			$description = utf8_encode($input->getArgument('description'));


			/**
			 * Query
			 */
			$rbac_id = Security::addItem($description);


			/**
			 * Display
			 */
			$table = new Table($output);
			$table->setHeaders([
				['ID', 'DESCRIPTION', 'USAGE'],
			]);

			$rows[] = [$rbac_id, $description, "if (Security::can($rbac_id)){}"];


			if (isset($rows) && is_array($rows) && count($rows) > 0) {
				$table->setRows($rows);
				$table->render();
			} else {
				$output->writeln('<error>An unknown error has occurred.</error>');
			}
			

		} catch (Exception $e) {
			$output->writeln('<error>' . $e->getMessage() . '</error>');
		}
	}
}