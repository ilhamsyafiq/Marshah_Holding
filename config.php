<?php
/**
 * Marshah Holding – Application Configuration
 *
 * Copy .env.example to .env and fill in your values.
 * Dependency-free: manual .env loader + define() constants + JSON store helpers.
 */

// ---------------------------------------------------------------------------
// Load .env file manually (no framework dependency)
// ---------------------------------------------------------------------------
$envFile = __DIR__ . '/.env';
if (file_exists($envFile)) {
    $lines = file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        $line = trim($line);
        if ($line === '' || str_starts_with($line, '#')) continue;
        if (strpos($line, '=') !== false) {
            [$key, $value] = explode('=', $line, 2);
            $key = trim($key);
            $value = trim($value);
            // Remove surrounding quotes
            $value = trim($value, '"\'');
            $_ENV[$key] = $value;
            putenv("$key=$value");
        }
    }
}

// ---------------------------------------------------------------------------
// Admin settings
// ---------------------------------------------------------------------------
define('ADMIN_PASSWORD', $_ENV['ADMIN_PASSWORD'] ?? 'marshah123');
define('ADMIN_EMAIL', $_ENV['ADMIN_EMAIL'] ?? 'info@marshahholding.com');

// ---------------------------------------------------------------------------
// Application settings
// ---------------------------------------------------------------------------
define('APP_URL', $_ENV['APP_URL'] ?? 'http://localhost/zasmahome');

// ---------------------------------------------------------------------------
// SMTP / Email settings (used as fallback / future SMTP transport)
// ---------------------------------------------------------------------------
define('SMTP_HOST', $_ENV['SMTP_HOST'] ?? 'smtp.gmail.com');
define('SMTP_PORT', (int)($_ENV['SMTP_PORT'] ?? 587));
define('SMTP_USERNAME', $_ENV['SMTP_USERNAME'] ?? '');
define('SMTP_PASSWORD', $_ENV['SMTP_PASSWORD'] ?? '');
define('SMTP_ENCRYPTION', $_ENV['SMTP_ENCRYPTION'] ?? 'tls');
define('SMTP_FROM_EMAIL', $_ENV['SMTP_FROM_EMAIL'] ?? ($_ENV['SMTP_USERNAME'] ?? 'no-reply@marshahholding.com'));
define('SMTP_FROM_NAME', $_ENV['SMTP_FROM_NAME'] ?? 'Marshah Holding');

// ---------------------------------------------------------------------------
// Storage
// ---------------------------------------------------------------------------
define('STORAGE_DIR', __DIR__ . '/storage');

/**
 * Load a JSON store as an associative array.
 * Creates the storage dir / file if missing so first-run works.
 *
 * @param string $file Absolute path to the JSON file.
 * @return array
 */
function load_json(string $file): array
{
    $dir = dirname($file);
    if (!is_dir($dir)) {
        @mkdir($dir, 0775, true);
    }
    if (!file_exists($file)) {
        // Seed with empty object so subsequent reads/writes are consistent
        file_put_contents($file, "{}");
        return [];
    }
    $raw = file_get_contents($file);
    if ($raw === false || trim($raw) === '') {
        return [];
    }
    $data = json_decode($raw, true);
    return is_array($data) ? $data : [];
}

/**
 * Persist an associative array to a JSON store (pretty printed).
 * Creates the storage dir if missing so first-run writes work.
 *
 * @param string $file Absolute path to the JSON file.
 * @param array  $data Data to persist.
 * @return void
 */
function save_json(string $file, array $data): void
{
    $dir = dirname($file);
    if (!is_dir($dir)) {
        @mkdir($dir, 0775, true);
    }
    // Use object shape when empty so JSON stays {} not []
    $flags = JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES;
    $json = empty($data) ? "{}" : json_encode($data, $flags);
    file_put_contents($file, $json);
}
