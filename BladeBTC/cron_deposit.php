<?php

require __DIR__ . '/bootstrap/app.php';

use BladeBTC\Helpers\Btc;
use BladeBTC\Helpers\Wallet;
use BladeBTC\Models\BotSetting;
use BladeBTC\Models\ErrorLogs;
use BladeBTC\Models\Investment;
use BladeBTC\Models\InvestmentPlan;
use BladeBTC\Models\Transactions;
use BladeBTC\Models\Users;

try {

    /**
     * Load .env file
     */
    $env = new Dotenv\Dotenv(__DIR__);
    $env->load();

    /**
     * Recover all address
     */
    $addresses = Wallet::listAddress();
    foreach ($addresses['addresses'] as $address) {


        /**
         * Check if address have balance
         */
        if ($address['total_received'] > 0) {


            /***
             * Validate Label User ID
             */
            if (Users::checkExistByInvestmentAddress($address['address'])) {


                /**
                 * Try go get user telegram ID from address
                 */
                $telegram_id = Users::getTelegramIDByInvestmentAddress($address['address']);


                /**
                 * Verify if we found telegram ID
                 */
                if (!is_null($telegram_id)) {


                    /**
                     * Build user object
                     */
                    $user = new Users($telegram_id);


                    /**
                     * Calculate BTC on this address
                     */
                    $userLastConfirmedInBTC = $user->getLastConfirmed();
                    $totalConfirmedForThisAddressInBTC = Btc::SatoshiToBitcoin(Wallet::getConfirmedReceivedByAddress($address['address']));
                    $confirmedNewDepositInBtc = $totalConfirmedForThisAddressInBTC - $userLastConfirmedInBTC;


                    /**
                     * Check if transaction have confirmation
                     */
                    if ($confirmedNewDepositInBtc > 0) {


                        /**
                         * Set last confirmed
                         */
                        $user->setLastConfirmed($totalConfirmedForThisAddressInBTC);
                        $user->refresh();

                        /**
                         * Check if new confirmed amount is higher to create an investment based on the investment plan active
                         */
                        $balanceConfirmed = $user->getLastConfirmed() - $user->getInvested();
                        if ($balanceConfirmed >= InvestmentPlan::getValueByName('minimum_invest')) {

                            /**
                             * Create investment
                             */
                            Investment::create($user->getTelegramId(), $balanceConfirmed);


                            /**
                             * Update invested
                             */
                            $newInvested = $user->getInvested() + $balanceConfirmed;
                            $user->setInvested($newInvested);


                            /**
                             * Give bonus to referent - First invest only
                             */
                            if ($user->getNumberOfInvestment() == 1) {


                                /**
                                 * Get referent Id
                                 */
                                $referent_id = $user->getReferentId();


                                /**
                                 * Give commission
                                 */
                                if (!is_null($referent_id)) {

                                    $rate = InvestmentPlan::getValueByName("commission_rate");
                                    $commission = ($balanceConfirmed * $rate) / 100;
                                    Users::giveCommission($referent_id, $commission);
                                }
                            }

                            /**
                             * Log transaction
                             */
                            Transactions::log([
                                "telegram_id" => $user->getTelegramId(),
                                "amount" => $confirmedNewDepositInBtc,
                                "withdraw_address" => "",
                                "message" => "",
                                "tx_hash" => "",
                                "tx_id" => "",
                                "status" => 1,
                                "type" => "deposit",
                            ]);


                            /**
                             * Send user message - Notification of deposit
                             */
                            $apiToken = BotSetting::getValueByName('app_id');
                            $data = [
                                'parse_mode' => 'HTML',
                                'chat_id' => $user->getTelegramId(),
                                'text' => 'Your deposit of <b>' . BTC::Format($confirmedNewDepositInBtc) . '</b> is now accepted and your balance of ' . BTC::Format($balanceConfirmed) . ' is invested. You will recover this amount with interest in your balance at the end of your contract.'
                            ];
                            $response = file_get_contents("https://api.telegram.org/bot$apiToken/sendMessage?" . http_build_query($data));
                        }
                        else {

                            /**
                             * Log transaction
                             */
                            Transactions::log([
                                "telegram_id" => $user->getTelegramId(),
                                "amount" => $confirmedNewDepositInBtc,
                                "withdraw_address" => "",
                                "message" => "",
                                "tx_hash" => "",
                                "tx_id" => "",
                                "status" => 1,
                                "type" => "deposit",
                            ]);

                            /**
                             * Send user message - Notification of deposit
                             */
                            $apiToken = BotSetting::getValueByName('app_id');
                            $data = [
                                'parse_mode' => 'HTML',
                                'chat_id' => $user->getTelegramId(),
                                'text' => 'Your deposit of <b>' . BTC::Format($confirmedNewDepositInBtc) . '</b> is now accepted but is not higher to invest. You have now an amount of ' . BTC::Format($balanceConfirmed) . ' BTC. The minimum invest is ' . BTC::Format(InvestmentPlan::getValueByName('minimum_invest')) . ' BTC.'
                            ];
                            $response = file_get_contents("https://api.telegram.org/bot$apiToken/sendMessage?" . http_build_query($data));
                        }
                    }
                }
            }
        }
    }

} catch (Exception $e) {
    try {

        ErrorLogs::Log($e->getCode(), $e->getMessage(), $e->getLine(), 'CRON DEPOSIT', $e->getFile());
        error_log($e->getMessage() . " on line " . $e->getLine() . " in file " . $e->getFile());

    } catch (Exception $q) {

        error_log($q->getMessage() . " on line " . $q->getLine() . " in file " . $q->getFile());
    }
}
