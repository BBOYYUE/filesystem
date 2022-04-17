<?php

namespace Bboyyue\Filesystem\Repositiories\Interfaces;

interface FilesystemInterface
{
    function createDir($name, $option = []);
    function createData($filePath);
    function move($dir);
    function copy($dir);
    function remove();
    function rename($name);

    function path();
    function url();

}