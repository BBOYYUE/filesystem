<?php

namespace Bboyyue\Filesystem\Repositiories\Impl;

use Bboyyue\Filesystem\Enum\FilesystemStatusEnum;
use Bboyyue\Filesystem\Enum\FilesystemTypeEnum;
use Bboyyue\Filesystem\Exception\FilesystemTypeErrorException;
use Bboyyue\Filesystem\Module\Filesystem;
use Bboyyue\Filesystem\Repositiories\Interfaces\FilesystemInterface;
use Bboyyue\Filesystem\Module\Filesystem as FilesystemModel;
use Bboyyue\Filesystem\Util\SyncUtil;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Sollado\File\Util\SolladoFileUtil;

Trait FilesystemTrait
{

    function createDir($name)
    {
        $filesystem = Filesystem::create([
            'name' => $name,
            'type' => FilesystemTypeEnum::DIR,
            'status' => FilesystemStatusEnum::WAITING,
            'uuid' => Str::uuid(),
            'user_id' => $this->user_id??1,
            'group_id' => $this->group_id??1,
        ]);
        $this->createChild($filesystem);
        return $this;
    }

    function createStorehouse($name, $option = []){
        $filesystem = Filesystem::create([
            'name' => $name,
            'type' => FilesystemTypeEnum::DIR,
            'status' => FilesystemStatusEnum::WAITING,
            'uuid' => Str::uuid(),
            'user_id' => isset($option['user_id'])??1,
            'group_id' => isset($option['group_id'])??1,
        ]);
        $filesystem->makeRoot();
        return $filesystem;
    }

    /**
     * @throws FilesystemTypeErrorException
     * @throws \Jiaxincui\ClosureTable\Exceptions\ClosureTableException
     */
    function createData($filePath, $option = [])
    {
        if($this->type === FilesystemTypeEnum::DATA) throw new FilesystemTypeErrorException($this->name. "的类型为".$this->type);
        $extension = pathinfo($filePath, PATHINFO_EXTENSION);
        $basename = trim(basename($filePath,$extension),'.');
        $dirname = dirname($filePath);
        $uuid = Str::uuid();
        $alias = Str::before($uuid, '-');
        $nextPath = $dirname.'/'.$alias.'.'.$extension;
        rename($filePath, $nextPath);
        $filesystem = Filesystem::create([
            'name' => $basename,
            'type' => FilesystemTypeEnum::DATA,
            'status' => FilesystemStatusEnum::WAITING,
            'alias' => $alias,
            'uuid' => $uuid,
            'user_id' => isset($option['user_id'])??1,
            'group_id' => isset($option['group_id'])??1,
        ]);
        $this->createChild($filesystem);

        if(is_file($nextPath)){
            $filesystem->addMedia($nextPath)->preservingOriginal()->toMediaCollection($extension);
        } elseif (is_dir($nextPath)){
            Storage::disk('public')->put('temp/'.$alias.'.dir', time());
            $path = Storage::disk('public')->path('temp/'.$alias.'.dir');
            // 将临时文件保存至路径
            $media = $filesystem->addMedia($path)->preservingOriginal()->toMediaCollection('dir');

            // 获取这个文件在线上的路径
            $media_path = Str::beforeLast($media->getPath(),'temp/'.$alias.'.dir').$alias.'.dir';
            if(env('MEDIA_DISK') === 'oss') {
                SyncUtil::SyncToOss($nextPath, $media_path, 'alpha-web');
            }else{
                SyncUtil::SyncToS3($nextPath, $media_path, 'alpha-api');
            }
        }
    }


    function move($folder)
    {
        $this->moveTo($folder);
    }


    function remove()
    {
        // TODO: Implement remove() method.
    }

    function rename($name)
    {
        $this->name = $name;
    }

    function path()
    {
        // TODO: Implement path() method.
    }

    function url()
    {
        // TODO: Implement url() method.
    }


}