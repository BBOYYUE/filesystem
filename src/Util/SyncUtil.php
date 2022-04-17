<?php

namespace Bboyyue\Filesystem\Util;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class SyncUtil
{
    static public function SyncToOss($sourcePath, $targetPath, $bucket = '', $host = 'oss:/'): array
    {
        $targetPath = $host . '/' . $bucket . '/' . $targetPath;
        $OssPath = Storage::disk('public')->path('oss-cmd');
        $OssCmdPath = is_file($OssPath . '/ossutil64.exe') ? $OssPath . '/ossutil64.exe' : $OssPath . '/ossutil64';
        $script = $OssCmdPath . "  cp --recursive --force " . $sourcePath . ' ' . $targetPath;
        exec($script, $output, $return_var);
        return $output;
    }

    static public function SyncToS3($sourcePath, $targetPath, $bucket = '', $host = 'minio'): array
    {
        $targetPath = $host . '/' . $bucket . '/' . $targetPath;
        $S3Path = Storage::disk('public')->path('s3-cmd');
        $S3CmdPath = is_file($S3Path . '/mc.exe') ? $S3Path . '/mc.exe' : $S3Path . '/mc';
        $script = $S3CmdPath . "  cp --recursive " . $sourcePath . DIRECTORY_SEPARATOR . ' ' . $targetPath;
        exec($script, $output, $return_var);
        return $output;
    }

    static public function RemoveToOss($path): bool
    {
        $ossPath =  'oss:/' . '/' . env('OSS_BUCKET') . '/' . $path;
        $OssCmdDirPath = Storage::disk('public')->path('oss-cmd');
        $OssCmdPath = is_file($OssCmdDirPath . '/ossutil64.exe') ? $OssCmdDirPath . '/ossutil64.exe' : $OssCmdDirPath . '/ossutil64';
        $script = $OssCmdPath . " rm --recursive --force " . $ossPath;
        exec($script, $output, $return_var);
        return true;
    }



    static public function RemoveToS3($path): bool
    {
        if (Storage::disk('s3')->exists($path)) {
            Storage::disk('s3')->deleteDirectory($path);
        }
        return true;
    }
}