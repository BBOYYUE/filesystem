<?php

namespace Bboyyue\Filesystem\Model;

use Bboyyue\Filesystem\Repositiories\Impl\FilesystemModelTrait;
use Bboyyue\Filesystem\Repositiories\Interfaces\FilesystemModelInterface;
use BenSampo\Enum\Traits\CastsEnums;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\EloquentSortable\Sortable;
use Spatie\EloquentSortable\SortableTrait;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\Tags\HasTags;
use Jiaxincui\ClosureTable\Traits\ClosureTable;

/**
 * @method static where(string $string, $uuid)
 */
class FilesystemModel extends Model implements Sortable, HasMedia, FilesystemModelInterface
{
    use HasFactory, InteractsWithMedia, HasTags, ClosureTable, SortableTrait, CastsEnums, FilesystemModelTrait;


    protected $fillable = [
        'name',
        'type',
        'status',
        'order',
        'uuid',
        'alias',
        'option',
        'extension',
        'model_type',
        'model_id',
        'user_id',
        'group_id',
        'use_type',
        'parent_id'
    ];


    /**
     * closures table 实现父子关系维护
     * @var string
     */
    protected string $closureTable = 'filesystem_closures';

    protected $ancestorColumn = 'ancestor';

    protected $descendantColumn = 'descendant';

    protected $distanceColumn = 'distance';

    protected $parentColumn = 'parent_id';

    /**
     * 数据表名
     * @var string
     */
    protected $table = 'filesystems';

    /**
     * 排序功能
     * @var array
     */
    public array $sortable = [
        'order_column_name' => 'order',
        'sort_when_creating' => true,
    ];

    /**
     * 在排序时按照某个字段进行分组
     * @return mixed
     */
    public function buildSortQuery()
    {
        return static::query()->where([
            ['model_id','=' ,$this->model_id],
            ['model_type', '=', $this->model_type]
        ]);
    }

}