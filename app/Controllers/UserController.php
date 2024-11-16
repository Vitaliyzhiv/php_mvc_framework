<?php

namespace App\Controllers;

use App\Models\User;

class UserController extends BaseController {

    // функция которая возвращает страницу регистрации
    public function register() {
        // возвращаем вид с помощью функции helper view
        return view('user/register', [
            // передаем в вид данные для формы регистрации
            'title' => 'Register page' 
        ]);

    }

    // Функция которая обрабатывает данные формы регистрации
    public function store() {
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
            // session()->setFlash('info', ('Info Message...'));
            session()->setFlash('success', 'Successfully validation');
        }

        //  делаем редирект на нужную страницу
        response()->redirect('/register');
        // dump($model->attributes);
        // dump($model->validate());
        // dump($model->getErrors());
        // // получаем данные из формы
        // dd($_POST);
    }

    // Страница которая возвращает функцию логина
    public function login() {
         // возвращаем вид с помощью функции helper view
         return view('user/login', [
            // передаем в вид данные для формы регистрации
            'title' => 'Login page' 
        ]);
    }



}