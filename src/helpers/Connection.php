<?php

class Connection
{
  private static $host = '162.241.62.58:3306';
  private static $user = 'javierze_jav_admin-1';
  private static $pass = 'olaolaola315';
  private static $db = 'javierze_mma';

  public static function connect()
  {
    try {
      $connection = new PDO('mysql:host=' . self::$host . ';dbname=' . self::$db, self::$user, self::$pass);

      $connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

      $connection->exec('set names utf8');

      return $connection;
    } catch (PDOException $e) {
      die('Ha ocurrido un error al conectar con la base de datos');
    } catch (Exception $e) {
      die('Ha ocurrido un error');
    }
  }
}
