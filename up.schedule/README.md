## О проекте
### Расписание
#### Учебный проект Битрикс Университет
#### Стек проекта:
1. Bitrix Framework
2. PHP - 8.1
3. MySQL - 5.7
4. Apache - 2.4
#### Процесс установки
...

После установки модуля необходимо перейти на новую систему роутинга:

1. Перенаправить обработку 404 ошибок на файл routing_index.php в файле .htaccess:
```
#RewriteCond %{REQUEST_FILENAME} !/bitrix/urlrewrite.php$  
#RewriteRule ^(.*)$ /bitrix/urlrewrite.php [L]  
RewriteCond %{REQUEST_FILENAME} !/bitrix/routing_index.php$  
RewriteRule ^(.*)$ /bitrix/routing_index.php [L]
```
2. В файле bitrix/.settings.php в секции routing указать:
```
'routing' => [ 'value' => [  
    'config' => [ 'schedule.php' ],  
]],
```

А также создать файл local/php_interface/init.php, в котором подключить данный модуль:
```
<?php

use Bitrix\Main\Loader;

Loader::includeModule('up.schedule');
```