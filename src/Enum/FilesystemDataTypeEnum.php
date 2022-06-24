<?php


namespace Bboyyue\Filesystem\Enum;


use BenSampo\Enum\Enum;

class FilesystemDataTypeEnum extends Enum
{
    /**
     * 全景图
     */
    const PANORAMA_IMG = 1;

    /**
     * 全景缩略图
     */
    const PANORAMA_THUMB_IMG = 2;

    /**
     * 全景磁贴目录
     */
    const PANORAMA_TILES = 3;


    /**
     * 全景xml
     */
    const PANORAMA_XML = 4;

    /**
     * 全景小地图
     */
    const PANORAMA_MAP = 5;


    /**
     * three 模型
     */
    const THREE_OBJ = 6;


    /**
     * three mtl 材质
     */
    const THREE_MATERIAL= 8;

    const DESIGN_IMG = 9;

    const DESIGN_VIDEO = 10;


}