<?php

namespace Bboyyue\Filesystem\Util;


use Illuminate\Support\Facades\Redis;

/**
 * Created by : PhpStorm
 * User: bboyyue
 * Email bboyyue@outlook.com
 * Date: 2022/4/23
 * Time: 12:36
 */
class RedisUtil
{
    static function createLocalPathCache($model, $path)
    {
        Redis::set(config('bboyyue.filesystems.redis.path_cache'), $model->id, $path);
    }
}