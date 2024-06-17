<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    protected $fillable = ['name', 'description', 'client_id', 'estimation'];

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function estimations()
    {
        return $this->hasMany(Estimation::class);
    }

    public function calculateEstimation()
    {
        return $this->estimations()->sum('amount');
    }
}
