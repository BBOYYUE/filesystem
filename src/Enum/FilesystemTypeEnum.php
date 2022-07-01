<?php

namespace Bboyyue\Filesystem\Enum;

use BenSampo\Enum\Enum;

class FilesystemTypeEnum extends Enum
{
    /**
     * 目录
     */
    const DIR = 1;
    /**
     * 文件
     */
    const DATA = 2;

    /**
     * 软连接
     */
    const LINK = 3;

}