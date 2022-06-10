<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Auth extends Model
{
    protected $table        = "auths";
    protected $primaryKey   = "auth_id";
    protected $fillable     = ['auth_nik', 'auth_role', 'auth_password', 'created_at', 'updated_at'];
}