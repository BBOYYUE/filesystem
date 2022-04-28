<?php

namespace Bboyyue\Filesystem\Repositiories\Impl;


use Bboyyue\Filesystem\Enum\FilesystemStatusEnum;
use Bboyyue\Filesystem\Enum\FilesystemTypeEnum;
use Bboyyue\Filesystem\Model\FilesystemModel;
use Bboyyue\Filesystem\Repositiories\Facade\Filesystem;
use Bboyyue\Filesystem\Util\MediaUtil;
use Bboyyue\Filesystem\Util\RedisUtil;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

/**
 * Created by : PhpStorm
 * User: bboyyue
 * Email bboyyue@outlook.com
 * Date: 2022/4/23
 * Time: 11:42
 */
trait FilesystemModelTrait
{

    function addChildDir($name, array $option = [], $tag = [])
    {
        return Filesystem::addDir($this,$name, $option, $tag);
    }

    function addData($filepath, $option = [], $tag = [])
    {
        return Filesystem::addData($this, $filepath, $option, $tag);
    }

    function addDataByRequest($request, $option = [], $tag = [])
    {
        return Filesystem::addDataByRequest($this, $request, $option, $tag);
    }

    function addDataByText($text, $option = [], $tag = [])
    {
        return Filesystem::addDataByText($this, $text, $option, $tag);
    }

    function removeChild($child)
    {
        $child->delete();
        return $this;
    }

    function renameChild($child, $name)
    {
        $child->name = $name;
        return $child;
    }

    function setTag($tag)
    {
        $this->attachTag($tag);
        return $this;
    }

    function getTag()
    {
        return $this->tags;
    }

    function searchChildByTag($tag)
    {
        // todo
    }

    function moveToDir($parent)
    {
        $this->moveTo($parent);
    }

    function path()
    {
    }


    function localPath()
    {
        return RedisUtil::getLocalPathCache($this);
    }

    function linePath()
    {
        return RedisUtil::getLinePathCache($this);
    }
}