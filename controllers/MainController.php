<?php

namespace app\controllers;

use app\models\Task;

class MainController extends Controller
{
    public function index()
    {
        $this->render('main');
    }

    public function viewTasks()
    {
        $task = new Task();

        $offset = intval($_POST['start'] ?? 0);

        $limit = intval($_POST['length'] ?? 0);

        $order = '';

        try {
            $order_column = $_POST['order'][0]['column'];
            $order_direction = $_POST['order'][0]['dir'];

            $order_column = $_POST['columns'][$order_column]['data'];

            if (
                in_array($order_column, $task->sortable_fields) &&
                in_array($order_direction, ['asc', 'desc'])
            ) {
                $order = $order_column.' '.$order_direction;
            }
        } catch (\Exception $e) {

        }

        echo json_encode($task->getList($order, $limit, $offset, $this->isAuthorized()));
    }

    public function createTask()
    {
        $author_name = strip_tags($_POST['author_name']);
        $author_email = strip_tags($_POST['author_email']);
        $text = strip_tags($_POST['text']);

        $task = new Task();
        $result = $task->insert([
            'author_name' => $author_name,
            'author_email' => $author_email,
            'text' => $text
        ]);

        if ($result)
            $_SESSION['success'] = 'Задача успешно создана';
        else
            $_SESSION['error'] = 'Ошибка';

        return $this->goHome();
    }

    public function login()
    {
        if (count($_POST)) {
            if (!isset($_POST['login']) || !isset($_POST['password']))
                return $this->goHome();

            if ($_POST['login'] != 'admin' || $_POST['password'] != '123')
                $_SESSION['error'] = 'Неправильные реквизиты доступа';
            else {
                $_SESSION['authorized'] = true;
                return $this->goHome();
            }
        }

        $this->render('login');
    }

    public function logout()
    {
        if (isset($_SESSION['authorized']))
            unset($_SESSION['authorized']);

        return $this->goHome();
    }

    public function edit()
    {
        if (!$this->isAuthorized())
            return $this->redirect('login');

        $id = intval($_GET['id']);

        if ($id > 0) {
            $task_model = new Task();
            $task = $task_model->selectRowById($id);
            if ($task) {
                if (count($_POST)) {
                    $text = strip_tags($_POST['text']);
                    $status = intval($_POST['status']);

                    $task_model->update([
                        'text' => $text,
                        'edited' => intval($task['edited'] || $task['text']!=$text),
                        'status' => $status
                    ],['id'=>$id]);

                    $_SESSION['success'] = 'Задача успешно отредактирована';
                }
                else {
                    $this->render('edit', [
                        'id' => $id,
                        'author_name' => $task['author_name'],
                        'author_email' => $task['author_email'],
                        'text' => $task['text'],
                        'status' => $task['status']
                    ]);
                    return true;
                }
            }
        }

        return $this->goHome();
    }
}