<?php

namespace Db;

use PDO;
use PDOException;
use Src\Contract\DbInterface;

class Db implements DbInterface
{
    private static $instance = null;

    private $dsn = 'mysql:host=localhost;';
    private $username = 'root';
    private $password = 'root';
    private $dbname = 'inlain';
    private $conn;

    /**
     * Создаем базу данных и подключаемся к ней
     */
    public function connect():void
    {
        try {
            $this->conn = new PDO($this->dsn, $this->username, $this->password);

            $stmt = $this->conn->query("SELECT SCHEMA_NAME FROM INFORMATION_SCHEMA.SCHEMATA WHERE SCHEMA_NAME = '$this->dbname'");
            $databaseExists = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$databaseExists) {
                $sql = include 'sql.php';
                foreach ($sql as $sqlQuery) {
                    $this->conn->exec($sqlQuery);
                }
            } else {
                $this->conn->query("USE $this->dbname");
            }

        } catch (PDOException $e) {
            print "Error!: " . $e->getMessage() . "<br/>";
            die();
        }
    }

    /**
     * Создаем базу данных и подключаемся к ней
     * @param $table
     * @return int
     */
    public function count(string $table):int
    {
        $result = $this->conn->query("SELECT COUNT(*) as count FROM {$table}");
        if ($result) {
            $row = $result->fetch(PDO::FETCH_ASSOC);
            return (int)$row["count"];
        }
        return 0;
    }

    /**
     * Получает единственный экземпляр класса (Singleton) и устанавливает подключение к базе данных
     * @return self объект-экземпляр данного класса
     */
    public static function getInstance():self
    {
        if (self::$instance === null) {
            self::$instance = new self();
            self::$instance->connect();
        }

        return self::$instance;
    }

    /**
     * Возвращает текущее подключение к БД
     * @return PDO
     */
    public function getConnection(): PDO
    {
        return $this->conn;
    }
}