<?php

namespace BladeBTC\GUI\Commands\Rbac;

use BladeBTC\GUI\Models\GroupModel;
use BladeBTC\GUI\Models\RbacModel;
use Exception;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Helper\TableCell;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class AssignmentRbac extends Command
{
	protected function configure()
	{
		$this
			->setName('rbac:assignment')
			->setDescription('Show RBAC assignment.')
			->setHelp("This command allows you to show RBAC item assigment.");
	}

	protected function execute(InputInterface $input, OutputInterface $output)
	{
		try {

			/**
			 * clear screen
			 */
			$output->write(sprintf("\033\143"));


			/**
			 * Get data
			 */
			$assigments = RbacModel::getAssignments();


			/**
			 * Render
			 */
			while ($assigment = $assigments->fetchObject()) {

				if (isset($group_id) && $group_id != $assigment->group_id) {

					if (isset($table)) {
						$table->render();
					}

					$output->writeln('');

					unset($rows);
				}

				$rows[] = [$assigment->rbac_items_id, RbacModel::getDescriptionById($assigment->rbac_items_id)];

				$table = new Table($output);
				$table
					->setHeaders([
						[new TableCell(GroupModel::getNameById($assigment->group_id) . ' (' . $assigment->group_id . ')', ['colspan' => 2])],
						['RBAC ID', 'RBAC DESCRIPTION'],
					])
					->setRows($rows);


				$group_id = $assigment->group_id;
			}


			/**
			 * Render last table
			 */
			if (isset($table)) {
				$table->render();
			}

		} catch (Exception $e) {
			$output->writeln('<error>' . $e->getMessage() . '</error>');
		}
	}
}





