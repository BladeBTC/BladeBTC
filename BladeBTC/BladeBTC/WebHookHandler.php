<?php

namespace BladeBTC;

use BladeBTC\Helpers\AddressValidator;
use Telegram\Bot\Api;

/**
 * Class WebHookHandler
 *
 * @package BladeBTC
 */
class WebHookHandler
{

	/**
	 * WebHookHandler constructor.
	 */
	public function __construct(Api $telegram)
	{

		/**
		 * Populate commands list
		 */
		$telegram->addCommands([
			Commands\StartCommand::class,
			Commands\WalletBalanceCommand::class,
			Commands\SmsAllCommand::class,
			Commands\BalanceCommand::class,
			Commands\InvestCommand::class,
			Commands\WithdrawCommand::class,
			Commands\ReinvestCommand::class,
			Commands\BackCommand::class,
			Commands\ErrorCommand::class,
			Commands\UpdateWalletCommand::class,
			Commands\OutCommand::class,
			Commands\ReferralCommand::class,
			Commands\InfoCommand::class,
		]);

		/**
		 * Handle commands
		 */
		$telegram->commandsHandler(true);

		/**
		 * Handle text command (button)
		 */
		$updates = $telegram->getWebhookUpdates();
		$text = $updates->getMessage()->getText();

		if (preg_match("/\bRevenue\b/i", $text)) {
			$telegram->getCommandBus()->handler('/revenue', $updates);
		} elseif (preg_match("/\bBalance\b/i", $text)) {
			$telegram->getCommandBus()->handler('/balance', $updates);
		} elseif (preg_match("/\bInvest\b/i", $text)) {
			$telegram->getCommandBus()->handler('/invest', $updates);
		} elseif (preg_match("/\bWithdraw\b/i", $text)) {
			$telegram->getCommandBus()->handler('/withdraw', $updates);
		} elseif (preg_match("/\bReinvest\b/i", $text)) {
			$telegram->getCommandBus()->handler('/reinvest', $updates);
		} elseif (preg_match("/\bBack\b/i", $text)) {
			$telegram->getCommandBus()->handler('/back', $updates);
		} elseif (preg_match("/\bTeam\b/i", $text)) {
			$telegram->getCommandBus()->handler('/referral', $updates);
		} elseif (preg_match("/\bHelp\b/i", $text)) {
			$telegram->getCommandBus()->handler('/info', $updates);
		} /**
		 * Message match nothing - Validate if text is a bitcoin wallet address and save it to user account.
		 */
		else {

			/**
			 * Text is a valid bitcoin address - Save it
			 */
			if (AddressValidator::isValid($text)) {
				$telegram->getCommandBus()->handler('/update_wallet', $updates);
			} /**
			 * Cannot handle message return error.
			 */
			else {

				/**
				 * Add command handled by the main command handler
				 * Avoid returning error for nothing.
				 */
				if (!preg_match("/\/out/", $text) &&
					!preg_match("/\/gwb/", $text) &&
					!preg_match("/\/sms/", $text) &&
					!preg_match("/\/start/", $text)
				) {
					$telegram->getCommandBus()->handler('/error', $updates);
				}
			}
		}
	}
}