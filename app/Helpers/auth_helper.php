<?php
if (!function_exists('user')) {
    function user(): ?array
    {
        return service('auth')->user();
    }
}
if (!function_exists('can')) {
    function can(string $permission): bool
    {
        return service('auth')->can($permission);
    }
}
if (!function_exists('has_role')) {
    function has_role(string $roleName): bool
    {
        return service('auth')->hasRole($roleName);
    }
}
if (!function_exists('has_any_role')) {
    function has_any_role(array $roleNames): bool
    {
        return service('auth')->hasAnyRole($roleNames);
    }
}
