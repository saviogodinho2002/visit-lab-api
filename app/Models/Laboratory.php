<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Laboratory extends Model
{
    use HasFactory;
    protected $fillable =
        [
            "name",
            "local",
            "user_id"
        ];
    public function coordinator(){
        $this->belongsTo(User::class);
    }
}
