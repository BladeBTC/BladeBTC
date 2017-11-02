

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
