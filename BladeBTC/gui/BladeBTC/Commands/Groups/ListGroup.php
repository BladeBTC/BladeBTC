<?php


namespace BladeBTC\GUI\Commands\Groups;

use BladeBTC\GUI\Models\GroupModel;
use Exception;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ListGroup extends Command
{
	protected function configure()
	{
		$this
			->setName('group:list')
			->setDescription('Displays a list of all active groups.')
			->setHelp("This command allows you to list all your active groups.");
	}

	protected function execute(InputInterface $input, OutputInterface $output)
	{

		try {

			$groups = GroupModel::getAll();

			$table = new Table($output);
			$table->setHeaders([
				['ID', 'DESCRIPTION'],
			]);


			while ($group = $groups->fetchObject()) {
				$rows[] = [$group->group_id, $group->group_name];
			}

			if (isset($rows) && is_array($rows) && count($rows) > 0) {
				$table->setRows($rows);
				$table->render();
			} else {
				$output->writeln('<error>There is currently no group.</error>');
			}

		} catch (Exception $e) {
			$output->writeln('<error>' . $e->getMessage() . '</error>');
		}
	}
}