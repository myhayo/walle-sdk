# walle-sdk
php sdk of myhayo/walle-server

Usage
---
- 首先确保安装好了laravel
```
composer require myhayo/walle-sdk
```
- 然后运行下面的命令来发布资源：
```
php artisan vendor --provider="Myhayo\Walle\Provider\WalleServiceProvider"
```
- 修改对应的env配置：
```
WALLE_SERVICE:瓦力服务器域名
WALLE_SECRECT_KEY:此项目对应的Secrect-Key
WALLE_APP_TAG:此项目对应的唯一标记
```
最后，就可以愉快的使用了

Class
---
#####基础类有2个:
- BasePkg 基包:  
    - setDefinedBasePkgTag()  自定义基包tag
    - setDefaultBasePkgTag()   走默认规则基包tag

- ExtraPkg 渠道扩展包:  
    - setDefaultExtraPkgTag()  自定义扩展包tag 
    - setDefinedExtraPkgTag()  走默认规则扩展包tag
    - setChannel() 设置渠道【自定义tag才需要单独设置】
    - setParams()  设置额外参数【如果有】

FUNCTIONS
---
- 上传基包并指定版本:  
```
$basePkg = new BasePkg();
$basePkg->setDefinedBasePkgTag("****"); 
// or $basePkg->setDefaultBasePkgTag(10); 
walle()->uploadBasePkg($basePkg, $filePath)  
```


- 分页查询基包列表:  
```
walle()->getBasePkgList($page, $limit) 
```

- 指定基包和渠道，生成对应渠道扩展包 
```
$basePkg = new BasePkg();
$basePkg->setDefinedBasePkgTag("****"); 
// or $basePkg->setDefaultBasePkgTag(10); 
generateExtraPkg($basePkg, ['official','vivo'],  1, 'pid=123')
``` 
  
- 分页查询[指定/所有]基包渠道扩展包列表: 
```
walle()->getExtraPkgList(1, 10, 'your_baseTag') 
``` 

  
- 分页查询最新渠道扩展包列表:  
```
walle()->getNewestExtraPkgList(1, 10)
```


- 获取分享链接:  
```
$basePkg = new BasePkg();
$basePkg->setDefinedBasePkgTag("****"); 
// or $basePkg->setDefaultBasePkgTag(10); 


$extraPkg = new ExtraPkg();
$extraPkg->setDefinedExtraPkgTag("****"); 
$extraPkg->setChannel('official')
$extraPkg->setParams('pid=123')
// or $basePkg->setDefaultExtraPkgTag($basePkg, 'official','pid=123'); 

walle()->getShareUrl($basePkg,$extraPkg) 
```
 


