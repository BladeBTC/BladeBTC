#!/bin/bash

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
MINIMUM_INVEST="0.02"
MINIMUM_REINVEST="0.005"
MINIMUM_PAYOUT="0.05"
BASE_RATE="6"
CONTRACT_DAY="30"
COMMISSION_RATE="10"
TIMER_TIME_HOUR="4"
REQUIRED_CONFIRMATIONS="3"
INTEREST_ON_REINVEST="0"
WITHDRAW_FEE="50000"

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
	clear
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
	apt-get install unzip apache2 php libapache2-mod-php htop webmin nodejs build-essential software-properties-common python-certbot-apache -y
	echo -e "\e[92mInstalling all needed package ... [DONE]\e[0m"

    #install mariadb
    apt-get install software-properties-common
    apt-key adv --recv-keys --keyserver hkp://keyserver.ubuntu.com:80 0xF1656F24C74CD1D8
    add-apt-repository 'deb [arch=amd64,arm64,ppc64el] http://mirror.its.dal.ca/mariadb/repo/10.3/ubuntu bionic main'
    apt update -y
    echo "mariadb-server-10.3 mysql-server/root_password password $PASS" | debconf-set-selections
    echo "mariadb-server-10.3 mysql-server/root_password_again password $PASS" | debconf-set-selections

    echo -e "\e[92mInstalling MariaDB ... [PLEASE WAIT]\e[0m"
	apt install mariadb-server-10.3 mariadb-client-10.3 -y
	service mysql restart
	echo -e "\e[92mInstalling MariaDB ... [DONE]\e[0m"

    #install phpmyadmin
    echo "phpmyadmin phpmyadmin/dbconfig-install boolean false" | debconf-set-selections
    echo "phpmyadmin phpmyadmin/app-password-confirm password $PASS" | debconf-set-selections
    echo "phpmyadmin phpmyadmin/mysql/admin-pass password $PASS" | debconf-set-selections
    echo "phpmyadmin phpmyadmin/mysql/app-pass password $PASS" | debconf-set-selections
    echo "phpmyadmin phpmyadmin/reconfigure-webserver multiselect apache2" | debconf-set-selections

    echo -e "\e[92mInstalling all needed package ... [PLEASE WAIT]\e[0m"
	apt install phpmyadmin -y
	echo -e "\e[92mInstalling all needed package ... [DONE]\e[0m"

    #install nodejs
    echo -e "\e[92mInstalling node.js ... [PLEASE WAIT]\e[0m"
	curl -sL https://deb.nodesource.com/setup_6.x | sudo -E bash -
	echo -e "\e[92mInstalling node.js ... [DONE]\e[0m"
    apt-get install npm -y

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
	mysql -u ${USER} -p${PASS} < ./main.sql
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
	echo "=================           SERVER IS GOING DOWN FOR REBOOT         ======================"
	echo "=========================================================================================="

	reboot

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

#DATABASE
while [[ "$PASS" == "" ]]
do
        PASS_DEFAULT=""
        read -p "Please enter database server password [$PASS_DEFAULT]: " PASS
        PASS="${PASS:-$PASS_DEFAULT}"
done

#TELEGRAM
while [[ "$APP_ID" == "" ]]
do
        APP_ID_DEFAULT=""
        read -p "Please enter your Telegram application ID ( API KEY ): " APP_ID
        APP_ID="${APP_ID:-$APP_ID_DEFAULT}"
done

while [[ "$APP_NAME" == "" ]]
do
        APP_NAME_DEFAULT=""
        read -p "Please enter your Telegram application name without @ [ EX: BladeBTCBot ]: " APP_NAME
        APP_NAME="${APP_NAME:-$APP_NAME_DEFAULT}"
done

#BLOCKCHAIN
while [[ "$WALLET_ID" == "" ]]
do
        WALLET_ID_DEFAULT=""
        read -p "Please enter your Blockchain wallet ID [ EX: cd6c4470-1195-4c44-83d7-7b223a2f8ggd ]: " WALLET_ID
        WALLET_ID="${WALLET_ID:-$WALLET_ID_DEFAULT}"
done

while [[ "$WALLET_PASSWORD" == "" ]]
do
        WALLET_PASSWORD_DEFAULT=""
        read -p "Please enter your Blockchain wallet password (Disable 2 form authentication and be sure to have only one password.): " WALLET_PASSWORD
        WALLET_PASSWORD="${WALLET_PASSWORD:-$WALLET_PASSWORD_DEFAULT}"
done

while [[ "$WALLET_PASSWORD_SECOND" == "" ]]
do
        WALLET_PASSWORD_SECOND_DEFAULT=""
        read -p "Please enter your Blockchain second wallet password (Be sure to disable 2 form authentication.): " WALLET_PASSWORD_SECOND
        WALLET_PASSWORD_SECOND="${WALLET_PASSWORD_SECOND:-$WALLET_PASSWORD_SECOND_DEFAULT}"
done

#SUPPORT
while [[ "$SUPPORT_CHAT_ID" == "" ]]
do
        SUPPORT_CHAT_ID_DEFAULT=""
        read -p "Please enter Telegram chat ID where your users can get support (Ex: @yourTelegramID): " SUPPORT_CHAT_ID
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
echo "PASS: ******"
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


