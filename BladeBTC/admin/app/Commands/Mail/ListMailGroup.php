<?php
/**
 * Created by PhpStorm.
 * User: ylafontaine
 * Date: 2018-06-04
 * Time: 11:32
 */

namespace App\Commands\Mail;

use Exception;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use App\Models\MailGroupModel;
use App\Models\MailGroupMemberModel;
use Symfony\Component\Console\Helper\TableCell;

class ListMailGroup extends Command
{
	protected function configure()
	{
		$this
			->setName('mailgroup:list')
			->setDescription('List mail groups.')
			->setHelp("This command allows you to list all mail groups.");
	}

	protected function execute(InputInterface $input, OutputInterface $output)
	{
		try {


			/**
			 * Get groups
			 */
			$groups = MailGroupModel::getAll();


			/**
			 * Render
			 */
			foreach ($groups as $group) {

				$rows = null;
				$rows[] = [];

				/**
				 * Get groups members
				 */
				$group_members = MailGroupMemberModel::getAllMembersFromGroupID($group->id);

				foreach ($group_members as $group_member) {
					$rows[] = [$group_member->id, $group_member->email, $group_member->alias];
				}

				$table = new Table($output);
				$table
					->setHeaders([
						[new TableCell($group->group_name . ' (' . $group->id . ')', ['colspan' => 3])],
						['MEMBER ID', 'EMAIL', 'ALIAS'],
					])
					->setRows($rows);

				$table->render();
			}


		} catch (Exception $e) {
			$output->writeln('<error>' . $e->getMessage() . '</error>');
		}

	}
}