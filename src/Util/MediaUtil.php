<?php

namespace Bboyyue\Filesystem\Util;


use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

/**
 * Created by : PhpStorm
 * User: bboyyue
 * Email bboyyue@outlook.com
 * Date: 2022/4/23
 * Time: 12:30
 */
class MediaUtil
{
    static function bindMedia($model, $filePath, $temp_path)
    {
        if (is_file($filePath)) {
            copy($filePath, $temp_path);
            $media = $model->addMedia($temp_path)
                ->preservingOriginal()
                ->toMediaCollection('filesystem');
        } elseif (is_dir($filePath)) {
            Storage::disk('local')->put(config('bboyyue-filesystems.temp_dir') . '/' . $model->alias, time());
            $path = Storage::disk('local')->path(config('bboyyue-filesystems.temp_dir') . '/' . $model->alias);
            $media = $model->addMedia($path)->toMediaCollection('filesystem');
            $media_path = $media->getPath();
            FilesystemUtil::copyDirAllFiles($filePath, $temp_path);
            if (env('MEDIA_DISK') === 'oss') {
                Storage::disk('oss')->delete($media_path);
                SyncUtil::SyncToOss($temp_path, $media_path, 'alpha-web');
            } else {
                Storage::disk('s3')->delete($media_path);
                SyncUtil::SyncToS3($temp_path, $media_path, 'alpha-api');
            }
        }
        return $media;
    }
}