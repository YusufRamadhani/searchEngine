<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ImportantWord extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'important_word';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['word', 'main_word', 'is_usage'];

    /**
     * The model's default values for attributes.
     *
     * @var array
     */
    protected $attributes = [
        'is_usage' => 0
    ];

    public $timestamps = false;
}
