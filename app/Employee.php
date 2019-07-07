<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    protected $table = 'employees';
    protected $fillable = [
        'first_name',
        'last_name',
        'company_id',
        'email',
        'phone',
    ];

    /**
     * Relates to single company
     * @return \App\Company
     */
    public function company()
    {
        return $this->belongsTo('App\Company');
    }
}
