<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class Company extends Model
{
    use Notifiable;
    protected $table = 'companies';
    protected $guarded = [];

    /**
     * Relates to many employees
     * @return Collection
     */
    public function employees()
    {
        return $this->belongsTo('App\Employee');
    }
}
