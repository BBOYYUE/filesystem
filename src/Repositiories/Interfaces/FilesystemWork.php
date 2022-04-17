<?php

namespace Bboyyue\Filesystem\Repositiories\Interfaces;

use Closure;

interface FilesystemWork
{
    public function handle($content, Closure $next);
}