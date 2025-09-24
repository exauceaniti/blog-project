<?php
function media_url($mediaPath)
{
    if (empty($mediaPath)) return '';
    // if mediaPath already contains assets/uploads/ or starts with /assets/uploads/
    $p = ltrim($mediaPath, '/');

    if (strpos($p, 'assets/uploads/') === 0) {
        return '/' . $p; // /assets/uploads/xxx
    }

    // otherwise assume it's filename only
    return '/assets/uploads/' . $p;
}
