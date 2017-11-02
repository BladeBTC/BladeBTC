#!/bin/bash

####################################################################################
#
# Open port 80, 443, 10000
# Create a folder named install
# Put this script (installer.sh) in the install folder
# Create a folder named bot
# Put all application files in the bot folder
# chmod 777 installer.sh
# sudo ./installer.sh
#
####################################################################################

####################################################################################
################################## VARIABLES #######################################
####################################################################################

#SERVER DOMAIN
DOMAIN=""

#DEBUG
DEBUG=0
MAIL=""

#DATABASE
HOST=""
USER=""
PASS=""
BDD=""

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
echo "============================================================================="

#check for root access
if [ "$(whoami)" != 'root' ]; then
	echo ""
	echo $"You have no permission to run $0 as non-root user. Use sudo"
	echo ""
	exit 1;
fi



####################################################################################
################################# INSTALLING #######################################
####################################################################################

make_install(){

	#webmin source
	echo -e "\n\nAdd source for webmin..."
	wget -O- http://www.webmin.com/jcameron-key.asc | sudo apt-key add -
	echo "deb http://download.webmin.com/download/repository sarge contrib" >> /etc/apt/sources.list

	#update server
	echo "Updating server..."
	apt-get update
	apt-get upgrade -y

	#install lamp-server
	check_install=`tasksel --list-task | grep 'i lamp-server'`
	if [ -z "$check_install" ]
	then
			echo "Installing LAMP Server..."
			tasksel install lamp-server
	else
		echo "LAMP Server already installed!"
	fi

	#write server name
	echo "Writing apache2 configuration..."
	echo "ServerName ServerName" >> /etc/apache2/apache2.conf

	#install some other package
	echo "Installing some other package required by this server..."
	apt-get install phpmyadmin htop mytop unzip zip unrar webmin -y

	#install nodejs
	curl -sL https://deb.nodesource.com/setup_6.x | sudo -E bash -
	apt-get install -y nodejs
	apt-get install -y build-essential
	
	#install wallet service
	sudo npm install -g blockchain-wallet-service
	
	
	#enable php extention
	echo "Loading PHP modules..."
	php5enmod mcrypt

	#enable apache module
	echo "Loading apache2 modules..."
	a2enmod rewrite
	a2enmod ssl
	
	
	#APACHE Config
	sitesAvailable='/etc/apache2/sites-available/'
	sitesAvailabledomain=$sitesAvailable$DOMAIN.conf
	sslSitesAvailabledomain=$sitesAvailable"ssl_"$DOMAIN.conf
	
	#Check if config exist
	if [ -e $sitesAvailabledomain ]; then
		
		echo -e $"This config already exists."
	
	else
		
		#creating website directory
		mkdir /var/www/bot
		
		#install website
		cp -R ./bot/* /var/www/bot/
		cp ./bot/.htaccess /var/www/bot/.htaccess
		
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
				
			</VirtualHost>" > $sitesAvailabledomain
		then
			echo -e $"There is an ERROR creating $domain file"
		else
			echo -e $"\nNew Virtual Host Created\n"
		fi
		
		### Add domain in /etc/hosts
		if ! echo "127.0.0.1	$DOMAIN" >> /etc/hosts
		then
			echo $"ERROR: Not able to write in /etc/hosts"
		else
			echo -e $"Host added to /etc/hosts file \n"
		fi
		
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
			</IfModule>" > $sslSitesAvailabledomain
		then
			echo -e $"There is an ERROR creating $domain file"
		else
			echo -e $"\nNew Virtual Host Created\n"
		fi
		
		### Add domain in /etc/hosts
		if ! echo "127.0.0.1	$DOMAIN" >> /etc/hosts
		then
			echo $"ERROR: Not able to write in /etc/hosts"
		else
			echo -e $"Host added to /etc/hosts file \n"
		fi
		
	fi
	
	#create application config
	if ! echo '
		#DEBUG
		DEBUG='$DEBUG'
		MAIL="'$MAIL'"

		#DATABASE
		HOST="'$HOST'"
		USER="'$USER'"
		PASS="'$PASS'"
		BDD="'$BDD'"

		#TELEGRAM
		APP_ID="'$APP_ID'"
		APP_NAME="'$APP_NAME'"

		#CHAINBLOCK
		WALLET_ID="'$WALLET_ID'"
		WALLET_PASSWORD="'$WALLET_PASSWORD'"
		WALLET_PASSWORD_SECOND="'$WALLET_PASSWORD_SECOND'"

		#RULES
		MINIMUM_INVEST="'$MINIMUM_INVEST'"
		MINIMUM_REINVEST="'$MINIMUM_REINVEST'"
		MINIMUM_PAYOUT="'$MINIMUM_PAYOUT'"
		BASE_RATE="'$BASE_RATE'"
		CONTRACT_DAY="'$CONTRACT_DAY'"
		COMMISSION_RATE="'$COMMISSION_RATE'"
		TIMER_TIME_HOUR="'$TIMER_TIME_HOUR'"
		REQUIRED_CONFIRMATIONS="'$REQUIRED_CONFIRMATIONS'"
		INTEREST_ON_REINVEST="'$INTEREST_ON_REINVEST'"
		WITHDRAW_FEE="'$WITHDRAW_FEE'"
		
		#SUPPORT
		SUPPORT_CHAT_ID="'$SUPPORT_CHAT_ID'"' > /var/www/bot/.env
	then
		echo -e $"There is an ERROR creating .env file"
	else
		echo -e $"\n.env Created\n"
	fi

	# enable website
	a2dissite 000-default
	a2ensite $DOMAIN
	a2ensite ssl_$DOMAIN
	
	#restart apache
	service apache2 restart
	
	#install certbot
	apt-get install software-properties-common
	add-apt-repository ppa:certbot/certbot
	apt-get update -y
	apt-get install -y python-certbot-apache 
	certbot --apache
	
	#restart apache
	service apache2 restart
	
	#composer install
	cd /var/www/bot/
	curl -sS https://getcomposer.org/installer |  php -- --install-dir=/usr/local/bin --filename=composer
	composer install
	
	#check right
	chmod -R 770 /var/www/bot
	chown -R www-data:www-data /var/www/bot
	chmod -R g+s /var/www/bot
	chmod -R u+s /var/www/bot

	#create database
	echo "create database $BDD" | mysql -u $USER -p$PASS
	mysql -u $USER -p$PASS $BDD < /var/www/bot/localhost.sql
	
	#Set WebHook
	curl https://api.telegram.org/bot$APP_ID/setWebhook?url=https://$DOMAIN/
	
	#cron 1
	(crontab -l 2>/dev/null; echo "0,5,10,15,20,25,30,35,40,45,50,55 * * * * curl https://$DOMAIN/cron_deposit.php") | crontab -
	
	#cron 2
	for (( i=0; i < 24; i=$i+$TIMER_TIME_HOUR ))
	do
		if [ -n "$CRON_HOUR" ]; then
				CRON_HOUR="$CRON_HOUR,$i"
		else
				CRON_HOUR="$i"
		fi
	done
	(crontab -l 2>/dev/null; echo "0 $CRON_HOUR * * * curl https://$DOMAIN/cron_interest.php") | crontab -
	
	
	#cron 3
	(crontab -l 2>/dev/null; echo "@monthly certbot renew") | crontab -
	
	
	
	#blockchain start and respawn
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
	
	
	echo "=========================================================================================="
	echo "=====================           REBOOT YOUR SERVER NOW         ==========================="
	echo "=========================================================================================="
	
}

####################################################################################
###################### FEED VARIABLE FROM USER DATA ################################
####################################################################################


#SERVER DOMAIN
while [ "$DOMAIN" == "" ]
do
	DOMAIN_DEFAULT=""
	read -p "Please enter server domain (Ex: yourbot.ddns.net) [$DOMAIN]: " DOMAIN
	DOMAIN="${DOMAIN:-$DOMAIN_DEFAULT}"
done

#DEBUG
while [ "$DEBUG" == "" ]
do
        DEBUG_DEFAULT="0"
        read -p "Would you like to put this application in debug mode? (Not recommanded for production server!) [$DEBUG_DEFAULT]: " DEBUG
        DEBUG="${DEBUG:-$DEBUG_DEFAULT}"
done


while [ "$MAIL" == "" ]
do
        MAIL_DEFAULT=""
        read -p "Please enter email address to send debug information [$MAIL_DEFAULT]: " MAIL
        MAIL="${MAIL:-$MAIL_DEFAULT}"
done

#DATABASE
while [ "$HOST" == "" ]
do
        HOST_DEFAULT="127.0.0.1"
        read -p "Please enter database server address (Ex: 127.0.0.1) [$HOST_DEFAULT]: " HOST
        HOST="${HOST:-$HOST_DEFAULT}"
done

while [ "$USER" == "" ]
do
        USER_DEFAULT="root"
        read -p "Please enter database server username [$USER_DEFAULT]: " USER
        USER="${USER:-$USER_DEFAULT}"
done

while [ "$PASS" == "" ]
do
        PASS_DEFAULT=""
        read -p "Please enter database server password [$PASS_DEFAULT]: " PASS
        PASS="${PASS:-$PASS_DEFAULT}"
done

while [ "$BDD" == "" ]
do
        BDD_DEFAULT="telegram_bot"
        read -p "Please enter server BDD [$BDD_DEFAULT]: " BDD
        BDD="${BDD:-$BDD_DEFAULT}"
done

#TELEGRAM
while [ "$APP_ID" == "" ]
do
        APP_ID_DEFAULT=""
        read -p "Please enter your Telegram application ID [$APP_ID_DEFAULT]: " APP_ID
        APP_ID="${APP_ID:-$APP_ID_DEFAULT}"
done

while [ "$APP_NAME" == "" ]
do
        APP_NAME_DEFAULT=""
        read -p "Please enter your Telegram application name without @ [$APP_NAME_DEFAULT]: " APP_NAME
        APP_NAME="${APP_NAME:-$APP_NAME_DEFAULT}"
done

#CHAINBLOCK
while [ "$WALLET_ID" == "" ]
do
        WALLET_ID_DEFAULT=""
        read -p "Please enter your Blockchain wallet ID (Don't put your wallet address.) [$WALLET_ID_DEFAULT]: " WALLET_ID
        WALLET_ID="${WALLET_ID:-$WALLET_ID_DEFAULT}"
done

while [ "$WALLET_PASSWORD" == "" ]
do
        WALLET_PASSWORD_DEFAULT=""
        read -p "Please enter your Blockchain wallet password (Disable 2 form authentication and be sure to have only one password.) [$WALLET_PASSWORD_DEFAULT]: " WALLET_PASSWORD
        WALLET_PASSWORD="${WALLET_PASSWORD:-$WALLET_PASSWORD_DEFAULT}"
done

while [ "$WALLET_PASSWORD_SECOND" == "" ]
do
        WALLET_PASSWORD_SECOND_DEFAULT=""
        read -p "Please enter your Blockchain second wallet password (Be sure to disable 2 form authentication.) [$WALLET_PASSWORD_SECOND_DEFAULT]: " WALLET_PASSWORD_SECOND
        WALLET_PASSWORD_SECOND="${WALLET_PASSWORD_SECOND:-$WALLET_PASSWORD_SECOND_DEFAULT}"
done

#RULES
while [ "$MINIMUM_INVEST" == "" ]
do
        MINIMUM_INVEST_DEFAULT="0.02"
        read -p "Please enter the minimum investment amount [$MINIMUM_INVEST_DEFAULT]: " MINIMUM_INVEST
        MINIMUM_INVEST="${MINIMUM_INVEST:-$MINIMUM_INVEST_DEFAULT}"
done

while [ "$MINIMUM_REINVEST" == "" ]
do
        MINIMUM_REINVEST_DEFAULT="0.005"
        read -p "Please enter the minimum reinvestment amount [$MINIMUM_REINVEST_DEFAULT]: " MINIMUM_REINVEST
        MINIMUM_REINVEST="${MINIMUM_REINVEST:-$MINIMUM_REINVEST_DEFAULT}"
done

while [ "$MINIMUM_PAYOUT" == "" ]
do
        MINIMUM_PAYOUT_DEFAULT="0.05"
        read -p "Please enter the minimum withdraw amount [$MINIMUM_PAYOUT_DEFAULT]: " MINIMUM_PAYOUT
        MINIMUM_PAYOUT="${MINIMUM_PAYOUT:-$MINIMUM_PAYOUT_DEFAULT}"
done

while [ "$BASE_RATE" == "" ]
do
        BASE_RATE_DEFAULT="6"
        read -p "Please enter the base rate in % of your bot (This is the amount in % that the bot give in interest.) [$BASE_RATE_DEFAULT]: " BASE_RATE
        BASE_RATE="${BASE_RATE:-$BASE_RATE_DEFAULT}"
done

while [ "$CONTRACT_DAY" == "" ]
do
        CONTRACT_DAY_DEFAULT="30"
        read -p "Please enter the contract time in day (This is the time in day that the investment are freeze to get interest.) [$CONTRACT_DAY_DEFAULT]: " CONTRACT_DAY
        CONTRACT_DAY="${CONTRACT_DAY:-$CONTRACT_DAY_DEFAULT}"
done

while [ "$COMMISSION_RATE" == "" ]
do
        COMMISSION_RATE_DEFAULT="10"
        read -p "Please enter the commission rate in % (This is the amount in % that the refferer get from the first investment of is reffral.) [$COMMISSION_RATE_DEFAULT]: " COMMISSION_RATE
        COMMISSION_RATE="${COMMISSION_RATE:-$COMMISSION_RATE_DEFAULT}"
done

while [ "$TIMER_TIME_HOUR" == "" ]
do
        TIMER_TIME_HOUR_DEFAULT="4"
        read -p "Please enter the interest interval in hour (Example if you put 4 than each 4 hours interest will be added to user account.) [$TIMER_TIME_HOUR_DEFAULT]: " TIMER_TIME_HOUR
        TIMER_TIME_HOUR="${TIMER_TIME_HOUR:-$TIMER_TIME_HOUR_DEFAULT}"
done

while [ "$REQUIRED_CONFIRMATIONS" == "" ]
do
        REQUIRED_CONFIRMATIONS_DEFAULT="3"
        read -p "Please enter the amount of confirmation required to validate a deposit [$REQUIRED_CONFIRMATIONS_DEFAULT]: " REQUIRED_CONFIRMATIONS
        REQUIRED_CONFIRMATIONS="${REQUIRED_CONFIRMATIONS:-$REQUIRED_CONFIRMATIONS_DEFAULT}"
done

while [ "$INTEREST_ON_REINVEST" == "" ]
do
        INTEREST_ON_REINVEST_DEFAULT="0"
        read -p "Please enter the commission rate in % gived on reinvest (This is the amount in % that the refferer get from the reinvestment of is reffral.) [$INTEREST_ON_REINVEST_DEFAULT]: " INTEREST_ON_REINVEST
        INTEREST_ON_REINVEST="${INTEREST_ON_REINVEST:-$INTEREST_ON_REINVEST_DEFAULT}"
done

while [ "$WITHDRAW_FEE" == "" ]
do
        WITHDRAW_FEE_DEFAULT="50000"
        read -p "Please enter the withdraw fee in satoshi (Small fee can make blockchain choose the fee himself and can create error.) [$WITHDRAW_FEE_DEFAULT]: " WITHDRAW_FEE
        WITHDRAW_FEE="${WITHDRAW_FEE:-$WITHDRAW_FEE_DEFAULT}"
done

#SUPPORT
while [ "$SUPPORT_CHAT_ID" == "" ]
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
echo "MAIL: $MAIL"
echo "HOST: $HOST"
echo "USER: $USER"
echo "PASS: $PASS"
echo "BDD: $BDD"
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
    case $yn in
        [Yy]* ) make_install; break;;
        [Nn]* ) echo -e "\nInstallation was cancel restart the installation to continue."
				exit;;
        * ) echo -e "\n\nWrong choice! Press Y to install the server or press N to cancel this installation.";;
    esac
done


