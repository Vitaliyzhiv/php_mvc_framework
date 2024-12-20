<?php

namespace App\Models;

use PHPFramework\Model;


class User extends Model
{

    // указываем, что модель должна использовать таблицу users
    protected string $table = 'users';
    
    // указываем, что модель не должна использовать timestamps
    public bool $timestamps = false;

    // указываем даннные которые модель должна принять в $loaded 
    protected array $loaded = ['name', 'email', 'password', 'confirmPassword'];

    // в $fillable указываем поля которые мы хотим сохранить в бд
    protected array $fillable = ['name', 'email', 'password'];

    // обьявляем правила
    protected array $rules = [
        'required' => ['name', 'email', 'password', 'confirmPassword'],
        'email' => ['email'],
        'lengthMin' => [
            ['password', 6]
        ],
        'lengthMax' => [
            ['name', 120],
            ['email', 120],
            ['password', 255],
            ['confirmPassword', 255]
        ],
        'equals' => [
            ['password', 'confirmPassword']
        ],
        'unique' => [
            ['email', 'Users,email']
        ],
    ];

    // переводы полей на русский язык
    protected array $labels = [
        'name' => 'Имя',
        'email' => 'E-mail',
        'password' => 'Пароль',
        'confirmPassword' => 'Подтверждение пароля'
    ];


}
