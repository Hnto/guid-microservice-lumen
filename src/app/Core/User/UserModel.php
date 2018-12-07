<?php

namespace App\Core\User;

use Illuminate\Auth\Authenticatable;
use Illuminate\Support\Collection;
use Laravel\Lumen\Auth\Authorizable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;

class UserModel extends Model implements AuthenticatableContract, AuthorizableContract
{
    use Authenticatable, Authorizable;

    protected $table = 'users';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'username', 'password'
    ];

    /**
     * @param $id
     * @return Collection|UserModel
     */
    public function user($id)
    {
        $user = UserModel::find($id);

        if (is_null($user)) {
            return new Collection([]);
        }

        return $user;
    }

    /**
     * @param $by
     * @param $value
     * @return UserModel
     */
    public function findBy($by, $value)
    {
        return UserModel::where($by, $value)
            ->first();
    }
}
