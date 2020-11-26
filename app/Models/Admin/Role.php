<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\Permission\Models\Role as BaseRole;

class Role extends BaseRole
{
    use HasFactory;

    const ROLE_USER = 'user';
    const ROLE_ADMIN = 'admin';

    /**
     * Массив ролей, которые запрещено удалять
     *
     * @return array
     *
     * @author Виталий Москвин <foreach@mail.ru>
     */
    public static function guardRoles()
    {
        return [
            static::ROLE_USER,
            static::ROLE_ADMIN,
        ];
    }

    public function getAssignedPermissionsIds()
    {
        $out = [];

        $permissions = $this->permissions;

        foreach ($permissions as $permission) {
            $out[] = $permission->id;
        }

        return $out;
    }
}
