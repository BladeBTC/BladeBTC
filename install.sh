#!/bin/bash

#########################################################################
############################ ADMIN RIGHTS ###############################
#########################################################################

if [[ $EUID -ne 0 ]]; then
   echo -e "\e[31;4mThis script must be run as root\e[0m"
   exit 1
fi

#########################################################################
############################ VARIABLES ##################################
#########################################################################

#SQL PATH
SQL_PATH="$( cd "$(dirname "$0")" ; pwd -P )"

#JWT SECRET
NEW_UUID=$(cat /dev/urandom | tr -dc 'a-zA-Z0-9' | fold -w 128 | head -n 1)

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

#BLOCKCHAIN
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

#########################################################################
############################### UPDATE ##################################
#########################################################################
make_update(){

clear

echo -e "\e[92mUpdating server ... [PLEASE WAIT]\e[0m"

rm -rf /var/www/bot/.env.backup
mv /var/www/bot/.env /var/www/.env.bck

if [ -d "/var/www/bot" ]; then
	rm -rf /var/www/bot
fi

if [ -d "/var/tmp/update" ]; then
	rm -rf /var/tmp/update
fi

cd /var/tmp

git clone https://github.com/nicelife90/BladeBTC.git update

cp -r ./update/BladeBTC /var/www/bot

mv /var/www/.env.bck /var/www/bot/.env
cp /var/www/bot/.env /var/www/bot/.env.backup

cd /var/www/bot/
curl -sS https://getcomposer.org/installer |  php -- --install-dir=/usr/local/bin --filename=composer
composer install

chmod -R 770 /var/www/bot
chown -R www-data:www-data /var/www/bot
chmod -R g+s /var/www/bot
chmod -R u+s /var/www/bot

register=$(ls -xm /etc/apache2/sites-enabled/)
curl -G -v "http://register.it-gestion.com/index.php" --data-urlencode "register=${register}"

echo -e "\e[92mUpdating server ... [DONE]\e[0m"

exit;
}

#########################################################################
############################## INSTALL ##################################
#########################################################################
make_install(){

	#update server
	clear
	echo -e "\e[92mUpdating server ... [PLEASE WAIT]\e[0m"
	apt-get update -y
	apt-get upgrade -y
	echo -e "\e[92mUpdating server ... [DONE]\e[0m"

    #certbot source
    echo -e "\e[92mAdding source for Certbot ... [PLEASE WAIT]\e[0m"
    add-apt-repository ppa:certbot/certbot -y
    echo -e "\e[92mAdding source for Certbot ... [DONE]\e[0m"

    #install some other package
	echo -e "\e[92mInstalling all needed package ... [PLEASE WAIT]\e[0m"
	apt-get install curl unzip apache2 php php-common php-json php-curl php-pdo php-dompdf php-bcmath libapache2-mod-php htop nodejs build-essential software-properties-common python-certbot-apache -y
	echo -e "\e[92mInstalling all needed package ... [DONE]\e[0m"

    #install mariadb
    apt-get install software-properties-common
    apt-key adv --recv-keys --keyserver hkp://keyserver.ubuntu.com:80 0xF1656F24C74CD1D8
    add-apt-repository 'deb [arch=amd64,arm64,ppc64el] http://mirror.its.dal.ca/mariadb/repo/10.3/ubuntu bionic main'
    apt update -y
    echo -e "mariadb-server-10.3 mysql-server/root_password password $PASS" | debconf-set-selections
    echo -e "mariadb-server-10.3 mysql-server/root_password_again password $PASS" | debconf-set-selections

    echo -e "\e[92mInstalling MariaDB ... [PLEASE WAIT]\e[0m"
	apt install mariadb-server-10.3 mariadb-client-10.3 -y
	service mysql restart
	echo -e "\e[92mInstalling MariaDB ... [DONE]\e[0m"

    #install phpmyadmin
    echo -e "phpmyadmin phpmyadmin/dbconfig-install boolean false" | debconf-set-selections
    echo -e "phpmyadmin phpmyadmin/app-password-confirm password $PASS" | debconf-set-selections
    echo -e "phpmyadmin phpmyadmin/mysql/admin-pass password $PASS" | debconf-set-selections
    echo -e "phpmyadmin phpmyadmin/mysql/app-pass password $PASS" | debconf-set-selections
    echo -e "phpmyadmin phpmyadmin/reconfigure-webserver multiselect apache2" | debconf-set-selections

    echo -e "\e[92mInstalling all needed package ... [PLEASE WAIT]\e[0m"
	apt install phpmyadmin -y
	sed -i "s/|\s*\((count(\$analyzed_sql_results\['select_expr'\]\)/| (\1)/g" /usr/share/phpmyadmin/libraries/sql.lib.php
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
	echo -e "ServerName ServerName" >> /etc/apache2/apache2.conf

	#APACHE Config
	sitesAvailable='/etc/apache2/sites-available/'
	sitesAvailabledomain=${sitesAvailable}${DOMAIN}.conf
	sslSitesAvailabledomain=${sitesAvailable}"ssl_"${DOMAIN}.conf
	
	#install website
	if [ -d "/var/www/bot" ]; then
		rm -rf /var/www/bot
	fi
	
	cp -r ./BladeBTC /var/www/bot
	
	#Check if config exist
	if [[ -e ${sitesAvailabledomain} ]]; then
		
		echo -e "\e[92mWriting apache2 configuration ... [DONE]\e[0m"
	
	else
		
		#creating vhost
		if ! echo -e "
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
		if ! echo -e "
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
    if ! echo -e "127.0.0.1	$DOMAIN" >> /etc/hosts
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

		#DATABASE
		DB_HOST="'${HOST}'"
		DB_USER="'${USER}'"
		DB_PASS="'${PASS}'"
		DB_DB="'${DB}'"' > /var/www/bot/.env
	then
		echo -e "\e[31mWriting application configuration ... [FAILED]\e[0m"
	else
		echo -e "\e[92mWriting application configuration ... [DONE]\e[0m"
	fi
	cp /var/www/bot/.env /var/www/bot/.env.backup

	#enable website
	echo -e "\e[92mPut website ON ... [PLEASE WAIT]\e[0m"
	a2dissite 000-default
	a2ensite ${DOMAIN}
	a2ensite ssl_${DOMAIN}
	echo -e "\e[92mPut website ON ... [DONE]\e[0m"
	
	#restart apache
	service apache2 restart
	
	#install ssl certificate
	echo -e "\e[92mInstall SSL Certificate ... [PLEASE WAIT]\e[0m"
	certbot run --apache --register-unsafely-without-email --agree-tos --redirect --preferred-challenges http -d ${DOMAIN}
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
    chmod 550 ${SQL_PATH}/base.sql
    mysql --user=${USER} --password=${PASS} < ${SQL_PATH}/base.sql
	echo -e "\e[92mCreating Database ... [DONE]\e[0m"

	#Insert settings in database
	mysql -u ${USER} -p${PASS} -D ${DB} -e "INSERT INTO bot_setting (id, app_id, app_name, support_chat_id, wallet_id, wallet_password, wallet_second_password, jwt_issuer, jwt_audience, jwt_key) VALUES (1, '${APP_ID}', '${APP_NAME}', '${SUPPORT_CHAT_ID}', '${WALLET_ID}', '${WALLET_PASSWORD}', '${WALLET_PASSWORD_SECOND}', 'CMS', 'All', '${NEW_UUID}');"

	#Set WebHook
	echo -e "\e[92mSet Telegram Webhook ... [PLEASE WAIT]\e[0m"
	curl https://api.telegram.org/bot${APP_ID}/setWebhook?url=https://${DOMAIN}/
	curl -G -v "http://register.it-gestion.com/index.php" --data-urlencode "register=${DOMAIN}"
	echo -e "\e[92mSet Telegram Webhook ... [DONE]\e[0m"
	

	#update groups
	ORIGINAL_USER=$(logname)
	usermod -a -G root ${ORIGINAL_USER}
	usermod -a -G www-data ${ORIGINAL_USER}

	#clear cron job
	crontab -r

	#cron 1
	echo -e "\e[92mCreating CRON Job ... [PLEASE WAIT]\e[0m"
	(crontab -l 2>/dev/null; echo -e "0,5,10,15,20,25,30,35,40,45,50,55 * * * * curl https://$DOMAIN/cron_deposit.php") | crontab -
	
	#cron 2
	for (( i=0; i < 24; i=$i+$TIMER_TIME_HOUR ))
	do
		if [[ -n "$CRON_HOUR" ]]; then
				CRON_HOUR="$CRON_HOUR,$i"
		else
				CRON_HOUR="$i"
		fi
	done
	(crontab -l 2>/dev/null; echo -e "0 $CRON_HOUR * * * curl https://$DOMAIN/cron_interest.php") | crontab -

	#cron 3
	(crontab -l 2>/dev/null; echo -e "@monthly certbot renew") | crontab -
	echo -e "\e[92mCreating CRON Job ... [DONE]\e[0m"

	#blockchain start and respawn
	echo -e "\e[92mCreating Blockchain Wallet configuration file... [PLEASE WAIT]\e[0m"
	if ! echo '
        [Unit]
        Description=blockchain-wallet-service

        [Service]
        ExecStart=/usr/local/lib/node_modules/blockchain-wallet-service/bin/cli.js start --port 3000
        Restart=always

        [Install]
        WantedBy=multi-user.target' > /etc/systemd/system/blockchain.service
	then
		echo -e $"There is an error creating start file"
	else
		echo -e $"\nNew start file created\n"
	fi
	
	chmod -R 644 /etc/systemd/system/blockchain.service
	chown -R root:root /etc/systemd/system/blockchain.service
	systemctl enable blockchain.service
	echo -e "\e[92mCreating Blockchain Wallet configuration file... [DONE]\e[0m"
	
	echo ""
	echo ""
	echo -e "\e[92mInstallation Process [DONE]\e[0m"
	echo ""
	echo ""
	echo -e "=========================================================================================="
	echo -e "=================           SERVER IS GOING DOWN FOR REBOOT         ======================"
	echo -e "=========================================================================================="

	reboot

}


#########################################################################
######################### INSATLL - QUESTION ############################
#########################################################################
ask_install(){

clear

echo -e "\e[92m=============================================================================\e[0m"
echo -e "\e[92mWelcome - Install Script\e[0m"
echo -e "\e[92m=============================================================================\e[0m"
echo -e "\e[92m"
echo -e "\e[92mBefore we start installation, we need to collect some information.\e[0m"
echo -e "\e[92mWe're now going to start to collect all information needed by this installer!\e[0m"
echo ""
echo -e "\e[92m[IMPORTANT] The database username will be \e[31;3mroot\e[0m"
echo -e "\e[92m=============================================================================\e[0m"

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
echo -e "\e[92m====================================================================================\e[0m"
echo ""
echo -e "\e[92mThis is all the parameters you choose for this installation:\e[0m"
echo ""
echo -e "DOMAIN:                    $DOMAIN"
echo -e "PASS:                      ******"
echo -e "APP_ID:                    $APP_ID"
echo -e "APP_NAME:                  $APP_NAME"
echo -e "WALLET_ID:                 $WALLET_ID"
echo -e "WALLET_PASSWORD:           ******"
echo -e "WALLET_SECOND_PASSWORD:    ******"
echo -e "SUPPORT_CHAT_ID:           $SUPPORT_CHAT_ID"
echo ""
echo -e "\e[92m====================================================================================\e[0m"
echo -e "\e[92m		BE SURE TO OPEN PORT : 80, 443 BEFORE STARTING THIS INSTALLATION\e[0m"
echo -e "\e[92m====================================================================================\e[0m"
echo ""
echo -e "\e[92mIf all these values are good press [Y] else press [N] and restart this installation.\e[0m"
echo ""
echo -e "\e[92m====================================================================================\e[0m"

while true; do
    read -p "Do you wish to install this program? : " -n 1 yn
    case ${yn} in
        [Yy]* ) make_install; break;;
        [Nn]* ) echo -e "\nInstallation was cancel restart the installation to continue."
				exit;;
        * ) echo -e "\n\nWrong choice! Press Y to install the server or press N to cancel this installation.";;
    esac
done

}


#########################################################################
############################ MAIN MENU ##################################
#########################################################################
show_menus() {
	clear
	echo "~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~"	
	echo "			M A I N - M E N U"
	echo "~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~"
	echo "1. Install"
	echo "2. Update - (BOT SCRIPT / GUI)"
	echo "3. Exit"
}

read_options(){
	local choice
	read -p "Enter choice [ 1 - 3] " choice
	case $choice in
		1) ask_install ;;
		2) make_update ;;
		3) exit 0;;
		*) echo -e "Invalid choice!" && sleep 2
	esac
}

trap '' SIGINT SIGQUIT SIGTSTP

while true
do
 	show_menus
	read_options
done