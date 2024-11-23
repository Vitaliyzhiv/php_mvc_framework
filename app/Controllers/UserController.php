<?php

namespace App\Controllers;

use App\Models\User;
use PHPFramework\Pagination;
// use Illuminate\Database\Capsule\Manager as Capsule;

class UserController extends BaseController
{

    // функция которая возвращает страницу регистрации
    public function register()
    {
        // возвращаем вид с помощью функции helper view
        return view('user/register', [
            // передаем в вид данные для формы регистрации
            'title' => 'Register page'
        ]);
    }

    // Функция которая обрабатывает данные формы регистрации
    public function store()
    {
        // создаем экземпляр класса User, который является моделью
        $model = new User();
        // вызываем метод loadData из класса User
        $model->loadData();
        // проверяем прошла ли успешно валидация формы
        if (!$model->validate()) {
            // записываем по ключу все ошибки с помощью метода класса Session->setFlash
            // так же файл в папке incs должен называться alert_ + ключ который мы передали в при вызове 
            // метода setFlash
            session()->setFlash('error', 'Validation errors');
            // устанавливаем в сессию ошибки формы
            session()->set('form_errors', $model->getErrors());
            // устанавливаем данные формы в сессию
            session()->set('form_data', $model->attributes);
        } else {
            // хешируем пароль перед вставкой в таблицу
            $model->attributes['password'] = password_hash($model->attributes['password'], PASSWORD_DEFAULT);
            // сохраняем данные с помошью метода save библиотеки Illuminate
            // Проверяем статус сохранения данных
            if ($id = $model->save()) {
                session()->setFlash('success', 'Thank you for registration. Your id is ' . $id);
            } else {
                session()->setFlash('error', 'Error Registration');
            }
        }

        //  делаем редирект на нужную страницу
        response()->redirect('/register');
    }

    // загрузка вида логина
    public function login()
    {
        // возвращаем вид с помощью функции helper view
        return view('user/login', [
            // передаем в вид данные для формы регистрации
            'title' => 'Login page'
        ]);
    }

    // загрузка вида страницы users
    public function index()
    {

        //  считаем количество пользователей в бд
        $users_cnt = db()->query("select count(*) from users")->getColumn();
        $limit = PAGINATION_SETTINGS['perPage'];
        // создаем экземпляр класса Pagination  для пагинации
        $pagination = new Pagination($users_cnt);

        $users = db()->query(
            "select * from  users limit $limit offset {$pagination->getOffset()}"
        )->get();

        return view('user/index', [
            'title' => 'Users',
            'users' => $users,
            'pagination' => $pagination
        ]);
    }
}
