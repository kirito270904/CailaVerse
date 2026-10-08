<?php
function e($value): string
{
    return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
}

function url(string $c = 'post', string $a = 'feed', array $params = []): string
{
    return 'index.php?' . http_build_query(array_merge(['c' => $c, 'a' => $a], $params));
}

function current_url(): string
{
    $q = $_SERVER['QUERY_STRING'] ?? '';
    return 'index.php' . ($q !== '' ? '?' . $q : '');
}

function redirect(string $to): void
{
    header('Location: ' . $to);
    exit;
}

function redirect_back(string $fallback): void
{
    $back = $_POST['back'] ?? '';
    if (is_string($back) && preg_match('/^index\.php(\?[A-Za-z0-9_=&%.+\-]*)?$/', $back)) {
        redirect($back);
    }
    redirect($fallback);
}

function flash(string $type, string $message): void
{
    $_SESSION['flash'] = ['type' => $type, 'message' => $message];
}

function get_flash(): ?array
{
    $f = $_SESSION['flash'] ?? null;
    unset($_SESSION['flash']);
    return $f;
}

function csrf_token(): string
{
    if (empty($_SESSION['csrf'])) {
        $_SESSION['csrf'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf'];
}

function csrf_field(): string
{
    return '<input type="hidden" name="csrf" value="' . e(csrf_token()) . '">'
        . '<input type="hidden" name="back" value="' . e(current_url()) . '">';
}

function csrf_check(): void
{
    $sent = $_POST['csrf'] ?? '';
    if (!is_string($sent) || !hash_equals($_SESSION['csrf'] ?? '', $sent)) {
        if (is_ajax()) {
            json_out(['ok' => false, 'message' => 'Session expired. Please refresh the page.'], 400);
        }
        http_response_code(400);
        exit('Invalid request token.');
    }
}

function is_ajax(): bool
{
    return ($_SERVER['HTTP_X_REQUESTED_WITH'] ?? '') === 'XMLHttpRequest';
}

function json_out(array $data, int $status = 200): void
{
    http_response_code($status);
    header('Content-Type: application/json; charset=UTF-8');
    echo json_encode($data);
    exit;
}

function clean($value): string
{
    return trim(strip_tags((string) $value));
}

function format_date(string $datetime): string
{
    return date('M j, Y g:i A', strtotime($datetime));
}

function time_ago(string $datetime): string
{
    $diff = time() - strtotime($datetime);
    if ($diff < 60) {
        return 'Just now';
    }
    if ($diff < 3600) {
        return floor($diff / 60) . 'm ago';
    }
    if ($diff < 86400) {
        return floor($diff / 3600) . 'h ago';
    }
    if ($diff < 604800) {
        return floor($diff / 86400) . 'd ago';
    }
    return date('M j, Y', strtotime($datetime));
}

function is_user_online(?string $lastActive): bool
{
    if (!$lastActive) {
        return false;
    }
    return (time() - strtotime($lastActive)) <= 300;
}

function avatar(array $user, int $size = 40, bool $showStatus = false): string
{
    $style = 'width:' . $size . 'px;height:' . $size . 'px;font-size:' . round($size * 0.42) . 'px;';
    $class = 'avatar' . ($size >= 44 ? ' avatar-ring' : '');
    $imgFile = $user['profile_image'] ?? '';
    $imgPath = $imgFile !== '' ? (__DIR__ . '/../public/uploads/' . basename($imgFile)) : '';

    $renderedAvatar = '';
    if ($imgFile !== '' && is_file($imgPath)) {
        $renderedAvatar = '<img src="uploads/' . e($imgFile) . '" class="' . $class . '" style="' . $style . '" alt="' . e($user['full_name'] ?? 'User') . '" loading="lazy" onerror="this.onerror=null;this.replaceWith(this.nextElementSibling);">';
        $name = $user['full_name'] ?? $user['username'] ?? '?';
        $initial = strtoupper(mb_substr($name, 0, 1));
        $renderedAvatar .= '<span class="' . $class . ' avatar-text d-none" style="' . $style . '">' . e($initial) . '</span>';
    } else {
        $name = $user['full_name'] ?? $user['username'] ?? '?';
        $initial = strtoupper(mb_substr($name, 0, 1));
        $renderedAvatar = '<span class="' . $class . ' avatar-text" style="' . $style . '">' . e($initial) . '</span>';
    }

    if ($showStatus) {
        $badgeSize = max(10, round($size * 0.26));
        $isOnline = isset($user['last_active']) ? is_user_online($user['last_active']) : false;
        $dotClass = $isOnline ? 'avatar-online-dot' : 'avatar-offline-dot';
        $title = $isOnline ? 'Online' : 'Offline';
        return '<span class="avatar-status-wrapper d-inline-block position-relative">'
            . $renderedAvatar
            . '<span class="' . $dotClass . '" style="width:' . $badgeSize . 'px;height:' . $badgeSize . 'px;" title="' . $title . '"></span>'
            . '</span>';
    }

    return $renderedAvatar;
}

function upload_image(array $file, ?string &$error): ?string
{
    if (!isset($file['error']) || $file['error'] === UPLOAD_ERR_NO_FILE) {
        return null;
    }
    if ($file['error'] !== UPLOAD_ERR_OK) {
        $error = 'Image upload failed.';
        return null;
    }
    if ($file['size'] > 2 * 1024 * 1024) {
        $error = 'Image must be 2MB or smaller.';
        return null;
    }
    $allowed = [
        'image/jpeg' => 'jpg',
        'image/png'  => 'png',
        'image/gif'  => 'gif',
        'image/webp' => 'webp',
    ];
    $mime = (new finfo(FILEINFO_MIME_TYPE))->file($file['tmp_name']);
    if (!isset($allowed[$mime])) {
        $error = 'Only JPG, PNG, GIF or WEBP images are allowed.';
        return null;
    }
    $name = bin2hex(random_bytes(8)) . '.' . $allowed[$mime];
    if (!move_uploaded_file($file['tmp_name'], __DIR__ . '/../public/uploads/' . $name)) {
        $error = 'Could not save the image.';
        return null;
    }
    return $name;
}

function delete_image(?string $name): void
{
    if ($name) {
        $path = __DIR__ . '/../public/uploads/' . basename($name);
        if (is_file($path)) {
            unlink($path);
        }
    }
}


function reaction_icon(?string $type): string
{
    return match ($type) {
        'love'  => '❤️',
        'care'  => '🥰',
        'haha'  => '😆',
        'wow'   => '😮',
        'sad'   => '😢',
        'angry' => '😡',
        default => '👍',
    };
}

function reaction_label(?string $type): string
{
    return match ($type) {
        'love'  => 'Love',
        'care'  => 'Care',
        'haha'  => 'Haha',
        'wow'   => 'Wow',
        'sad'   => 'Sad',
        'angry' => 'Angry',
        default => 'Like',
    };
}
