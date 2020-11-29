<?php
require_once ('db.php');

class init_db extends \app\db
{
    public function __construct()
    {
        $link = mysqli_connect($this->host, $this->user, $this->password);

        if ($link == false){
            print("Ошибка: Невозможно подключиться к MySQL " . mysqli_connect_error());
        }
        else {
            if ($this->show_logs) print("Соединение установлено успешно<br/>");

            $sql = 'USE '.$this->db_name;
            if ($this->show_logs) echo "Check is DB exists;<br/>";
            $result = mysqli_query($link, $sql);
            if (!$result) {
                if ($this->show_logs) echo "DB not exists. Create it;<br/>";
                $sql = 'CREATE DATABASE '.$this->db_name;
                mysqli_query($link, $sql);
            }

            $link = mysqli_connect($this->host, $this->user, $this->password, $this->db_name);

            if ($link) {
                if ($this->show_logs) echo "Connected to DB. Check is table exists;<br/>";
                $sql = 'SELECT COUNT(*) `count` FROM '.$this->table;
                $result = mysqli_query($link, $sql);

                if (!$result) {
                    if ($this->show_logs) echo "Table not exists. Create it;<br/>";
                    $sql = '
                        create table tasks
                        (
                            `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
                            `author_name` varchar (50) COLLATE utf8mb4_unicode_ci NOT NULL,
                            `author_email` varchar (50) COLLATE utf8mb4_unicode_ci NOT NULL,
                            `text` text COLLATE utf8mb4_unicode_ci not null,
                            `status` tinyint(1) DEFAULT 0,
                            `edited` tinyint(1) DEFAULT 0,
                             PRIMARY KEY (`id`)
                        ) DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
                    ';
                    $result = mysqli_query($link, $sql);
                    if ($this->show_logs) echo "Create table result: <br/>";
                    var_dump($result);
                }
                else
                {
                    if ($this->show_logs) echo "Table exists.<br/>";
                    $row = mysqli_fetch_assoc($result);
                    if ($this->show_logs) echo "Table rows count: ".$row['count']."<br/>";
                }
            }
        }
    }
}

$db = new init_db();