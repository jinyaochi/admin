<?php
/**
 * Created by PhpStorm.
 * User: 89340
 * Date: 2019/4/7
 * Time: 20:41
 */

namespace App\Models;

use App\Models\Gds\GdsZan;
use Illuminate\Database\Eloquent\Builder;

class Comment extends Model
{
    protected $fillable = [
        'content','user_id','parent_id','reply_id'
    ];
    /**
     * The "booting" method of the model.
     *
     * @return void
     */
    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope('sorts', function(Builder $builder) {
            $builder->orderBy('id', 'desc');
        });
    }

    public function model()
    {
        return $this->morphTo();
    }

    public function zan()
    {
        return $this->morphMany(GdsZan::class, 'model');
    }

    public function user(){
        return $this->belongsTo(User::class,'user_id');
    }

    public function son(){
        return $this->hasMany(Comment::class,'parent_id');
    }

    public function reply(){
        return $this->belongsTo(Comment::class,'reply_id');
    }
}
