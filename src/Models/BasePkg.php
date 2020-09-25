<?php


namespace Myhayo\Walle\Models;


class BasePkg
{
    private $basePkgTag = "";

    public function __construct()
    {
    }


    /**
     * 设置自定义基包tag
     *
     * @param $basePkgTag
     */
    public function setDefinedBasePkgTag($basePkgTag)
    {
        $this->basePkgTag = $basePkgTag;
    }


    /**
     * 设置默认规则的基包tag
     *
     * @param $appVersion
     */
    public function setDefaultBasePkgTag($appVersion)
    {
        $this->basePkgTag = 'v_' . $appVersion;
    }


    /**
     * 获得基包tag
     *
     * @return string
     */
    public function getBasePkgTag()
    {
        return $this->basePkgTag;
    }

}