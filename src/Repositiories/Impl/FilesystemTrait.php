<?php

namespace Bboyyue\Filesystem\Repositiories\Impl;


use Bboyyue\Filesystem\Enum\FilesystemTypeEnum;
use Bboyyue\Filesystem\Model\FilesystemModel;
use Bboyyue\Filesystem\Repositiories\Facade\Filesystem;
use Bboyyue\Filesystem\Util\RedisUtil;
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
     * @return $this
     */
    function bindFilesystemDir($parent = null)
    {
        $name = $this->name;
        $dir = Filesystem::makeRoot($name);
        $dir->model_type = __CLASS__;
        $dir->model_id = $this->id;
        $dir->save();
        if ($parent) $parent->addChild($dir);
        return $this;
    }

    /**
     * @param $filepath
     * @param null $option
     * @param null $dir
     * @param null $tag
     * @return $this|FilesystemTrait
     * @throws Exception
     */
    function addFilesystemData($filepath, $option = null, $dir = null, $tag = null)
    {
        if ($dir) {
            $dir->addData($filepath, $option, $tag);
            return $this;
        }
        $dir = FilesystemModel::where([
            ['model_type', '=', __CLASS__],
            ['model_id', '=', $this->id]
        ])->first();
        if (!$dir) throw new Exception('资源状态异常');
        return $this->addFilesystemData($filepath, $option, $dir, $tag);
    }

    function addFilesystemDataByRequest($request, $option = null, $dir = null, $tag = null)
    {
        if ($dir) {
            $dir->addDataByRequest($request, $option, $tag);
            return $this;
        }
        $dir = FilesystemModel::where([
            ['model_type', '=', __CLASS__],
            ['model_id', '=', $this->id]
        ])->first();
        if (!$dir) throw new Exception('资源状态异常');
        return $this->addFilesystemDataByRequest($request, $option, $dir, $tag);
    }

    function addFilesystemDataByText($text, $option = null, $dir = null, $tag = null)
    {
        if ($dir) {
            $dir->addDataByText($text, $option, $tag);
            return $this;
        }
        $dir = FilesystemModel::where([
            ['model_type', '=', __CLASS__],
            ['model_id', '=', $this->id]
        ])->first();
        if (!$dir) throw new Exception('资源状态异常');
        return $this->addFilesystemDataByText($text, $option, $dir, $tag);
    }

    function addFilesystemDir($name, $option = null, $dir = null, $tag = null)
    {
        if ($dir) {
            $dir->addChildDir($name, $option, $tag);
            return $this;
        }
        $dir = FilesystemModel::where([
            ['model_type', '=', __CLASS__],
            ['model_id', '=', $this->id]
        ])->first();
        if (!$dir) throw new Exception('资源状态异常');
        return $this->addFilesystemDir($name, $dir, $option, $tag);
    }


    function addFilesystemLink($name, $dir = null, $source = null)
    {
        if ($dir) {
            $dir->addChildLink($name, $dir, $source);
            return $this;
        }
        $dir = FilesystemModel::where([
            ['model_type', '=', __CLASS__],
            ['model_id', '=', $this->id]
        ])->first();
        if (!$dir) throw new Exception('资源状态异常');
        return $this->addFilesystemLink($name, $dir, $source);
    }

    /**
     *
     * @param int $type
     * @param string $extension
     * @param array $option
     * @param array $tag
     * @param string $tagAllOrAny
     * @return mixed
     */
    function listFilesystem($type = 0, $extension = '', $option = [], $tag = [], $tagAllOrAny = '')
    {
        if ($tag) {
            if ($tagAllOrAny === 'all') {
                $query = FilesystemModel::withAllTags($tag);
            } else {
                $query = FilesystemModel::withAnyTags($tag);
            }
        } else {
            $query = new FilesystemModel;
        }
        $where = [
            ['model_type', '=', __CLASS__],
            ['model_id', '=', $this->id]
        ];
        if ($option) $where = array_merge($where, $option);
        if ($type) {
            $where[] = ['type', '=', $type];
        }
        if ($extension) {
            $where[] = ['extension', '=', $extension];
        }

        return $query->where($where)->get();
    }


    function listFilesystemData()
    {
        return FilesystemModel::where([
            ['model_type', '=', __CLASS__],
            ['model_id', '=', $this->id],
            ['type', '=', FilesystemTypeEnum::DATA]
        ])->orWhere([
            ['model_type', '=', __CLASS__],
            ['model_id', '=', $this->id],
            ['type', '=', FilesystemTypeEnum::LINK]
        ])->get();
    }

    /**
     * 查看当前资源绑定的文件夹
     * @return mixed
     */
    function listFilesystemDir()
    {
        return FilesystemModel::where([
            ['model_type', '=', __CLASS__],
            ['model_id', '=', $this->id],
            ['type', '=', FilesystemTypeEnum::DIR]
        ])->get();
    }

    function getFilesystemByExtension($extension)
    {
        return FilesystemModel::where([
            ['model_type', '=', __CLASS__],
            ['model_id', '=', $this->id],
            ['extension', '=', $extension]
        ])->get();
    }

    function getFilesystemByTag($tag, $tagAllOrAny = 'all')
    {
        if ($tagAllOrAny === 'all') {
            $query = FilesystemModel::withAllTags($tag);
        } else {
            $query = FilesystemModel::withAnyTags($tag);
        }
        return $query->where([
            ['model_type', '=', __CLASS__],
            ['model_id', '=', $this->id]
        ])->get();
    }

    function listFilesystemLocalPath()
    {
//        todo
    }

    function clearFilesystem()
    {
        foreach (FilesystemModel::where([
            ['model_type', '=', __CLASS__],
            ['model_id', '=', $this->id]
        ])->get() as $val) {
            $this->removeFilesystem($val);
        }
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
        $cache = RedisUtil::getLocalPathCache($file);
        if (!$cache) {
            if ($file->type == FilesystemTypeEnum::LINK) {
                RedisUtil::createLocalPathCache(FilesystemModel::where([
                    ['uuid', '=', $this->uuid],
                    ['type', '!=', FilesystemTypeEnum::LINK]
                ])->first());
            } else {
                RedisUtil::createLocalPathCache($file);
            }
            $cache = RedisUtil::getLocalPathCache($file);
        }
        return RedisUtil::getLocalPathCache($file);
    }

    function getFilesystemLinePath($file)
    {
        $cache = RedisUtil::getLinePathCache($file);
        if (!$cache) {
            if ($file->type == FilesystemTypeEnum::LINK) {
                RedisUtil::createLinePathCache(FilesystemModel::where([
                    ['uuid', '=', $this->uuid],
                    ['type', '!=', FilesystemTypeEnum::LINK]
                ])->first());
            } else {
                RedisUtil::createLinePathCache($file);
            }
            $cache = RedisUtil::getLinePathCache($file);
        }
        return $cache;
    }
}