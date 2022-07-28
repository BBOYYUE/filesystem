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

    function addChildLink($name, $dir, $source)
    {
        return Filesystem::addChildLink($name, $dir, $source);
    }

    function removeChildLink()
    {
        $files = FilesystemModel::where([
            ['parent_id','=', $this->id],
            ['type', '=', 3]
        ])->get();
        foreach ($files as $file){
            Filesystem::remove($file);
        }
        return $this;
    }

    function removeChildWhere($where)
    {
        $query =  FilesystemModel::where('parent_id','=', $this->id);
        foreach ($where as $val){
            if($val[1] == 'in') {
                $query->whereIn($val[0], $val[2]);
            }else{
                $query->where($val[0], $val[1], $val[2]);
            }
        }
        $files = $query->get();
        foreach ($files as $file){
            Filesystem::remove($file);
        }
        return $this;
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
        $cache = RedisUtil::getLocalPathCache($this);
        if(!$cache){
            if($this->type == FilesystemTypeEnum::LINK){
                RedisUtil::createLocalPathCache(FilesystemModel::where([
                    ['uuid', '=', $this->uuid],
                    ['type', '!=', FilesystemTypeEnum::DATA]
                ])->first());
            }else{
                RedisUtil::createLocalPathCache($this);
            }
            $cache = RedisUtil::getLocalPathCache($this);
        }
        return RedisUtil::getLocalPathCache($this);
    }

    function linePath()
    {
        $cache = RedisUtil::getLinePathCache($this);
        if(!$cache){
            if($this->type == FilesystemTypeEnum::LINK){
                RedisUtil::createLinePathCache(FilesystemModel::where([
                    ['uuid', '=', $this->uuid],
                    ['type', '!=', FilesystemTypeEnum::LINK]
                ])->first());
            }else{
                RedisUtil::createLinePathCache($this);
            }
            $cache = RedisUtil::getLinePathCache($this);
        }
        return $cache;
    }
}