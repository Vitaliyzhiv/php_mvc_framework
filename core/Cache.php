<?php

namespace PHPFramework;

class Cache
{


    // метод для добавления данных в кеш
    public function set($key, $data, $seconds = 3600): void
    {
        // формируем массив для хранения данных и времени истечения
        $content['data'] = $data;
        $content['expiration'] = time() + $seconds;

        // определяем путь к файлу кеша
        $cache_file = CACHE . '/' . md5($key) . '.txt';
        // сохраняем данные в файл 
        // serialize() преобразует массив в строку, чтобы его можно сохранить в файле
        // так как хранить массив в файле плохая идея
        // file_put_contents() записывает данные в файл
        file_put_contents($cache_file, serialize($content));
    }
    // метод получения данных из кеша
    public function get($key, $default = null)
    {
        // определяем путь к файлу кеша
        $cache_file = CACHE . '/' . md5($key) . '.txt';

        // если файл существует 
        if (file_exists($cache_file)) {
            // десериализуем данные из файла
            $content = unserialize(file_get_contents($cache_file));
            // проверяем актуальность кеша
            if (time() <= $content['expiration']) {
                // возвращаем данные из кеша
                return $content['data'];
            }

            // если кеш недействителен, удаляем его
            // знак @ используется для подавления ошибок при вызове функции unlink.
            // Это необходимо, чтобы избежать появления предупреждений или ошибок в случае,
            // если файл кеша отсутствует или недоступен для удаления.
            @unlink($cache_file);
        }

        // если кеш недействителен или не существует, возвращаем значение по умолчанию
        return $default;
    }
    // метод для удаления данных из кеша
    public function remove($key) {
        // определяем путь к файлу кеша
        $cache_file = CACHE. '/'. md5($key). '.txt';
        // удаляем файл
        if (file_exists($cache_file)) {
            @unlink($cache_file);
        }
    }
}
