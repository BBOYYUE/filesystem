<?php


namespace Bboyyue\Filesystem\Repositiories\Interfaces;


/**
 * Facade interface
 * 提供一系列门面接口, 使得外部可以通过门面对文件系统进行一些操作
 * Interface FilesystemFacadeInterface
 * @package Bboyyue\Filesystem\Repositiories\Interfaces
 */
interface FilesystemFacadeInterface
{
    /**
     * 获取某个目录的子元素
     * @param $dir
     * @return mixed
     */
    static function getDescendants($dir);

    /**
     * 创建文件目录, 创建目录必须指定父级目录
     * @param $dir
     * @param $name
     * @param array $option
     * @param array $tag
     * @return mixed
     */
    static function addDir($dir, $name, array $option = [], array $tag = []);

    /**
     * 创建一个木有父目录的文件夹
     * @param $name
     * @param array $option
     * @return mixed
     */
    static function makeRoot($name, array $option = []);

    /**
     * 创建文件, 创建文件需要指定文件保存的位置, 和文件的路径
     * 创建文件时, 要先保存在本地临时目录
     * 并且在 redis 中创建一条指向到临时文件位置的记录
     * @param $dir
     * @param $filePath
     * @param array $tag
     * @return mixed
     */
    static function addData($dir, $filePath, $option =[], array $tag = []);

    static function addDataByText($dir, $text, $option = [], $tag = []);
    /**
     * 从请求保存文件
     * @param $dir
     * @param $request
     * @return mixed
     */
    static function addDataByRequest($dir, $request, $option =[], $tag = []);

    /**
     * 将目录或者文件移至新的目录下
     * @param $oldDir
     * @param $newDir
     * @return mixed
     */
    static function move($oldDir, $newDir);

    /**
     * 删除文件资源
     * @param $file
     * @return mixed
     */
    static function remove($file);

    /**
     * 给某个文件资源修改文件
     * @param $file
     * @param $name
     * @return mixed
     */
    static function rename($file, $name);

    /**
     * 给某个文件资源打标签
     * @param $file
     * @param $tag
     * @return mixed
     */
    static function setTag($file, $tag);

    /**
     * 获取某个文件的 tag
     * @param $file
     * @return mixed
     */
    static function getTag($file);

    /**
     * 获取某个文件资源类型的路径
     * @param $file
     * @return mixed
     */
    static function path($file);

    /**
     * 获取某个文件资源的下载url
     * @param $file
     * @return mixed
     */
    static function url($file);


    /** 获取文件的本地地址
     * @param $file
     * @return mixed
     */
    static function localPath($file);

    /**
     * 获取文件的线上地址
     */
    static function linePath($file);
}