<?php
/**
 * /login -> /new_signin permanent redirect.
 *
 * NewSignin is the canonical sign-in page (better UX, real password
 * validation, supports email-code passwordless login). The old /login
 * page used a minimal form that didn't even validate passwords end-to-end.
 *
 * Kept as a 301 redirect rather than deleting the route so external
 * bookmarks, email links, and third-party integrations don't 404.
 * After 6-12 months of clean logs, safe to remove this route entirely.
 *
 * Preserves the ?next= query param so post-login redirects still work.
 */

$next = isset($_GET['next']) ? (string) $_GET['next'] : '';
$target = '/new_signin';
if ($next !== '') {
    // Only allow same-origin relative paths in `next` — guard against
    // open-redirect by refusing protocol-relative or absolute URLs.
    if ($next[0] === '/' && (strlen($next) < 2 || $next[1] !== '/')) {
        $target .= '?next=' . urlencode($next);
    }
}

header('Location: ' . $target, true, 301);
exit;
