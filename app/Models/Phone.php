<?php

namespace App\Models;

use PHPFramework\Model;

class Phone extends Model
{

    // указываем, что модель должна использовать таблицу phones
    protected $table = 'phones';
    
    // указываем, что модель не должна использовать timestamps
    public $timestamps = false;

    
}
