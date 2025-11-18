<?php 

namespace Core\Resolver;

class LayoutResolver
{
    public static function resolve(?string $role): string
    {
        return match ($role) {
            'admin' => 'admin',
            'user' => 'user',
            default => 'public',
        };
    }
}
