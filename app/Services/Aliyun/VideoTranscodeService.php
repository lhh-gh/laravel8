<?php

declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: SillyCat
 * Date: 2025-04-14
 * Time: 18:07
 */

namespace App\Services\Aliyun;

use App\Services\VideoTranscodeService as TranscodeService;
use Illuminate\Support\Facades\Log;

class VideoTranscodeService extends TranscodeService
{

    /**
     * VideoTranscodeService constructor.
     * @throws ClientException
     */
    public function __construct()
    {
        AlibabaCloud::accessKeyClient(AliyunConf::VIDEO_ACCESS_KEY, AliyunConf::VIDEO_ACCESS_KEY_SECRET)
            ->regionId(env('ALIYUN_VIDEO_REGION'))
            ->asGlobalClient();
    }

    /**
     * 视频转码
     * @param  string  $sourceUrl  输入视频地址
     * @param  array  $extra  额外配置参数： id、 width(视频宽度)
     * @return bool                     请求结果，仅代表请求是否成功，不代表最终转码结果
     * @throws BusinessException
     * @throws ClientException
     * @throws ServerException
     */
    protected function interaction()
    {
        Log::info(__CLASS__ . '.interaction param:', [$this->sourceUrl, $this->extra]);

        $ossLocation = env('ALIYUN_VIDEO_BUCKET_LOCATION');
        $ossBucket = env('ALIYUN_VIDEO_BUCKET_NAME');
        $userData = 'interaction#' . $this->extra['id'];
        $templateId = $this->getTemplate($this->extra['width']);

        $input = [
            'Location' => $ossLocation,
            'Bucket' => $ossBucket,
            'Object' => urlencode($this->makeInputObject())
        ];

        $outputs = [
            [
                'OutputObject' => urlencode($this->makeOutputObject()),
                'Container' => ['Format' => AliyunConf::VIDEO_TRANSCODE_FORMAT],
                'TemplateId' => $templateId,
                'UserData' => $userData
            ]
        ];

        return $this->submitTranscodeJob($input, $outputs, $ossLocation, $ossBucket);
    }

    /**
     * 提交转码作业
     * @param $input
     * @param $outputs
     * @param  string  $ossLocation
     * @param  string  $ossBucket
     * @return bool
     * @throws ClientException
     * @throws ServerException
     */
    protected function submitTranscodeJob($input, $outputs, $ossLocation = '', $ossBucket = '')
    {
        $result = AlibabaCloud::mts()
            ->v20140618()
            ->submitJobs()
            ->setAcceptFormat('JSON')
            ->withInput(json_encode($input))
            ->withOutputs(json_encode($outputs))
            ->withOutputBucket($ossBucket)
            ->withOutputLocation($ossLocation)
            ->withPipelineId(env('ALIYUN_VIDEO_TRANSCODE_PIPELINE'))
            ->request();

        return $result->isSuccess();
    }

    /**
     * 根据源文件名称，生成转码后地址(主要用作于类外直接调用)
     * @param  string  $sourceUrl  转码前源文件名称
     * @return string               转码后URL地址
     * @throws BusinessException
     */
    public function makeCompressUrl($sourceUrl = '')
    {
        if (!$sourceUrl) {
            Log::warning(__CLASS__ . '.makeCompressUrl sourceName Not Exists');
            throw new BusinessException('源文件地址错误', Error::INVALID_PARAM);
        }
        $inputFile = basename($sourceUrl);
        $outputObject = $this->makeOutputObject($inputFile);
        return env('ALIYUN_VIDEO_BUCKET_URL') . $outputObject;
    }

    /**
     * 根据视频地址，生成封面图地址
     * @param $videoUrl
     * @return string
     */
    public function makeCoverImg($videoUrl)
    {
        return $videoUrl . '?x-oss-process=video/snapshot,t_0,w_0,h_0,m_fast';
    }

    /**
     * 生成输出文件bucket地址
     * @param $inputFile string 输入文件 -用于类外部直接调用makeCompressUrl时生成的路径
     * @return string
     * @throws BusinessException
     */
    protected function makeOutputObject($inputFile = '')
    {
        if (isset(VideoTranscodeConf::$videoPath[$this->scene]['output'])) {
            if (!$inputFile) {
                $inputFile = basename($this->sourceUrl);
            }

            return VideoTranscodeConf::$videoPath[$this->scene]['output'] . md5(
                $inputFile
            ) . '.' . AliyunConf::VIDEO_TRANSCODE_FORMAT;
        } else {
            Log::error(__CLASS__ . '.makeOutputObject outputpath not config');
            throw new BusinessException('缺少输出配置文件', Error::INVALID_PARAM);
        }
    }

    /**
     * 生成输入文件bucket地址
     * @return string
     * @throws BusinessException
     */
    protected function makeInputObject()
    {
        if (isset(VideoTranscodeConf::$videoPath[$this->scene]['input'])) {
            return VideoTranscodeConf::$videoPath[$this->scene]['input'] . basename($this->sourceUrl);
        } else {
            Log::error(__CLASS__ . '.makeInputObject inputpath not config');
            throw new BusinessException('缺少输入配置文件', Error::INVALID_PARAM);
        }
    }

    /**
     * 根据宽度定义转码模板
     * @param  int  $width
     * @return string
     */
    protected function getTemplate($width = 0)
    {
        if ($width > 1280) {
            $templateId = AliyunConf::VIDEO_TRANSCODE_HD;   //高清
        } else {
            $templateId = AliyunConf::VIDEO_TRANSCODE_SD;   //标清
        }
        return $templateId;
    }
}
