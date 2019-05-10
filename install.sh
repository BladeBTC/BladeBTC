#!/bin/bash

####################################################################################
#
# Open port 80, 443, 10000
# chmod 770 install.sh
# sudo ./install.sh
#
####################################################################################

####################################################################################
################################## VARIABLES #######################################
####################################################################################

#SERVER DOMAIN
DOMAIN=""

#DEBUG
DEBUG=0

#DATABASE
HOST="127.0.0.1"
USER="root"
PASS=""
DB="telegram_bot"

#TELEGRAM
APP_ID=""
APP_NAME=""

#CHAINBLOCK
WALLET_ID=""
WALLET_PASSWORD=""
WALLET_PASSWORD_SECOND=""

#RULES
MINIMUM_INVEST=""
MINIMUM_REINVEST=""
MINIMUM_PAYOUT=""
BASE_RATE=""
CONTRACT_DAY=""
COMMISSION_RATE=""
TIMER_TIME_HOUR=""
REQUIRED_CONFIRMATIONS=""
INTEREST_ON_REINVEST=""
WITHDRAW_FEE=""

#SUPPORT
SUPPORT_CHAT_ID=""

####################################################################################
#################################### START #########################################
####################################################################################

clear

echo "============================================================================="
echo " Welcome - Install Script"
echo "============================================================================="
echo ""
echo "Before we start installation, we need to collect some information."
echo "We're now going to start to collect all information needed by this installer!"
echo ""
echo -e "[IMPORTANT] The database username will be \e[92mroot\e[0m"
echo "============================================================================="

#check for root access
if [[ $EUID -ne 0 ]]; then
   echo "This script must be run as root"
   exit 1
fi

####################################################################################
################################# INSTALLING #######################################
####################################################################################

make_install(){

	#update server
	echo -e "\e[92mUpdating server ... [PLEASE WAIT]\e[0m"
	apt-get update -y
	apt-get upgrade -y
	echo -e "\e[92mUpdating server ... [DONE]\e[0m"

	#webmin source
	echo -e "\n\n\e[92mAdding source for Webmin ... [PLEASE WAIT]\e[0m"
	wget -O- http://www.webmin.com/jcameron-key.asc | sudo apt-key add -
	echo "deb http://download.webmin.com/download/repository sarge contrib" >> /etc/apt/sources.list
	echo -e "\e[92mAdd source for Webmin ... [DONE]\e[0m"

    #certbot source
    echo -e "\e[92mAdding source for Certbot ... [PLEASE WAIT]\e[0m"
    add-apt-repository ppa:certbot/certbot -y
    echo -e "\e[92mAdding source for Certbot ... [DONE]\e[0m"

    #install some other package
	echo -e "\e[92mInstalling all needed package ... [PLEASE WAIT]\e[0m"
	apt-get install apache2 mysql-server php libapache2-mod-php htop webmin nodejs build-essential software-properties-common python-certbot-apache -y
	echo -e "\e[92mInstalling all needed package ... [DONE]\e[0m"

    #install nodejs
    echo -e "\e[92mInstalling node.js ... [PLEASE WAIT]\e[0m"
	curl -sL https://deb.nodesource.com/setup_6.x | sudo -E bash -
	echo -e "\e[92mInstalling node.js ... [DONE]\e[0m"

	#install wallet service
	echo -e "\e[92mInstalling Blockchain Wallet API ... [PLEASE WAIT]\e[0m"
	sudo npm install -g blockchain-wallet-service
	echo -e "\e[92mInstalling Blockchain Wallet API ... [DONE]\e[0m"

	#enable apache module
	echo -e "\e[92mLoading apache2 modules ... [PLEASE WAIT]\e[0m"
	a2enmod rewrite
	a2enmod ssl
	echo -e "\e[92mLoading apache2 modules ... [DONE]\e[0m"
	
    #write server name
	echo -e "\e[92mWriting apache2 configuration ... [PLEASE WAIT]\e[0m"
	echo "ServerName ServerName" >> /etc/apache2/apache2.conf

	#APACHE Config
	sitesAvailable='/etc/apache2/sites-available/'
	sitesAvailabledomain=${sitesAvailable}${DOMAIN}.conf
	sslSitesAvailabledomain=${sitesAvailable}"ssl_"${DOMAIN}.conf
	
	#Check if config exist
	if [[ -e ${sitesAvailabledomain} ]]; then
		
		echo -e "\e[92mWriting apache2 configuration ... [DONE]\e[0m"
	
	else
		
		#install website
		cp -r ./BladeBTC /var/www/bot

		#creating vhost
		if ! echo "
			<VirtualHost *:80>
			
				#general
				ServerAdmin webmaster@$DOMAIN
				ServerName $DOMAIN
				ServerAlias $DOMAIN
				
				#directory
				DocumentRoot /var/www/bot/
				<Directory />
					AllowOverride All
				</Directory>
				<Directory /var/www/bot/>
					Options -Indexes +FollowSymLinks +MultiViews
					AllowOverride all
					Require all granted
				</Directory>
				
				#log
				ErrorLog /var/log/apache2/$DOMAIN-error.log
				LogLevel error
				CustomLog /var/log/apache2/$DOMAIN-access.log combined
				
			</VirtualHost>" > ${sitesAvailabledomain}
		then
		    echo -e "\e[31mWriting apache2 configuration ... [FAILED]\e[0m"
		else

			echo -e "\e[92mWriting apache2 configuration ... [DONE]\e[0m"
		fi
	fi

    #Check if config exist
    echo -e "\e[92mWriting SSL apache2 configuration ... [PLEASE WAIT]\e[0m"
	if [[ -e ${sslSitesAvailabledomain} ]]; then

		echo -e "\e[92mWriting SSL apache2 configuration ... [DONE]\e[0m"

	else

	    #creating SSL vhost
		if ! echo "
			<IfModule mod_ssl.c>
				<VirtualHost *:443>

					#general
					ServerAdmin webmaster@$DOMAIN
					ServerName $DOMAIN
					ServerAlias $DOMAIN

					#directory
					DocumentRoot /var/www/bot/
					<Directory />
						AllowOverride All
					</Directory>
					<Directory /var/www/bot/>
						Options -Indexes +FollowSymLinks +MultiViews
						AllowOverride all
						Require all granted
						SSLOptions +StdEnvVars
					</Directory>

					#log
					ErrorLog /var/log/apache2/$DOMAIN-error.log
					LogLevel error
					CustomLog /var/log/apache2/$DOMAIN-access.log combined

					#ssl
					SSLEngine on
					SSLProtocol             all -SSLv3
					SSLCipherSuite          ECDHE-ECDSA-CHACHA20-POLY1305:ECDHE-RSA-CHACHA20-POLY1305:ECDHE-ECDSA-AES128-GCM-SHA256:ECDHE-RSA-AES128-GCM-SHA256:ECDHE-ECDSA-AES256-GCM-SHA384:ECDHE-RSA-AES256-GCM-SHA384:DHE-RSA-AES128-GCM-SHA$
					SSLHonorCipherOrder     on
					SSLCompression          off

					SSLCertificateFile      /etc/ssl/certs/ssl-cert-snakeoil.pem
					SSLCertificateKeyFile /etc/ssl/private/ssl-cert-snakeoil.key
					#SSLCertificateChainFile /etc/apache2/ssl.crt/server-ca.crt

				</VirtualHost>
			</IfModule>" > ${sslSitesAvailabledomain}
		then
			echo -e "\e[31mWriting SSL apache2 configuration ... [FAILED]\e[0m"
		else
			echo -e "\e[92mWriting SSL apache2 configuration ... [DONE]\e[0m"
		fi

	fi

    ### Add domain in /etc/hosts
    echo -e "\e[92mWriting host file configuration ... [PLEASE WAIT]\e[0m"
    if ! echo "127.0.0.1	$DOMAIN" >> /etc/hosts
    then
        echo -e "\e[31mWriting host file configuration ... [FAILED]\e[0m"
    else
        echo -e "\e[92mWriting host file configuration ... [DONE]\e[0m"
    fi

	#create application config
	echo -e "\e[92mWriting application configuration ... [PLEASE WAIT]\e[0m"
	if ! echo '
		#DEBUG
		DEBUG='${DEBUG}'
		MAIL="'$MAIL'"

		#DATABASE
		HOST="'${HOST}'"
		USER="'${USER}'"
		PASS="'${PASS}'"
		BDD="'${DB}'"

		#TELEGRAM
		APP_ID="'${APP_ID}'"
		APP_NAME="'${APP_NAME}'"

		#CHAINBLOCK
		WALLET_ID="'${WALLET_ID}'"
		WALLET_PASSWORD="'${WALLET_PASSWORD}'"
		WALLET_PASSWORD_SECOND="'${WALLET_PASSWORD_SECOND}'"

		#RULES
		MINIMUM_INVEST="'${MINIMUM_INVEST}'"
		MINIMUM_REINVEST="'${MINIMUM_REINVEST}'"
		MINIMUM_PAYOUT="'${MINIMUM_PAYOUT}'"
		BASE_RATE="'${BASE_RATE}'"
		CONTRACT_DAY="'${CONTRACT_DAY}'"
		COMMISSION_RATE="'${COMMISSION_RATE}'"
		TIMER_TIME_HOUR="'${TIMER_TIME_HOUR}'"
		REQUIRED_CONFIRMATIONS="'${REQUIRED_CONFIRMATIONS}'"
		INTEREST_ON_REINVEST="'${INTEREST_ON_REINVEST}'"
		WITHDRAW_FEE="'${WITHDRAW_FEE}'"
		
		#SUPPORT
		SUPPORT_CHAT_ID="'${SUPPORT_CHAT_ID}'"' > /var/www/bot/.env
	then
		echo -e "\e[31mWriting application configuration ... [FAILED]\e[0m"
	else
		echo -e "\e[92mWriting application configuration ... [DONE]\e[0m"
	fi

	# enable website
	echo -e "\e[92mPut website ON ... [PLEASE WAIT]\e[0m"
	a2dissite 000-default
	a2ensite ${DOMAIN}
	a2ensite ssl_${DOMAIN}
	echo -e "\e[92mPut website ON ... [DONE]\e[0m"
	
	#restart apache
	service apache2 restart
	
	#install ssl certificate
	echo -e "\e[92mInstall SSL Certificate ... [PLEASE WAIT]\e[0m"
	certbot --apache
	echo -e "\e[92mInstall SSL Certificate ... [DONE]\e[0m"
	
	#restart apache
	service apache2 restart
	
	#composer install
	echo -e "\e[92mRunning composer install ... [PLEASE WAIT]\e[0m"
	cd /var/www/bot/
	curl -sS https://getcomposer.org/installer |  php -- --install-dir=/usr/local/bin --filename=composer
	composer install
	echo -e "\e[92mRunning composer install ... [DONE]\e[0m"
	
	#check right
	echo -e "\e[92mAdjust website file rights ... [PLEASE WAIT]\e[0m"
	chmod -R 770 /var/www/bot
	chown -R www-data:www-data /var/www/bot
	chmod -R g+s /var/www/bot
	chmod -R u+s /var/www/bot
	echo -e "\e[92mAdjust website file rights ... [DONE]\e[0m"

	#create database
	echo -e "\e[92mCreating Database ... [PLEASE WAIT]\e[0m"
	create_database
	echo -e "\e[92mCreating Database ... [DONE]\e[0m"

	#Set WebHook
	echo -e "\e[92mSet Telegram Webhook ... [PLEASE WAIT]\e[0m"
	curl https://api.telegram.org/bot${APP_ID}/setWebhook?url=https://${DOMAIN}/
	echo -e "\e[92mSet Telegram Webhook ... [DONE]\e[0m"
	
	#cron 1
	echo -e "\e[92mCreating CRON Job ... [PLEASE WAIT]\e[0m"
	(crontab -l 2>/dev/null; echo "0,5,10,15,20,25,30,35,40,45,50,55 * * * * curl https://$DOMAIN/cron_deposit.php") | crontab -
	
	#cron 2
	for (( i=0; i < 24; i=$i+$TIMER_TIME_HOUR ))
	do
		if [[ -n "$CRON_HOUR" ]]; then
				CRON_HOUR="$CRON_HOUR,$i"
		else
				CRON_HOUR="$i"
		fi
	done
	(crontab -l 2>/dev/null; echo "0 $CRON_HOUR * * * curl https://$DOMAIN/cron_interest.php") | crontab -
	
	
	#cron 3
	(crontab -l 2>/dev/null; echo "@monthly certbot renew") | crontab -
	echo -e "\e[92mCreating CRON Job ... [DONE]\e[0m"

	#blockchain start and respawn
	echo -e "\e[92mCreating Blockchain Wallet configuration file... [PLEASE WAIT]\e[0m"
	if ! echo '
		description "blockchain-wallet-service"

		start on runlevel [2345]
		stop on runlevel [!2345]

		respawn

		script
			exec blockchain-wallet-service start --port 3000
		end script' > /etc/init/blockchain.conf
	then
		echo -e $"There is an error creating start file"
	else
		echo -e $"\nNew start file created\n"
	fi
	
	chmod -R 644 /etc/init/blockchain.conf
	chown -R root:root /etc/init/blockchain.conf
	echo -e "\e[92mCreating Blockchain Wallet configuration file... [DONE]\e[0m"
	
	echo ""
	echo ""
	echo -e "\e[92mInstallation Process [DONE]\e[0m"
	echo ""
	echo ""
	echo "=========================================================================================="
	echo "=====================           REBOOT YOUR SERVER NOW         ==========================="
	echo "=========================================================================================="
	
}

function create_database() {

#Create MySQL root Password
service mysql stop
mkdir /var/run/mysqld; sudo chown mysql /var/run/mysqld
mysqld_safe --skip-grant-tables&
read -r -d '' SQL_CREATE_PASSWORD << EOM
update user set authentication_string=PASSWORD('${PASS}') where user='${USER}';
DELETE FROM mysql.user WHERE User='';
DELETE FROM mysql.user WHERE User='root' AND Host NOT IN ('localhost', '127.0.0.1', '::1');
DROP DATABASE IF EXISTS test;
DELETE FROM mysql.db WHERE Db='test' OR Db='test\\_%';
FLUSH PRIVILEGES;
quit
EOM
mysql --user=root mysql < ${SQL_CREATE_PASSWORD}
killall mysqld
service mysql start

#Create Database
echo "create database $DB" | mysql -u ${USER} -p${PASS}

#Create Tables
read -r -d '' SQL_SCRIPT << EOM
SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT = @@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS = @@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION = @@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;


-- --------------------------------------------------------

--
-- Table structure for table `investment`
--

CREATE TABLE `investment` (
  `id`                  INT(11)        NOT NULL,
  `telegram_id`         INT(11)        NOT NULL,
  `amount`              DECIMAL(15, 8) NOT NULL,
  `rate`                DECIMAL(4, 2)  NOT NULL,
  `contract_end_date`   TIMESTAMP      NULL     DEFAULT NULL,
  `contract_start_date` TIMESTAMP      NOT NULL DEFAULT CURRENT_TIMESTAMP
)
  ENGINE = InnoDB
  DEFAULT CHARSET = utf8;

-- --------------------------------------------------------

--
-- Table structure for table `referrals`
--

CREATE TABLE `referrals` (
  `id`                   INT(11)   NOT NULL,
  `telegram_id_referent` INT(11)   NOT NULL,
  `telegram_id_referred` INT(11)   NOT NULL,
  `bind_date`            TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
)
  ENGINE = InnoDB
  DEFAULT CHARSET = utf8;

-- --------------------------------------------------------

--
-- Table structure for table `transactions`
--

CREATE TABLE `transactions` (
  `id`               INT(11)   NOT NULL,
  `telegram_id`      INT(25)            DEFAULT NULL,
  `amount`           DECIMAL(15, 8)     DEFAULT NULL,
  `withdraw_address` TINYTEXT,
  `message`          TEXT,
  `tx_hash`          TEXT,
  `notice`           TEXT,
  `status`           INT(1)             DEFAULT NULL,
  `type`             VARCHAR(50)        DEFAULT NULL,
  `date`             TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
)
  ENGINE = InnoDB
  DEFAULT CHARSET = utf8;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id`                 INT(25)       NOT NULL,
  `telegram_username`  TINYTEXT,
  `telegram_first`     TINYTEXT,
  `telegram_last`      TINYTEXT,
  `telegram_id`        INT(25)                DEFAULT NULL,
  `balance`            DOUBLE(15, 8) NOT NULL DEFAULT '0.00000000',
  `invested`           DOUBLE(15, 8) NOT NULL DEFAULT '0.00000000',
  `profit`             DOUBLE(15, 8) NOT NULL DEFAULT '0.00000000',
  `commission`         DOUBLE(15, 8) NOT NULL DEFAULT '0.00000000',
  `payout`             DOUBLE(15, 8) NOT NULL DEFAULT '0.00000000',
  `rate`               DECIMAL(4, 2)          DEFAULT NULL,
  `investment_address` TINYTEXT,
  `last_confirmed`     DOUBLE(15, 8)          DEFAULT NULL,
  `wallet_address`     TINYTEXT,
  `referral_link`      TINYTEXT,
  `created_at`         TIMESTAMP     NOT NULL DEFAULT CURRENT_TIMESTAMP
)
  ENGINE = InnoDB
  DEFAULT CHARSET = utf8;


--
-- Indexes for dumped tables
--

--
-- Indexes for table `investment`
--
ALTER TABLE `investment`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `referrals`
--
ALTER TABLE `referrals`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `telegram_id_referred` (`telegram_id_referred`);

--
-- Indexes for table `transactions`
--
ALTER TABLE `transactions`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `telegram_id` (`telegram_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `investment`
--
ALTER TABLE `investment`
  MODIFY `id` INT(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `referrals`
--
ALTER TABLE `referrals`
  MODIFY `id` INT(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `transactions`
--
ALTER TABLE `transactions`
  MODIFY `id` INT(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` INT(25) NOT NULL AUTO_INCREMENT;


/*!40101 SET CHARACTER_SET_CLIENT = @OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS = @OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION = @OLD_COLLATION_CONNECTION */;
EOM

mysql -u ${USER} -p${PASS} ${DB} < ${SQL_SCRIPT}

}

####################################################################################
###################### FEED VARIABLE FROM USER DATA ################################
####################################################################################


#SERVER DOMAIN
while [[ "$DOMAIN" == "" ]]
do
	DOMAIN_DEFAULT=""
	read -p "Please enter server domain (Ex: yourbot.ddns.net) [$DOMAIN]: " DOMAIN
	DOMAIN="${DOMAIN:-$DOMAIN_DEFAULT}"
done

#DEBUG
while [[ "$DEBUG" == "" ]]
do
        DEBUG_DEFAULT="0"
        read -p "Would you like to put this application in debug mode? (Not recommanded for production server!) [$DEBUG_DEFAULT]: " DEBUG
        DEBUG="${DEBUG:-$DEBUG_DEFAULT}"
done

#DATABASE
while [[ "$HOST" == "" ]]
do
        HOST_DEFAULT="127.0.0.1"
        read -p "Please enter database server address (Ex: 127.0.0.1) [$HOST_DEFAULT]: " HOST
        HOST="${HOST:-$HOST_DEFAULT}"
done

while [[ "$PASS" == "" ]]
do
        PASS_DEFAULT=""
        read -p "Please enter database server password [$PASS_DEFAULT]: " PASS
        PASS="${PASS:-$PASS_DEFAULT}"
done

while [[ "$DB" == "" ]]
do
        BDD_DEFAULT="telegram_bot"
        read -p "Please enter server BDD [$BDD_DEFAULT]: " DB
        DB="${DB:-$BDD_DEFAULT}"
done

#TELEGRAM
while [[ "$APP_ID" == "" ]]
do
        APP_ID_DEFAULT=""
        read -p "Please enter your Telegram application ID [$APP_ID_DEFAULT]: " APP_ID
        APP_ID="${APP_ID:-$APP_ID_DEFAULT}"
done

while [[ "$APP_NAME" == "" ]]
do
        APP_NAME_DEFAULT=""
        read -p "Please enter your Telegram application name without @ [$APP_NAME_DEFAULT]: " APP_NAME
        APP_NAME="${APP_NAME:-$APP_NAME_DEFAULT}"
done

#CHAINBLOCK
while [[ "$WALLET_ID" == "" ]]
do
        WALLET_ID_DEFAULT=""
        read -p "Please enter your Blockchain wallet ID (Don't put your wallet address.) [$WALLET_ID_DEFAULT]: " WALLET_ID
        WALLET_ID="${WALLET_ID:-$WALLET_ID_DEFAULT}"
done

while [[ "$WALLET_PASSWORD" == "" ]]
do
        WALLET_PASSWORD_DEFAULT=""
        read -p "Please enter your Blockchain wallet password (Disable 2 form authentication and be sure to have only one password.) [$WALLET_PASSWORD_DEFAULT]: " WALLET_PASSWORD
        WALLET_PASSWORD="${WALLET_PASSWORD:-$WALLET_PASSWORD_DEFAULT}"
done

while [[ "$WALLET_PASSWORD_SECOND" == "" ]]
do
        WALLET_PASSWORD_SECOND_DEFAULT=""
        read -p "Please enter your Blockchain second wallet password (Be sure to disable 2 form authentication.) [$WALLET_PASSWORD_SECOND_DEFAULT]: " WALLET_PASSWORD_SECOND
        WALLET_PASSWORD_SECOND="${WALLET_PASSWORD_SECOND:-$WALLET_PASSWORD_SECOND_DEFAULT}"
done

#RULES
while [[ "$MINIMUM_INVEST" == "" ]]
do
        MINIMUM_INVEST_DEFAULT="0.02"
        read -p "Please enter the minimum investment amount [$MINIMUM_INVEST_DEFAULT]: " MINIMUM_INVEST
        MINIMUM_INVEST="${MINIMUM_INVEST:-$MINIMUM_INVEST_DEFAULT}"
done

while [[ "$MINIMUM_REINVEST" == "" ]]
do
        MINIMUM_REINVEST_DEFAULT="0.005"
        read -p "Please enter the minimum reinvestment amount [$MINIMUM_REINVEST_DEFAULT]: " MINIMUM_REINVEST
        MINIMUM_REINVEST="${MINIMUM_REINVEST:-$MINIMUM_REINVEST_DEFAULT}"
done

while [[ "$MINIMUM_PAYOUT" == "" ]]
do
        MINIMUM_PAYOUT_DEFAULT="0.05"
        read -p "Please enter the minimum withdraw amount [$MINIMUM_PAYOUT_DEFAULT]: " MINIMUM_PAYOUT
        MINIMUM_PAYOUT="${MINIMUM_PAYOUT:-$MINIMUM_PAYOUT_DEFAULT}"
done

while [[ "$BASE_RATE" == "" ]]
do
        BASE_RATE_DEFAULT="6"
        read -p "Please enter the base rate in % of your bot (This is the amount in % that the bot give in interest.) [$BASE_RATE_DEFAULT]: " BASE_RATE
        BASE_RATE="${BASE_RATE:-$BASE_RATE_DEFAULT}"
done

while [[ "$CONTRACT_DAY" == "" ]]
do
        CONTRACT_DAY_DEFAULT="30"
        read -p "Please enter the contract time in day (This is the time in day that the investment are freeze to get interest.) [$CONTRACT_DAY_DEFAULT]: " CONTRACT_DAY
        CONTRACT_DAY="${CONTRACT_DAY:-$CONTRACT_DAY_DEFAULT}"
done

while [[ "$COMMISSION_RATE" == "" ]]
do
        COMMISSION_RATE_DEFAULT="10"
        read -p "Please enter the commission rate in % (This is the amount in % that the refferer get from the first investment of is reffral.) [$COMMISSION_RATE_DEFAULT]: " COMMISSION_RATE
        COMMISSION_RATE="${COMMISSION_RATE:-$COMMISSION_RATE_DEFAULT}"
done

while [[ "$TIMER_TIME_HOUR" == "" ]]
do
        TIMER_TIME_HOUR_DEFAULT="4"
        read -p "Please enter the interest interval in hour (Example if you put 4 than each 4 hours interest will be added to user account.) [$TIMER_TIME_HOUR_DEFAULT]: " TIMER_TIME_HOUR
        TIMER_TIME_HOUR="${TIMER_TIME_HOUR:-$TIMER_TIME_HOUR_DEFAULT}"
done

while [[ "$REQUIRED_CONFIRMATIONS" == "" ]]
do
        REQUIRED_CONFIRMATIONS_DEFAULT="3"
        read -p "Please enter the amount of confirmation required to validate a deposit [$REQUIRED_CONFIRMATIONS_DEFAULT]: " REQUIRED_CONFIRMATIONS
        REQUIRED_CONFIRMATIONS="${REQUIRED_CONFIRMATIONS:-$REQUIRED_CONFIRMATIONS_DEFAULT}"
done

while [[ "$INTEREST_ON_REINVEST" == "" ]]
do
        INTEREST_ON_REINVEST_DEFAULT="0"
        read -p "Please enter the commission rate in % gived on reinvest (This is the amount in % that the refferer get from the reinvestment of is reffral.) [$INTEREST_ON_REINVEST_DEFAULT]: " INTEREST_ON_REINVEST
        INTEREST_ON_REINVEST="${INTEREST_ON_REINVEST:-$INTEREST_ON_REINVEST_DEFAULT}"
done

while [[ "$WITHDRAW_FEE" == "" ]]
do
        WITHDRAW_FEE_DEFAULT="50000"
        read -p "Please enter the withdraw fee in satoshi (Small fee can make blockchain choose the fee himself and can create error.) [$WITHDRAW_FEE_DEFAULT]: " WITHDRAW_FEE
        WITHDRAW_FEE="${WITHDRAW_FEE:-$WITHDRAW_FEE_DEFAULT}"
done

#SUPPORT
while [[ "$SUPPORT_CHAT_ID" == "" ]]
do
        SUPPORT_CHAT_ID_DEFAULT=""
        read -p "Please enter Telegram chat ID where your users can get support (Ex: @yourTelegramID) [$SUPPORT_CHAT_ID_DEFAULT]: " SUPPORT_CHAT_ID
        SUPPORT_CHAT_ID="${SUPPORT_CHAT_ID:-$SUPPORT_CHAT_ID_DEFAULT}"
done

clear

#validate parameters
echo "++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++"
echo ""
echo -e "This is all the parameters you choose for this installation:"
echo ""
echo "DOMAIN: $DOMAIN"
echo "DEBUG: $DEBUG"
echo "HOST: $HOST"
echo "USER: $USER"
echo "PASS: $PASS"
echo "BDD: $DB"
echo "APP_ID: $APP_ID"
echo "APP_NAME: $APP_NAME"
echo "WALLET_ID: $WALLET_ID"
echo "WALLET_PASSWORD: $WALLET_PASSWORD"
echo "MINIMUM_INVEST: $MINIMUM_INVEST Bitcoin"
echo "MINIMUM_REINVEST: $MINIMUM_REINVEST Bitcoin"
echo "MINIMUM_PAYOUT: $MINIMUM_PAYOUT Bitcoin"
echo "BASE_RATE: $BASE_RATE%"
echo "CONTRACT_DAY: $CONTRACT_DAY days"
echo "COMMISSION_RATE: $COMMISSION_RATE%"
echo "TIMER_TIME_HOUR: $TIMER_TIME_HOUR hours"
echo "REQUIRED_CONFIRMATIONS: $REQUIRED_CONFIRMATIONS confirmations"
echo "INTEREST_ON_REINVEST: $INTEREST_ON_REINVEST%"
echo "WITHDRAW_FEE: $WITHDRAW_FEE satoshi"
echo "SUPPORT_CHAT_ID: $SUPPORT_CHAT_ID"
echo ""
echo "===================================================================================="
echo "		BE SURE TO OPEN PORT : 80, 443, 10000 BEFORE STARTING THIS INSTALLATION"
echo "===================================================================================="
echo ""
echo "If all these values are good press [Y] else press [N] and restart this installation."
echo ""
echo -e "++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++\n"

while true; do
    read -p "Do you wish to install this program? : " -n 1 yn
    case ${yn} in
        [Yy]* ) make_install; break;;
        [Nn]* ) echo -e "\nInstallation was cancel restart the installation to continue."
				exit;;
        * ) echo -e "\n\nWrong choice! Press Y to install the server or press N to cancel this installation.";;
    esac
done


