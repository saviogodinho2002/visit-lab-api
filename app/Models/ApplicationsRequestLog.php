<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ApplicationsRequestLog extends Model
{
    use HasFactory;
    protected $fillable = [
        'method',
        'path',
        'query_parameters',
        'headers',
        'ip',
        'user_agent',
        "application_id"
    ];
    protected $casts = [
        'query_parameters' => 'json',
        'headers' => 'json',
    ];
    public function application(){
        return $this->belongsTo(Application::class);
    }
}
