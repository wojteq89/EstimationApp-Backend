<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Estimation extends Model
{
    protected $fillable = ['name', 'description', 'project_id', 'client_id', 'date', 'type', 'amount'];

    public function project()
    {
        return $this->belongsTo(Project::class);
    }
}
