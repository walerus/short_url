-- phpMyAdmin SQL Dump
-- version 4.2.7.1
-- http://www.phpmyadmin.net
--
-- Хост: 127.0.0.1
-- Время создания: Фев 16 2017 г., 03:18
-- Версия сервера: 5.6.20
-- Версия PHP: 5.5.15

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

-- --------------------------------------------------------

--
-- Структура таблицы `data_of_urls`
--

CREATE TABLE IF NOT EXISTS `data_of_urls` (
`id` int(11) NOT NULL,
  `long_url` tinytext COLLATE utf8_unicode_ci NOT NULL COMMENT 'Оригинальный URL',
  `short_url` tinytext COLLATE utf8_unicode_ci NOT NULL COMMENT 'Привязанный URL',
  `user_ip` varchar(20) COLLATE utf8_unicode_ci NOT NULL COMMENT 'IP пользователя',
  `follow_a_link` int(11) NOT NULL COMMENT 'Переходов по ссылке',
  `date` int(11) NOT NULL COMMENT 'Дата добавления',
  `date_normal` varchar(20) COLLATE utf8_unicode_ci NOT NULL COMMENT 'Дата добавления',
  `user_brouser` text COLLATE utf8_unicode_ci NOT NULL COMMENT 'Браузер пользователя'
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='Таблица учета ссылок' AUTO_INCREMENT=1 ;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `data_of_urls`
--
ALTER TABLE `data_of_urls`
 ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `data_of_urls`
--
ALTER TABLE `data_of_urls`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=1;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
