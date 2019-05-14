<?php
namespace BladeBTC\GUI\Commands\Mail;

use BladeBTC\GUI\Models\MailGroupMemberModel;
use Exception;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class UserDelMailGroup extends Command
{
	protected function configure()
	{
		$this
			->setName('mailgroup:deluser')
			->setDescription('Delete member from a mail group.')
			->addArgument('member_id', InputArgument::REQUIRED, 'Member ID')
			->setHelp("This command allows you to delete member from a mail group.");
	}

	protected function execute(InputInterface $input, OutputInterface $output)
	{
		try {

			/**
			 * Get param
			 */
			$member_id = utf8_encode($input->getArgument('member_id'));


			/**
			 * Query
			 */
			MailGroupMemberModel::delete($member_id);


			/**
			 * Display
			 */
			$table = new Table($output);
			$table->setHeaders([
				['ID', 'STATUS'],
			]);

			$rows[] = [$member_id, "DELETED"];


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