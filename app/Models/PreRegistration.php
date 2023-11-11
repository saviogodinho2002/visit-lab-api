<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

/**
 *
 *
 * @mixin Builder
 */
class PreRegistration extends Model
{
    use HasFactory;
    protected $fillable =
        [
            "login",
            "email",
            "status",
            "role_id",
            "user_id",
            "laboratory_id",

        ];
    public function laboratory(){
        return $this->belongsTo(Laboratory::class);
    }

    /*
     * @scope
     */
    public function scopeOwn(Builder $query): void
    {
        if(Auth::user()->hasRole(["professor"])){
            $query
                ->where("user_id",'=', Auth::user()->id);

        }
    }

}
