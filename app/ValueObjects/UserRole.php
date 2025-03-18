<?php

namespace App\ValueObjects;

use App\ValueObjects\Concerns\Arrayable;
use App\ValueObjects\Concerns\WithLabels;

enum UserRole: string
{
    use Arrayable;
    use WithLabels;

    case ADMIN = 'admin';
    case USER = 'user';

    public function label(): string
    {
        return __("value_objects.user_role.{$this->value}");
    }
}
