<?php
namespace Src\Core\Resolver;

use Src\Core\Render\Fragment;

class InjectResolver {
    public static function injectAll(array $params = []): void {
        Fragment::meta('$title');
        Fragment::header($params);
        Fragment::nav($params);
        Fragment::footer($params);
    }
}
