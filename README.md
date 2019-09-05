# VTWeb

#### 环境配置

1. PHP >= 5.5
2. Nginx >= 1.12.0 
3. MySQL >= 5.6
4. Redis >= 4.0

#### 注意点
1. 使用biny框架  http://www.billge.cc/

2. Nginx配置参考
```
server {
    set $project_root "项目路径";

    listen       80;
    server_name  vtweb.site;
    root   $project_root/web;
    index index.php  index.html ;

    charset      utf-8;
        client_max_body_size  200M;

        fastcgi_connect_timeout 3600s;
        fastcgi_send_timeout 3600s;
        fastcgi_read_timeout 3600s;
        fastcgi_buffer_size 128k;
        fastcgi_buffers 8 128k;
        fastcgi_busy_buffers_size 256k;
        fastcgi_temp_file_write_size 256k;
        fastcgi_intercept_errors on;


    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    # pass the PHP scripts to FastCGI server listening on 127.0.0.1:9000
    #
    location ~ \.php$ {
        fastcgi_pass   127.0.0.1:9000;
        fastcgi_index  index.php;
        fastcgi_param  SCRIPT_FILENAME  $document_root$fastcgi_script_name;
        include        fastcgi_params;
    }
}
``` 

3. Web入口文件
 /web/index.php
 该文件不在代码版本库里, 请自行将 /web/index.php.example 复制一份，名称改为 index.php
 
4. Shell入口文件
 /shell.php
 该文件不在代码版本库里, 请自行将 /shell.php.example 复制一份，名称改为 shell.php
 
5. 配置文件分3个，分别为
 /app/config/dns_dev.php
 /app/config/dns_pre.php
 /app/config/dns_pub.php
 
6. 在Web及Shell入口文件中,可修改 dev/pre/pub模式，分别对应不同的配置文件

7. 需要redis服务

```
//dev pre pub
defined('SYS_ENV') or define('SYS_ENV', 'pre');
```

7. 确保目录可写权限，如果运行中有权限错误，可自行创建所需目录
 

#### 部署

1. 创建数据库,  并执行 使用 /database 目录下的sql文件，初始化数据库结构
2. 配置入口文件中的, SYS_ENV 的模式, 并再对应的/app/config/dns_*.php中，配置数据库，redis等服务信息
3. 执行创建管理员脚本,默认用户名密码为super
```
php shell.php init/admin
```
