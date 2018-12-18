# 惠域通宝

## 功能

## 使用的技术
- Laravel 5.6
- Vue 2.0+
- 前端框架模版 https://github.com/PanJiaChen/vueAdmin-template
- 接口使用规范 Graphql 
    - 前端，使用 axios POST Graphql
    - 后端，使用 https://github.com/Folkloreatelier/laravel-graphql
    
## 日常开发
yarn install
npm run watch
php artisan migrate
php artisan queue:restart

## 生产部署
yarn install
npm run prod
php artisan migrate
php artisan queue:restart
