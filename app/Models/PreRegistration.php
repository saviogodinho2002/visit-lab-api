<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
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
            "registered",
            "role_id",
            "user_id",
            "laboratory_id",

        ];
    protected $casts =[
     "registered"=>"bool"
    ];
}
