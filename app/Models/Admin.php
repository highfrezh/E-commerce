<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Admin extends Authenticatable
{
    // use HasFactory;
    use Notifiable, HasFactory;

    protected $guard = 'admin';
    protected $fillable = [
        'name', 'type', 'mobile', 'email', 'password', 'image',
        'status', 'created_at', 'updated_at'
    ];
    protected $hidden = ['password', 'remember_token',];
}
