<?php

namespace app\models;

use app\db;

class Task extends db
{
    public $select_fields = ['author_name', 'author_email', 'text', 'status', 'id', 'edited'];
    public $sortable_fields = ['author_name', 'author_email', 'status', 'id'];

    public function getList($order, $limit, $offset, $is_authorized = false)
    {
        $count = $this->countRows();

        $data = $this->selectRows(
            implode(',',$this->select_fields),
            '',
            ($order!=''?$order:'id desc'),
            $limit,
            $offset
        );

        foreach ($data as $k => $row) {
            if ($is_authorized)
                $edit_link = " <a href='task?id=".$row['id']."'>Edit</a>";
            else
                $edit_link = '';

            if ($row['edited'])
                $edited = '<div class="edited">Отредактировано администратором</div>';
            else
                $edited = '';

            $data[$k]['status'] = ($data[$k]['status'] == 0 ? 'Новая' : 'Выполнена').$edit_link.$edited;
        }

        return [
            'data' => $data,
            'recordsTotal' => $count,
            'recordsFiltered' => $count,
        ];
    }

    public function createTask($author_name, $author_email, $text)
    {
        $this->insert([
            'author_name' => $author_name,
            'author_email' => $author_email,
            'text' => $text
        ]);
    }
}