<?php

namespace Bboyyue\Filesystem\Util;

class FilesystemUtil
{

    static public function getDirAllFiles($dirPath, array $fileList = []): array
    {
        if ($handle = opendir($dirPath)) {
            while (false !== ($file = readdir($handle))) {
                $filePath = $dirPath . '/' . $file;
                if (is_dir($filePath) && $file != '.' && $file != '..') {
                    array_merge($fileList, self::getDirAllFiles($filePath));
                } elseif (is_file($filePath)) {
                    $fileList[] = $filePath;
                }
            }
        }
        return $fileList;
    }

    static public function delDirAllFiles($dirPath)
    {
        if ($handle = opendir($dirPath)) {
            while (false !== ($file = readdir($handle))) {
                $filePath = $dirPath . '/' . $file;
                if (is_dir($filePath) && $file != '.' && $file != '..') {
                    self::delDirAllFiles($filePath);
                } elseif (is_file($filePath)) {
                    unlink($filePath);
                }
            }
        }
        closedir($handle);
        rmdir($dirPath);
    }

    static public function copyDirAllFiles($dir1, $dir2)
    {
        if(!file_exists($dir2)){
            mkdir($dir2);
        }
        $arr = scandir($dir1);
        foreach ($arr as $val){
            if($val != '.' && $val != '..'){
                $sourceFile = $dir1 . DIRECTORY_SEPARATOR . $val;
                $targetFile = $dir2 . DIRECTORY_SEPARATOR . $val;
                if(is_dir($sourceFile)){
                    self::copyDirAllFiles($sourceFile, $targetFile);
                }else{
                    copy($sourceFile, $targetFile);
                }
            }
        }
    }
}