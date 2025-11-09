<?php

namespace Core\Session;

class UserContext
{
    public static function name(): string
    {
        return $_SESSION['user']['name'] ?? 'Invité';
    }

    public static function role(): string
    {
        return $_SESSION['user']['role'] ?? 'visiteur';
    }

   
    // public static function avatar(): ?string
    // {
    //     return $_SESSION['user']['avatar'] ?? null;
    // }

    public static function id(): ?int
    {
        return $_SESSION['user']['id'] ?? null;
    }
}
