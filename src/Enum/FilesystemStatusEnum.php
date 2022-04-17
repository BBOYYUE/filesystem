<?php

namespace Bboyyue\Filesystem\Enum;

use BenSampo\Enum\Enum;

final class FilesystemStatusEnum extends Enum
{
    /**
     * 处理中
     */
    const PENDING = 0;
    /**
     * 已成功
     */
    const SUCCESS = 1;
    /**
     * 已失败
     */
    const FAILED = 2;
    /**
     * 等待中
     */
    const WAITING = 3;
}