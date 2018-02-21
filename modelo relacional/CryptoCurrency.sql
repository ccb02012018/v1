-- phpMyAdmin SQL Dump
-- version 3.5.8.2
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Feb 19, 2018 at 03:53 PM
-- Server version: 10.2.12-MariaDB
-- PHP Version: 5.6.33

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `CryptoCurrency`
--

-- --------------------------------------------------------

--
-- Table structure for table `Account`
--

CREATE TABLE IF NOT EXISTS `Account` (
  `acc_id` int(11) NOT NULL AUTO_INCREMENT,
  `acc_exc_id` int(11) NOT NULL,
  `acc_key` varchar(255) NOT NULL,
  `acc_secret` varchar(255) NOT NULL,
  `acc_name` varchar(45) DEFAULT NULL,
  `acc_limit_weight` int(11) NOT NULL,
  PRIMARY KEY (`acc_id`),
  KEY `fk_acc_exc_idx` (`acc_exc_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `Account`
--

INSERT INTO `Account` (`acc_id`, `acc_exc_id`, `acc_key`, `acc_secret`, `acc_name`, `acc_limit_weight`) VALUES
(1, 1, 'XnBAtdgSaILAnT7vRLyVH5KI1rj7NFs0dwMZf3SqJWLL4IGijx9vyXQbdMkVV9Ur', 'UVsNzI5UiYhwcUkb4c9RNOrV2K1s3CbF5zHo9IrebSMAXMs3WemyGVkTZXHf0ZN2', 'binance primaria', 671);

-- --------------------------------------------------------

--
-- Table structure for table `Bot`
--

CREATE TABLE IF NOT EXISTS `Bot` (
  `bot_id` int(11) NOT NULL AUTO_INCREMENT,
  `bot_name` varchar(45) NOT NULL,
  `bot_exc_id` int(11) NOT NULL,
  `bot_last_update` int(11) DEFAULT NULL,
  `bot_typ_bot_id` int(11) NOT NULL,
  `bot_typ_can_id` int(11) NOT NULL,
  `bot_cur_id` int(11) NOT NULL COMMENT 'Market currency',
  `bot_sleep` tinyint(1) NOT NULL,
  `bot_wake_up` int(11) DEFAULT NULL,
  `bot_active` tinyint(1) NOT NULL,
  `bot_running` tinyint(1) NOT NULL,
  `bot_process_id` varchar(45) DEFAULT NULL,
  `bot_acc_id` int(11) DEFAULT NULL,
  `bot_bot_ins_id` int(11) DEFAULT NULL COMMENT 'Instancia de bot actual',
  `bot_delay` int(11) NOT NULL DEFAULT 0 COMMENT 'Segundos de delay para despertar',
  PRIMARY KEY (`bot_id`),
  KEY `fk_bot_typ_bot_idx` (`bot_typ_bot_id`),
  KEY `fk_bot_typ_can_idx` (`bot_typ_can_id`),
  KEY `fk_bot_cur_idx` (`bot_cur_id`),
  KEY `fk_bot_exc_idx` (`bot_exc_id`),
  KEY `fk_bot_acc_idx` (`bot_acc_id`),
  KEY `fk_bot_bot_ins_idx` (`bot_bot_ins_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=13 ;

--
-- Dumping data for table `Bot`
--

INSERT INTO `Bot` (`bot_id`, `bot_name`, `bot_exc_id`, `bot_last_update`, `bot_typ_bot_id`, `bot_typ_can_id`, `bot_cur_id`, `bot_sleep`, `bot_wake_up`, `bot_active`, `bot_running`, `bot_process_id`, `bot_acc_id`, `bot_bot_ins_id`, `bot_delay`) VALUES
(1, 'botExchange', 1, 1518978722, 1, 3, 1, 0, 1518978486, 1, 0, NULL, NULL, NULL, 0),
(2, 'botSync', 1, 1518979236, 2, 18, 1, 0, 1519065633, 1, 0, NULL, NULL, NULL, 0),
(3, 'botCandle', 1, 1519060503, 3, 9, 1, 0, 1519060500, 1, 0, NULL, NULL, NULL, 2),
(4, 'botCandlesGen', 1, 1519063394, 4, 9, 1, 0, 1519063200, 1, 0, NULL, NULL, NULL, 10);

-- --------------------------------------------------------

--
-- Table structure for table `BotInstance`
--

CREATE TABLE IF NOT EXISTS `BotInstance` (
  `bot_ins_id` int(11) NOT NULL AUTO_INCREMENT,
  `bot_ins_bot_id` int(11) NOT NULL,
  `bot_ins_start` int(11) NOT NULL,
  `bot_ins_end` int(11) DEFAULT NULL,
  PRIMARY KEY (`bot_ins_id`),
  KEY `fk_bot_idx` (`bot_ins_bot_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=440 ;

-- --------------------------------------------------------

--
-- Table structure for table `Candlestick`
--

CREATE TABLE IF NOT EXISTS `Candlestick` (
  `can_id` int(11) NOT NULL AUTO_INCREMENT,
  `can_exc_id` int(11) NOT NULL,
  `can_cur_id` int(11) NOT NULL,
  `can_typ_can_id` int(11) NOT NULL,
  `can_open_time` bigint(15) NOT NULL,
  `can_open` decimal(10,8) NOT NULL,
  `can_high` decimal(10,8) NOT NULL,
  `can_low` decimal(10,8) NOT NULL,
  `can_close` decimal(10,8) NOT NULL,
  `can_close_time` bigint(15) NOT NULL,
  `can_volumen_int` int(11) NOT NULL,
  `can_volume_decimal` int(11) NOT NULL,
  `can_quote_asset_volume` decimal(16,8) NOT NULL COMMENT 'BTC volume',
  `can_number_trades` int(11) NOT NULL,
  `can_tb_base_asset_volume` decimal(16,8) NOT NULL COMMENT 'taker buy Cantidad',
  `can_tb_quote_asset_volume` decimal(16,8) NOT NULL COMMENT 'Taker buy precio',
  `can_ignore` decimal(16,8) DEFAULT NULL COMMENT 'algo',
  PRIMARY KEY (`can_id`),
  KEY `fk_can_typ_can_idx` (`can_typ_can_id`),
  KEY `fk_can_cur_idx` (`can_cur_id`),
  KEY `fk_can_exc_idx` (`can_exc_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `CandlestickHistory`
--

CREATE TABLE IF NOT EXISTS `CandlestickHistory` (
  `can_his_id` int(11) NOT NULL AUTO_INCREMENT,
  `can_his_exc_id` int(11) NOT NULL,
  `can_his_cur_id` int(11) NOT NULL,
  `can_his_typ_can_id` int(11) NOT NULL,
  `can_his_open_time` bigint(15) NOT NULL,
  `can_his_open` decimal(10,8) NOT NULL,
  `can_his_high` decimal(10,8) NOT NULL,
  `can_his_low` decimal(10,8) NOT NULL,
  `can_his_close` decimal(10,8) NOT NULL,
  `can_his_close_time` bigint(15) NOT NULL,
  `can_his_volumen_int` int(11) NOT NULL,
  `can_his_volume_decimal` int(11) NOT NULL,
  `can_his_quote_asset_volume` decimal(16,8) NOT NULL COMMENT 'BTC volume',
  `can_his_number_trades` int(11) NOT NULL,
  `can_his_tb_base_asset_volume` decimal(16,8) NOT NULL COMMENT 'taker buy Cantidad',
  `can_his_tb_quote_asset_volume` decimal(16,8) NOT NULL COMMENT 'Taker buy precio',
  `can_his_ignore` decimal(16,8) DEFAULT NULL COMMENT 'algo',
  PRIMARY KEY (`can_his_id`),
  KEY `fk_can_typ_can_idx` (`can_his_typ_can_id`),
  KEY `fk_can_cur_idx` (`can_his_cur_id`),
  KEY `fk_can_exc_idx` (`can_his_exc_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=9 ;

--
-- Dumping data for table `CandlestickHistory`
--

INSERT INTO `CandlestickHistory` (`can_his_id`, `can_his_exc_id`, `can_his_cur_id`, `can_his_typ_can_id`, `can_his_open_time`, `can_his_open`, `can_his_high`, `can_his_low`, `can_his_close`, `can_his_close_time`, `can_his_volumen_int`, `can_his_volume_decimal`, `can_his_quote_asset_volume`, `can_his_number_trades`, `can_his_tb_base_asset_volume`, `can_his_tb_quote_asset_volume`, `can_his_ignore`) VALUES
(1, 1, 2, 9, 1519002900000, '0.08800700', '0.08800700', '0.08792100', '0.08798600', 1519003199999, 11, 65500000, '1.02530783', 57, '7.52600000', '0.66218244', '0.00000000'),
(2, 1, 3, 9, 1519002900000, '0.00000474', '0.00000475', '0.00000474', '0.00000475', 1519003199999, 19303, 0, '0.09153222', 6, '3600.00000000', '0.01710000', '0.00000000'),
(3, 1, 2, 9, 1519003200000, '0.08797600', '0.08799900', '0.08796800', '0.08799600', 1519003499999, 5, 93000000, '0.52176695', 23, '4.51500000', '0.39728725', '0.00000000'),
(4, 1, 3, 9, 1519003200000, '0.00000475', '0.00000475', '0.00000475', '0.00000475', 1519003499999, 6508, 0, '0.03091300', 2, '6508.00000000', '0.03091300', '0.00000000'),
(5, 1, 2, 9, 1519059900000, '0.08557300', '0.08558400', '0.08557300', '0.08557600', 1519060199999, 6, 31600000, '0.54049798', 18, '0.00000000', '0.00000000', '0.00000000'),
(6, 1, 3, 9, 1519059900000, '0.00000464', '0.00000464', '0.00000463', '0.00000463', 1519060199999, 40770, 0, '0.18908028', 8, '31518.00000000', '0.14624352', '0.00000000'),
(7, 1, 2, 9, 1519060200000, '0.08562200', '0.08563100', '0.08555000', '0.08560000', 1519060499999, 8, 38700000, '0.71789094', 42, '1.78600000', '0.15290520', '0.00000000'),
(8, 1, 3, 9, 1519060200000, '0.00000463', '0.00000463', '0.00000462', '0.00000462', 1519060499999, 155295, 0, '0.71760071', 25, '59218.00000000', '0.27372497', '0.00000000');

-- --------------------------------------------------------

--
-- Table structure for table `Currency`
--

CREATE TABLE IF NOT EXISTS `Currency` (
  `cur_id` int(11) NOT NULL AUTO_INCREMENT,
  `cur_name` varchar(45) NOT NULL,
  `cur_code` varchar(45) NOT NULL,
  PRIMARY KEY (`cur_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;

--
-- Dumping data for table `Currency`
--

INSERT INTO `Currency` (`cur_id`, `cur_name`, `cur_code`) VALUES
(1, 'Bitcoin', 'BTC'),
(2, 'Ethereum', 'ETH'),
(3, 'Tron', 'TRX');

-- --------------------------------------------------------

--
-- Table structure for table `CurrencyExchange`
--

CREATE TABLE IF NOT EXISTS `CurrencyExchange` (
  `cur_exc_cur_id` int(11) NOT NULL,
  `cur_exc_exc_id` int(11) NOT NULL,
  `cur_exc_code` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`cur_exc_cur_id`,`cur_exc_exc_id`),
  KEY `fk_cur_exc_exc_idx` (`cur_exc_exc_id`),
  KEY `fk_cur_exc_cur_idx` (`cur_exc_cur_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `CurrencyExchange`
--

INSERT INTO `CurrencyExchange` (`cur_exc_cur_id`, `cur_exc_exc_id`, `cur_exc_code`) VALUES
(1, 1, 'BTC'),
(2, 1, 'ETH'),
(3, 1, 'TRX');

-- --------------------------------------------------------

--
-- Table structure for table `Exchange`
--

CREATE TABLE IF NOT EXISTS `Exchange` (
  `exc_id` int(11) NOT NULL AUTO_INCREMENT,
  `exc_name` varchar(45) NOT NULL,
  `exc_limit` int(11) DEFAULT NULL,
  `exc_taked` tinyint(1) DEFAULT NULL,
  `exc_take_time` int(11) DEFAULT NULL,
  `exc_local_time_sincronize` int(11) DEFAULT NULL,
  `exc_server_time_sincronize` int(11) DEFAULT NULL,
  `exc_difference` int(11) DEFAULT NULL,
  `exc_last_reset_limit` int(11) DEFAULT NULL,
  `exc_last_update` int(11) DEFAULT NULL,
  `exc_last_sincronitation` int(11) DEFAULT NULL,
  PRIMARY KEY (`exc_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `Exchange`
--

INSERT INTO `Exchange` (`exc_id`, `exc_name`, `exc_limit`, `exc_taked`, `exc_take_time`, `exc_local_time_sincronize`, `exc_server_time_sincronize`, `exc_difference`, `exc_last_reset_limit`, `exc_last_update`, `exc_last_sincronitation`) VALUES
(1, 'Binance', 1200, 1, NULL, 1518979236, 1518979235, -1, NULL, 1518979236, 1518979236);

-- --------------------------------------------------------

--
-- Table structure for table `Log`
--

CREATE TABLE IF NOT EXISTS `Log` (
  `log_id` int(11) NOT NULL AUTO_INCREMENT,
  `log_message` varchar(255) NOT NULL,
  `log_time_stamp` int(11) NOT NULL,
  `log_date_time` datetime NOT NULL DEFAULT current_timestamp(),
  `log_bot_ins_id` int(11) NOT NULL,
  PRIMARY KEY (`log_id`),
  KEY `fk_bot_ins_idx` (`log_bot_ins_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3110 ;

-- --------------------------------------------------------

--
-- Table structure for table `TypeBot`
--

CREATE TABLE IF NOT EXISTS `TypeBot` (
  `typ_bot_id` int(11) NOT NULL AUTO_INCREMENT,
  `typ_bot_description` varchar(45) NOT NULL,
  PRIMARY KEY (`typ_bot_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=5 ;

--
-- Dumping data for table `TypeBot`
--

INSERT INTO `TypeBot` (`typ_bot_id`, `typ_bot_description`) VALUES
(1, 'Exchange'),
(2, 'Sync'),
(3, 'Candle'),
(4, 'Candles Generator');

-- --------------------------------------------------------

--
-- Table structure for table `TypeCandlestick`
--

CREATE TABLE IF NOT EXISTS `TypeCandlestick` (
  `typ_can_id` int(11) NOT NULL AUTO_INCREMENT,
  `typ_can_description` varchar(45) NOT NULL,
  `typ_can_milliseconds` bigint(15) NOT NULL,
  PRIMARY KEY (`typ_can_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=23 ;

--
-- Dumping data for table `TypeCandlestick`
--

INSERT INTO `TypeCandlestick` (`typ_can_id`, `typ_can_description`, `typ_can_milliseconds`) VALUES
(1, '1s', 1000),
(2, '3s', 3000),
(3, '5s', 5000),
(4, '10s', 10000),
(5, '15s', 15000),
(6, '30s', 30000),
(7, '1m', 60000),
(8, '3m', 180000),
(9, '5m', 300000),
(10, '15m', 900000),
(11, '30m', 1800000),
(12, '1h', 3600000),
(13, '2h', 7200000),
(14, '4h', 14400000),
(15, '6h', 21600000),
(16, '8h', 28800000),
(17, '12h', 43200000),
(18, '1d', 86400000),
(19, '3d', 259200000),
(22, '10m', 600000);

--
-- Constraints for dumped tables
--

--
-- Constraints for table `Account`
--
ALTER TABLE `Account`
  ADD CONSTRAINT `fk_acc_exc` FOREIGN KEY (`acc_exc_id`) REFERENCES `Exchange` (`exc_id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `Bot`
--
ALTER TABLE `Bot`
  ADD CONSTRAINT `fk_bot_acc` FOREIGN KEY (`bot_acc_id`) REFERENCES `Account` (`acc_id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_bot_bot_ins` FOREIGN KEY (`bot_bot_ins_id`) REFERENCES `BotInstance` (`bot_ins_id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_bot_cur` FOREIGN KEY (`bot_cur_id`) REFERENCES `Currency` (`cur_id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_bot_exc` FOREIGN KEY (`bot_exc_id`) REFERENCES `Exchange` (`exc_id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_bot_typ_bot` FOREIGN KEY (`bot_typ_bot_id`) REFERENCES `TypeBot` (`typ_bot_id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_bot_typ_can` FOREIGN KEY (`bot_typ_can_id`) REFERENCES `TypeCandlestick` (`typ_can_id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `BotInstance`
--
ALTER TABLE `BotInstance`
  ADD CONSTRAINT `fk_bot` FOREIGN KEY (`bot_ins_bot_id`) REFERENCES `Bot` (`bot_id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `Candlestick`
--
ALTER TABLE `Candlestick`
  ADD CONSTRAINT `fk_can_cur` FOREIGN KEY (`can_cur_id`) REFERENCES `Currency` (`cur_id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_can_exc` FOREIGN KEY (`can_exc_id`) REFERENCES `Exchange` (`exc_id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_can_typ_can` FOREIGN KEY (`can_typ_can_id`) REFERENCES `TypeCandlestick` (`typ_can_id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `CandlestickHistory`
--
ALTER TABLE `CandlestickHistory`
  ADD CONSTRAINT `fk_can_cur0` FOREIGN KEY (`can_his_cur_id`) REFERENCES `Currency` (`cur_id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_can_exc0` FOREIGN KEY (`can_his_exc_id`) REFERENCES `Exchange` (`exc_id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_can_typ_can0` FOREIGN KEY (`can_his_typ_can_id`) REFERENCES `TypeCandlestick` (`typ_can_id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `CurrencyExchange`
--
ALTER TABLE `CurrencyExchange`
  ADD CONSTRAINT `fk_cur_exc_cur` FOREIGN KEY (`cur_exc_cur_id`) REFERENCES `Currency` (`cur_id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_cur_exc_exc` FOREIGN KEY (`cur_exc_exc_id`) REFERENCES `Exchange` (`exc_id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `Log`
--
ALTER TABLE `Log`
  ADD CONSTRAINT `fk_bot_ins` FOREIGN KEY (`log_bot_ins_id`) REFERENCES `BotInstance` (`bot_ins_id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
