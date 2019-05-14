<?php
namespace BladeBTC\GUI\Commands\Mail;

use Exception;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use BladeBTC\GUI\Models\MailGroupModel;

class CreateMailGroup extends Command
{
	protected function configure()
	{
		$this
			->setName('mailgroup:create')
			->setDescription('Create new mail group.')
			->addArgument('name', InputArgument::REQUIRED, 'Group Name.')
			->setHelp("This command allows you to create a new mail group.");
	}

	protected function execute(InputInterface $input, OutputInterface $output)
	{
		try {

			/**
			 * Get param
			 */
			$group_name = utf8_encode($input->getArgument('name'));


			/**
			 * Query
			 */
			$group_id = MailGroupModel::addGroup($group_name);


			/**
			 * Display
			 */
			$table = new Table($output);
			$table->setHeaders([
				['ID', 'DESCRIPTION'],
			]);

			$rows[] = [$group_id, $group_name];


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