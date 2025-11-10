<?php
/**
 * Cache Class - Redis-based caching layer
 *
 * Provides simple interface for caching data in Redis to improve performance.
 * Falls back gracefully if Redis is unavailable.
 *
 * PERFORMANCE IMPACT:
 * - Configuration cache: saves 1 DB query per request
 * - User data cache: saves 1-2 DB queries per request
 * - Counter cache: saves 10-20 COUNT queries per page
 *
 * Expected improvement: 30-50% reduction in DB load
 */

class Cache {
    private static $redis = null;
    private static $enabled = false;
    private static $connected = false;

    /**
     * Initialize Redis connection
     * Called automatically on first use
     */
    private static function init() {
        if (self::$redis !== null) {
            return;
        }

        // Check if Redis extension is loaded
        if (!extension_loaded('redis')) {
            error_log('Cache: Redis extension not loaded');
            self::$enabled = false;
            return;
        }

        try {
            self::$redis = new Redis();

            // Connect to Redis (localhost:6379 by default)
            $host = getenv('REDIS_HOST') ?: '127.0.0.1';
            $port = getenv('REDIS_PORT') ?: 6379;
            $timeout = 2.0;

            if (self::$redis->connect($host, $port, $timeout)) {
                self::$connected = true;
                self::$enabled = true;

                // Test connection
                self::$redis->ping();
            } else {
                error_log('Cache: Failed to connect to Redis');
                self::$enabled = false;
            }
        } catch (Exception $e) {
            error_log('Cache: Redis connection error - ' . $e->getMessage());
            self::$enabled = false;
            self::$redis = null;
        }
    }

    /**
     * Get value from cache
     *
     * @param string $key Cache key
     * @return mixed|false Value or false if not found/unavailable
     */
    public static function get($key) {
        self::init();

        if (!self::$enabled || !self::$connected) {
            return false;
        }

        try {
            $value = self::$redis->get($key);

            if ($value === false) {
                return false;
            }

            // Unserialize if it's a serialized value
            $unserialized = @unserialize($value);
            return ($unserialized !== false) ? $unserialized : $value;
        } catch (Exception $e) {
            error_log('Cache: Get error - ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Set value in cache
     *
     * @param string $key Cache key
     * @param mixed $value Value to cache
     * @param int $ttl Time to live in seconds (default: 3600 = 1 hour)
     * @return bool Success
     */
    public static function set($key, $value, $ttl = 3600) {
        self::init();

        if (!self::$enabled || !self::$connected) {
            return false;
        }

        try {
            // Serialize complex data types
            if (is_array($value) || is_object($value)) {
                $value = serialize($value);
            }

            return self::$redis->setex($key, $ttl, $value);
        } catch (Exception $e) {
            error_log('Cache: Set error - ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Delete key from cache
     *
     * @param string $key Cache key
     * @return bool Success
     */
    public static function delete($key) {
        self::init();

        if (!self::$enabled || !self::$connected) {
            return false;
        }

        try {
            return self::$redis->del($key) > 0;
        } catch (Exception $e) {
            error_log('Cache: Delete error - ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Delete multiple keys matching pattern
     *
     * @param string $pattern Pattern with wildcards (e.g., "user:*")
     * @return int Number of keys deleted
     */
    public static function deletePattern($pattern) {
        self::init();

        if (!self::$enabled || !self::$connected) {
            return 0;
        }

        try {
            $keys = self::$redis->keys($pattern);
            if (empty($keys)) {
                return 0;
            }

            return self::$redis->del($keys);
        } catch (Exception $e) {
            error_log('Cache: Delete pattern error - ' . $e->getMessage());
            return 0;
        }
    }

    /**
     * Flush all cache (USE WITH CAUTION!)
     *
     * @return bool Success
     */
    public static function flush() {
        self::init();

        if (!self::$enabled || !self::$connected) {
            return false;
        }

        try {
            return self::$redis->flushDB();
        } catch (Exception $e) {
            error_log('Cache: Flush error - ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Increment counter
     *
     * @param string $key Cache key
     * @param int $value Value to increment by (default: 1)
     * @return int|false New value or false on failure
     */
    public static function increment($key, $value = 1) {
        self::init();

        if (!self::$enabled || !self::$connected) {
            return false;
        }

        try {
            return self::$redis->incrBy($key, $value);
        } catch (Exception $e) {
            error_log('Cache: Increment error - ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Decrement counter
     *
     * @param string $key Cache key
     * @param int $value Value to decrement by (default: 1)
     * @return int|false New value or false on failure
     */
    public static function decrement($key, $value = 1) {
        self::init();

        if (!self::$enabled || !self::$connected) {
            return false;
        }

        try {
            return self::$redis->decrBy($key, $value);
        } catch (Exception $e) {
            error_log('Cache: Decrement error - ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Check if cache is available
     *
     * @return bool
     */
    public static function isAvailable() {
        self::init();
        return self::$enabled && self::$connected;
    }

    /**
     * Get cache stats
     *
     * @return array|false Stats array or false if unavailable
     */
    public static function stats() {
        self::init();

        if (!self::$enabled || !self::$connected) {
            return false;
        }

        try {
            $info = self::$redis->info();
            return [
                'connected' => true,
                'used_memory' => $info['used_memory_human'] ?? 'N/A',
                'total_keys' => self::$redis->dbSize(),
                'uptime_days' => isset($info['uptime_in_days']) ? $info['uptime_in_days'] : 'N/A'
            ];
        } catch (Exception $e) {
            error_log('Cache: Stats error - ' . $e->getMessage());
            return false;
        }
    }
}
?>
