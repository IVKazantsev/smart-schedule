## О проекте
### SMART-Schedule
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

Создать файл local/php_interface/init.php, в котором подключить данный модуль:
```
<?php

use Bitrix\Main\Loader;

Loader::includeModule('up.schedule');
```

    Создать пользовательские поля из панели администратора:
    1. Перейти в your_domain/bitrix/
    2. Авторизироваться от имени администратора
    3. Перейти в Настройки -> Настройки продукта -> Пользовательские поля
    4. Нажать кнопку добавить
    5. Выбрать тип "Целое число"
    6. В объект вписать "USER"
    7. Вписать код поля UF_ROLE_ID
    8. Нажать сохранить
    9. Произвести те же шаги, но в пункте 7 указать код поля UF_GROUP_ID

3. Установить Composer в папку bitrix, а также библиотеку PHPSpreadSheet для работы с Excel-файлами
