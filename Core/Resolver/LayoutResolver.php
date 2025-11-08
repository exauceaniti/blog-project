<?php 

namespace Core\Resolver;

class LayoutResolver
{
    public static function resolve(?string $role): string
    {
        return match ($role) {
            'admin' => 'admin-layout',
            'user' => 'user-layout',
            default => 'public-layout',
        };
    }
}
