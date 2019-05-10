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

	#create gui application config

	NEW_UUID=$(cat /dev/urandom | tr -dc 'a-zA-Z0-9' | fold -w 32 | head -n 1)

	echo -e "\e[92mWriting application configuration ... [PLEASE WAIT]\e[0m"
	if ! echo '
		#########################################
        ####              Debug              ####
        #########################################

        DEBUG=0

        #########################################
        ####               BDD               ####
        #########################################

		BDD_HOST="'${HOST}'"
		BDD_USER="'${USER}'"
		BDD_PASS="'${PASS}'"
		BDD_BDD="'${DB}'"

        #########################################
        ####               JWT               ####
        #########################################

        ISSUER="CMS"
        AUDIENCE=All
        JWT_KEY='${NEW_UUID}'

        #########################################
        ####               SMTP              ####
        #########################################

        SMTP_HOST=127.0.0.1
        SMTP_PORT=25
        EMAIL_DOMAIN='${DOMAIN}'
        EMAIL_NAME="'${PASS}' - No-Reply"' > /var/www/bot/GUI/.env
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

	#composer install gui
	echo -e "\e[92mRunning composer install ... [PLEASE WAIT]\e[0m"
	cd /var/www/bot/GUI
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

	read -r -d '' SQL_SCRIPT << EOM

CREATE DATABASE telegram_bot;

USE telegram_bot;

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

--
-- Structure de la table `gui_account`
--

CREATE TABLE `gui_account` (
  `id` int(11) NOT NULL,
  `first_name` varchar(30) DEFAULT NULL,
  `last_name` varchar(50) DEFAULT NULL,
  `username` varchar(32) DEFAULT NULL,
  `password` tinytext,
  `email` varchar(150) DEFAULT NULL,
  `profile_img` text,
  `last_login_date` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  `last_login_ip` varchar(20) DEFAULT NULL,
  `login_attempt` int(1) NOT NULL DEFAULT '0',
  `account_group` int(1) DEFAULT NULL,
  `inscription_date` timestamp NULL DEFAULT NULL,
  `deleted` int(1) NOT NULL DEFAULT '0',
  `deleted_date` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Account System' ROW_FORMAT=DYNAMIC;

--
-- Contenu de la table `gui_account`
--

INSERT INTO `gui_account` (`id`, `first_name`, `last_name`, `username`, `password`, `email`, `profile_img`, `last_login_date`, `last_login_ip`, `login_attempt`, `account_group`, `inscription_date`, `deleted`, `deleted_date`) VALUES
(1, 'Yanick', 'Lafontaine', 'ylafontaine', '$2y$10$9kt.cXYdlYMktoqfxtgWQOViMaomSwr5rAdD9R8Zuv3A1CTdafp2a', 'ylafontaine@addison-electronique.com', 'avatar.png', '2018-07-20 16:04:01', '::1', 0, -1, NULL, 0, NULL);

-- --------------------------------------------------------

--
-- Structure de la table `gui_group`
--

CREATE TABLE `gui_group` (
  `id` int(11) NOT NULL,
  `group_id` int(2) NOT NULL,
  `group_name` varchar(35) NOT NULL,
  `dashboard` tinytext
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Définition des groupe pour le CMS';

--
-- Contenu de la table `gui_group`
--

INSERT INTO `gui_group` (`id`, `group_id`, `group_name`, `dashboard`) VALUES
(1, -1, 'Développeur', 'dashboard');

-- --------------------------------------------------------

--
-- Structure de la table `gui_mail_group`
--

CREATE TABLE `gui_mail_group` (
  `id` int(11) NOT NULL,
  `group_name` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `gui_mail_group_member`
--

CREATE TABLE `gui_mail_group_member` (
  `id` int(11) NOT NULL,
  `mail_group_id` int(11) NOT NULL,
  `email` tinytext NOT NULL,
  `alias` tinytext
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `gui_menu`
--

CREATE TABLE `gui_menu` (
  `id` int(11) NOT NULL,
  `menu_id` int(1) DEFAULT NULL,
  `title` varchar(100) NOT NULL,
  `icon` tinytext NOT NULL,
  `display_order` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Table contenant les menus principaux du site';

--
-- Contenu de la table `gui_menu`
--

INSERT INTO `gui_menu` (`id`, `menu_id`, `title`, `icon`, `display_order`) VALUES
(1, -1, 'Développeur', 'fa-code', 2),
(4, 12, 'Configuration', 'fa-cogs', 1);

-- --------------------------------------------------------

--
-- Structure de la table `gui_module`
--

CREATE TABLE `gui_module` (
  `id` int(10) NOT NULL,
  `description` varchar(200) NOT NULL,
  `name` varchar(200) NOT NULL,
  `icon` varchar(200) NOT NULL,
  `access_level` tinytext NOT NULL,
  `parent` int(11) NOT NULL,
  `static` int(1) NOT NULL DEFAULT '0',
  `visits` int(11) DEFAULT '0',
  `last_visit` timestamp NULL DEFAULT NULL,
  `active` int(1) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Table contenant les modules';

--
-- Contenu de la table `gui_module`
--

INSERT INTO `gui_module` (`id`, `description`, `name`, `icon`, `access_level`, `parent`, `static`, `visits`, `last_visit`, `active`) VALUES
(1, 'Accueil', 'dashboard', 'fa-wrench', '1;-1;8;4;5', -1, 1, 18389, '2018-07-20 16:05:19', 1),
(2, 'Gestion des comptes', 'manage-account', 'fa-wrench', '-1;5', 12, 0, 219, '2018-07-20 15:43:00', 1),
(4, 'Gestion du menu', 'manage-menu', 'fa-wrench', '-1', 12, 0, 218, '2018-07-20 15:43:04', 1),
(5, 'Gestion des modules', 'manage-module', 'fa-wrench', '-1', 12, 0, 231, '2018-07-20 15:43:09', 1),
(6, 'Mon compte', 'profile', 'fa-wrench', '1;-1;4;5', -1, 1, 139, '2018-07-13 15:19:13', 1),
(7, 'Gestion des permissions', 'manage-rbac', 'fa-wrench', '-1', 12, 0, 43, '2018-07-20 15:43:15', 1),
(8, 'Accès refusé', 'denied', 'fa-wrench', '1;-1;8;4;5', -1, 1, 33, '2018-05-25 19:31:18', 1),
(35, 'Page de test', 'devtest', 'fa-wrench', '-1', -1, 0, 146, '2018-07-20 15:44:28', 1);

-- --------------------------------------------------------

--
-- Structure de la table `gui_rbac_assignment`
--

CREATE TABLE `gui_rbac_assignment` (
  `group_id` int(11) NOT NULL,
  `rbac_items_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Contenu de la table `gui_rbac_assignment`
--

INSERT INTO `gui_rbac_assignment` (`group_id`, `rbac_items_id`) VALUES
(-1, 1),
(-1, 3);

-- --------------------------------------------------------

--
-- Structure de la table `gui_rbac_items`
--

CREATE TABLE `gui_rbac_items` (
  `id` int(11) NOT NULL,
  `description` tinytext CHARACTER SET utf8 COLLATE utf8_unicode_ci
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Contenu de la table `gui_rbac_items`
--

INSERT INTO `gui_rbac_items` (`id`, `description`) VALUES
(1, 'Peut voir le temps de chargement de la page.'),
(3, 'Peut afficher la barre de débogage.');

-- --------------------------------------------------------

--
-- Structure de la table `gui_setting`
--

CREATE TABLE `gui_setting` (
  `id` int(11) NOT NULL,
  `setting_name` varchar(50) NOT NULL,
  `setting_value` text NOT NULL,
  `description` tinytext NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Table des paramètres de l''intranet';

--
-- Index pour les tables exportées
--

--
-- Index pour la table `gui_account`
--
ALTER TABLE `gui_account`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD KEY `account_group` (`account_group`);

--
-- Index pour la table `gui_group`
--
ALTER TABLE `gui_group`
  ADD PRIMARY KEY (`id`),
  ADD KEY `group_id` (`group_id`);

--
-- Index pour la table `gui_mail_group`
--
ALTER TABLE `gui_mail_group`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `group_name` (`group_name`);

--
-- Index pour la table `gui_mail_group_member`
--
ALTER TABLE `gui_mail_group_member`
  ADD PRIMARY KEY (`id`),
  ADD KEY `mail_group_id` (`mail_group_id`);

--
-- Index pour la table `gui_menu`
--
ALTER TABLE `gui_menu`
  ADD PRIMARY KEY (`id`),
  ADD KEY `menu_id` (`menu_id`);

--
-- Index pour la table `gui_module`
--
ALTER TABLE `gui_module`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `gui_rbac_assignment`
--
ALTER TABLE `gui_rbac_assignment`
  ADD PRIMARY KEY (`group_id`,`rbac_items_id`),
  ADD KEY `rbac_items_id` (`rbac_items_id`);

--
-- Index pour la table `gui_rbac_items`
--
ALTER TABLE `gui_rbac_items`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `gui_setting`
--
ALTER TABLE `gui_setting`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT pour les tables exportées
--

--
-- AUTO_INCREMENT pour la table `gui_account`
--
ALTER TABLE `gui_account`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=73;
--
-- AUTO_INCREMENT pour la table `gui_group`
--
ALTER TABLE `gui_group`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
--
-- AUTO_INCREMENT pour la table `gui_mail_group`
--
ALTER TABLE `gui_mail_group`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT pour la table `gui_mail_group_member`
--
ALTER TABLE `gui_mail_group_member`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT pour la table `gui_menu`
--
ALTER TABLE `gui_menu`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;
--
-- AUTO_INCREMENT pour la table `gui_module`
--
ALTER TABLE `gui_module`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=44;
--
-- AUTO_INCREMENT pour la table `gui_rbac_items`
--
ALTER TABLE `gui_rbac_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;
--
-- AUTO_INCREMENT pour la table `gui_setting`
--
ALTER TABLE `gui_setting`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- Contraintes pour les tables exportées
--

--
-- Contraintes pour la table `gui_mail_group_member`
--
ALTER TABLE `gui_mail_group_member`
  ADD CONSTRAINT `gui_mail_group_member_ibfk_1` FOREIGN KEY (`mail_group_id`) REFERENCES `gui_mail_group` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `gui_rbac_assignment`
--
ALTER TABLE `gui_rbac_assignment`
  ADD CONSTRAINT `gui_rbac_assignment_ibfk_1` FOREIGN KEY (`rbac_items_id`) REFERENCES `gui_rbac_items` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `gui_rbac_assignment_ibfk_2` FOREIGN KEY (`group_id`) REFERENCES `gui_group` (`group_id`) ON DELETE CASCADE ON UPDATE CASCADE;

/*!40101 SET CHARACTER_SET_CLIENT = @OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS = @OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION = @OLD_COLLATION_CONNECTION */;
EOM

    mysql --user=${USER} --password=${PASS} -e "${SQL_SCRIPT}"
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
        [Unit]
        Description=blockchain-wallet-service

        [Service]
        ExecStart=blockchain-wallet-service start --port 3000
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


