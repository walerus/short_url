<?php
// Включение журнала ошибок
error_reporting(E_ALL);

ini_set("log_errors", 1);
ini_set("error_log", "php-error-check_choice.log");

// Старт сессии
@session_start();

$test_session = session_id();

if (isset($_POST['url_check'], $_POST['check_id']) && trim($_POST['url_check']) != '' && trim($_POST['check_id']) == $test_session) {

    // Автоподгрузка классов
    function __autoload($name)
    {
        include("classes/_class." . $name . ".php");
    }

    # Класс конфига
    $config = new config;

    # Функции
    $func = new func;

    # База данных
    $db = new db($config->HostDB, $config->UserDB, $config->PassDB, $config->BaseDB);

    # Фильтр
    $_POST['url_check'] = trim(preg_replace('~[^\w]~', '', $_POST['url_check']));

    $CheckChoice = $func->CheckChoice($_POST['url_check'], $config, $db);

    if ($CheckChoice === false) {
        echo json_encode(array('error' => true, 'answer' => 'BAD'));
        exit;
    } else {
        echo json_encode(array('error' => false, 'answer' => 'Ok'));
        exit;
    }

} else {
    echo json_encode(array('error' => true, 'answer' => 'BAD'));
    exit;
}

