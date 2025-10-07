<?php
function media_url($path)
{
    if (empty($path)) {
        return null;
    }
    return strpos($path, 'assets/uploads/') === 0
        ? "/" . $path
        : "/assets/uploads/" . $path;
}
