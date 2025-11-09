<?php 

namespace Core\Http;


class Redirector
{
    public static function to(string $url): void
    {
        header("Location: $url");
        exit;
    }
}
