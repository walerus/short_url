<?php
/**
 * Create short URL project
 *
 * PHP version 5
 *
 * @category  PHP
 * @package   PHP_CodeSniffer
 * @author    Walerus Walik <waleruscool@gmail.com>
 * @copyright 2016-2017 Walerus Walik
 * @license   Walerus Walik Licence
 * @link      http://pear.php.net/package/PHP_CodeSniffer
 */

// Включение журнала ошибок / Enable error log
ini_set("log_errors", 1);
ini_set("error_log", "php-error.log");
date_default_timezone_set('Europe/Moscow');

// Старт сессии / Session start
@session_start();

// Старт буфера вывода / Turn on output buffering
@ob_start();


/**
 * Счетчик / Counter
 *
 * @return mixed
 */
function timerSet()
{
    list($seconds, $microSeconds) = explode(' ', microtime());
    return $seconds + (float)$microSeconds;
}

$_timer_a = timerSet();

/**
 * Автоподгрузка классов / Classes auto load
 *
 * @param int $name 
 *
 * @return int
 */
function __autoload($name)
{
    include("classes/_class." . $name . ".php");
}

// Класс конфига / Configuration class
$config = new config;

// Функции / Function class
$func = new func;

// База данных / Data Base class
$db = new db($config->HostDB, $config->UserDB, $config->PassDB, $config->BaseDB);

// Удаляем связку Original URL <=> Short URL, через время указанное в конфиге
// Delete Original URL <=> Short URL, over time in config
$date_check = (time() - (86400 * $config->Days_To_Remove));
$db->Query("DELETE FROM `data_of_urls` WHERE `date` < '" . $date_check . "'");

// Шапка / Header section
include("inc/_header.php");

if (isset($_GET["short_url_code"])) {

    $short_url_code = strval($_GET["short_url_code"]);
    $find_result = $func->FindAndSendLocation($short_url_code, $config, $db);
}

$db->Query("SELECT `long_url`, `short_url`, `user_ip`, `follow_a_link`, `date_normal` FROM `data_of_urls` ORDER BY `follow_a_link` DESC LIMIT 15");

$result_statistic = array();
if ($db->NumRows() > 0) {

    $st_count = 1;
    while ($tmp_row = $db->FetchAssoc()) {

        $result_statistic[] = array(
            $st_count,
            $tmp_row['long_url'],
            'http://' . $_SERVER['HTTP_HOST'] . '/' . $tmp_row['short_url'],
            $tmp_row['user_ip'],
            $tmp_row['follow_a_link'],
            $tmp_row['date_normal']
        );

        $st_count++;
    }
}

// Тело / Body section
include("pages/_index.php");

// Подвал / Footer section
include("inc/_footer.php");

// Заносим контент из буфера в переменную / Return the contents of the output buffer
$content = ob_get_contents();

// Очищаем буфер / Clean (erase) the output buffer and turn off output buffering
ob_end_clean();

// Заменяем данные / Replace date in the content
$content = str_replace("{!DOMEN!}", $config->Default_Site_URL, $content);
$content = str_replace("{!TITLE!}", $config->Default_Site_Title, $content);
$content = str_replace('{!DESCRIPTION!}', $config->Default_Site_Desc, $content);
$content = str_replace('{!KEYWORDS!}', $config->Default_Site_Keyw, $content);
$content = str_replace('{!GEN_PAGE!}', sprintf("%.5f", (timerSet() - $_timer_a)), $content);

// Выводим контент / Output the content
echo $content;
?>