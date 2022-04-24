<?php

namespace Bboyyue\Filesystem\Repositiories\Impl;


use Bboyyue\Filesystem\Enum\FilesystemStatusEnum;
use Bboyyue\Filesystem\Enum\FilesystemTypeEnum;
use Bboyyue\Filesystem\Model\FilesystemModel;
use Bboyyue\Filesystem\Util\MediaUtil;
use Bboyyue\Filesystem\Util\RedisUtil;
use Illuminate\Support\Facades\Storage;

/**
 * Created by : PhpStorm
 * User: bboyyue
 * Email bboyyue@outlook.com
 * Date: 2022/4/23
 * Time: 11:42
 */
trait FilesystemModelTrait
{
    function addChildDir($name)
    {
        $child = Filesystem::makeRoot($name);
        $child->model_type = __CLASS__;
        $child->model_id = $this->id;
        $child->save();
        $this->addChild($child);
        return $child;
    }

    function addData($filepath)
    {
        $uuid = Str::uuid();
        $fileName = basename($filepath);
        $extension = is_dir($filepath) ? 'dir' : pathinfo($fileName, PATHINFO_EXTENSION);
        $alias = is_dir($filepath) ? Str::before($uuid, '-') : Str::before($uuid, '-') . '.' . $extension;
        $name = Str::before($fileName, "." . $extension);
        $temp_path = Storage::disk('local')->path(config("bboyyue-filesystems.temp_dir") . '/' . $alias);
        copy($filepath, $temp_path);
        $filesystem = FilesystemModel::create([
            'name' => $name,
            'type' => FilesystemTypeEnum::DATA,
            'status' => FilesystemStatusEnum::PENDING,
            'uuid' => $uuid,
            'alias' => $alias,
            'extension' => $extension,
            'model_type' => $this->model_type,
            'model_id' => $this->model_id,
        ]);
        $this->addChild($filesystem);
        MediaUtil::bindMedia($filesystem, $temp_path);
        RedisUtil::createLocalPathCache($filesystem, $temp_path);
        return $filesystem;
    }

    function addDataByRequest($request)
    {
        $uuid = Str::uuid();
        $fileName = $request->file->name;
        $extension = $request->file->extension();
        $alias = Str::before($uuid, '-') . '.' . $extension;
        $name = Str::before($fileName, "." . $extension);
        $temp_path = Storage::disk('local')->path(config("bboyyue-filesystems.temp_dir") . '/' . $alias);
        $request->photo->store($temp_path, 'local');
        $filesystem = FilesystemModel::create([
            'name' => $name,
            'type' => FilesystemTypeEnum::DATA,
            'status' => FilesystemStatusEnum::PENDING,
            'uuid' => $uuid,
            'alias' => $alias,
            'extension' => $extension,
            'model_type' => $this->model_type,
            'model_id' => $this->model_id,
        ]);
        $this->addChild($filesystem);
        MediaUtil::bindMedia($filesystem, $temp_path);
        RedisUtil::createLocalPathCache($filesystem, $temp_path);
        return $filesystem;
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

    }

    function path()
    {
    }


    function localPath()
    {

    }
}