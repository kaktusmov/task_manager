<?php

namespace app;

class db {
    protected $host = 'localhost';
    protected $user = 'user';
    protected $password = 'password';
    protected $db_name = 'task_manager';
    protected $table = 'tasks';

    protected $show_logs = false;

    public function connect()
    {
        $link = mysqli_connect($this->host, $this->user, $this->password, $this->db_name);
        mysqli_set_charset($link,'utf8mb4');
        return $link;
    }

    public function insert($data)
    {
        foreach ($data as $k => $elem)
        {
            $data[$k] = "'".$elem."'";
        }
        $keys = implode(',',array_keys($data));
        $values = implode(',',$data);
        $sql = "INSERT INTO $this->table ($keys) VALUES ($values)";
        $connection = $this->connect();
        return mysqli_query($connection, $sql);
    }

    public function update($set_array,$where_input)
    {
        foreach ($set_array as $key=>$value)
            $set[] = $key." = '$value'";
        $set = implode(',',$set);

        if (is_array($where_input)) {
            foreach ($where_input as $key=>$value)
                $where[] = $key." = '$value'";
            $where = implode(' AND ',$where);
        }
        else
            $where = $where_input;

        $sql = "UPDATE $this->table SET $set WHERE $where";

        $connection = $this->connect();
        return mysqli_query($connection, $sql);
    }

    public function selectRowById($id)
    {
        $result = mysqli_query($this->connect(), "SELECT * FROM $this->table WHERE id = $id LIMIT 1");
        return mysqli_fetch_assoc($result);
    }

    public function selectRows($select = '*', $where = '', $order = '', $limit = '', $offset = '')
    {
        if (is_array($where)) {
            $where_arr = [];
            foreach ($where as $key=>$value)
                $where_arr[] = $key." = '$value'";
            $where = implode(' AND ',$where_arr);
        }

        if ($order !== '')
            $order = " ORDER BY ".$order;

        if ($where !== '')
            $where = " WHERE ".$where;

        if ($limit !== '')
            $limit = " LIMIT ".$limit;

        if ($offset !== '')
            $offset = ' OFFSET '.$offset;

        $query = "SELECT $select FROM $this->table $where $order $limit $offset";

        $result = mysqli_query($this->connect(), $query);

        return mysqli_fetch_all($result, MYSQLI_ASSOC);
    }

    public function countRows()
    {
        $query = "SELECT COUNT(*) FROM $this->table";

        $result = mysqli_query($this->connect(), $query);

        $data = mysqli_fetch_row($result);
        return $data[0];
    }
}