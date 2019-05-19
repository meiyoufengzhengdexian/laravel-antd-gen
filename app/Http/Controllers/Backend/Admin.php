<?php
/**
 * Created by PhpStorm.
 * User: x
 * Date: 19-5-10
 * Time: 下午10:32
 */

namespace App\Http\Controllers\Backend;
use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Foundation\Auth\Access\Authorizable;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Hash;
use Silber\Bouncer\Database\HasRolesAndAbilities;

class Admin extends Model  implements
    AuthenticatableContract,
    AuthorizableContract,
    CanResetPasswordContract
{
    use Authenticatable, Authorizable, CanResetPassword;
    use HasRolesAndAbilities;
    use Notifiable;

    protected $table = "admin";

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'phone', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function findForPassport($userName)
    {
        return $this->newQuery()->orWhere('name', $userName)->orWhere('phone', $userName)->orWhere('email', $userName)->first();
    }

    public function validateForPassportPasswordGrant($password)
    {
        return Hash::check($password, $this->getAuthPassword());
    }
}