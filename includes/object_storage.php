<?php
// Unified Object Storage configuration and helpers for:
// - Amazon S3
// - Wasabi (S3-compatible)
// - DigitalOcean Spaces (S3-compatible)
// - MinIO (S3-compatible)
//
// Requirements:
// - AWS SDK for PHP available at includes/vendor (preferred)
// - Credentials/region/bucket values provided via DB (in $inc) or env vars
// - This file is included from includes/inc.php AFTER $inc is loaded
//
// Usage:
//   storage_active_provider();                  // 'minio' | 's3' | 'spaces' | 'wasabi' | 'local'
//   storage_public_url('uploads/files/a.mp4');  // returns public URL depending on provider
//   storage_upload('/path/local.mp4', 'uploads/files/a.mp4', true);
//   storage_delete('uploads/files/a.mp4');

use Aws\S3\S3Client;
use Aws\Exception\AwsException;

// Attempt to load AWS SDK
if (file_exists(__DIR__ . '/vendor/autoload.php')) {
    require_once __DIR__ . '/vendor/autoload.php';
} elseif (file_exists(__DIR__ . '/s3/vendor/autoload.php')) {
    require_once __DIR__ . '/s3/vendor/autoload.php';
}

/** Return the active provider key: s3|wasabi|spaces|local */
function storage_active_provider(): string {
    $s3Status = $GLOBALS['s3Status'] ?? '0';
    $WasStatus = $GLOBALS['WasStatus'] ?? '0';
    $digitalOceanStatus = $GLOBALS['digitalOceanStatus'] ?? '0';
    $minioStatus = $GLOBALS['minioStatus'] ?? '0';
    // Keep legacy precedence: S3 > Spaces > Wasabi
    // Prefer MinIO when explicitly enabled
    if ($minioStatus == '1') return 'minio';
    if ($s3Status == '1') return 's3';
    if ($digitalOceanStatus == '1') return 'spaces';
    if ($WasStatus == '1') return 'wasabi';
    return 'local';
}

/** Provider config as an array (computed from $inc + env) */
function storage_provider_config(): array {
    $provider = storage_active_provider();
    switch ($provider) {
        case 'minio':
            $bucket   = getenv('MINIO_BUCKET')   ?: ($GLOBALS['minioBucket'] ?? null);
            $region   = getenv('MINIO_REGION')   ?: ($GLOBALS['minioRegion'] ?? 'us-east-1');
            $key      = getenv('MINIO_KEY')      ?: ($GLOBALS['minioKey'] ?? null);
            $secret   = getenv('MINIO_SECRET')   ?: ($GLOBALS['minioSecret'] ?? null);
            $endpoint = getenv('MINIO_ENDPOINT') ?: ($GLOBALS['minioEndpoint'] ?? null); // e.g. https://minio.example.com:9000
            $public   = getenv('MINIO_PUBLIC_BASE') ?: ($GLOBALS['minioPublicBase'] ?? null);
            $pathStyle = getenv('MINIO_PATH_STYLE');
            $pathStyle = ($pathStyle === false || $pathStyle === '') ? true : in_array(strtolower((string)$pathStyle), ['1','true','yes','on'], true);
            $sslVerify = getenv('MINIO_SSL_VERIFY');
            $sslVerify = ($sslVerify === false || $sslVerify === '') ? true : in_array(strtolower((string)$sslVerify), ['1','true','yes','on'], true);

            // Compute default public base if not provided
            if (!$public && $endpoint && $bucket) {
                $public = rtrim($endpoint, '/') . '/' . $bucket . '/';
            }
            return [
                'provider'    => 'minio',
                'bucket'      => $bucket,
                'region'      => $region,
                'endpoint'    => $endpoint,
                'public_base' => $public ?: '/',
                'credentials' => ['key' => $key, 'secret' => $secret],
                'options'     => [
                    'use_path_style_endpoint' => $pathStyle,
                    'http_verify'             => $sslVerify,
                ],
            ];
        case 'spaces':
            $space = getenv('SPACES_NAME') ?: ($GLOBALS['oceanspace_name'] ?? null);
            $region = getenv('SPACES_REGION') ?: ($GLOBALS['oceanregion'] ?? '');
            $key = getenv('SPACES_KEY') ?: ($GLOBALS['oceankey'] ?? null);
            $secret = getenv('SPACES_SECRET') ?: ($GLOBALS['oceansecret'] ?? null);
            $endpoint = ($space && $region) ? "https://{$space}.{$region}.digitaloceanspaces.com" : null;
            $public = getenv('SPACES_PUBLIC_BASE') ?: ($GLOBALS['digitalOceanPublicBase'] ?? null);
            if ($public) {
                $public = rtrim($public, '/') . '/';
            } elseif ($endpoint) {
                $public = $endpoint . '/';
            }
            return [
                'provider' => 'spaces',
                'bucket' => $space,
                'region' => $region,
                'endpoint' => $endpoint,
                'public_base' => $public ?: '/',
                'credentials' => ['key' => $key, 'secret' => $secret],
                'options' => [ 'bucket_endpoint' => true ],
            ];
        case 'wasabi':
            $bucket = getenv('WASABI_BUCKET') ?: ($GLOBALS['WasBucket'] ?? null);
            $region = getenv('WASABI_REGION') ?: ($GLOBALS['WasRegion'] ?? '');
            $key = getenv('WASABI_KEY') ?: ($GLOBALS['WasKey'] ?? null);
            $secret = getenv('WASABI_SECRET') ?: ($GLOBALS['WasSecretKey'] ?? null);
            $endpoint = $region ? "https://s3.{$region}.wasabisys.com" : null;
            $public = getenv('WASABI_PUBLIC_BASE') ?: ($GLOBALS['WasPublicBase'] ?? null);
            if ($public) {
                $public = rtrim($public, '/') . '/';
            } elseif ($bucket && $region) {
                $public = "https://{$bucket}.s3.{$region}.wasabisys.com/";
            }
            return [
                'provider' => 'wasabi',
                'bucket' => $bucket,
                'region' => $region,
                'endpoint' => $endpoint,
                'public_base' => $public ?: '/',
                'credentials' => ['key' => $key, 'secret' => $secret],
                'options' => [ 'use_path_style_endpoint' => true ],
            ];
        case 's3':
            $bucket = getenv('S3_BUCKET') ?: ($GLOBALS['s3Bucket'] ?? null);
            $region = getenv('S3_REGION') ?: ($GLOBALS['s3Region'] ?? '');
            $key = getenv('S3_KEY') ?: ($GLOBALS['s3Key'] ?? null);
            $secret = getenv('S3_SECRET') ?: ($GLOBALS['s3SecretKey'] ?? null);
            $public = getenv('S3_PUBLIC_BASE') ?: ($GLOBALS['s3PublicBase'] ?? null);
            if ($public) {
                $public = rtrim($public, '/') . '/';
            } elseif ($bucket && $region) {
                $public = "https://{$bucket}.s3.{$region}.amazonaws.com/";
            }
            return [
                'provider' => 's3',
                'bucket' => $bucket,
                'region' => $region,
                'endpoint' => null,
                'public_base' => $public ?: '/',
                'credentials' => ['key' => $key, 'secret' => $secret],
                'options' => [],
            ];
        default:
            return [
                'provider' => 'local',
                'public_base' => $GLOBALS['base_url'] ?? '/',
            ];
    }
}

/** Build or reuse a singleton S3Client for active provider */
function storage_client(): ?S3Client {
    static $client = null;
    static $cachedProvider = null;
    $provider = storage_active_provider();
    if ($provider === 'local') return null;
    if ($client && $cachedProvider === $provider) return $client;

    $cfg = storage_provider_config();
    $args = [
        'version' => 'latest',
        'region'  => $cfg['region'] ?? '',
        'credentials' => $cfg['credentials'] ?? null,
    ];
    if (!empty($cfg['endpoint'])) {
        $args['endpoint'] = $cfg['endpoint'];
    }
    if (!empty($cfg['options']['use_path_style_endpoint'])) {
        $args['use_path_style_endpoint'] = true;
    }
    if (!empty($cfg['options']['bucket_endpoint'])) {
        $args['bucket_endpoint'] = true;
    }
    if (array_key_exists('http_verify', $cfg['options'] ?? [])) {
        $args['http'] = ['verify' => (bool)$cfg['options']['http_verify']];
    }
    try {
        $client = new S3Client($args);
        $cachedProvider = $provider;
        return $client;
    } catch (AwsException $e) {
        error_log('Storage client init failed: ' . $e->getMessage());
        return null;
    }
}

/** Compute public URL for a given key/path */
function storage_public_url(string $key): string {
    $cfg = storage_provider_config();
    if (($cfg['provider'] ?? 'local') === 'local') {
        // Ensure no leading '../' when serving locally
        return rtrim($cfg['public_base'], '/') . '/' . ltrim($key, '/');
    }
    return ($cfg['public_base'] ?? '/') . ltrim($key, '/');
}

/** Return true if active provider is a remote object storage. */
function storage_is_remote(): bool {
    return storage_active_provider() !== 'local';
}

/**
 * Publish a set of keys and return public URL of primary key.
 * - Keys should be relative (e.g., 'uploads/files/2025-01-01/name.ext').
 * - If cleanupIfRemote is true, remove local files only when provider is remote.
 */
function storage_publish_and_url(string $primaryKey, array $allKeys = [], bool $cleanupIfRemote = true): string {
    $keys = $allKeys;
    if (!in_array($primaryKey, $keys, true)) { $keys[] = $primaryKey; }
    $cleanup = $cleanupIfRemote && storage_is_remote();
    storage_publish_many($keys, true, $cleanup);
    return storage_public_url($primaryKey);
}

/**
 * Publish a set of relative keys to active storage.
 * Each item is either a string key (relative path like 'uploads/files/..')
 * or an array ['key' => key, 'local' => fullLocalPath].
 * Returns mapping key => publicUrl. Optionally cleans up local files.
 */
function storage_publish_many(array $items, bool $public = true, bool $cleanup = false): array {
    $urls = [];
    $isRemote = storage_is_remote();
    foreach ($items as $item) {
        if (is_string($item)) {
            $key = ltrim($item, '/');
            $local = dirname(__DIR__) . '/' . $key; // ../ relative to includes/
        } else {
            $key = ltrim((string)($item['key'] ?? ''), '/');
            $local = (string)($item['local'] ?? '');
            if ($local === '') { $local = dirname(__DIR__) . '/' . $key; }
        }
        if ($key === '') { continue; }
        if (is_file($local)) {
            $uploadedOk = true;
            if ($isRemote) {
                $uploadedOk = @storage_upload($local, $key, $public);
            }
            // Clean up only if remote provider AND upload succeeded
            if ($cleanup && $isRemote && $uploadedOk) {
                @unlink($local);
            }
        }
        $urls[$key] = storage_public_url($key);
    }
    return $urls;
}

/** Convenience: publish keys if local exists; return the first available URL by preference. */
function storage_publish_pick_url(array $keysByPriority, bool $public = true): ?string {
    if (!$keysByPriority) { return null; }
    $items = [];
    foreach ($keysByPriority as $k) { $items[] = $k; }
    $urls = storage_publish_many($items, $public, false);
    foreach ($keysByPriority as $k) {
        $k = ltrim($k, '/');
        if (!empty($urls[$k])) { return $urls[$k]; }
    }
    return null;
}

/** Upload a local file to object storage under the given key */
function storage_upload(string $localPath, string $remoteKey, bool $public = true): bool {
    $provider = storage_active_provider();
    if ($provider === 'local') {
        // No-op for local in this helper
        return is_file($localPath);
    }
    $cfg = storage_provider_config();
    $client = storage_client();
    if (!$client) { return false; }

    $bucket = $cfg['bucket'] ?? null;
    if (!$bucket) { return false; }

    $args = [
        'Bucket' => $bucket,
        'Key'    => ltrim($remoteKey, '/'),
        'SourceFile' => $localPath,
    ];
    // Respect provider nuances: Wasabi buckets often use policy-level public settings.
    if ($public && in_array($provider, ['s3','spaces','minio'], true)) { $args['ACL'] = 'public-read'; }

    try {
        $client->putObject($args);
        return true;
    } catch (AwsException $e) {
        error_log('Storage upload failed: ' . $e->getMessage());
        return false;
    }
}

/** Delete a remote object by key */
function storage_delete(string $remoteKey): bool {
    $provider = storage_active_provider();
    if ($provider === 'local') { return true; }
    $cfg = storage_provider_config();
    $client = storage_client();
    if (!$client) { return false; }
    $bucket = $cfg['bucket'] ?? null;
    if (!$bucket) { return false; }
    try {
        $client->deleteObject([
            'Bucket' => $bucket,
            'Key'    => ltrim($remoteKey, '/'),
        ]);
        return true;
    } catch (AwsException $e) {
        error_log('Storage delete failed: ' . $e->getMessage());
        return false;
    }
}
