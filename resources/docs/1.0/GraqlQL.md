# GraphQL 项目规范

---

>**作者：朱智超**

## Types

### 常规类型

1. 根据 `Model` 进行创建，并按照对应目录放置
2. `Type` 的 `description` 必须要填写，方便生成文档时查看
3. 主键 `id` 的，一定要使用 `Type::id()` 进行定义
4. 根据字段类型，选择使用 `id` / `int` / `string` / `数组` / `对象`
5. 如果有需要使用的关联或 `JSON` 类型，自行构建对应的关联
6. 名称为模型名称加 `Type` 结尾

### 输入类型

1. 如果是输入类型的 `input` 的话，建到模型下的 `input` 目录内
2. 命名：对应的 `query` 或 `mutation` 完整名称 + `InputType`
     
     >>>迅速知道这个 `input` 是哪个文件的
     
     >>>同时通过结尾能迅速得知是 `InputType` 类型

## Query

### 文件命名

1. 如果是分页，请用 单数的模型名称 + `PaginatorQuery` 进行命名。
2. 如果不带分页，仅仅是列表 复数的模型名称 + `Query` 进行命名。

### 参数命名

1. 如果是分页的话，这样进行定义。

```php
'paginator'  => [
    'name'        => 'paginator',
    'type'        => Type::nonNull(GraphQL::type('PaginatorInput')),
    'description' => 'Display a specific page',
    'rules'       => ['required'],
],
```

2. 如果仅仅有少量的查询内容，可以直接自行定义。

```php
'keyword'   => [
    'type' => Type::string(),
],
'status'   => [
    'type' => Type::string(),
],
```

3. 如果有更多的查询内容，建议新建 type 需要根据输入内容进行定义。

```php
'more' => [
    'name'        => 'more',
    'type'        => GraphQL::type('CustomerHistoryPaginatorInputType'),
    'description' => '更多搜索参数',
],
```

>>> 特别注意，如果定义的为 `more` 名称的输入内容，查询时会自动把该内容放置到和 `paginator` 同级，以方便查询
>>> 例如：

```json
{
	"paginator": {
		"page": 1,
		"limit": 20
	},
	"more": {
	  "keyword": "123123"
	}
}
```

等同于

```json
{
	"paginator": {
		"page": 1,
		"limit": 20
	},
	"keyword": "123123"
}
```

### 查询

1. 新建 wheres 方法，如何写，可以参考 `App\GraphQL\Queries\Fxs\FxsAdvertPaginatorQuery` 或者 `docs` 下面有个简单的文档。

```php
'status',
// 如果传参 status=1 的话 where status = '1'；
// 如果 status 前端没有传值，那么不会构造

'created_at.gt' => $this->fireInput('year', function ($year) {
    return carbon($year.'-01-01 00:00:00');
}),
// 如果 year 不传值，什么都不会构造。
//如果传值 year=2018，那么就会执行闭包方法， 根据闭包结果构造 where year>'2018-01-01 00:00:00'

'name.like' => $this->appendInput('name', '%'),
// 如果 name 不传值，什么都不会构造。
// 如果传值 name=张 ，那么就会构造 where name like '张%'

'id.in' => $this->getInputArgs('ids', []),
// 如果 ids 不传值，什么都不会构造，因为默认值为 [] ，构造时会被忽略。
// 如果传值 ids=[1,3,4] ，那么就会构造 where id in (1,3,4)

'deleted_at.is_not' => true,
// 如果判断某个字段是否为 null ，使用 is_not 或者 is ，但是注意对应的值不能为 null ，因为值为 null 时，会被自动跳过

'age' => [
    'gt' => 12,
    'lt' => 16,
],
// where age > 12 and age < 16

'height' => [
    'gt'  => '180',
    'lt'  => '160',
    'mix' => 'or',
],
// (age > 180 or age < 160)

DB::raw('3=4'),
// where 3=4

function (Builder $q) {
    $q->where('id', 4);
},
// where id=4

new ModelScope('popular'),
// 会调用的对应的模型  scopePopular 方法

new ModelScope('older', 60),
// 等同于
function (Builder $q) {
    $q->older(60);
},

'id'  => When::make(true)->success('34'),
// where id = 34
'url' => When::make(false)->success('http://www.baidu.com')->fail('http://www.google.com'),
// where url='http://www.google.com'
```

2. 进行查询

```php
Advert::getList($this->getConditions($args))
```

### Mutation

1. 命名以 `Mutation` 结尾。
2. 新增和更新尽量使用同一个操作，减少工作量。新增和更新可以通过 `id` 是否传入进行判断。参考：`App\GraphQL\Mutations\Fxs\Advert\StoreFxsAdvertMutation.php`
3. 新增和保存的内容通过 `data` 结构体进行构造：
```php
'data' => [
    'type'        => Type::nonNull(GraphQL::type('StoreFxsAdvertMutationInput')),
    'description' => '广告信息数据',
    'rules'       => ['required'],
],
```
