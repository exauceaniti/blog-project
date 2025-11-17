<?php
namespace Src\Core\Resolver;

use Src\Core\Lang\MessageBag;

class PageTitleResolver {
    public static function resolve(string $route): string {
        $cleanRoute = strtok($route, '?');
        $cleanRoute = rtrim($cleanRoute, '/');

        // Cherche dans MessageBag
        $title = MessageBag::get("titles.$cleanRoute");
        if (!str_starts_with($title, "Message inconnu")) {
            return $title;
        }

        // Routes dynamiques
        if (preg_match('#^/articles/(\d+)$#', $cleanRoute, $matches)) {
            return "Article #" . $matches[1];
        }

        if (preg_match('#^/user/profile/(\d+)$#', $cleanRoute, $matches)) {
            return "Profil utilisateur #" . $matches[1];
        }

        return "Mon Blog";
    }
}
