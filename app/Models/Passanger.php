<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Passanger extends Model
{
    use HasFactory;

    protected $table = 'passengers';

     // необходимы для создания записи в базу через ModelName::create()
     protected $fillable = ['booking_id', 'first_name','last_name', 'birth_date', 'document_number',
                            'place_from', 'place_back'];

     // поля которые не надо открыто показывать при отправке User в JSON формате
     protected $hidden = ['created_at', 'updated_at', 'booking_id'];

     // связь 1 ко многим
     public function booking()
    {
        return $this->hasMany(Booking::class, 'id', 'booking_id');
    }
}
