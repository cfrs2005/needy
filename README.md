
# needy 1.0 - by php yii2 mysql youzan

fork python  https://github.com/abbeyokgo/youzan_yaofan

demo http://yaofan.88bnb.com


## init system

```
composer install

```

## edit config

```
vim config/db.php

vim config/params.php
```



## install db
```
php yii migrate
```

## add crontab plan

```
crontab -e

*/5 * * * * cd $path;php yii pay/run > /tmp/run.log
```


**准备环境**


1. 提前注册好[有赞](https://j.youzan.com/tMzSKY)，注册好有赞之后，再注册[有赞云](https://console.youzanyun.com/register)个人开发者

2. 店铺授权
- 创建可授权店铺: 微商城店铺 或者 有赞微小店 都可以。前者面向商家提供功能完善的线上商城，后者面向个人提供分销商品和自营工具可免费使用；
- 应用授权: 创建完店铺后，再登录到有赞云控制台创建自用型应用并授权刚创建的店铺；

3. 获取密钥/店铺信息
进入[有赞云后台](https://console.youzanyun.com/application/setting)分别获取：
    - client_id
    - client_secret
    - 店铺id

 
