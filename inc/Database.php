<?php
//System Path(for php)
if (!defined('BASE_PATH')) {
    define('BASE_PATH', dirname(__DIR__));
}

// browser path (for css, js, images)
if (!defined('BASE_URL')) {
    define('BASE_URL', '/MediaLibrary-MVC-');
}
class Database
{
    private static ?PDO $connection = null;
    private static string $host   = '127.0.0.1';
    private static string $dbname = 'database01';
    private static string $user   = 'root';
    private static string $pass   = '';

    public static function getConnection(): PDO
    {
        if (self::$connection === null) {//self means same class=> if there is no previous ,i will create
            self::$connection = new PDO(//PDO driver/bridge from code to database
                "mysql:host=" . self::$host . ";dbname=" . self::$dbname . ";charset=utf8",
                self::$user,//CREATE CONNECTION = CREATE RESOURCES
                self::$pass,
                [//how u 
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,//if error return error
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC//when we pull data we will return associated arr
                ]
            );
        }

        return self::$connection;//otherwise use created resource
    }
}
