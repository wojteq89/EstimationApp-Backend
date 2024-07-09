<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Estimation extends Model
{
    protected $fillable = ['name', 'description', 'project_id', 'client_id', 'date', 'type', 'amount'];

    protected static function boot()
    {
        parent::boot();

        // Zdarzenie po utworzeniu nowej wyceny
        static::created(function ($estimation) {
            $estimation->updateProjectEstimationSum();
        });

        // Zdarzenie po aktualizacji istniejącej wyceny
        static::updated(function ($estimation) {
            $estimation->updateProjectEstimationSum();
        });

        // Zdarzenie po usunięciu wyceny
        static::deleted(function ($estimation) {
            $estimation->updateProjectEstimationSum();
        });
    }

    // Metoda do aktualizacji sumy wycen w projekcie
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
