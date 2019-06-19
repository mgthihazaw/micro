<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ItemType extends Model
{
    protected $fillable = ['name'];

    public function attributes(){
    	return $this->belongsToMany('App\Attribute');
    }

    public function items(){
    	return $this->hasMany('App\Item');
    }
}
