<?php


namespace Bboyyue\Filesystem;


class ServiceProvider extends \Illuminate\Support\ServiceProvider
{
    public function boot(){
        if (!class_exists('CreateSolladoFileTable')) {

        }
    }
}