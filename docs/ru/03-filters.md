

# Фильтры

Расширение содержит 4 дополнительных фильтра. Все фильтры расширяют класс `AbstractFilter`.

### FieldsFilter

FieldsFilter содержит ограничения для запрашиваемых полей модели.

Пример:

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

Свойсто `fields` содержит список полей модели, которые можно запросить. Если оно пустое, все поля модели разрешено
запрашивать, если не пустое - только поля из списка. Если запрашиваются другие поля, будет выброшено исключение
`BadRequestHttpException`.

### ExpandFilter

ExpandFilter содержит ограничения для запрашиваемых связей модели.

Пример:

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

Свойство `fields` содержит список связей модели, которые можно запросить. Если оно пустое, все связи модели разрешено
запрашивать, если не пустое - только связи из списка. Если запрашиваются другие связи, будет выброшено исключение
`BadRequestHttpException`.

### SortFilter

SortFilter содержит ограничения для полей, по которым разрешено сортировать.

Пример:

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

Свойство `fields` содржит список полей, по которым можно сортировать. Каждый элемент может быть строкой - именем поля
модели или массивом, где ключ - поле связи модели (с использованием `dot notation`, синтаксис с точкой), а значение -
имя связи модели. Если оно пустое, сортировка по любому из полей модели и полей связей модели разрешена, если не пустое
- только по полям из списка. Если запрашиваются другие поля, будет выброшено исключение `BadRequestHttpException`.

### ConditionFilter

ConditionFilter дополняет `yii\data\DataFilter` за счёт возможности передавать условия в GET-параметрах запроса.

Пример:

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

Теперь можно задавать условия при помощи параметров запроса:

```
GET http://sitename.com/api/users?name=john
```


← [Создание веб-сервиса](02-restful.md)
