
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
