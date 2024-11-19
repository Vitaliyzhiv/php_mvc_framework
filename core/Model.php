<?php

namespace PHPFramework;

use \Illuminate\Database\Eloquent\Model as EloquentModel;
use Valitron\Validator;

abstract class Model extends EloquentModel
{

    // массив для хранения данных из формы 
    protected array $loaded = [];
    // создаем изначально пустой массив для того какие именно поля из формы мы хотим сохранять в бд
    protected  $fillable = [];
    // создаем массив атрибутов, которые мы будем принимать на основе $fillable
    public  $attributes = [];
    // создаем защищенный массив для правил
    protected array $rules = [];

    // массив для сбора ошибок
    protected array $errors = [];

    // массив для перевода названий полей
    protected array $labels = [];


    // переопределение метода save из класса EloquentModel
    public function save(array $options = [])
    {

        // проходимся циклом по атрибутам 
        foreach ($this->attributes as $key => $value) {
            // проверяем существует ли такое поле в $fillable
            if (!in_array($key, $this->fillable)) {
                // если нет, удаляем его из массива $attributes
                unset($this->attributes[$key]);
            }
        }
        return parent::save($options);
    }

    public function loadData()
    {
        // используя функцию helper request и ее метод getData получаем данные
        $data =  request()->getData();
        // проходимся циклом по массиву $fillable и отбираем только нужные данные
        foreach ($this->loaded as $field) {
            // проверяем существует ли такой ключ в массиве $data
            if (isset($data[$field])) {
                // если существует, добавляем его в массив $attributes
                $this->attributes[$field] = $data[$field];
            } else {
                $this->attributes[$field] = '';
            }
        }
    }

    // создаем функцию для валидации 
    public function validate($data = [], $rules = [], $labels = []): bool
    {
        // проверяем передавались ли данные
        if (!$data) {
            // возвращаем аттрибуты модели
            $data = $this->attributes;
        }
        // проверяем передавались ли правила
        if (!$rules) {
            $rules = $this->rules;
        }
        // проверяем передавались ли поля для изменения названий
        if (!$labels) {
            $labels = $this->labels;
        }
        // указываем папку для языковых файлов, в нашем случае это папка lang в корне проекта
        Validator::langDir(WWW . '/lang');
        // указываем язык валидатору
        Validator::lang('ru');
        // создаем экземпляр класса валидатор из библиотеки  valitron
        $validator = new Validator($data);
        // добавляем правила валидации к валидатору
        $validator->rules($rules);
        // добавляем поля для изменения названий в валидатору
        $validator->labels($labels);
        // если валидатор прошел проверку
        if ($validator->validate()) {
            return true;
        } else {
            // записываем ошибки 
            $this->errors = $validator->errors();
            return false;
        }
    }

    // создаем функцию для получения ошибок
    public function getErrors(): array
    {
        return $this->errors;
    }
}
