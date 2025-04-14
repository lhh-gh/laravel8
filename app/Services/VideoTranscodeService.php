<?php

declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: SillyCat
 * Date: 2025-04-14
 * Time: 17:53
 */

namespace App\Services;

use App\Exceptions\BusinessException;
use Illuminate\Support\Facades\Log;
use Psr\Log\LoggerInterface;

abstract class VideoTranscodeService
{

    protected string $sourceUrl = '';
    protected array $extra = [];
    protected string $scene = '';

    /**
     * 提交转码作业
     * @param  string  $scene         场景
     * @param  string  $sourceUrl     源文件地址
     * @param  array  $extra          额外数据
     * @return mixed
     * @throws BusinessException
     */
    public function videoTranscode(string $scene, string $sourceUrl = '', array $extra = [])
    {
        Log::info(__CLASS__.'.videoTranscode param:', compact('scene', 'sourceUrl', 'extra'));

        if (!$scene) {
            Log::warning(__CLASS__.'.videoTranscode loss scene');
            throw new BusinessException(400, '缺少场景');
        }

        if (!$sourceUrl) {
            Log::warning(__CLASS__.'.videoTranscode loss sourceUrl');
            throw new BusinessException(400, '缺少源文件地址');
        }

        $this->scene = $scene;
        $this->sourceUrl = $sourceUrl;
        $this->extra = $extra;

        return call_user_func([$this, $this->scene]);
    }

    /**
     * @param  string  $func
     * @param  array  $arg
     * @throws BusinessException
     */
    public function __call(string $func = '', array $arg = [])
    {
        Log::warning(__CLASS__ . $func . ' function not Exists');
        throw new BusinessException(400, '访问不存在的方法');
    }

    /**
     * 根据源文件地址，生成转码后地址
     * @param  string  $sourceUrl
     * @return mixed
     */
    abstract public function makeCompressUrl(string $sourceUrl);

    /**
     * 根据视频地址，生成封面图地址
     * @param $videoUrl
     * @return mixed
     */
    abstract public function makeCoverImg($videoUrl);

}
