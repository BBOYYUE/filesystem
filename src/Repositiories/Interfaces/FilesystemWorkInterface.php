<?php

namespace Bboyyue\Filesystem\Repositiories\Interfaces;

use Closure;

/**
 * Interface FilesystemWork
 * 文件系统管道契约
 * @package Bboyyue\Filesystem\Repositiories\Interfaces
 */
interface FilesystemWorkInterface
{
    public function handle($content, Closure $next);
}