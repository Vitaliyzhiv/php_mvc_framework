<?php

namespace PHPFramework;

class Session {

    // конструктор класса инициализирует начало сессии 
    public function __construct() {
        session_start();
    }

    // функция для записи данных в сессию
    public function set($key, $value) {
        $_SESSION[$key] = $value;
    }

    // функция для чтения данных из сессии
    public function get($key, $default = null) {
        return $_SESSION[$key] ?? $default;
    }

    // проверка есть ли ключ в сессии
    public function has($key) {
        return isset($_SESSION[$key]);
    }

    // функция для удаления данных из сессии 
    public function remove($key) {
        if ($this->has($key)) {
            unset($_SESSION[$key]);
        }
    }


    // функция для  установки флеш (одноразовых) сообщений для сессии
    public function setFlash($key, $value) {
        $_SESSION['flash'][$key] = $value;
    }

    // функция для получения флеш сообщений
    public function getFlash($key, $default = null) {
        if (isset($_SESSION['flash'][$key])) {
            $value = $_SESSION['flash'][$key];
            unset($_SESSION['flash'][$key]);
        }
        return $value ?? $default;
    }

}
