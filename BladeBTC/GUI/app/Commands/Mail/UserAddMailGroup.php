<?php
/**
 * Created by PhpStorm.
 * User: ylafontaine
 * Date: 2018-06-04
 * Time: 11:32
 */

namespace App\Commands\Mail;

use App\Models\MailGroupMemberModel;
use Exception;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class UserAddMailGroup extends Command
{
	protected function configure()
	{
		$this
			->setName('mailgroup:adduser')
			->setDescription('Add member to specific mail group.')
			->addArgument('group_id', InputArgument::REQUIRED, 'Mail Group ID.')
			->addArgument('email', InputArgument::REQUIRED, 'Email Address.')
			->addArgument('alias', InputArgument::OPTIONAL, 'Email Alias')
			->setHelp("This command allows you to add member to mail group.");
	}

	protected function execute(InputInterface $input, OutputInterface $output)
	{
		try {

			/**
			 * Get param
			 */
			$group_id = utf8_encode($input->getArgument('group_id'));
			$email = utf8_encode($input->getArgument('email'));
			$alias = utf8_encode($input->getArgument('alias'));

			if (empty($alias)) {
				$alias = null;
			}

			/**
			 * Query
			 */
			$id = MailGroupMemberModel::add($group_id, $email, $alias);


			/**
			 * Display
			 */
			$table = new Table($output);
			$table->setHeaders([
				['ID', 'GROUP ID', 'EMAIL', 'ALIAS'],
			]);

			$rows[] = [$id, $group_id, $email, $alias];


			if (isset($rows) && is_array($rows) && count($rows) > 0) {
				$table->setRows($rows);
				$table->render();
			} else {
				$output->writeln('<error>Une erreur inconnue s\'est produite.</error>');
			}

		} catch (Exception $e) {
			$output->writeln('<error>' . $e->getMessage() . '</error>');
		}
	}
}