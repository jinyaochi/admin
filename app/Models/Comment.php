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
        'content','user_id'
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
}
