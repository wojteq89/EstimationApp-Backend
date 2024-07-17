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
            if ($estimation->isDirty('project_id')) {
                $oldProject = $estimation->getOriginal('project_id');
                $newProject = $estimation->project_id;
                
                if (!is_null($oldProject)) {
                    $oldProjectInstance = Project::find($oldProject);
                    if ($oldProjectInstance) {
                        $oldProjectInstance->updateEstimationSum();
                    }
                }
                
                $estimation->updateProjectEstimationSum();
            }
        });

        static::deleted(function ($estimation) {
            $estimation->updateProjectEstimationSum();
        });
    }

    public function updateProjectEstimationSum()
    {
        $project = $this->project;
        if ($project) {
            $project->updateEstimationSum();
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
