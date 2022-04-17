<h1 align="center"> filesystem </h1>

<p align="center"> .</p>


## Installing

```shell
$ composer require bboyyue/filesystem -vvv
```

## Usage

TODO

## 一个文件系统

- 使用 jiaxincui/closure-table 保存文件层级关系
- 使用 spatie/eloquent-sortable 保存文件顺序
- 使用 spatie/laravel-tags 保存文件类型
- 使用 spatie/laravel-medialibrary 保存文件
- 使用 laravel/framework 实现各种基本操作
- 使用 spatie/laravel-activitylog 记录日志
- 使用 bensampo/laravel-enum 保存文件状态

## 数据表设计
- folder 保存文件目录信息
- file 保存目录下的文件


## 类型

- 目录
- 文件
    - 文件夹类型文件 (包含多个文件, 且单个文件的信息不重要)
    - 单个文件类型文件

## Contributing

You can contribute in one of three ways:

1. File bug reports using the [issue tracker](https://github.com/bboyyue/filesystem/issues).
2. Answer questions or fix bugs on the [issue tracker](https://github.com/bboyyue/filesystem/issues).
3. Contribute new features or update the wiki.

_The code contribution process is not very formal. You just need to make sure that you follow the PSR-0, PSR-1, and PSR-2 coding guidelines. Any new code contributions must be accompanied by unit tests where applicable._

## License

MIT