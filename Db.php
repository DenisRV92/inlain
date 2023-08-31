<?

namespace Db;

use PDO;
use PDOException;


class Db
{
    private static $instance = null;

    private $dsn = 'mysql:host=localhost;';
    private $username = 'root';
    private $password = 'root';
    private $conn;

    /**
     * Создаем базу данных и подключаемся к ней
     */
    public function connect()
    {

        try {
            $this->conn = new PDO($this->dsn, $this->username, $this->password);
            $sql = include 'sql.php';
            foreach ($sql as $sql) {
                $this->conn->exec($sql);
            }
            $this->conn->query("use inlain");

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
    public function сount($table)
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
    public static function getInstance()
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
    public function getConnection()
    {
        return $this->conn;
    }
}
