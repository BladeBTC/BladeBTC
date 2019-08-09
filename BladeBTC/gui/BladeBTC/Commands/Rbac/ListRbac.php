<?php

namespace BladeBTC\GUI\Commands\Rbac;


use BladeBTC\GUI\Helpers\Security;
use Exception;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;


class ListRbac extends Command
{
	protected function configure()
	{
		$this
			->setName('rbac:list')
			->setDescription('Displays a list of all active RBACs.')
			->setHelp("This command allows you to list all your actives RBAC.");
	}

	protected function execute(InputInterface $input, OutputInterface $output)
	{

		try {

			$rbacs = Security::getItems();

			$table = new Table($output);
			$table->setHeaders([
				['ID', 'DESCRIPTION', 'USAGE'],
			]);


			while ($rbac = $rbacs->fetchObject()) {
				$rows[] = [$rbac->id, $rbac->description, "if (Security::can({$rbac->id})){}"];
			}

			if (isset($rows) && is_array($rows) && count($rows) > 0) {
				$table->setRows($rows);
				$table->render();
			} else {
				$output->writeln('<error>There is currently no rule.</error>');
			}
			
		} catch (Exception $e) {
			$output->writeln('<error>' . $e->getMessage() . '</error>');
		}

	}
}