<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Laravel\Passport\HasApiTokens;
use App\Notifications\ResetPassword;

class User extends Authenticatable
{
    use HasApiTokens, SoftDeletes, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'image',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function sendPasswordResetNotification($token)
    {
        $this->notify(new ResetPassword($token));
    }

    public function setPasswordAttribute($value)
    {
        $this->attributes['password'] = bcrypt($value);
    }

    public function roles()
    {
        return $this->belongsToMany(Role::class);
    }

    public function financials()
    {
        return $this->hasMany(Financial::class, 'usuario_id');
    }

    public function budgets()
    {
        return $this->hasMany(Budget::class, 'usuario_id');
    }

    public function sales()
    {
        return $this->hasMany(Sale::class, 'usuario_id');
    }

    public function addRole($role)
    {
        if (!empty($role)) {
            if (is_string($role)) {
                return !$this->roles()->where('nome', $role)->first() ? $this->roles()->save(
                    Role::where('nome', '=', $role)->firstOrFail()
                ) : false;
            }
            return !$this->roles()->where('nome', $role->nome)->first() ? $this->roles()->save(
                Role::where('nome', '=', $role->nome)->firstOrFail()
            ) : false;
        }
        return false;
    }

    public function removeRole($role)
    {
        if (is_string($role)) {
            return $this->roles()->detach(
                Role::where('nome', '=', $role)->firstOrFail()
            );
        }
        return $this->roles()->detach(
            Role::where('nome', '=', $role->nome)->firstOrFail()
        );
    }

    public function existsRole($role)
    {
        if (is_string($role)) {
            return $this->roles->contains('nome', $role);
        }
        return $role->intersect($this->roles)->count();
    }

    public function existsAdmin()
    {
        return $this->existsRole('admin');
    }

    public function getRole()
    {
        return $this->roles()->first();
    }

    public function getWithSearchAndPagination($search, $paginate, $restore = false)
    {

        $paginate = $paginate ?? 10;

        $queryBuilder = self::where('name', 'like', '%' . $search . '%');

        if ($restore)
            $queryBuilder = $queryBuilder->onlyTrashed();

        return $queryBuilder->paginate($paginate);
    }

    public function findUserById($id)
    {

        return self::find($id);

    }

    public function restoreUserById($id)
    {

        $user = self::onlyTrashed()->find($id);

        return $user ? $user->restore() : false;
    }

    public function createUser(array $input)
    {

        return self::create($input);

    }

    public function updateUser(array $input)
    {

        return self::update($input);

    }

    public function deleteUser()
    {

        return self::delete();

    }

}
