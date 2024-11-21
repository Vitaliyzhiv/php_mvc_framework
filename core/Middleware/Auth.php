<?php

namespace PHPFramework\Middleware;


class Auth {


    public function handle(): void {
        
        //  если проверка авторизации не пройдена устанавливаем flash message и перенаправляем на страницу 
        // логина
        if (!check_auth()) {
            session()->setFlash('error', "Пожалуйста авторизуйтесь");
            response()->redirect(base_url('/login'));
        }
    }
}