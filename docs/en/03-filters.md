# Filters

This library contain 4 powerful action filters. All filters is subclasses of `AbstractFilter`.

### FieldsFilter

FieldsFilter contain constraints for requested model fields.

Example usage:

```php
public function behaviors()
{
return [
    'fieldsFilter' => [
        'class' => FieldsFilter::class,
        'only' => ['index', 'view'],
        'fields' => ['id', 'name'],
    ],
];
}
```

`fields` property contain list of allowed to querying model fields. If it's empty, all model fields are allowed.
If it's not empty, only this model fields are allowed. If in query are requested others fields,
`BadRequestHttpException` will be thrown.

### ExpandFilter

ExpandFilter contain constraints for requested model relations fields.

Example usage:

```php
public function behaviors()
{
return [
    'expandFilter' => [
        'class' => ExpandFilter::class,
        'only' => ['index', 'view'],
        'fields' => ['comments'],
    ],
];
}
```

`fields` property contain list of model relations which allowed to querying. If it's empty, all model relations are
allowed. If it's not empty, only this model relations are allowed. If in query are requested others relations,
`BadRequestHttpException` will be thrown.

### SortFilter

SortFilter contain constraints for requested sort fields.

Example usage:

```php
public function behaviors()
{
return [
    'sortFilter' => [
        'class' => SortFilter::class,
        'only' => ['index'],
        'fields' => ['id', 'comment.id' => 'comments'],
    ],
];
}
```

`fields` property contain list of fields which allowed to sorting. It may be string of model field or array, where key
is relation field in dot notation, value is relation name. If it's empty, all model fields are
allowed. If it's not empty, only this model fields are allowed. If in query are requested others fields,
`BadRequestHttpException` will be thrown.

### ConditionFilter

ConditionFilter supplements `yii\data\DataFilter` due to the possibility of obtaining conditions in the GET parameters
of the request.

Example usage:

```php
public function behaviors()
{
return [
    'conditionFilter' => [
        'class' => ConditionFilter::class,
        'only' => ['index'],
    ],
];
}
```


Now you can add conditions using GET-parameters:

```
GET http://sitename.com/api/users?name=john
```


← [Creating a REST-service](02-restful.md)
