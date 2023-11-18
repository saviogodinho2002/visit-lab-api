<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Visit extends Model
{
    use HasFactory;
    protected $fillable = [
        "laboratory_id",
        "user_id",
        "visitor_name",
        "visitor_document",
        "entry_at",
        "out_at"
    ];
    public function laboratory(){
        return $this->belongsTo(Laboratory::class);
    }
    public function scopeOwn(Builder $query): void
    {
        if(Auth::user()->hasRole(["professor"])){
            $query
                ->whereHas("laboratory",function (Builder $queryHas){
                    $queryHas->where("laboratories.user_id",'=', Auth::user()->id );
                });

        }elseif (Auth::user()->hasRole(["monitor"])){
            $query
                ->where("id",'=', Auth::user()->laboratory_id);

        }
    }
}
