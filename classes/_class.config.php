<?php
/**
 * Create short URL project
 *
 * PHP version 5
 *
 * @category  PHP
 * @package   PHP_Walerus
 * @author    Walerus Walik <waleruscool@gmail.com>
 * @copyright 2016-2017 Walerus Walik
 * @license   Walerus Walik Licence
 * @link      http://pear.php.net/package/PHP_CodeSniffer
 */

/**
 * MyClass Doc Comment
 *
 * @category Class
 * @package  Config
 * @author   Walerus Walik <waleruscool@gmail.com>
 * @license  Walerus Walik
 * @link     LocalHost
 */
class Config
{

    public $Default_Site_Title = 'Short URL Service';
    public $Default_Site_URL = 'http://my_syte.com/';
    public $Default_Site_Name = 'S_U_S';

    public $Default_Site_Desc = 'My description site';
    public $Default_Site_Keyw = 'My keywords site, keywords site, site';

    /*
    *	Через сколько дней удалить связку Original URL <=> Short URL
    *	Min and Max Length for Short URL
    */
    public $Days_To_Remove = 15;

    /*
    *	Минимальное и Максимальное количество символов для случайного Short URL
    *	Min and Max Length for Short URL
    */
    public $Default_Min_URL_Length = 7;
    public $Default_Max_URL_Length = 9;

    /*
    *	Положительные коды ответа сервера
    *	Response from the server code
    */
    public $Default_Return_Code = array('200', '301', '302');

    /*
    *	Настройка БД
    *	Data Base config
    */
    public $HostDB = 'localhost';
    public $BaseDB = '*****';
    public $UserDB = '*****';
    public $PassDB = '*****';
}
