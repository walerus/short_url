<?php
// Включение журнала ошибок
error_reporting(E_ALL);

ini_set("log_errors", 1);
ini_set("error_log", "php-error-check_long_url.log");

# Старт сессии
@session_start();

$test_session = session_id();

# Автоподгрузка классов
function __autoload($name)
{
    include("classes/_class." . $name . ".php");
}

# Класс конфига 
$config = new config;

if (isset($_POST['user_long_url'], $_POST['user_choice_data'], $_POST['check_id']) && trim($_POST['user_long_url']) != '' && trim($_POST['check_id']) == $test_session) {

    # Проверяем URL не ссылается ли сам на себя
    $test_url = parse_url(trim($_POST['user_long_url']), PHP_URL_HOST);

    if (trim($_SERVER['HTTP_HOST']) == $test_url) {
        echo json_encode(array('error' => true, 'answer' => 'bad', 'code' => '000'));
        exit;
    }

    //настраиваем курл
    $curl = curl_init();
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($curl, CURLOPT_TIMEOUT, 30);
    curl_setopt($curl, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows; U; Windows NT 6.0; en; rv:1.9.0.1) Gecko/2008070208 Firefox/3.0.1');
    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
    curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1);

    curl_setopt($curl, CURLOPT_REFERER, 'http://google.com/');
    curl_setopt($curl, CURLOPT_URL, trim($_POST['user_long_url']));
    curl_exec($curl);
    $code = curl_getinfo($curl);

    curl_close($curl);

    if (in_array($code['http_code'], $config->Default_Return_Code)) {

        # Функции
        $func = new func;

        # База данных
        $db = new db($config->HostDB, $config->UserDB, $config->PassDB, $config->BaseDB);

        # Если пользователь указал свой Short URL
        if (trim($_POST['user_choice_data'] != '')) {
            # Фильтр
            $_POST['user_choice_data'] = trim(preg_replace('~[^\w]~', '', $_POST['user_choice_data']));

            # Проверяем short URL пользователя
            $CheckChoice = $func->CheckChoice($_POST['user_choice_data'], $config, $db);

            if ($CheckChoice === false) {
                echo json_encode(array('error' => true, 'answer' => 'bad', 'code' => '0000'));
                exit;
            } else {
                $set_short_url_value = $_POST['user_choice_data'];
            }
        } else {
            $set_short_url_value = $func->RandomShortURL($config, $db);
        }

        if (trim($set_short_url_value) == 'ERROR') {
            echo json_encode(array('error' => true, 'answer' => 'bad', 'code' => '0000'));
            exit;
        }

        // Заносим данные
        $now_time = $func->GetTime();
        $db->Query("INSERT INTO `data_of_urls` (
													`id`, 
													`long_url`, 
													`short_url`, 
													`user_ip`, 
													`follow_a_link`, 
													`date`, 
													`date_normal`, 
													`user_brouser`
												) 
										VALUES (
													NULL, 
													'" . $db->RealEscape(trim($_POST['user_long_url'])) . "', 
													'" . $set_short_url_value . "', 
													'" . $func->UserIP . "', 
													'0', 
													'" . $now_time . "', 
													'" . $func->GetTime($now_time, false) . "', 
													'" . $func->UserAgent . "'
												);"
        );

        echo json_encode(array('error' => false, 'answer' => 'Ok', 'code' => $code['http_code'], 'short_url' => $set_short_url_value));
        exit;
    } else {
        echo json_encode(array('error' => true, 'answer' => 'bad', 'code' => $code['http_code']));
        exit;
    }
} else {
    echo json_encode(array('error' => true, 'answer' => 'bad', 'code' => '000'));
}

