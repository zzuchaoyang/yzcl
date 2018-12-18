<!doctype html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>惠域通宝供应端管理后台</title>

</head>
<body>
<div id="app">
</div>
<script src="{{ mix('/build/supplier/js/app.js') }}"></script>
</body>
</html>
