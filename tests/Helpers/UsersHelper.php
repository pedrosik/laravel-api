<?php

declare(strict_types=1);

namespace Tests\Helpers;

use App\Models\User;
use App\ValueObjects\UserPermission;
use App\ValueObjects\UserRole;

class UsersHelper
{
    public static function createAdmin(): User
    {
        $user = User::factory()->create();

        $user->syncRoles([UserRole::ADMIN->value]);

        return $user;
    }

    public static function createWithPermission(UserPermission $permission): User
    {
        return self::createWithPermissions([$permission]);
    }

    public static function createWithPermissions(array $permissions): User
    {
        /** @var User $user */
        $user = User::factory()->create();

        $permissions = collect($permissions)->map(fn (UserPermission $permission) => $permission->value);

        $user->syncPermissions($permissions);

        return $user;
    }
}
