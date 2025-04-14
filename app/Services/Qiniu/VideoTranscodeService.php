<?php

declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: SillyCat
 * Date: 2025-04-14
 * Time: 17:58
 */

namespace App\Services\Qiniu;

use App\Exceptions\BusinessException;
use App\Services\VideoTranscodeService as TranscodeService;
use Illuminate\Support\Facades\Log;

class VideoTranscodeService extends TranscodeService
{
    /**
     * 视频转码
     * @param string $sourceUrl
     * @param array $extra
     * @return bool
     * @throws BusinessException
     */
    protected function interaction(): bool
    {
        Log::info(__CLASS__.'.interaction param:', [$this->sourceUrl, $this->extra]);

        $callbackUrl = $this->makeCallbackUrl($this->extra['id']);

        return $this->submitTranscodeJob($callbackUrl);
    }

    /**
     * 提交转码作业
     * @param $video
     * @param $callbackUrl
     * @return bool
     */
    protected function submitTranscodeJob($callbackUrl = ''): bool
    {
        //源文件
        $video = basename($this->sourceUrl);

        //认证
        $auth = new Auth(env('QINIU_ACCESSKEY'), env('QINIU_SECRETKEY'));
        $c = new \Qiniu\Processing\PersistentFop($auth);

        //输出(转码)地址
        $output = VideoTranscodeConf::$videoPath[$this->scene]['output'].md5($video).'.mp4';
        $saveAs = \Qiniu\base64_urlSafeEncode(env('QINIU_BUCKET').':'.$output);
        $fops = 'avthumb/mp4/vcodec/libx264/s/720x720/autoscale/1|saveas/'. $saveAs;

        //提交转码作业
        $ret = $c->execute(env('QINIU_BUCKET'), $video, $fops, 'video1', $callbackUrl, true);
        Log::info(__CLASS__.'.submitTranscodeJob ret:', $ret);

        if (is_array($ret) && count($ret) > 0) {
            return true;
        } else {
            return false;
        }
    }

    //生成七牛回调地址
    protected function makeCallbackUrl($extra)
    {
        $data = $this->scene.'#'.$extra;
        return route('qiniuCallback', ['data' => $data]);
    }

    /**
     * 根据源文件地址，生成转码后地址
     * @param  string  $sourceUrl
     * @return mixed|string
     * @throws BusinessException
     */
    public function makeCompressUrl(string $sourceUrl = '')
    {
        if (!$sourceUrl) {
            Log::warning(__CLASS__.'.makeCompressUrl sourceName Not Exists');
            throw new BusinessException(400, '源文件地址错误');
        }
        return env('QINIU_HOST')."/".md5(basename($sourceUrl)).".mp4";
    }

    /**
     * 根据视频地址，生成封面图地址
     * @param $videoUrl
     * @return string
     */
    public function makeCoverImg($videoUrl)
    {
        return $videoUrl.'?vframe/jpg/offset/0';
    }

}
