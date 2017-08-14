<?PHP

class func
{

    public $UserIP = "Undefined"; # IP пользователя / User IP
    public $UserCode = "Undefined"; # Код от IP	/ User code
    public $UserAgent = "Undefined"; # Браузер пользователя / User browser

    /*======================================================================*\
    Function:	__construct
    Output:		Нет
    Description: Выполняется при создании экземпляра класса
    \*======================================================================*/
    public function __construct()
    {
        $this->UserIP = $this->GetUserIp();
        $this->UserCode = $this->IpCode();
        $this->UserAgent = $this->UserAgent();
    }

    /*======================================================================*\
    Function:	__destruct
    Output:		Нет
    Description: Уничтожение объекта
    \*======================================================================*/
    public function __destruct()
    {

    }

    /*======================================================================*\
    Function:	GetUserIp
    Output:		UserIp
    Description: Определяет IP пользователя
    \*======================================================================*/
    public function GetUserIp()
    {

        if ($this->UserIP == "Undefined") {

            if (isset($_SERVER['HTTP_X_FORWARDED_FOR']) AND !empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {

                $client_ip = (!empty($_SERVER['REMOTE_ADDR'])) ? $_SERVER['REMOTE_ADDR'] : ((!empty($_ENV['REMOTE_ADDR'])) ? $_ENV['REMOTE_ADDR'] : "unknown");
                $entries = preg_split('[, ]', $_SERVER['HTTP_X_FORWARDED_FOR']);

                reset($entries);

                while (list(, $entry) = each($entries)) {

                    $entry = trim($entry);

                    if (preg_match("/^([0-9]+\.[0-9]+\.[0-9]+\.[0-9]+)/", $entry, $ip_list)) {

                        $private_ip = array(
                            '/^0\./',
                            '/^127\.0\.0\.1/',
                            '/^192\.168\..*/',
                            '/^172\.((1[6-9])|(2[0-9])|(3[0-1]))\..*/',
                            '/^10\..*/'
                        );

                        $found_ip = preg_replace($private_ip, $client_ip, $ip_list[1]);

                        if ($client_ip != $found_ip) {
                            $client_ip = $found_ip;
                            break;
                        }
                    }
                }

                $this->UserIP = $client_ip;

                return $client_ip;
            } else {

                if (!empty($_SERVER['REMOTE_ADDR'])) {

                    if ($_SERVER['REMOTE_ADDR'] == '::1') {
                        return '192.168.1.1';
                    }

                    return $_SERVER['REMOTE_ADDR'];
                } else {
                    if ((!empty($_ENV['REMOTE_ADDR']))) {
                        return $_ENV['REMOTE_ADDR'];
                    } else {
                        return "unknown";
                    }
                }

                return (!empty($_SERVER['REMOTE_ADDR'])) ? $_SERVER['REMOTE_ADDR'] : ((!empty($_ENV['REMOTE_ADDR'])) ? $_ENV['REMOTE_ADDR'] : "unknown");
            }

        } else {

            $tmp_ip = $this->UserIP;

            if ($tmp_ip == '::1') {
                $tmp_ip = '192.168.1.1';
            }

            return $tmp_ip;
        }
    }

    /*======================================================================*\
    Function:	IpCode
    Output:		String, Example 255025502550255
    Input:		-
    Description: Возвращает IP с замененными знаками "." на "0"
    \*======================================================================*/
    public function IpCode()
    {

        $tmp_ip = $this->GetUserIp();

        if ($tmp_ip == '::1') {
            $tmp_ip = '192.168.1.1';
        }

        $arr_mask = explode(".", $tmp_ip);

        return $arr_mask[0] . "0" . $arr_mask[1] . "0" . $arr_mask[2] . "0" . $arr_mask[3];

    }

    /*======================================================================*\
    Function:	GetTime
    Description: Возвращаер дату
    \*======================================================================*/
    public function GetTime($tis = 0, $unix = true, $template = "d.m.Y H:i:s")
    {

        if ($tis == 0) {
            return ($unix) ? time() : date($template, time());
        } else return date($template, $tis);
    }

    /*======================================================================*\
    Function:	UserAgent
    Description: Возвращает браузер пользователя
    \*======================================================================*/
    public function UserAgent()
    {

        if (!isset($_SERVER['HTTP_USER_AGENT'])) {
            $_SERVER['HTTP_USER_AGENT'] = '';
        }

        return $this->TextClean($_SERVER['HTTP_USER_AGENT']);
    }

    /*======================================================================*\
    Function:	TextClean
    Description: Очистка текста
    \*======================================================================*/
    public function TextClean($text)
    {

        $array_find = array("`", "<", ">", "^", '"', "~", "\\");
        $array_replace = array("&#96;", "&lt;", "&gt;", "&circ;", "&quot;", "&tilde;", "");

        return str_replace($array_find, $array_replace, $text);
    }

    /*======================================================================*\
    Function:	CheckChoice
    Description: Проверка на существование в БД указанного пользователем Short URL
    \*======================================================================*/
    public function CheckChoice($url_check, $config, $db)
    {

        $test_len = mb_strlen($url_check, 'UTF-8');

        if (($test_len < $config->Default_Min_URL_Length) || ($test_len > $config->Default_Max_URL_Length)) {
            return false;
        }

        $db->Query("SELECT * FROM `data_of_urls` WHERE `short_url` = '" . $db->RealEscape($url_check) . "' LIMIT 1");

        return ($db->NumRows() > 0) ? false : true;
    }


    /**
     * GenerateText / Генерируем случайный текст
     *
     * @param int $length
     * @param bool $small
     * @param bool $big
     * @param bool $numbers
     * @param bool $underline
     *
     * @return string
     */
    public function GenerateText($length = 10, $small = true, $big = true, $numbers = true, $underline = true)
    {

        $allowable_characters = '';
        if ($small) {
            $allowable_characters .= 'abcdefghjklmnopqrstuvwxyz';
        }

        if ($big) {
            $allowable_characters .= 'ABCDEFGHJKLMNOPQRSTUVWXYZ';
        }

        if (($numbers) || (!$allowable_characters)) {
            $allowable_characters .= '0123456789';
        }

        if ($underline) {
            $allowable_characters .= '_';
        }

        $ps_len = strlen($allowable_characters);
        $pass = '';
        for ($i = 0; $i < $length; $i++) {
            $pass .= $allowable_characters[mt_rand(0, $ps_len - 1)];
        }

        return $pass;
    }

    /*======================================================================*\
    Function:	RandomShortURL
    Output:		String
    Description: Генерируем случайный Short URL
    \*======================================================================*/
    public function RandomShortURL($config, $db)
    {

        $shuffle = true;
        $count = 0;
        $trycount = 10;

        while ($shuffle) {
            $tmp_name = $this->GenerateText($config->Default_Max_URL_Length);

            $test_DB = $this->CheckChoice($tmp_name, $config, $db);

            if ($test_DB === true) {
                return $tmp_name;
            }

            if ($count >= $trycount) {
                return 'ERROR';
            } else {
                $count++;
            }
        }
    }

    /*======================================================================*\
    Function:	FindAndSendLocation
    Description: Редирект на Original URL, при найденом Short URL в БД
    \*======================================================================*/
    public function FindAndSendLocation($find, $config, $db)
    {

        $db->Query("SELECT * FROM `data_of_urls` WHERE `short_url` = '" . $db->RealEscape($find) . "' LIMIT 1");

        $redirect_url = $config->Default_Site_URL;

        if ($db->NumRows() > 0) {
            $tmp_data = $db->FetchAssoc();

            $db->Query("UPDATE `data_of_urls` SET `follow_a_link` = `follow_a_link` + '1' WHERE `short_url` = '" . $find . "'");

            $redirect_url = $tmp_data['long_url'];
        }

        $test_url = parse_url($redirect_url);

        if (!isset($test_url['scheme'])) {
            $redirect_url = "http://" . $redirect_url;
        }

        header('Location: ' . $redirect_url, true, 301);
    }

}

?>