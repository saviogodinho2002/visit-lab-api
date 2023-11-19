<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

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
        return $this->belongsTo(User::class,"user_id");
    }
    public function monitors(){
        return $this->hasMany(User::class);
    }
    public function scopeOwn(Builder $query): void
    {
        if(Auth::user()->hasRole(["professor"])){
            $query
                ->where("user_id",'=', Auth::user()->id);
        }
    }
}
