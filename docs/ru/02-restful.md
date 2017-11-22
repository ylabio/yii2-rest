# Создание веб-сервиса

### ActiveController

Расширяет класс `yii\rest\Controller`.

Включает классы-действия и класс, который описывает формат ответа.

Для каждой сущности вашего API создавайте контроллер, расширяющий `ActiveController`.

Контроллер содержит следующие дополнительные свойства:
- `$fieldsAttributes` - содержит конфигурацию фильтра [FieldsFilter](03-filters.md#fieldsfilter).
- `$expandAttributes` - содержит конфигурацию фильтра [ExpandFilter](03-filters.md#expandfilter).
- `$sortAttributes` - содержит конфигурацию фильтра [SortFilter](03-filters.md#sortfilter).
- `$filterAttributes` - содержит конфигурацию фильтра [ConditionFilter](03-filters.md#conditionfilter).

### Serializer

Сериализует ответ REST приложения. Этот класс является свойством `ActiveController`.

Используется в `ActiveController` для корректного ответа при ошибках.

### IndexAction

Расширяет метод `prepareDataProvider()` для добавления связей (с помощью класса `RelationKeeper`), сортировки (с
помощью [SortFilter](03-filters.md#sortfilter)) и дополнительных условий запроса (с помощью
[ConditionFilter](03-filters.md#conditionfilter)).

### CreateAction

Расширяет метод `run()` для сохранения связанных моделей вместе с родительской (с помощью трейта `RelationSaver`).

### UpdateAction

Расширяет метод `run()` для сохранения связанных моделей вместе с родительской (с помощью трейта `RelationSaver`).

### LinkAction

Класс действия, которое прикрепляет связанные модели.

Запрос должен выглядеть следующим образом - масси, где ключами являются имя связей, а значениями - идентификаторы
связанных моделей.

Пример:

```
['comments' => [1, 2, 3], 'tags' => [1, 2]]
```

### UnlinkAction
Класс действия для удаления связанных моделей.

Синтаксис аналогичен `LinkAction`.


← [Установка](01-installation.md) | [Фильтры](03-filters.md) →