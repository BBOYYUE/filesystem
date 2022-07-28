<?php

namespace Bboyyue\Filesystem\Util;


use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

/**
 * Illuminate\Support\Facades\Redis::hgetall(config('bboyyue-filesystems.redis.path_local_cache'));
 * Created by : PhpStorm
 * User: bboyyue
 * Email bboyyue@outlook.com
 * Date: 2022/4/23
 * Time: 12:36
 */
class RedisUtil
{

    static function clearAll()
    {
        $local = Redis::hgetall(config('bboyyue-filesystems.redis.path_local_cache'));
        $line = Redis::hgetall(config('bboyyue-filesystems.redis.path_line_cache'));
        foreach ($local as $key => $val) {
            Redis::hdel(config('bboyyue-filesystems.redis.path_local_cache'), $key);
        }
        foreach ($line as $key => $val){
            Redis::hdel(config('bboyyue-filesystems.redis.path_line_cache'), $key);
        }
    }


    static function createLocalPathCache($model)
    {
        $alias = Str::before($model->uuid, '-') . '.' . $model->extension;
        $path = Storage::disk('local')->path(config("bboyyue-filesystems.temp_dir") . '/' . $alias);
        $output= Redis::hset(config('bboyyue-filesystems.redis.path_local_cache'), $model->id, $path);
        return $output;
    }

    static function getLocalPathCache($model)
    {
        return Redis::hget(config('bboyyue-filesystems.redis.path_local_cache'), $model->id);
    }

    static function createLinePathCache($model)
    {

        $path = $model->getFirstMedia('filesystem')->getPath();
        return Redis::hset(config('bboyyue-filesystems.redis.path_line_cache'), $model->id, $path);
    }

    static function getLinePathCache($model)
    {
        return Redis::hget(config('bboyyue-filesystems.redis.path_line_cache'), $model->id);
    }
}