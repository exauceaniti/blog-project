<?php
namespace Src\Core\Render;


class Fragment
{
    protected static string $basePath = __DIR__ . '/../../../templates/includes/';

    public static function meta(string $title = 'Mon Blog', array $params = []): void
    {
        extract($params);
        include self::$basePath . 'meta.php';
    }


    public static function header(array $params = []): void
    {
        extract($params);
        include self::$basePath . 'header.php';
    }

    public static function nav(array $params = []): void
    {
        extract($params);
        include self::$basePath . 'nav.php';
    }

    public static function footer(array $params = []): void
    {
        extract($params);
        include self::$basePath . 'footer.php';
    }

    public static function component(string $name, array $params = []): void
    {
        extract($params);
        $path = __DIR__ . '/../Components/' . ucfirst($name) . '.php';

        if (file_exists($path)) {
            include $path;
        } else {
            throw new \Exception("Le composant '$name' n'existe pas.");
        }
    }
}
