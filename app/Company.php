<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class Company extends Model
{
    use Notifiable;

    protected $table = 'companies';
    protected $guarded = [];

    // public function getLogoAttribute()
    // {
    //     return $this->getOriginal('logo')->path();
    // }
}
