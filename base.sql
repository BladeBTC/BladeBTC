
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