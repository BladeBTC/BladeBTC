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


            /**
             * Check if address label is valid
             */
            if (!empty($address['label'])) {

                /***
                 * Validate Label User ID
                 */
                if (Users::checkExistByLabelNumber($address['label'])) {


                    /**
                     * Build user object
                     */
                    $user = new Users($address['label']);


                    /**
                     * Calculate BTC on this address
                     */
                    $userLastConfirmedInBTC = $user->getLastConfirmed();
                    $totalConfirmedForThisAddressInBTC = Btc::SatoshiToBitcoin($address['total_received']);
                    $confirmedNewDepositInBtc = $totalConfirmedForThisAddressInBTC - $userLastConfirmedInBTC;


                    /**
                     * Check if transaction have confirmation
                     */
                    if ($confirmedNewDepositInBtc > 0) {


                        /**
                         * Set last confirmed
                         */
                        $user->setLastConfirmed($totalConfirmedForThisAddressInBTC);


                        /**
                         * Create investment
                         */
                        Investment::create($user->getTelegramId(), $confirmedNewDepositInBtc);


                        /**
                         * Update invested
                         */
                        $newInvested = $user->getInvested() + $confirmedNewDepositInBtc;
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
                                $commission = ($confirmedNewDepositInBtc * $rate) / 100;
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
                            'chat_id' => $user->getTelegramId(),
                            'text' => 'Your deposit of <b>' . $confirmedNewDepositInBtc . '</b> is now accepted and invested. You will recover this amount with interest in your balance at the end of your contract.'
                        ];
                        $response = file_get_contents("https://api.telegram.org/bot$apiToken/sendMessage?" . http_build_query($data));

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
