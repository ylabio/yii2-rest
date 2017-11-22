# Creating a REST-service

### ActiveController

The child of `yii\rest\Controller` class.

Includes action-classes and a class that describes the format of the response.

For each entity in API, is created one controller, child of `ActiveController`.

Contains following additional properties:
- `$fieldsAttributes` - contains configuration for [FieldsFilter](03-filters.md#fieldsfilter).
- `$expandAttributes` - contains configuration for [ExpandFilter](03-filters.md#expandfilter).
- `$sortAttributes` - contains configuration for [SortFilter](03-filters.md#sortfilter).
- `$filterAttributes` - contains configuration for [ConditionFilter](03-filters.md#conditionfilter).

### Serializer

Serialize the response of a REST application. The class is registered in the `ActiveController` properties.

Used in `ActiveController` for correct output of errors.

### IndexAction

Overrides method `prepareDataProvider()` for add joins (through `RelationKeeper` class), add order by (through
[SortFilter](03-filters.md#sortfilter)) and add query conditions (through [ConditionFilter](03-filters.md#conditionfilter)).

### CreateAction

Overrides method `run()` for saving model relations together with model (through `RelationSaver` trait).

### UpdateAction

Overrides method `run()` for saving model relations together with model (through `RelationSaver` trait).

### LinkAction

Action for adding related models to existing model.

Request must contains body parameters for info about related models as relation name => related models ids.

Example:

```
['comments' => [1, 2, 3], 'tags' => [1, 2]]
```

### UnlinkAction
Action for removing related models to existing model.

Syntax is same as in `LinkAction`.


← [Installation](01-installation.md) | [Filters](03-filters.md) →
