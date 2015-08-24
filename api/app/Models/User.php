<?php namespace App\Models;
use Hash;
use Illuminate\Auth\Authenticatable;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;

use Zizaco\Entrust\Traits\EntrustUserTrait;

class User extends BaseModel implements AuthenticatableContract, CanResetPasswordContract
{
    use TimestampsFormatTrait;
    use EntrustUserTrait;

    use Authenticatable, CanResetPassword;

    protected $table = 'user';
    protected $fillable = ['lastname', 'firstname', 'email', 'password', 'active'];
    protected $hidden = ['password', 'remember_token'];
    public $timestamps = true;

    public function setPasswordAttribute($pass){
        $this->attributes['password'] = Hash::make($pass);
    }
}
