<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    use HasFactory;

    // указываем имя таблицы в БД
    protected $table = 'bookings';

    // необходимы для создания записи в базу через ModelName::create()
    protected $fillable = ['flight_from', 'flight_back', 'date_from', 'date_back', 'code'];

    protected $hidden = ['created_at', 'updated_at', 'id', 'flight_from', 'flight_back', 'date_from', 'date_back'];

    public function flight_f()
   {
       return $this->hasOne('Flight');
   }

   public function flight_b()
   {
       return $this->hasOne('Flight');
   }
}
