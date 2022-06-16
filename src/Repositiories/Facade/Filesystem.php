<?php

namespace Bboyyue\Filesystem\Repositiories\Facade;


use Bboyyue\Filesystem\Enum\FilesystemStatusEnum;
use Bboyyue\Filesystem\Enum\FilesystemTypeEnum;
use Bboyyue\Filesystem\Model\FilesystemModel;
use Bboyyue\Filesystem\Repositiories\Interfaces\FilesystemFacadeInterface;
use Bboyyue\Filesystem\Util\FilesystemUtil;
use Bboyyue\Filesystem\Util\MediaUtil;
use Bboyyue\Filesystem\Util\RedisUtil;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

/**
 * Created by : PhpStorm
 * User: bboyyue
 * Email bboyyue@outlook.com
 * Date: 2022/4/23
 * Time: 12:06
 */
class Filesystem implements FilesystemFacadeInterface
{


    static function createFilesystem($data): FilesystemModel
    {
        $filesystem = new FilesystemModel();
        foreach ($data as $key => $val) {
            $filesystem->$key = $val;
        }
        $filesystem->save();
        return $filesystem;
    }

    static function getDescendants($dir)
    {
        // TODO: Implement getDescendants() method.
    }

    static function addDir($dir, $name, array $option = [], $tag = [])
    {
        $uuid = Str::uuid();
        $alias = Str::before($uuid, '-');
        $data = [
            'name' => $name,
            'type' => FilesystemTypeEnum::DIR,
            'status' => FilesystemStatusEnum::PENDING,
            'uuid' => $uuid,
            'alias' => $alias,
            'model_type' => $dir->model_type ?? '',
            'model_id' => $dir->model_id ?? 0,
        ];
        if ($option) {
            foreach ($option as $key => $val) {
                $data[$key] = $val;
            }
        }
        $filesystem = self::createFilesystem($data);

        if ($tag) {
            $filesystem->attachTag($tag);
        }

        $dir->addChild($filesystem);
        return $filesystem;
    }

    static function makeRoot($name, array $option = [])
    {
        $uuid = Str::uuid();
        $alias = Str::before($uuid, '-');
        $data = [
            'name' => $name,
            'type' => FilesystemTypeEnum::DIR,
            'status' => FilesystemStatusEnum::PENDING,
            'uuid' => $uuid,
            'alias' => $alias,
        ];
        if ($option) {
            foreach ($option as $key => $val) {
                $data[$key] = $val;
            }
        }
        return self::createFilesystem($data);
    }

    static function addData($dir, $filePath, $option = [], $tag = [])
    {
        $uuid = Str::uuid();
        $fileName = basename($filePath);
        if (is_dir($filePath)) {
            $extension = Str::afterLast($filePath, '.') ?? 'dir';
        } else {
            $extension = pathinfo($fileName, PATHINFO_EXTENSION);
        }
        $alias = Str::before($uuid, '-') . '.' . $extension;
        $name = Str::before($fileName, "." . $extension);
        $temp_path = Storage::disk('local')->path(config("bboyyue-filesystems.temp_dir") . '/' . $alias);

        $data = [
            'name' => $name,
            'type' => FilesystemTypeEnum::DATA,
            'status' => FilesystemStatusEnum::PENDING,
            'uuid' => $uuid,
            'alias' => $alias,
            'extension' => $extension,
            'model_type' => $dir->model_type ?? '',
            'model_id' => $dir->model_id ?? 0,
        ];

        if ($option) {
            foreach ($option as $key => $val) {
                $data[$key] = $val;
            }
        }
        $filesystem = self::createFilesystem($data);

        if ($tag) {
            $filesystem->attachTag($tag);
        }

        $dir->addChild($filesystem);
        MediaUtil::bindMedia($filesystem, $filePath, $temp_path);
        RedisUtil::createLocalPathCache($filesystem, $temp_path);
        RedisUtil::createLinePathCache($filesystem);
        return $filesystem;
    }

    static function addDataByText($dir, $text, $option = [], $tag = [])
    {
        $uuid = Str::uuid();
        $extension = 'xml';
        $name = Str::before($uuid, '-');
        $alias = $name . '.' . $extension;
        $temp_path = Storage::disk('local')->path(config("bboyyue-filesystems.temp_dir") . '/' . $alias);
        Storage::disk('local')->put(config("bboyyue-filesystems.temp_dir") . '/' . $alias, $text);
        $data = [
            'name' => $name,
            'type' => FilesystemTypeEnum::DATA,
            'status' => FilesystemStatusEnum::PENDING,
            'uuid' => $uuid,
            'alias' => $alias,
            'extension' => $extension,
            'model_type' => $dir->model_type ?? '',
            'model_id' => $dir->model_id ?? 0,
        ];
        if ($option) {
            foreach ($option as $key => $val) {
                $data[$key] = $val;
            }
        }
        $filesystem = self::createFilesystem($data);

        if ($tag) {
            $filesystem->attachTag($tag);
        }
        $dir->addChild($filesystem);
        MediaUtil::bindMedia($filesystem, $temp_path, $temp_path);
        RedisUtil::createLocalPathCache($filesystem, $temp_path);
        RedisUtil::createLinePathCache($filesystem);
        return $filesystem;
    }

    static function addDataByRequest($dir, $request, $option = [], $tag = [])
    {
        $uuid = Str::uuid();
//        var_dump($request->file);
        $fileName = $request->file->getClientOriginalName();
        $extension = $request->file->extension();
        $alias = Str::before($uuid, '-') . '.' . $extension;
        $name = Str::before($fileName, "." . $extension);
        $temp_path = Storage::disk('local')->path(config("bboyyue-filesystems.temp_dir") . '/' . $alias);
        $request->file->storeAs(config("bboyyue-filesystems.temp_dir") ,  $alias, 'local');

        $data = [
            'name' => $name,
            'type' => FilesystemTypeEnum::DATA,
            'status' => FilesystemStatusEnum::PENDING,
            'uuid' => $uuid,
            'alias' => $alias,
            'extension' => $extension,
            'model_type' => $dir->model_type ?? '',
            'model_id' => $dir->model_id ?? 0,
        ];
        if ($option) {
            foreach ($option as $key => $val) {
                $data[$key] = $val;
            }
        }
        $filesystem = self::createFilesystem($data);

        if ($tag) {
            $filesystem->attachTag($tag);
        }
        $dir->addChild($filesystem);

        MediaUtil::bindMedia($filesystem, $temp_path, $temp_path);
        RedisUtil::createLocalPathCache($filesystem, $temp_path);
        RedisUtil::createLinePathCache($filesystem);
        return $filesystem;
    }

    static function move($oldDir, $newDir)
    {

    }

    static function remove($file)
    {
        $file->delete();
    }

    static function rename($file, $name)
    {
        $file->name = $name;
        $file->save();
        return $file;
    }

    static function setTag($file, $tag)
    {
        $file->attachTag($tag);
        return $file;
    }

    static function getTag($file)
    {
        return $file->tags;
    }

    static function path($file)
    {
        // TODO: Implement path() method.
    }

    static function url($file)
    {
        // TODO: Implement url() method.
    }

    static function localPath($file)
    {
        return RedisUtil::getLocalPathCache($file);
    }

    static function linePath($file)
    {
        return RedisUtil::getLinePathCache($file);
    }
}