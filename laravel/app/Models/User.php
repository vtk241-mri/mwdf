<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Foundation\Auth\User as Authenticatable;

#[Fillable(['email', 'password', 'role'])]
#[Hidden(['password'])]
class User extends Authenticatable
{
    public const ROLE_CLIENT = 'client';
    public const ROLE_MANAGER = 'manager';
    public const ROLE_ADMIN = 'admin';

    /**
     * Рівні доступу: чим більше число, тим більше прав
     */
    public const ROLE_LEVELS = [
        self::ROLE_CLIENT  => 1,
        self::ROLE_MANAGER => 2,
        self::ROLE_ADMIN   => 3,
    ];

    public $timestamps = false;

    /**
     * Чи має користувач роль $role або вищу
     *
     * @param string $role
     * @return bool
     */
    public function hasRole(string $role): bool
    {
        return self::ROLE_LEVELS[$this->role] >= self::ROLE_LEVELS[$role];
    }

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'password' => 'hashed',
        ];
    }
}
