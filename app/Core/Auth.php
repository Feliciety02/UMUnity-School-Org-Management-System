<?php

declare(strict_types=1);

namespace App\Core;

final class Auth
{
    public static function user(): ?array
    {
        $userId = app()->session()->get('user_id');

        if (!$userId) {
            return null;
        }

        return get_user_by_id((int)$userId, app()->db());
    }

    public static function check(): bool
    {
        return self::user() !== null;
    }

    public static function role(): ?string
    {
        $role = app()->session()->get('role');
        return is_string($role) ? strtolower($role) : null;
    }

    public static function login(array $user): void
    {
        app()->session()->put('user_id', (int)$user['user_id']);
        app()->session()->put('role', strtolower((string)$user['role']));
        app()->session()->put('full_name', (string)$user['full_name']);
    }

    public static function logout(): void
    {
        app()->session()->invalidate();
    }

    public static function requireRole(array|string $roles): void
    {
        $roles = (array)$roles;
        $role = self::role();

        if (!$role || !in_array($role, $roles, true)) {
            header('Location: ' . url('/login'));
            exit;
        }
    }
}
