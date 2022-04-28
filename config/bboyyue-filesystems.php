<?php

use Illuminate\Support\Facades\Storage;

return [
    "temp_dir" => 'bboyyue_filesystem_temp_dir',
    "job"=> "filesystem_job",
    "redis" => [
        /**
         * 文件路径缓存
         */
        "path_local_cache" => "filesystem_path_local_cache",

        /**
         * 线上文件路径缓存
         */
        "path_line_cache" => "filesystem_path_line_cache",
        /**
         * 任务进度
         */
        "progress" => "filesystem_progress",

        /**
         * info
         * 保存任务的详细信息
         */
        "info" => "asset_info",

        /**
         * working
         */
        "working" => "asset_working",

        /**
         * waiting
         */
        "waiting" => "filesystem_waiting",

        /**
         * pending
         */
        "pending" => "filesystem_pending",

        /**
         * success
         */
        "success" => "filesystem_success",

        /**
         * failed
         */
        "failed" => "filesystem_failed"
    ],
    "tag" => [
        ""
    ]
];