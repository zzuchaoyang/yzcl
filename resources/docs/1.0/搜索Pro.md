# 搜索进阶文档

---

>**作者：朱智超**

## 简介

搜索是对最常用的列表操作。为此我们针对搜索进行了优化，目的是为了更安全、高效、优雅的进行搜索的编码。

## 涉及到的文件

### Request

请求对象，是在程序运行到 `controller` 之前要进行实例化，并操作的一个类。针对每个请求，我们都可以构建一个请求对象，例如：
```php
<?php

namespace App\Http\Requests\Member\Organization;

use App\Http\Requests\Request;

class AgentIndexRequest extends Request
{
	public function authorize()
	{
		return true;
	}

	public function rules()
	{
		return [
		];
	}
}
```
这是一个针对 agent 列表搜索的一个请求类。

这个类通常默认是处理验证请求数据是否符合要求的，并返回对应的提示信息。

而查询条件也可以理解为前端传参，要查询一些数据，所以选择在这里处理请求的 conditions 数据。

### GraqhQL 查询文件

例如：`FinanceDeclarationPaginatorQuery.php` 文件，是对 GraphQL 的查询。所有的查询的字段、配置、处理请求等都在这个文件中。

```php
class FinanceDeclarationPaginatorQuery extends BaseQuery
{
    protected $attributes = [
        'name'        => 'financeDeclarationPaginator',
        'description' => '金融列表',
    ];

    public function type()
    {
        return GraphQL::pagination(GraphQL::type('FinanceDeclaration'));
    }

    public function args()
    {
        return [
            'paginator' => [
                'name'        => 'paginator',
                'type'        => Type::nonNull(GraphQL::type('PaginatorInput')),
                'description' => '分页信息对象',
            ],
            'more'      => [
                'name'        => 'more',
                'type'        => GraphQL::type('FinanceDeclarationPaginatorInput'),
                'description' => '更多搜索参数',
            ],
        ];
    }

    public function authenticated($root, $args, $currentUser)
    {
        if (is_hfd_request() && hfd_user()) {
            return true;
        }

        return !!$currentUser;
    }

    protected function wheres()
    {
        $wheres = [
            // 查看范围
            new ModelScope('listByUser'),
            // 业务种类
            'business_type' => When::make($this->getInputArgs('business_type') != '不限')->success($this->getInputArgs('business_type')),
            // 状态
            'status'        => When::make($this->getInputArgs('status') != '不限')->success($this->getInputArgs('status')),
            // 债务人姓名、证件号、手机号
            When::make($this->getInputArgs('debtor'))->success(new ModelScope('debtor', $this->getInputArgs('debtor'))),
            // 状态
            When::make($this->getInputArgs('date'))->success(new ModelScope('date', $this->getInputArgs('date'))),
        ];

        return $wheres;
    }

    public function resolve($root, $args, $context, $info)
    {
        return FinanceDeclaration::getList($this->getConditions($args), ...FinanceDeclaration::getWithAndSelect($info));
    }
}
```



## 综述

具体怎么来写？简单举个例子看看 request 里面如何写。GraphQL 需要先看完 [GraphQL定义和查询](/docs/1.0/GraqlQL) 部分

```php
<?php

namespace App\Http\Requests\Member\Organization;

use App\Http\Requests\Request;

class AgentIndexRequest extends Request
{
	protected function wheres()
	{
		return [
			'store_id',		// 店 ID
			'grade_id',		// 职级
			'position_id',	// 职务
		];
	}
}
```

我们简单定义了 `wheres` 方法，并写了一些参数。前端就可以进行请求了

```html
[GET] ?store_id=3&grade_id=2&position_id=1&page=1
```

那么我们可以在控制器里面注入这个请求对象：

```php
<?php

namespace App\Http\Controllers\Member\Organization\API;

use App\Http\Controllers\MemberController;
use App\Http\Requests\Member\Organization\AgentIndexRequest;
use App\Models\Organization\Agent;

class AgentAPIController extends MemberController
{
    public function index(AgentIndexRequest $request)
	{
		$conditions = $request->getConditions();
		
		return Agent::getList($conditions);
	}
}
```

这样就能获取到 `某店，某职级，某职务` 的人员数据了。

## 惰性加载

通常，我们预定于的搜索条件很多，例如上面例子是 3 个类型。但是往往我们可能只选择了某两个查询项，例如

```html
[GET] ?store_id=&grade_id=2&position_id=1&page=1
```

这样，我们传递的门店 id 是空值，或者

```html
[GET] ?grade_id=2&position_id=1&page=1
```

并没有传递 store_id 。

那么通过 `$request->getConditions()` 拿到的 `where` 条件会自动把 `store_id` 过滤掉。

**其原理很简单，如果对应的值取不到，或者取到值为 `null` 或 `''`，就自动过滤掉**

## 字段映射

很多时候，我们前端定义的传参内容并不一定和我们数据库字段一一对应，那么，就需要写一下映射了。放心，绝对简单：

```php
return [
	'store_id'    => $this->my_store_id,				// 店 ID
	'grade_id'    => $this->input('my_grade_id'),		// 职级
	'position_id' => $this->position->id,				// 职务
];
```

**注意：**
由于这个类集成 `Request` 类，所以默认 `$this` 就是 `$request`。
所以，可以不使用 `input` 方法就可以直接读属性获取传参。而 `$this->position->id` 这种情况是当使用了隐式绑定的时候，获取了 `position` 队形

## 操作符

除了上面默认的为 `=` 操作，我们还需要更多其他的操作符

```php
return [
    'id.in' => [1, 2, 3],
    'deleted_at.not_null' => 'null',
    'name' => [ 'like' => '张%' ],
];
```

可用的操作符如下

```
'eq' : '='
'ne' : '<>' 
'gt' : '>'
'gte': '>='
'ge' : '>=' 
'lt' : '<'
'lte': '<='
'le' : '<='
'like'
'in'
'not_in'
'is'
'is_not'
```

>注意：
> `is` 或 `is_not` 的对应值必须不为 `null`，会被忽略掉这个条件的
> `in` 或 `not_in` 的值如果是空数组，那么这个条件也会被忽略掉

## 丰富传参内容

### fireInput
如果传递的参数内容并不能满足需要，还需要进行一些简单的加工，可以这样做：

```php
return [
	'name.like' => $this->fireInput('name', function ($value) {
						 return $value.'%';
				   })
];
```

这样就能通过 `?name=张` 来获取所有姓张的员工。

`fireInput` 方法
 第一个参数：前端的传参 key
 第二个参数：处理这个传参的内容
 
`fireInput` 行为
如果不能够获取前端传参，那么直接返回 null ，也就是后续处理中会过滤都这条 where 规则
如果能够获取值，那么将获取值传递到闭包，可以自由的进行处理

### appendInput

如果仅仅是想在获取的参数值后面添加数据，可以这样来：

```php
return [
	'name.like' => $this->appendInput('name', '%')
];
```

但是你可能会问，为什么不获取数据之后直接添加 `%` ？

```php
return [
	'name.like' => $this->input('name').'%'
];
```

第二种写法是错误的，因为可能返回结果是这样的，当前端没有传参时，结果如下：

```php
return [
	'name.like' => '%'
];
```

这样会按照值为 `%` 进行搜索。

### when

>当这个人是被禁用户的时候，我们会额外添加一个搜索条件，不让该用户搜索到任何内容

```php
return [
    'id' => $this->when(user()->locked_at, 0),
];
```

这里会根据 `user()->locked_at` 值进行判断，得到的结果如下

```php
// user()->locked_at 值存在
return [
    'id' => 0,
];

// user()->locked_at 值不存在
return [
    'id' => null,
];
```

根据之前约定的，如果 `键值` 为空值（包括空字符串，但不包括 0 ），这个条件就不会生效

`when` 还有以下用法，满足你的各种需求：
```php
'your_field_in_mysql' => $this->when($bool, function() {
        # 这里你可以写你的逻辑，最后返回值即可
        return $this->your_field_in_request/2;
        # 也可以调用一些方法
        # 如果是局部方法，能用 private 就不要用 protected 更不要用 public 定义
        return $this->yourMethodToTransform($value);

        # 同理，返回 null 的时候，这行条件不生效
        return null;
    }),
```

### RAW

原生的 DB::raw 我们也要支持，这个不需要别的，只需要你的语句，只要你会 `sql`，就可以写

```php
return [
    DB::raw('1=0'),
];
```

### 闭包

如果你的查询*特别*复杂，以上各种形式都满足不了，那么你可以祭出终极大招了

```php
use Illuminate\Database\Eloquent\Builder;

return [
    function (Builder $q) {
        $q->whereHas('role');
    },
];
```

闭包只有一个传参，`$q` 为 `Illuminate\Database\Eloquent\Builder`。看到了这个类，你应该知道如何去使用了吧！

这个就是原生的 `laravel` 查询对象，把所有你需要的查询放里面吧！剩下不多说，自由发挥去吧！

### and or

查询的时候，经常会有一些逻辑，他们之间可能是 and 或者是 or

```php
return [
    'created_at' => [
        'gt' => '2017-09-01',
        'lt' => '2017-09-30'
    ],
];
```

默认 `created_at` 的 `大于小于` 操作是 `and` 关联，

如果需要 `or` 操作，可以这样写

```php
return [
    'id' => [
        'in'  => [23, 24, 30],
        'lt'  => 25,
        'mix' => 'or'
    ],
];
```


### 自定义的 laravel 本地作用域

```php
return [
    new ModelScope('listByUser'),
];
```

上面的代码会在执行的时候，会调用模型的 scopeListByUser 方法。

如果需要传参：

```php
return [
    new ModelScope('older', 60),
];
```

上面的代码等同于

```php
function (Builder $q) {
    $q->older(60);
},
```

### When 对象操作

```php
return [
'id'  => When::make(true)->success('34'),
'url' => When::make(false)->success('http://www.baidu.com')->fail('http://www.google.com'),
];
```

执行的结果为
```php
where id = 34
where url='http://www.google.com'
```

### 示例：部用上是什么样子？

```php
return [
    'role_layer_id',
    'org_x.like' => $this->appendInput('path', '%'),
    'id'         => $this->input('agent_id'),
    'grade_id',
    'position_id',
    'company_id' => company_id(),
    function (Builder $q) {
        $q->whereHas('role');
    },
    DB::raw('1=0'),
];
```


## 排序

如果自行构造查询数组的话：

```php
$condtions['order'] = [
    'name' => 'asc',
    'id'   => 'desc',
];
```

针对 GraphQL 的排序，需要前端传参

```json
{
  	"paginator": {
  		"page": 1,
  		"limit": 20,
  		"sort": "-id"
  	}
}
```

如果多个排序可以这样写

```json
{
  	"paginator": {
  		"page": 1,
  		"limit": 20,
  		"sorts": [
  		  "+name",
  		  "-id"
  		]
  	}
}
```
