<?php

namespace PHPFramework;

class Response
{

    public function setResponseCode(int $code): void
    {
        http_response_code($code);
    }

    public function redirect($url = '')
    {
        if ($url) {
            $redirect = $url;
        } else {
            // если не был передан путь для редирект то используем редирект на referer (то откуда пришел user)
            $redirect = $_SERVER['HTTP_REFERER'] ?? base_url('/');
        }

        // делаем редирект
        header('Location: '. $redirect);
        die;
    }

}