<?php

namespace App\Models;

use PHPFramework\Model;

class User extends Model
{

    // заполняем поля $fillable и вставляем поля которые мы ожидаем из формы
    protected array $fillable = ['name', 'email', 'password', 'confirmPassword'];

    // обьявляем правила
    protected array $rules = [
        'required' => ['name', 'email', 'password', 'confirmPassword'],
        'email' => ['email'],
        'lengthMin' => [
            ['password', 6]
        ],
        'equals' => [
            ['password', 'confirmPassword']
        ]
    ];

    // переводы полей на русский язык
    protected array $labels = [
        'name' => 'Имя',
        'email' => 'E-mail',
        'password' => 'Пароль',
        'confirmPassword' => 'Подтверждение пароля'
    ];
}
