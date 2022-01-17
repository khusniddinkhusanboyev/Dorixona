<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;


class Filial extends Model 
{
    use  HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    public $table = "filiallar";
    public $timestamps = false;
    protected $fillable = [
                'id',
                'nomi',
                'second_name', 
                'img',
                'img2',
                'img3',
                'img4',
                'lat',
                'lng',
                'time_create',
                'type',
                'parol',
                'login',
                'telefon',
                'tartib',
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
   
}
