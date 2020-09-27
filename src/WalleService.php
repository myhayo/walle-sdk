<?php


namespace Myhayo\Walle;


use GuzzleHttp\Client;
use Myhayo\Walle\Models\BasePkg;
use Myhayo\Walle\Models\ExtraPkg;

class WalleService
{

    public function __construct()
    {
    }


    /**
     * 指定版本，上传基包文件
     *
     * @param BasePkg $basePkg
     * @param         $filePath
     *
     * @return mixed|string
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function uploadBasePkg(BasePkg $basePkg, $filePath)
    {
        $errMsg = '';

        $url = config('walle.walle_service') . 'api/v1/internal/' . config('walle.app_tag') . '/base-pkg';

        $basePkgTag = $basePkg->getBasePkgTag();
        if (empty($basePkgTag)) {
            $errMsg = "请先设置基包tag";
        }

        $multipartData = [
            [
                'name'     => 'base_tag',
                'contents' => $basePkgTag,
            ],
            [
                'name'     => 'apk_file',
                'contents' => fopen($filePath, 'r'),
            ],
        ];
        $result = $this->postRequest($url, $multipartData);

        if (empty($result) || $result['code'] != 0) {
            $errMsg = $result['error']['message'] ?? '上传基包文件失败';
        }

        return $errMsg;
    }


    /**
     * 分页查询基包列表
     *
     * @param        $page
     * @param        $limit
     *
     * @return array
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function getBasePkgList($page, $limit)
    {
        $list = [
            'list'  => [],
            'total' => 0,
        ];

        $url = config('walle.walle_service') . 'api/v1/internal/' . config('walle.app_tag') . '/base-pkg';
        $url .= '?page=' . $page . '&limit=' . $limit;

        $result = $this->getRequest($url);
        if (!empty($result) && $result['code'] == 0) {
            foreach ($result['data']['list'] as $val) {
                $list['list'][] = [
                    'base_tag' => $val['base_tag'],
                ];
            }

            $list['total'] = $result['data']['total'];
        }

        return $list;
    }


    /**
     * 指定基包，生成对应的渠道扩展包
     *
     * @param BasePkg $basePkg
     * @param         $channelList
     * @param int     $isNewest 是否将最新包指向于此包
     * @param string  $params   额外打包参数
     *
     * @return string
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function generateExtraPkg(BasePkg $basePkg, $channelList, $isNewest = 1, $params = '')
    {
        $errMsg = '';

        $basePkgTag = $basePkg->getBasePkgTag();
        if (empty($basePkgTag)) {
            $errMsg = "请先设置基包tag";
        }

        $url = config('walle.walle_service') . 'api/v1/internal/' . config('walle.app_tag') . '/base-pkg/' . $basePkgTag . '/extra-pkg';

        $multipartData = [
            [
                'name'     => 'channels',
                'contents' => json_encode($channelList),
            ],
            [
                'name'     => 'params',
                'contents' => $params,
            ],
            [
                'name'     => 'is_newest',
                'contents' => $isNewest,
            ],
        ];
        $result = $this->postRequest($url, $multipartData);

        if (empty($result) || $result['code'] != 0) {
            $errMsg = $result['error']['message'] ?? '扩展包文件打包失败';
        }

        return $errMsg;
    }


    /**
     * 分页查询渠道扩展包列表【可指定基包】
     *
     * @param        $page
     * @param        $limit
     * @param string $baseTag
     *
     * @return array
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function getExtraPkgList($page, $limit, $baseTag = '')
    {
        $list = [
            'list'  => [],
            'total' => 0,
        ];

        $url = config('walle.walle_service') . 'api/v1/internal/' . config('walle.app_tag') . '/extra-pkg';
        $url .= '?page=' . $page . '&limit=' . $limit;

        if (!empty($baseTag)) {
            $url .= '&base_tag=' . $baseTag;
        }

        $result = $this->getRequest($url);
        if (!empty($result) && $result['code'] == 0) {
            foreach ($result['data']['list'] as $val) {
                $list['list'][] = ExtraPkg::formatExtraPkgInfo($val);
            }

            $list['total'] = $result['data']['total'];
        }

        return $list;
    }


    /**
     * 分页查询渠道扩展包最新指向列表
     *
     * @param        $page
     * @param        $limit
     * @param string $channel 按渠道查询【如果有】
     * @param string $baseTag 按基包tag查询【如果有】
     *
     * @return array
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function getNewestExtraPkgList($page, $limit, $channel = '', $baseTag = '')
    {
        $list = [
            'list'  => [],
            'total' => 0,
        ];

        $url = config('walle.walle_service') . 'api/v1/internal/' . config('walle.app_tag') . '/extra-pkg/newest';
        $url .= '?page=' . $page . '&limit=' . $limit;

        if (!empty($channel)) {
            $url .= '&channel=' . $channel;
        }

        if (!empty($baseTag)) {
            $url .= '&base_tag=' . $baseTag;
        }

        $result = $this->getRequest($url);
        if (!empty($result) && $result['code'] == 0) {
            foreach ($result['data']['list'] as $val) {
                $list['list'][] = ExtraPkg::formatExtraPkgInfo($val);
            }

            $list['total'] = $result['data']['total'];
        }

        return $list;
    }


    /**
     * 获取分享包url
     *
     * @param BasePkg  $basePkg
     * @param ExtraPkg $extraPkg
     *
     * @return string
     */
    public function getShareUrl(BasePkg $basePkg, ExtraPkg $extraPkg)
    {
        $url = "";

        $basePkgTag = $basePkg->getBasePkgTag();
        if (empty($basePkgTag)) {
            return $url;
        }

        $extraPkgTag = $extraPkg->getExtraPkgTag();
        if (empty($basePkgTag)) {
            return $url;
        }

        $channel = $extraPkg->getChannel();
        if (empty($channel)) {
            return $url;
        }

        $url = config('walle.walle_service') . '/api/v1/redirect/' . config('walle.app_tag')
            . '/b/' . $basePkgTag . '/e/' . $extraPkgTag . '?channel=' . $channel;


        if ($extraPkg->getParams()) {
            $url .= '&params=' . $extraPkg->getParams();
        }

        return $url;
    }


    /**
     * post请求
     *
     * @param        $url
     * @param        $data
     * @param string $params_type
     *
     * @return array
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    private function postRequest($url, $data, $params_type = 'multipart')
    {
        $client = new Client();
        $options = [
            'headers' => [
                'Secret-Key' => config('walle.secrect_key'),
            ],
            'timeout' => 100,
        ];

        if ($params_type == 'multipart') {
            $options['multipart'] = $data;
        } elseif ($params_type == 'json') {
            $options['json'] = $data;
        }

        try {
            $res = $client->post($url, $options);

            return json_decode($res->getBody()->getContents(), true);
        } catch (\Exception $ex) {
        }

        return [];
    }


    /**
     * GET 请求数据
     *
     * @param $url
     *
     * @return array|mixed
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    private function getRequest($url)
    {
        $client = new Client();
        $options = [
            'headers' => [
                'Secret-Key' => config('walle.secrect_key'),
            ],
            'timeout' => 100,
        ];

        try {
            $res = $client->get($url, $options);

            return json_decode($res->getBody()->getContents(), true);
        } catch (\Exception $ex) {
        }

        return [];
    }
}