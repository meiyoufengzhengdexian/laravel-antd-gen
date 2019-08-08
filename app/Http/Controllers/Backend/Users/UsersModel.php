<?php
namespace App\Http\Controllers\Backend\Users;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Http\Controllers\Backend\BackendModel;
use Silber\Bouncer\Database\HasRolesAndAbilities;


class UsersModel extends BackendModel
{
    use HasRolesAndAbilities;
    protected $table = 'users';
    protected $hidden = ['password', 'remember_token'];
}
