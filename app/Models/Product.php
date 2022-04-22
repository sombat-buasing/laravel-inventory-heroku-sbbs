<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;


    protected $fillable = [
        'name',
        'slug',
        'description',
        'price',
        'image',
        'user_id'
    ];

    public function users(){
        // Select *
        // From Products p
        // Inner join users u on p.user_id =  u.id
        return $this->belongsTo('App\Models\User', 'user_id')->select(['id', 'fullname', 'avatar']);  
    }
    
}

// Relationship to Users
