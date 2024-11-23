<?php

namespace PHPFramework;

class Database
{

    // защищенное свойство, экземпляр класса PDO для хранения подключения к базе данных 
    protected \PDO $connection;

    // защищенное свойство stmt, экземпляр класса \PDOStatement для выполнения запросов  c 
    // подготвленными выражениями для защиты от sql инъекций
    protected \PDOStatement $stmt;

    // конструктор класса Database, который будет вызван при создании экземпляра класса
    public function __construct()
    {
        // подготовка подключения к базе данных
        $dsn = "mysql:host=" . DB_SETTINGS['host'] . ";dbname=" . DB_SETTINGS['database'] . ";charset=" . DB_SETTINGS['charset'];

        // реализуем подключение с помощью конструкции try catch для обработки исключений
        try {
            $this->connection = new \PDO($dsn, DB_SETTINGS['username'], DB_SETTINGS['password'], DB_SETTINGS['options']);
        } catch (\PDOException $e) {
            //  с помощью функции error_log запываем в файл log ошибку 
            error_log("[" . date('Y-m-d H:i:s') . "] DB ERROR: . {$e->getMessage()}" . PHP_EOL, 3, ERROR_LOGS);
            abort('Database connection error', 500);
        }
        
    }

    // функция для подготовки и выполнения запроса к базе данных.
    /**
     * Prepares and executes an SQL statement with given parameters to prevent SQL injection.
     * 
     * @param string $sql The SQL query to be prepared and executed.
     * @param array $params The parameters to bind to the SQL query.
     * 
     * @return $this Returns the instance of the statement, allowing for method chaining.
     */
    public function query($sql, $params = [])
    {
        // вставляем в подготовленное выражение SQL параметры из массива params,
        // это защита от SQL инъекций, 
        // PDO будет подставлять эти параметры в запрос
        $this->stmt = $this->connection->prepare($sql);

        // выполняем запрос с параметрами
        $this->stmt->execute($params);

        // возвращаем подготовленное выражение
        // его можно использовать для получения данных из БД
        return $this;
    }

    // метод get для получения всех данных (not void)
    /**
     * Retrieves all rows of a result set as an associative array.
     * 
     * Returns an array of results or false if no results were found.
     * 
     * @return array|false
     */
    public function get(): array|false
    {
        return $this->stmt->fetchAll() ?: false;
    }

    // метод getOne для получения одной записи
    /**
     * Retrieves the next row of a result set as an associative array.
     * 
     * @return array|false An associative array representing the fetched row, or false if no row is found.
     */
    public function getOne()
    {
        return $this->stmt->fetch() ?: false;
    }

    // метод getAssoc для формирования ассоциативных массивов где ключом одного обьекта массива будет id таблицы
    /**
     * Returns an associative array where the key is the value of the field specified in the $key parameter
     * and the value is an associative array with the data from the database.
     *
     * @param string $key The field name to use as the key for the associative array.
     * @return array An associative array with the data from the database.
     */
    public function getAssoc($key = 'id'): array
    {
        $data = [];
        // проходимся циклом по строкам результата запроса
        while ($row = $this->stmt->fetch()) {
            // в каждой итерации цикла мы создаем ассоциативный массив 
            // где ключ - это значение поля $key, 
            // а значение - это ассоциативный массив с данными из БД
            $data[$row[$key]] = $row;
        }
        // возвращаем ассоциативный массив
        return $data;
    }


    // метод getColumn для  возвращения данных одного столбца следующей строки результирующего набора
    /**
     * Retrieves the next column of a result set row.
     *
     * @return mixed The value of the next column or false if there are no more columns.
     */
    public function getColumn()
    {
        return $this->stmt->fetchColumn();
    }

    // метод получения всех данных из таблицы (void)
    // пример:
    // $data = db()->findAll('users'); # подключение с помощью функции хелпера, которая возвращает экземпляр класса  Database  переданного в 
    // класс Application
    // $data = $db->findAll('users');  # подключение от свойства класса db
    // dump($data);

    /**
     * Retrieves all records from the specified table.
     *
     * @param string $tbl The name of the table to query.
     *
     * @return array Returns array of records.
     */
    public function findAll($tbl)
    {
        // создаем запрос на получение всех данных из таблицы
        $this->query("select * from {$tbl}");
        // возвращаем массив полученных записей 
        return $this->stmt->fetchAll();
    }

    // метод получения одной записи из БД
    // пример:
    //  $user = $db->findOne('users', 1);
    //  dump($user);
    /**
     * Retrieves a single record from the database based on a specified key and value.
     *
     * @param string $tbl   The name of the table to query.
     * @param mixed  $value The value to match against the specified key column.
     * @param string $key   The column name to be used as the key for the query. Default is 'id'.
     * 
     * @return array|false  The associative array representing the fetched record, or false if no record is found.
     */
    public function findOne($tbl, $value, $key = "id")
    {
        // создаем запрос на получение одной записи из таблицы
        $this->query("select * from {$tbl} where {$key} = ? LIMIT 1", [$value]);
        // возвращаем одну запись из БД
        return $this->stmt->fetch();
    }

    // функция для возвращения страницы с ошибкой 404, если данных не было найдено

    /**
     * Find one record in the database or throw a 404 error
     * 
     * @param string $tbl The name of the table to search in
     * @param mixed $value The value to search for
     * @param string $key The key to use for the search (default is 'id')
     * @return array The found record or throws a 404 error
     */
    public function FindOrFail($tbl, $value, $key = "id")
    {

        // обращаемся к findOne для получения данных
        $res = $this->findOne($tbl, $value, $key);

        // если данные не нашлись, то возвращаем страницу с ошибкой 404
        // с помощью функции хелпера abort
        if (!$res) {
            // передаем 404 ошибку и сообщение "Data not found"
            abort(404, 'Data not found');
        }

        // возвращаем найденные данные
        return $res;
    }

    // метод получения id последней вставленной записи

    /**
     * Retrieves the ID of the last inserted row.
     *
     * @return false|string The ID of the last inserted row, or false if no row was inserted.
     */
    public function getInsertId(): false|string
    {
        return $this->connection->lastInsertId();
    }

    // метод rowCount который возвращает количество затронутых строк в таблице
    /**
     * Retrieves the number of rows in the result set.
     *
     * @return int The number of rows in the result set.
     */
    public function rowCount(): int
    {
        return $this->stmt->rowCount();
    }

  
    // Транзакционными являются такие запросы в которых одновременно происходят несколько операций с несколькими таблицами
    // В таких запросах важно чтобы все его части исполнились, дабы не нарушить целостность данных
    // Если же была допущена ошибка во время запроса то произойдет откат до предыдущего состояния
    // метод для формирования транзакции
    /**
     * Initiates a database transaction.
     *
     * @return bool True on success, or false on failure.
     */
    public function beginTransaction(): bool
    {
       return $this->connection->beginTransaction();
    }
    
    // метод для завершения транзакции
    /**
     * Commits a database transaction.
     *
     * @return bool True on success, or false on failure.
     */
    public function commit(): bool
    {
        return $this->connection->commit();
    }

    // метод для отката транзакции
    /**
     * Rolls back a database transaction.
     *
     * @return bool True on success, or false on failure.
     */
    public function rollBack(): bool
    {
        return $this->connection->rollBack();
    }


}
