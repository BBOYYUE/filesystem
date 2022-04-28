<?php


namespace Bboyyue\Filesystem;


class ServiceProvider extends \Illuminate\Support\ServiceProvider
{
    public function boot(){
        if(!is_file(config_path('bboyyue-filesystems.php'))){
            $this->publishes([
                __DIR__ . '/../config/bboyyue-filesystems.php' => config_path('bboyyue-filesystems.php'),
            ], 'config');
        }
        if (!class_exists('CreateFilesystemTable') && !class_exists('CreateFilesystemClosureTable')) {
            $this->publishes([
                __DIR__ . '/../database/migrations/create_filesystem_table.php.stub' => database_path('migrations/' . date('Y_m_d_His', time()) . '_create_filesystem_table.php'),
            ], 'migrations');
            $this->publishes([
                __DIR__ . '/../database/migrations/create_filesystem_closure_table.php.stub' => database_path('migrations/' . date('Y_m_d_His', time()) . '_create_filesystem_closure_table.php'),
            ], 'migrations');
        }
    }

    public function register()
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/bboyyue-filesystems.php', 'bboyyue-filesystems');
    }
}