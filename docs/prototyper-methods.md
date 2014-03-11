### Methods

#### Parameters

Optional parameters:

- `featured` bool
- `random` bool
- `offset` int
- `limit` int

Example:

```php
$params = array(
	'featured' => true,
	'random' => true,
	'offset' => 2,
	'limit' => 5
);
```

Additional Squarespace parameters:

- `category` mixed
- `tag` mixed
- `raw` bool

```php
$params = array(
	'category' => array(
		'Category 1',
		'Category 2'
	),
	'tag' => array(
		'Tag 1',
		'Tag 2'
	)
);
```

Data from Squarespace is filtered by default, passing `'raw' => true` will return unfiltered data.

#### Request

For a collection of items use `get_items($collection)`:

```php
$app['client']->get_items($collection, $params = array());
```

As an example, when using Squarespace, to request this collection `http://www.destinationkors.com/sporty-sexy-glam?format=json-pretty` use:

```php
$app['client']->get_items('sporty-sexy-glam');
```

This would return an array of items.

---------------------------------------

To request one item use `get_item($item)`:

```php
$app['client']->get_item($item, $params = array());
```

When using Squarespace, requesting a single item, like `http://www.destinationkors.com/sporty-sexy-glam/sporty-sexy-glam?format=json-pretty`, use:

```php
$app['client']->get_item('sporty-sexy-glam/sporty-sexy-glam');
```

Alternatively, a collection could be passed to `get_item()`, which would return one item from the collection. 

Note: If a single item is passed to `get_item()`, `$params` are ignored.
