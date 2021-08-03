<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class IndexTerm extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'index_term';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['term', 'content'];
}
