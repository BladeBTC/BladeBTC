<?php

namespace BladeBTC\GUI\Commands\Rbac;

use BladeBTC\GUI\Helpers\Security;
use Exception;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class AssignRbac extends Command
{
	protected function configure()
	{
		$this
			->setName('rbac:assign')
			->setDescription('Assign RBAC item to a user group. (Use -- -1 for de developer group)')
			->addArgument('group_id', InputArgument::REQUIRED, 'User group.')
			->addArgument('rbac_id', InputArgument::REQUIRED, 'RBAC item.')
			->setHelp("This command allows you to assign RBAC item to a user group.");
	}

	protected function execute(InputInterface $input, OutputInterface $output)
	{
		try {

			Security::addAssignment($input->getArgument('group_id'), $input->getArgument('rbac_id'));

			$output->writeln('<info>[RBAC]</info>');
			$output->writeln('<info>ID : ' . $input->getArgument('rbac_id') . '</info>');
			$output->writeln('<info>GROUP : ' . $input->getArgument('group_id') . '</info>');
			$output->writeln('<info>Successfuly assigned</info>');

		} catch (Exception $e) {
			$output->writeln('<error>' . $e->getMessage() . '</error>');
		}
	}
}