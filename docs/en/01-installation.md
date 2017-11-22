# Installation

The preferred way to install this extension is through [composer](http://getcomposer.org/download/).

Either run

```
php composer.phar require --prefer-dist ylab/rest "*"
```

or add

```
"ylab/rest": "*"
```

to the require section of your `composer.json` file.

## Controller Connection

In order to start using the library it is necessary to inherit from the `ActiveController`.

In your controller it is necessary to specify the class of model with which the library will work.

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

In addition to inheritance from the controller, for correct work of the library it is necessary to specify the
response format - JSON.

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

For correct error responses necessary add custom `errorHandler` to your RESTful app config:

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

After completing the above steps, the library is ready for use.


[Creating a REST-service](02-restful.md) →
