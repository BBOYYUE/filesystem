<?php


namespace Bboyyue\Filesystem\Repositiories\Interfaces;

/**
 * Trait interface
 * filesystem 可以直接扩展 laravel模型,
 * 扩展后的模型可以直接操作模型绑定的文件和目录
 * Interface FilesystemTraitInterface
 * @package Bboyyue\Filesystem\Repositiories\Interfaces
 */
interface FilesystemTraitInterface
{

    /**
     * 将当前模型和某个文件资源相绑定
     * @return mixed
     */
    function bindFilesystemDir($parent = null);


    /**
     * 往当前资源绑定的目录下新增文件
     * 如果当前资源绑定了多个目录, 那么可以指定目录
     * @param $filepath
     * @param null $option
     * @param null $tag
     * @param null $dir
     * @return mixed
     */
    function addFilesystemData($filepath, $option = null, $tag = null , $dir = null );

    /**
     * 从文本保存文件
     * @param $text
     * @param null $option
     * @param null $dir
     * @param null $tag
     * @return mixed
     */
    function addFilesystemDataByText($text, $option = null, $dir = null, $tag = null);

    /**
     * 从请求保存文件
     * @param $request
     * @param null $option
     * @param null $tag
     * @param null $dir
     * @return mixed
     */
    function addFilesystemDataByRequest($request, $option = null, $tag = null, $dir = null);

    /**
     * 往当前资源绑定的目录下新增文件夹
     * 如果当前资源绑定了多个目录, 那么可以指定目录
     * @param $child
     * @param null $dir
     * @param null $option
     * @param null $tag
     * @return mixed
     */
    function addFilesystemDir($child, $option = null, $dir = null, $tag = null);

    /**
     * 查看当前资源绑定的文件资源
     * @return mixed
     */
    function listFilesystem($type = 0, $extension = '', $option=[], $tag = [], $tagAllOrAny = '');

    /**
     * 查看当前资源绑定的文件实体
     * @return mixed
     */
    function listFilesystemData();

    /**
     * 查看当前资源绑定的文件夹
     * @return mixed
     */
    function listFilesystemDir();

    /**
     * 查看当前资源绑定的文件资源的本地路径
     * 这里通过 redis 实现,
     * @return mixed
     */
    function listFilesystemLocalPath();

    /**
     * 移除当前资源目录下的某个文件
     * @param $file
     * @return mixed
     */
    function removeFilesystem($file);

    /**
     * 修改当前资源目录下的某个文件的名字
     * @param $file
     * @param $name
     * @return mixed
     */
    function renameFilesystem($file, $name);

    /**
     * 给当前目录下的文件资源打标签
     * @param $file
     * @param $tag
     * @return mixed
     */
    function setTagFilesystem($file, $tag);

    /**
     * 通过后缀获取资源
     * @param $extension
     * @return mixed
     */
    function getFilesystemByExtension($extension);


    /**
     * 通过标签获取资源
     * @param $tag
     * @return mixed
     */
    function getFilesystemByTag($tag);

    /**
     * 获取某个文件的路径
     * @param $file
     * @return mixed
     */
    function getFilesystemPath($file);

    /**
     * 获取某个文件的本地路径
     * 通过 redis 实现, 如果不存在则会自动下载
     * @param $file
     * @return mixed
     */
    function getFilesystemLocalPath($file);

    /**
     * 获取某个文件的线上地址
     * @param $file
     * @return mixed
     */
    function getFilesystemLinePath($file);

}