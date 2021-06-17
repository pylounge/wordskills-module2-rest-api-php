<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Airport extends Model
{
    use HasFactory;

     // указываем имя таблицы в БД
     protected $table = 'airports';

     // необходимы для создания записи в базу через ModelName::create()
     protected $fillable = ['city', 'name','iata'];

     // поля которые не надо открыто показывать при отправке User в JSON формате
     protected $hidden = ['created_at', 'updated_at', 'id'];

     // связь 1 ко многим
     public function flights()
    {
        return $this->hasMany('Flight');
    }
}


