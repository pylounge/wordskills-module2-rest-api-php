<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Flight extends Model
{
    use HasFactory;

    // максимальное количество мест для рейса
    const MAX_NUMBER_SEATS = 156;

    // указываем имя таблицы в БД
    protected $table = 'flights';

    // поля которые не надо открыто показывать при отправке User в JSON формате
    protected $hidden = ['created_at', 'updated_at'];

    // задаём связь 1 ко многим с таблицей аэропорты
    // т.к. один аэропорт может содержать множество рейсов
    public function airport_from()
    {
        return $this->belongsTo(Airport::class, 'from_id', 'id');
    }

    public function airport_to()
    {
        return $this->belongsTo(Airport::class, 'to_id', 'id');
    }

    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }

    public function countAvaibleSeat($flightFromDate, $from=true)
    {
        //print_r([$flightFromId, $flightFromDate, $flightBackId, $flightBackDate]);

        if ($from === true)
        {
            $bookedFromFlights = Booking::where('flight_from', $this->id)
            ->where('date_from', $flightFromDate)->get();
        }
        else
        {
            $bookedFromFlights = Booking::where('flight_back', $this->id)
            ->where('date_back', $flightFromDate)->get();
        }

        $avaibleSeat = 0;
        foreach ($bookedFromFlights as $bookedFlight) {
            $bookedRecords = Passanger::where('booking_id', $bookedFlight->id)->count();
            $avaibleSeat += $bookedRecords;
        }

        return Flight::MAX_NUMBER_SEATS - $avaibleSeat;
    }

}
