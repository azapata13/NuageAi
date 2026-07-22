<?php
declare(strict_types=1);

session_start();

const APP_NAME = 'MarketingAuto DEV';
const DATA_DIR = __DIR__ . '/data/requests';

function ensure_storage(): void
{
    if (!is_dir(DATA_DIR)) {
        mkdir(DATA_DIR, 0750, true);
    }
}

function csrf_token(): string
{
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }

    return $_SESSION['csrf_token'];
}

function verify_csrf(): void
{
    $token = $_POST['csrf_token'] ?? '';
    if (!is_string($token) || !hash_equals(csrf_token(), $token)) {
        http_response_code(403);
        exit('Invalid session token.');
    }
}

function h(?string $value): string
{
    return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
}

function contains_text(string $haystack, string $needle): bool
{
    return strpos($haystack, $needle) !== false;
}

function starts_with_text(string $haystack, string $needle): bool
{
    return substr($haystack, 0, strlen($needle)) === $needle;
}

function redirect_to(string $path): void
{
    header('Location: ' . $path, true, 303);
    exit;
}

function normalize_lines(string $raw): array
{
    $lines = preg_split('/\R+/', $raw) ?: [];
    $clean = [];

    foreach ($lines as $line) {
        $line = trim($line);
        if ($line === '') {
            continue;
        }
        $clean[$line] = $line;
    }

    return array_values($clean);
}

function normalize_url(string $input): string
{
    $value = trim($input);
    if ($value === '') {
        return '';
    }

    if (!preg_match('/^https?:\/\//i', $value)) {
        $value = 'https://' . $value;
    }

    return filter_var($value, FILTER_VALIDATE_URL) ? $value : trim($input);
}

function classify_source(string $input): array
{
    $normalized = normalize_url($input);
    $host = parse_url($normalized, PHP_URL_HOST);
    $host = strtolower((string) $host);
    $path = strtolower((string) parse_url($normalized, PHP_URL_PATH));

    $platform = 'website';
    $sourceType = 'site';
    $accessMode = 'manual_review';
    $status = 'needs_review';
    $notes = ['Manual review required before collection.'];

    if (contains_text($host, 'reddit.com')) {
        $platform = 'reddit';
        $sourceType = contains_text($path, '/r/') ? 'community' : 'post';
        $accessMode = 'official_api';
        $status = 'approved';
        $notes = ['Use official Reddit API or approved access only.'];
    } elseif (contains_text($host, 'youtube.com') || contains_text($host, 'youtu.be')) {
        $platform = 'youtube';
        $sourceType = contains_text($path, '/watch') || contains_text($host, 'youtu.be') ? 'video' : 'channel';
        $accessMode = 'official_api';
        $status = 'approved';
        $notes = ['Use official YouTube Data API or approved access only.'];
    } elseif ($host === 'x.com' || contains_text($host, 'twitter.com')) {
        $platform = 'x';
        $sourceType = 'profile';
        $accessMode = 'official_api';
        $status = 'needs_review';
        $notes = ['Use official X API or approved access only; verify plan limits first.'];
    } elseif (contains_text($host, 't.me') || contains_text($host, 'telegram.me')) {
        $platform = 'telegram';
        $sourceType = 'channel';
        $accessMode = 'official_api';
        $status = 'needs_review';
        $notes = ['Use authorized Telegram access only; private groups require manual approval.'];
    } elseif (contains_text($host, 'facebook.com')) {
        $platform = 'facebook';
        $sourceType = contains_text($path, '/groups/') ? 'group' : 'page';
        $accessMode = contains_text($path, '/groups/') ? 'assisted_mode' : 'manual_review';
        $status = 'needs_review';
        $notes = ['Facebook is official API only. Groups without API access are assisted mode only.'];
    } elseif (filter_var($normalized, FILTER_VALIDATE_URL)) {
        $platform = 'website';
        $sourceType = 'site';
        $accessMode = 'manual_review';
        $status = 'needs_review';
        $notes = ['Check robots, terms, permissions, and public access before any crawl.'];
    } else {
        $platform = 'other';
        $sourceType = 'unknown';
        $accessMode = 'manual_review';
        $status = 'needs_review';
        $notes = ['Input is not a valid URL. Treat as a manual research target.'];
    }

    return [
        'source_id' => bin2hex(random_bytes(8)),
        'input_url' => trim($input),
        'normalized_url' => $normalized,
        'platform' => $platform,
        'source_type' => $sourceType,
        'access_mode' => $accessMode,
        'status' => $status,
        'compliance_notes' => $notes,
    ];
}

function create_request(array $payload): string
{
    ensure_storage();
    $id = date('Ymd-His') . '-' . bin2hex(random_bytes(4));
    $payload['request_id'] = $id;
    $payload['created_at'] = gmdate('c');
    $payload['updated_at'] = gmdate('c');
    $payload['version'] = 1;

    file_put_contents(request_path($id), json_encode($payload, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));

    return $id;
}

function request_path(string $id): string
{
    if (!preg_match('/^[A-Za-z0-9_.-]+$/', $id)) {
        http_response_code(400);
        exit('Invalid request id.');
    }

    return DATA_DIR . '/' . $id . '.json';
}

function load_request(string $id): array
{
    $path = request_path($id);
    if (!is_file($path)) {
        http_response_code(404);
        exit('Request not found.');
    }

    $data = json_decode((string) file_get_contents($path), true);
    if (!is_array($data)) {
        http_response_code(500);
        exit('Request data is corrupted.');
    }

    return $data;
}

function save_request(array $request): void
{
    $request['updated_at'] = gmdate('c');
    file_put_contents(request_path((string) $request['request_id']), json_encode($request, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
}

function score_badge_class(int $score): string
{
    if ($score >= 70) {
        return 'strong';
    }
    if ($score >= 45) {
        return 'medium';
    }

    return 'low';
}

function initial_score(array $source, array $keywords): int
{
    $score = 30;

    if ($source['status'] === 'approved') {
        $score += 20;
    }
    if ($source['access_mode'] === 'official_api') {
        $score += 15;
    }
    if ($source['access_mode'] === 'assisted_mode') {
        $score += 5;
    }
    if (count($keywords) > 0) {
        $score += 10;
    }

    return min(100, $score);
}
