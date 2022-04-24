<?php

namespace Bboyyue\Filesystem\Repositiories\Impl;


use Bboyyue\Filesystem\Model\FilesystemModel;
use Exception;

/**
 * Created by : PhpStorm
 * User: yehui
 * Date: 2022/4/23
 * Time: 10:11
 */
trait FilesystemTrait
{
    /**
     * @param $dir
     * @return $this
     */
    function bindFilesystemDir($dir)
    {
        $dir->model_type = __CLASS__;
        $dir->model_id = $this->id;
        $dir->save();
        return $this;
    }

    /**
     * @param $filepath
     * @param null $dir
     * @return $this|FilesystemTrait
     * @throws Exception
     */
    function addFilesystemData($filepath, $dir = null)
    {
        if ($dir) {
            $dir->addData($filepath);
            return $this;
        }
        $dir = FilesystemModel::where([
            ['model_type', '=', __CLASS__],
            ['model_id', '=', $this->id]
        ])->first();
        if (!$dir) throw new Exception('资源状态异常');
        return self::addFilesystemData($filepath, $dir);
    }

    function addFilesystemDataByRequest($request, $dir = null)
    {
        if ($dir) {
            $dir->addDataByRequest($request);
            return $this;
        }
        $dir = FilesystemModel::where([
            ['model_type', '=', __CLASS__],
            ['model_id', '=', $this->id]
        ])->first();
        if (!$dir) throw new Exception('资源状态异常');
        return self::addFilesystemDataByRequest($request, $dir);
    }

    function addFilesystemDir($name, $dir = null)
    {
        if ($dir) {
            $dir->addChildDir($name);
            return $this;
        }
        $dir = FilesystemModel::where([
            ['model_type', '=', __CLASS__],
            ['model_id', '=', $this->id]
        ])->first();
        if (!$dir) throw new Exception('资源状态异常');
        return self::addFilesystemDir($name, $dir);
    }

    function listFilesystem()
    {
        return FilesystemModel::where([
            ['model_type', '=', __CLASS__],
            ['model_id', '=', $this->id]
        ])->get();
    }

    function listFilesystemLocalPath()
    {
//        todo
    }

    function removeFilesystem($file)
    {
        $file->delete();
        return $this;
    }

    function renameFilesystem($file, $name)
    {
        $file->name = $name;
        $file->save();
        return $this;
    }

    function setTagFilesystem($file, $tag)
    {
        $file->setTag($tag);
        return $this;
    }

    function getFilesystemPath($file)
    {
        // todo
    }

    function getFilesystemLocalPath($file)
    {

    }
}