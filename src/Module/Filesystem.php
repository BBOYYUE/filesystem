<?php

namespace Bboyyue\Filesystem\Module;

use Bboyyue\Filesystem\Repositiories\Impl\FilesystemTrait;
use Bboyyue\Filesystem\Repositiories\Interfaces\FilesystemInterface;
use BenSampo\Enum\Traits\CastsEnums;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\EloquentSortable\SortableTrait;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\Tags\HasTags;
use Jiaxincui\ClosureTable\Traits\ClosureTable;

/**
 * @method static where(string $string, $uuid)
 */
class Filesystem extends Model implements HasMedia, FilesystemInterface
{
    use HasFactory, InteractsWithMedia, HasTags, ClosureTable, SortableTrait, CastsEnums, FilesystemTrait;

    /**
     * closures table 实现父子关系维护
     * @var string
     */
    protected string $closureTable = 'filesystem_closures';

    /**
     * 排序功能
     * @var array
     */
    public array $sortable = [
        'order_column_name' => 'order',
        'sort_when_creating' => true,
    ];

}