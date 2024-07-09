<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Estimation extends Model
{
    protected $fillable = ['name', 'description', 'project_id', 'client_id', 'date', 'type', 'amount'];

    protected static function boot()
    {
        parent::boot();

        static::created(function ($estimation) {
            $estimation->updateProjectEstimationSum();
        });

        static::updated(function ($estimation) {
            $estimation->updateProjectEstimationSum();
        });

        static::deleted(function ($estimation) {
            $estimation->updateProjectEstimationSum();
        });
    }

    public function updateProjectEstimationSum()
    {
        $project = $this->project;
        if ($project) {
            $project->update([
                'estimation' => $project->estimations()->sum('amount')
            ]);
        }
    }

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function client()
    {
        return $this->belongsTo(Client::class, 'client_id', 'id')->via('project');
    }
}
