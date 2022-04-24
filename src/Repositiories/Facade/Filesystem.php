<?php

namespace Bboyyue\Filesystem\Repositiories\Facade;


use Bboyyue\Filesystem\Enum\FilesystemStatusEnum;
use Bboyyue\Filesystem\Enum\FilesystemTypeEnum;
use Bboyyue\Filesystem\Model\FilesystemModel;
use Bboyyue\Filesystem\Repositiories\Interfaces\FilesystemFacadeInterface;
use Bboyyue\Filesystem\Util\MediaUtil;
use Bboyyue\Filesystem\Util\RedisUtil;
use Illuminate\Support\Facades\Storage;

/**
 * Created by : PhpStorm
 * User: bboyyue
 * Email bboyyue@outlook.com
 * Date: 2022/4/23
 * Time: 12:06
 */
class Filesystem implements FilesystemFacadeInterface
{

    function getDescendants($dir)
    {
        // TODO: Implement getDescendants() method.
    }

    function addDir($dir, $name, $option)
    {
        $uuid = Str::uuid();
        $alias = Str::before($uuid, '-');
        $filesystem = FilesystemModel::create([
            'name' => $name,
            'type' => FilesystemTypeEnum::DIR,
            'status' => FilesystemStatusEnum::PENDING,
            'uuid' => $uuid,
            'alias' => $alias,
            'model_type' => $dir->model_type,
            'model_id' => $dir->id,
        ]);
        $dir->addChild($filesystem);
        return $dir;
    }

    function makeRoot($name, array $option = [])
    {
        $uuid = Str::uuid();
        $alias = Str::before($uuid, '-');
        $filesystem = FilesystemModel::create([
            'name' => $name,
            'type' => FilesystemTypeEnum::DIR,
            'status' => FilesystemStatusEnum::PENDING,
            'uuid' => $uuid,
            'alias' => $alias,
        ]);
        return $filesystem;
    }

    function addData($dir, $filepath)
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
            'model_type' => $dir->model_type,
            'model_id' => $dir->model_id,
        ]);
        $dir->addChild($filesystem);
        MediaUtil::bindMedia($filesystem, $temp_path);
        RedisUtil::createLocalPathCache($filesystem, $temp_path);
        return $filesystem;
    }

    function addDataByRequest($dir, $request)
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
            'model_type' => $dir->model_type,
            'model_id' => $dir->model_id,
        ]);
        $dir->addChild($filesystem);
        MediaUtil::bindMedia($filesystem, $temp_path);
        RedisUtil::createLocalPathCache($filesystem, $temp_path);
        return $filesystem;
    }

    function move($oldDir, $newDir)
    {

    }

    function remove($file)
    {
        $file->delete();
    }

    function rename($file, $name)
    {
        $file->name = $name;
        $file->save();
        return $file;
    }

    function setTag($file, $tag)
    {
       $file->attachTag($tag);
       return $file;
    }

    function getTag($file)
    {
        return $file->tags;
    }

    function path($file)
    {
        // TODO: Implement path() method.
    }

    function url($file)
    {
        // TODO: Implement url() method.
    }

    function localPath($file)
    {
        // TODO: Implement localPath() method.
    }
}