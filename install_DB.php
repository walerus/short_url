<?php
// Включение журнала ошибок
error_reporting( E_ALL );

ini_set("log_errors", 1);
ini_set("error_log", "php-error_install_DB.log");

# Автоподгрузка классов
function __autoload($name){ include("classes/_class.".$name.".php");}

# Класс конфига 
$config = new config;

# База данных
$db = new db( $config->HostDB, $config->UserDB, $config->PassDB, $config->BaseDB );

$db->Query( "
CREATE TABLE IF NOT EXISTS `data_of_urls` (
`id` int(11) NOT NULL,
  `long_url` tinytext COLLATE utf8_unicode_ci NOT NULL COMMENT 'Оригинальный URL',
  `short_url` tinytext COLLATE utf8_unicode_ci NOT NULL COMMENT 'Привязанный URL',
  `user_ip` varchar(20) COLLATE utf8_unicode_ci NOT NULL COMMENT 'IP пользователя',
  `follow_a_link` int(11) NOT NULL COMMENT 'Переходов по ссылке',
  `date` int(11) NOT NULL COMMENT 'Дата добавления',
  `date_normal` varchar(20) COLLATE utf8_unicode_ci NOT NULL COMMENT 'Дата добавления',
  `user_brouser` text COLLATE utf8_unicode_ci NOT NULL COMMENT 'Браузер пользователя'
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='Таблица учета ссылок' AUTO_INCREMENT=1
" );

$db->Query( "ALTER TABLE `data_of_urls` ADD PRIMARY KEY (`id`)" );
$db->Query( "ALTER TABLE `data_of_urls` MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=1" );

echo "Table created, do not forget to delete this file - install_DB.php !";
?>