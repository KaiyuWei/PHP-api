<?php

namespace App\Helpers;

class CacheHelper
{
    protected $cacheFilePath = __DIR__ . '/../../storage/cache/';

    protected $cacheDuration;

    public function __construct() {
        $config = include __DIR__ . '/../../config/cache.php';
        $this->cacheDuration = $config['cache_duration'];
    }


    public function getCache($key)
    {
        $cacheFileName = md5($key) . '.cache';
        $file = $this->cacheFilePath . $cacheFileName;

        $isCacheUsable = file_exists($file) && (filemtime($file) > (time() - $this->cacheDuration));
        if ($isCacheUsable) {
            return unserialize(file_get_contents($file));
        }
        return false;
    }

    public function setCache($key, $data)
    {
        $cacheFileName = md5($key) . '.cache';
        $file = $this->cacheFilePath . $cacheFileName;

        file_put_contents($file, serialize($data));
    }
}