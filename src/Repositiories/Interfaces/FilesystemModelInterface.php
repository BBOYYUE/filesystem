<?php

namespace Bboyyue\Filesystem\Repositiories\Interfaces;


/**
 * Interface FilesystemModelInterface
 * 扩展文件模型, 使得文件实例可以直接进行一些操作
 * @package Bboyyue\Filesystem\Repositiories\Interfaces
 */
interface FilesystemModelInterface
{
    /**
     * 获取子元素
     * @return mixed
     */
    function getDescendants();

    /**
     * 新增一个子目录
     * @param $name
     * @return mixed
     */
    function addChildDir($name);

    /**
     * 新增一个子文件
     * @param $filepath
     * @return mixed
     */
    function addData($filepath);

    /**
     * @param $request
     * @return mixed
     */
    function addDataByRequest($request);

    /**
     * 删除某个子元素
     * @param $child
     * @return mixed
     */
    function removeChild($child);

    /**
     * 给某个子元素修改名字
     * @param $child
     * @param $name
     * @return mixed
     */
    function renameChild($child, $name);

    /**
     * 给当前资源打标签
     * @param $tag
     * @return mixed
     */
    function setTag($tag);

    /**
     * 获取当前资源的标签
     * @return mixed
     */
    function getTag();

    /**
     * 获取包含标签的子元素
     * @param $tag
     * @return mixed
     */
    function searchChildByTag($tag);

    /**
     * 将当前文件资源移动到某个目录
     * @param $parent
     * @return mixed
     */
    function moveTo($parent);

    /**
     * 如果当前资源是 data, 那么返回路径
     * @return mixed
     */
    function path();

    /**
     * 如果当前资源是 data, 那么返回本地路径, 如果不存在, 则会下载
     * @return mixed
     */
    function localPath();
}