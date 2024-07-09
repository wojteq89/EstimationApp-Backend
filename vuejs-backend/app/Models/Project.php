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

    public function updateEstimationSum()
    {
        $this->estimation = $this->estimations()->sum('amount');
        $this->save();
    }

    protected static function boot()
    {
        parent::boot();
        static::updated(function ($project) {
            if ($project->isDirty('client_id')) {
                $project->estimations()->update(['client_id' => $project->client_id]);
            }
        });
    }
}
