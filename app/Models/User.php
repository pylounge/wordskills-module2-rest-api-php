<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    use HasFactory;

    // указываем имя таблицы в БД
    protected $table = 'users';

    // необходимы для создания записи в базу через ModelName::create()
    protected $fillable = ['first_name', 'last_name','phone',
                            'password', 'document_number'];

    // поля которые не надо открыто показывать при отправке User в JSON формате
    protected $hidden = ['password', 'api_token'];
}
