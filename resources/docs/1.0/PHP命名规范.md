# PHP命名规范


此规范是针对本项目，并非业内统一规范（业内暂无统一规范）。命名的规范默认遵循 laravel 文档内的命名约定（文档并未明确指出，但是文档命名方式统一），以及国外大多开源项目的约定。

---

>**作者：朱智超**

## 变量名

PHP 变量默认为首字母小写的驼峰命名，例如 `$bargainType` 、`$stepName`，避免 `$step_name`、`$bargain_type`

命名不怕长就怕写代号，正例：`$result`，`$option` 对应的反例：`$res`, `$opt`。哪怕没写注释，查一下单词就能知道什么意思。如果没写注释，还是缩写，那么👻知道什么意思

正确使用正复数表示变量的含义，例如，`$comment` 表示一条评论对象， `$comments` 表示对象的集合。默认一个完整的单词就是一个对象

`$comment` 内容字符串，那么可以这样写： `$commentText`、`$commentStr`

`$comment` 属性，那么可以这样写： `$commentId`、`$commentUserId`、`$commentCreatedAt`

`$comment` 数组信息： `$commentInfo`、`$commentArr`

多个评论的 ID 数组，`$commentIds`。多个用户的 ID 数组，`$userIds`。

常用变量名称和表示：`$conditions` 请求的条件。`$amount` 统计的和。

## 方法名

首字母小写驼峰

## 模型名

和表名对应
