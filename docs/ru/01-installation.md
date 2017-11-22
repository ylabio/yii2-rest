# Установка
Лучший способ установки расширения - с помощью [composer](http://getcomposer.org/download/).

Запустите в консоли команду

```
php composer.phar require --prefer-dist ylab/rest "*"
```

или добавьте строку

```
"ylab/rest": "*"
```

в секцию `require` файла `composer.json`.

## Наследование от контроллера

Для использования библиотеки вам необходимо наследовать ваш REST-контроллер от контроллера `ActiveController`,
представленного в данном расширении.

Также в вашем контроллере необходимо указать класс модели ресурса.

```php
<?php

namespace backend\controllers;

use app\models\Post;
use ylab\rest\ActiveController;

class PostController extends ActiveController
{
    public $modelClass = Post::class;
}

```

Для корректной работы библиотеки необходимо установить свойству `format` компонента `response` значение `JSON` в
конфигурации вашего приложения.

```php
<?php

return [
    // ...
    'components' => [
        // ...
        'response' => [
            'format' => yii\web\Response::FORMAT_JSON,
            'charset' => 'UTF-8',
        ],
    ],
    // ...
];
```

Для корректных ответов при ошибках необходимо использовать `errorHandler`, поставляемый в этом расширении:

```php
<?php

return [
    // ...
    'components' => [
        // ...
        'errorHandler' => [
            'class' => ylab\rest\ErrorHandler::class,
        ],
    ],
];
```

После выполнения этих шагов расширение готово к использованию.


[Создание веб-сервиса](02-restful.md) →
