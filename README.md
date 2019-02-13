php 轻型小框架
架构：

    app
        controller
        model
        view
    
    config
        app.php
        database.php
    
    easy
        框架核心文件
    help
        自封装类
    
    public
        index.php 入口文件
        .htaccess apache规则
        css
        js
        images
        ...
    
    route
        Route.php 注册路由
    
    composer.json  依赖的第三方包

数据访问：
    1>>>>>>>
        可使用\easy\db\DB 类来操作数据库，支持链式操作。
        比如：
            select:  DB::table($table_name)->where(array('action'=>1,'itcode'=>'chensw10'))->orderby('id desc')->limit(10)->get()
            insert:   DB::table($name)->insert(array('userid'=>'xxxx','part'=>'aaaaa'....))
            update :   DB::table($name)->where(['userid'=>'chensw10'])->update(['score'=>99,'sex'=>'male'])
            ......
    2>>>>>>
        可继承\easy\db\postgres
            具体可看该类下的方法
    
    
配置信息获取：
    \easy\Config::get($attr,$file)   $attr表示获得的配置key，$file为config下配置文件名

分页类
 \easy\db()->page();

未完待续



    
    
            
            