<?php

namespace Bboyyue\Filesystem\Util;

use Bboyyue\Filesystem\Enum\FilesystemDataTypeEnum;
use Intervention\Image\Facades\Image;

class FilesystemUtil
{

    /**
     * 遍历文件夹, 返回一个一维的文件路径列表
     * @param $dirPath
     * @param array $fileList
     * @return array
     */
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

    /**
     * 操作系统级别的文件系统操作, 递归删除一整个目录的所有文件
     * @param $dirPath
     */
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

    /**
     * 递归的将一个目录下的所有文件复制到另一个目录
     * @param $dir1
     * @param $dir2
     */
    static public function copyDirAllFiles($dir1, $dir2)
    {
        if (!file_exists($dir2)) {
            mkdir($dir2);
        }
        $arr = scandir($dir1);
        foreach ($arr as $val) {
            if ($val != '.' && $val != '..') {
                $sourceFile = $dir1 . DIRECTORY_SEPARATOR . $val;
                $targetFile = $dir2 . DIRECTORY_SEPARATOR . $val;
                if (is_dir($sourceFile)) {
                    self::copyDirAllFiles($sourceFile, $targetFile);
                } else {
                    copy($sourceFile, $targetFile);
                }
            }
        }
    }

    /**
     * 读取文件, 判断文件的用途
     * 如果是长宽比为 2:1 的 jpg 图片, 会返回全景图
     * 如果是尺寸不固定的 png 图片, 会返回切图
     * 如果是长宽相等 并且为 2 的倍数的 jpg 图片, 会返回纹理切图
     * 如果是 obj, gltf 等格式, 会返回为模型文件,
     * 如果是 xml 会返回为 xml 文件
     */

    static public function getFileDataType($filePath): array
    {
        $mime = mime_content_type($filePath);
        $option = [
            'use_type' => null,
            'filesize' => filesize($filePath),
        ];
        switch ($mime) {
            case 'image/jpeg':
            case 'image/jpg':
                $image = Image::make($filePath);
                $option['width'] = $image->width();
                $option['height'] = $image->height();
                if ($image->width() / $image->height() == 2) {
                    $option['use_type'] = FilesystemDataTypeEnum::PANORAMA_IMG;
                } else if ($image->width() == $image->height()) {
                    $option['use_type'] = FilesystemDataTypeEnum::THREE_MATERIAL;
                } else {
                    $option['use_type'] = FilesystemDataTypeEnum::DESIGN_IMG;
                }
                break;
            case 'image/png':
            case 'image/webp':
                $image = Image::make($filePath);
                $option['width'] = $image->width();
                $option['height'] = $image->height();
                $option['use_type'] = FilesystemDataTypeEnum::DESIGN_IMG;
                break;
            case 'video/mp4':
                $option['use_type'] = FilesystemDataTypeEnum::DESIGN_VIDEO;
                break;
            case 'application/x-tgif':
                $option['use_type'] = FilesystemDataTypeEnum::THREE_OBJ;
                break;

        }
        return $option;

    }
}