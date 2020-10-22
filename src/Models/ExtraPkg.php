<?php


namespace Myhayo\Walle\Models;


class ExtraPkg
{
    private $extraPkgTag = "";
    private $channel = "";
    private $params = "";

    public function __construct()
    {
    }


    /**
     * 设置自定义扩展包tag
     *
     * @param $ExtraPkgTag
     */
    public function setDefinedExtraPkgTag($ExtraPkgTag)
    {
        $this->extraPkgTag = $ExtraPkgTag;
    }


    /**
     * 指定基包，设置默认规则的扩展包tag
     *
     * @param BasePkg $basePkg
     * @param         $channel
     * @param string  $params
     *
     * @return string
     */
    public function setDefaultExtraPkgTag(BasePkg $basePkg, $channel, $params = '')
    {
        $baseTag = $basePkg->getBasePkgTag();

        $this->setChannel($channel);
        $this->extraPkgTag = $baseTag . '_' . $this->getChannel();


        if ($params) {
            $this->setParams(md5($params));
            $this->extraPkgTag .= '_' . $this->getParams();
        }

        return $this->extraPkgTag;
    }


    /**
     * 获得基包tag
     *
     * @return string
     */
    public function getExtraPkgTag()
    {
        return $this->extraPkgTag;
    }


    /**
     * 设置渠道
     *
     * @param $channel
     */
    public function setChannel($channel)
    {
        $this->channel = $channel;
    }


    /**
     * 获取渠道
     *
     * @return string
     */
    public function getChannel()
    {
        return $this->channel;
    }


    /**
     * 设置$params
     *
     * @param $params
     */
    public function setParams($params)
    {
        $this->params = $params;
    }


    /**
     * 获取$params
     *
     * @return string
     */
    public function getParams()
    {
        return $this->params;
    }


    /**
     * 格式化渠道扩展包信息
     *
     * @param      $val
     *
     * @return array
     */
    public static function formatExtraPkgInfo($val)
    {
        return [
            'base_tag'  => $val['base_tag'],
            'extra_tag' => $val['extra_tag'],
            'channel'   => $val['channel'],
            'oss_url'   => $val['oss_url'],
            'status'    => $val['status'],
        ];
    }


    /**
     * 格式化最新渠道扩展包信息
     *
     * @param      $val
     *
     * @return array
     */
    public static function formatNewestExtraPkgInfo($val)
    {
        // 此地址永远指向最新包，同一渠道都一样
        $apkUrl = "";
        if ($val['apk_url']) {
            $apkUrl = config('walle.walle_service') . $val['apk_url'];
        }

        return [
            'base_tag'   => $val['base_tag'],
            'extra_tag'  => $val['extra_tag'],
            'channel'    => $val['channel'],
            'apk_url'    => $apkUrl,
            'pre_status' => $val['pre_status'],
        ];
    }
}