ThinkPHP是为了 简化 企业级 应用开发 和 敏捷WEB 应用开发 而诞生的。
ThinkPHP是一个 快速、兼容而且简单的 轻量级 国产 PHP开发框架
包含了底层架构、兼容处理、基类库、数据库访问层、模板引擎、缓存机制、插件机制、角色认证、表单处理等常用的组件，并且对于跨版本、跨平台和跨数据库移植都比较方便。
### 配置虚拟主机
    1、创建项目根目录
    2、修改Apache的配置增加虚拟主机配置   wamp64\bin\apache\apache2.4.41\conf\extra\httpd-vhosts.conf
        <VirtualHost *:80>
            ServerName shop.com          // 需修改
            ServerAlias localhost
            DocumentRoot "${INSTALL_DIR}/www/shop"      // 需修改
            <Directory "${INSTALL_DIR}/www/shop">       // 需修改
                Options +Indexes +Includes +FollowSymLinks +MultiViews
                AllowOverride All
                Require local
            </Directory>
        </VirtualHost>
    3、修改本机hosts指向  C:\Windows\System32\drivers\etc\hosts
        127.0.0.1 shop.com      // 新增  
    4、重启Apache
    5、浏览器访问测试
### 部署项目
    1、拷贝TP框架的源代码（ThinkPHP文件）
    2、创建项目的入口文件（index.php)
        define('APP_PATH','./Application/');    // 定义应用的存储地址，固定格式
        include './ThinkPHP/ThinkPHP.php';  // 载入TP框架的入口地址，固定格式
    3、访问项目生产Application文件 
            Applacation目录是自动生成的
                Applacation目录：
                    Common：代表所有模块的公共模块
                    Home：模块
                        Common：存储当前模块下的函数
                        Conf：当前模块下的配置信息
                        Controller：控制器
                        Model：模型
                        View：视图
                        index.html：安全文件，防止用户直接访问该文件
                    Runtime：代表具体项目运行中的临时储存的地方
### 创建控制器
    控制器的存储方式
    存储路径：/应用(Application)/模块/Controller    // Application\Home\Controller\TestController.class.php
    文件名称：控制器（首字母大写） + Contraller + .class.php
    控制器代码创建规则
        1、声明命名空间         // namespace Home\Controller;
        2、引入TP的控制器基类   // use Think\Controller;
        3、创建自己的控制器的类 // class GoodsController extends Controller{}
        4、创建自己的方法      // public function index1(){}
        <?php
            namespace Home\Controller;
            use Think\Controller;
            class GoodsController extends Controller{
                public function test(){

                }
                public function test2(){

                }
            }
        ?>
        命名空间：tp的命名空间其实就是虚拟目录，目的是为了自动加载类（不是管理文件）；
            tp命名空间包括 初始命名空间：Library 和 根命名空间 两部分
            tp框架里所有的类文件里都要写上命名空间！！！
### TP中的全地址解析:当访问某一个方法时需要在浏览器上输入的完整地址
    配置文件 convention.php
    'DEFAULT_MODULE'         => 'Home', // 默认模块
    'DEFAULT_CONTROLLER'     => 'test', // 默认控制器名称
    'DEFAULT_ACTION'         => 'test', // 默认操作名称
### TP的调试模式
    在项目开发需要开启调试模式(入口文件中)，会指出具体的错误位置
    上线时需要关闭调试模式，可以减少日志文件的生成，代码出错时，只会提示错误，不会提示具体的错误位置（更加的安全）
    日志文件太多，导致磁盘爆满，不能够正常运行项目
### TP 文件配置的位置（三个地方）
    1、TP默认的惯例配置文件：ThinkPHP\Conf\convention.php
    2、公共模块配置文件：\Application\Common\Conf\config.php
        <?php
            return array(
                //'配置项'=>'配置值'
                'URL_MODEL'=>2,  // 设置URL模式为重写模式
                'DEFAULT_MODULE'=>'Home',  // 设置默认访问的模块
                'MODULE_ALLOW_LIST'=>array('Home','Admin'),	// 设置容许访问的模块
                // 增加自定义的模板替换配置信息
                'TMPL_PARSE_STRING'=>array(
                    '__PUBLIC_ADMIN__'=>'/Public/Admin',
                    '__PUBLIC_HOME__'=>'/Public/Home',
                    '__PUBLIC__'=>'/Public/ueditor',
                ),
                /* 数据库设置 */
                'DB_TYPE'                => 'mysql', // 数据库类型
                'DB_HOST'                => '127.0.0.1', // 服务器地址
                'DB_NAME'                => 'jxshop', // 数据库名
                'DB_USER'                => 'root', // 用户名
                'DB_PWD'                 => 'root', // 密码
                'DB_PORT'                => '3308', // 端口
                'DB_PREFIX'              => 'jx_', // 数据库表前缀
            );
        ?>
    3、模块配置文件：\Application\Home\Conf\config.php
    配置的优先级：模块配置文件 > 公共模块配置文件 > TP默认的惯例配置文件
    在实际开发中不要直接修改TP默认的惯例配置文件，可以通过覆盖的方式进行修改(修改 模块配置文件 或者 公共模块配置文件 )
### TP中创建模块：前、后台分离
    前台：用户与数据交互
    后台：数据管理
    创建后台模块：
    Application
        Admin文件
            Controller文件
                IndexController.class.php文件
                    namespace Admin\Controller;
                    use Think\Controller;
                        class IndexController extends Controller{
                            public function testadmin(){
                                echo "testadmin";
                            }
                        }
            Model
            View
            index.php
### TP中四种URL模式
    1、普通模式
        默认情况下是通过入口文件 m(模块) / c(控制器) / a(方法名) 传递三个参数,在任何情况下都可以使用
        http://tp.com/index.php?m=admin&c=index&a=testadmin
    2、pathinfo模式
        此模式比较符合搜索引擎优化，格式像访问某一个目录下的文件
        pathinfo需要有web服务器（例如：Apache）的支持，有些服务器不支持
        http://tp.com/index.php/Admin/Index/testadmin/name/value
    3、重写模式
        在pathinfo的基础上增加了一个重写规则（需要web服务器提供重写规则），重写模式一般使用在隐藏项目的入口文件
        具体的访问格式不固定，受具体的重写规则文件影响
    4、兼容模式
        为了确保要使用pathinfo模式，但是web服务器不支持模式
        http://tp.com/index.php?s=/Admin/Index/testadmin
### 实现隐藏项目的入口文件
    1、修改Apache的主配置文件，开启Apache的重写模式
        LoadModule rewrite_module modules/mod_rewrite.so
    2、修改虚拟主机的配置

        <VirtualHost *:80>
        ServerName tp.com
        ServerAlias localhost
        DocumentRoot "${INSTALL_DIR}/www/tp"
        <Directory "${INSTALL_DIR}/www/tp">
            Options +Indexes +Includes +FollowSymLinks +MultiViews
            AllowOverride All    // all为允许重写
            Require local
        </Directory>
        </VirtualHost>
    3、  .htaccess   重写规则文件
### URL地址生成
    1、查看U函数的使用
    2、生成连接地址

        public function testU(){
            echo U('test').'<hr/>';
            echo U('test','id=2').'<hr/>';
            echo U('Test/test','id=2').'<hr/>';
            echo U('Test/test#top','id=2').'<hr/>';
            echo U('Test/test#top@www.itcast.cn','id=2').'<hr/>';
        }
        --> /index.php/Home/Test/test.html
        --> /index.php/Home/Test/test/id/2.html
        --> /index.php/Home/Test/test/id/2.html
        --> /index.php/Home/Test/test/id/2.html#top
        --> http://www.itcast.cn/index.php/Home/Test/test/id/2.html#top
        默认使用U函数生成的连接地址 对应的pathinfo模式。
        对于指定的URL表示不全时，会自动的将当前具体的模式、控制器进行补充
    3、修改配置文件生成普通模式的连接地址
        'URL_MODEL'=>0,     // 0 (普通模式); 1 (PATHINFO 模式); 2 (REWRITE  模式); 3 (兼容模式)  默认为PATHINFO 模式
        /index.php?m=Home&c=Test&a=test
        /index.php?m=Home&c=Test&a=test&id=2
        /index.php?m=Home&c=Test&a=test&id=2
        /index.php?m=Home&c=Test&a=test&id=2#top
        http://www.itcast.cn/index.php?m=Home&c=Test&a=test&id=2#top
    4、使用重写模式来生成连接地址
        'URL_MODEL'=>2,     // 0 (普通模式); 1 (PATHINFO 模式); 2 (REWRITE  模式); 3 (兼容模式)  默认为PATHINFO 模式
        /Home/Test/test.html
        /Home/Test/test/id/2.html
        /Home/Test/test/id/2.html
        /Home/Test/test/id/2.html#top
        http://www.itcast.cn/Home/Test/test/id/2.html#top
        注意：设置URL_MODEL参数作用控制U函数使用哪一种模式来生成连接地址。并不是用于限制TP必须要使用哪一种模式才能访问
### 页面跳转与重定向
    页面跳转
        TP控制器基类已经封装好了两个方法（success 和 error）就能够实现跳转的方法
            success 成功之后跳转
            error  失败之后跳转
        1、success 和 error 具体的使用方式 是一样的
            protected function error($message = '', $jumpUrl = '', $ajax = false){}
                $jumpUrl = '' 为空时，默认返回上一级    建议用U函数来生成跳转地址
                $ajax = false
                    1、标识是否是ajax返回数据
                    2、设置具体的提示页面的等待时间
        2、成功和失败的模板地址   ThinkPHP\Tpl\dispatch_jump.tpl
    页面重定向，前两个参数与U函数一模一样
        public function testR(){
            $this->redirect('testU','id=3',10,'等待10s跳转');
        }
### 空操作（空方法）：当用户访问某一个控制下不存在的方法，TP会自动将指向当前控制下对应的_empty方法，提升用户操作体验
    public function _empty(){
        echo 'in empty'.'<br/>';
        echo ACTION_NAME;
    }
    空操作：当用户访问某一个不存在的控制器，TP会自动的执行空控制器下的对应的方法
        EmptyController.class.php
            // 声明命名空间
            namespace Home\Controller;
            // 引用TP控制器基类
            use Think\Controller;
            class EmptyController extends Controller{
                public function _empty(){
                    echo 'in EmptyController empty action<br/>';
                    echo CONTROLLER_NAME.'<br/>';  // 当前用户访问的控制器的名称
                    echo ACTION_NAME;
                }
            }
        TestController.class.php
            // 处理当访问当前控制器下不存在的方法，会自动执行该方法

            public function _empty(){
                echo 'in empty'.'<br/>';
                echo ACTION_NAME;
            }
### 视图渲染（View）：将具体的视图模板进行输出显示
    在TP中有两个方法可以实现具体的视图的输出功能  display 和 fetch
    display：获取具体要输出的内容，然后直接输出
    fetch：获取具体输出的内容，但是不会自动输出（很少用）
    display：
        1、不带任何参数，自动定位到当前操作的模板文件
            $this->display();
        2、指定当前控制器下的模板
            $this->display('add');
        3、指定其他控制器下的模板
            $this->display('Index:add');
        4、指定其他模块的模板
            $this->display('Admin@Index:add');
        5、完整模板名称
### 模板的替换功能
    public function add(){
        // display 没有指定任何的参数信息，渲染跟方法同名的模板文件 View/Goods/ass.html
        $this->display();
    }
    href="/Pulic/add.css"   
### 模板替换规则
    内置的模板替换规则
    __ROOT__:   ROOT 会替换成当前网站的地址（不包含域名）<br/>
    __APP__:    APP 会替换成当前应用的URl地址（不包含域名）<br/>
    __MODULE__:  MODULE 会替换成当前模块的URL地址（不包含域名）<br/>
    __CONTROLLER__:    CONTROLLER 会替换成当前控制器的URL地址（不包含域名）<br/>
    __ROOT__:ACTION 会替换成当前操作的URL地址（不包含域名）<br/>
    __ACTION__:SELF 会替换成当前页面<br/>
    __PUBLIC__:PUBLIC 会替换成当前网站的公共目录 通常是 /public/<br/>
    自定义的模板替换: __PUBLIC_ADMIN__
	'TMPL_PARSE_STRING'=>ARRAY(
		'__PUBLIC_ADMIN__'=>'/Public/admin'
	)
    :ROOT 会替换成当前网站的地址（不包含域名）
    :APP 会替换成当前应用的URl地址（不包含域名）
    /Home:MODULE 会替换成当前模块的URL地址（不包含域名）
    /Home/Goods:CONTROLLER 会替换成当前控制器的URL地址（不包含域名）
    :ACTION 会替换成当前操作的URL地址（不包含域名）
    /Home/Goods/add:SELF 会替换成当前页面
    /Public:PUBLIC 会替换成当前网站的公共目录 通常是 /public/
    自定义的模板替换: /Public/admin
### 模板变量赋值与显示
    1、创建方法实现赋值
    2、创建模板显示具体的数据    
### 系统变量：TP内置的一个变量。
    对于系统变量可以在模板中直接使用，不需要进行任何的赋值，对于系统变量可以输出内容包括TP内置配置项配置信息、TP或者PHP内置的常量、PHP超全局数组（$_POST,$_GET等）。对于在模板中使用系统中使用变量时都是 “{Think” 开头
### 模板函数
    在模板中使用某一个函数（PHP内置函数或者TP的公共函数）将具体的数据转换成为其他格式
    1、创建方法，渲染模板
        public function testfunc(){
            $time=time();
            $this->assign('time',$time);
            $this->display();
        }
        
    2、
        1、使用PHP原生代码实现转换操作：<?php echo date('Y-m-d H:i:s',$time)?><br/>
        2、使用TP模板引擎来实现具体的转换：{$time|date='Y-m-d H:i:s',###}<br/>
            // "|"表示要使用函数 date值是具体的函数名称 后面是具体的参数 "###"代表当前变量
        3、特殊写法：{:date('Y-m-d H:i:s',$time)}<br/>
        4、使用U函数来生成连接地址：{:U('index','id=4')}
### 模板运算符
    在TP的模板当中可以支持 加、减、乘、除、取模等操作
    public function jisuan(){
        $this->assign('a',10);
        $this->assign('b',2);
        $this->display();
    }

    <body>
        a+b={$a+$b}<br/>
        a-b={$a-$b}<br/>
        a*b={$a*$b}<br/>
        a/b={$a/$b}<br/>
    </body>
### 内置标签
    Foreach 
    if标签  and or eq neq gt lt egt elt heq nheq
    比较标签  eq neq gt lt egt elt heq nheq
    Volist 
        name：指定需要循环的变量名称
        id：指定每次循环时的临时变量
        mod：每次在循环的过程中都会增加一个mod变量。对于该变量的值从0到设置的值轮询出现。使用mod可以实现隔行变色的效果
        empty：当循环的变量为空，显示在empty中设置的内容（对于具体的内容不支持直接使用HTML代码）
        offset：指定偏移量（指定从具体的哪一个元素开始进行循环）
        length：指定具体的循环次数
        key：指定具体循环的下标的变量名称。具体下标变量对应的值（1，2，3）
    创建方法，渲染模板
        // 内置标签 foreach if 比较标签
        public function test1(){
            $data=array(
                'a'=>array('name'=>'tp1','version'=>5.0),
                'b'=>array('name'=>'tp2','version'=>3.2)
            );
            $this->assign('data',$data); // foreach
            $this->assign('day',7);  // if
            $this->assign('month',12); // 比较标签
            $this->display();
        }
        // 内置标签 volist 
        public function test2(){
            $data=array(
                'a'=>array('name'=>'tp1','version'=>5.0),
                'b'=>array('name'=>'tp2','version'=>3.2),
                'c'=>array('name'=>'tp3','version'=>3.2),
                'd'=>array('name'=>'tp4','version'=>3.2),
                'e'=>array('name'=>'tp5','version'=>3.2),
                'f'=>array('name'=>'tp6','version'=>3.2),
            );
            $this->assign('data',$data); // volist
            $this->display();
        }
    创建模板

        <!DOCTYPE html>
        <html lang="en">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <meta http-equiv="X-UA-Compatible" content="ie=edge">
            <title>Document</title>
        </head>
        <body>
            使用volist标签实现基本的操作<br/>
            <volist name='data' id='vo'>
                循环数据的原始下标：{$key}----{$vo.name}<br/>
            </volist>
                使用volist标签实现基本的操作
                循环数据的原始下标：a----tp1
                循环数据的原始下标：b----tp2
                循环数据的原始下标：c----tp3
                循环数据的原始下标：d----tp4
                循环数据的原始下标：e----tp5
                循环数据的原始下标：f----tp6
            <hr/>
            使用volist标签实现偏移量（offset）和长度（length）<br/>
            <volist name='data' id='vo' offset='2' length='3'>
                循环数据的原始下标：{$key}----{$vo.name}<br/>
            </volist>
                循环数据的原始下标：c----tp3
                循环数据的原始下标：d----tp4
                循环数据的原始下标：e----tp5
            <hr/>
            使用volist标签实现指定的循环下标<br/>
            <volist name='data' id='vo' offset='2' length='3' key='keys'>
                循环数据的原始下标：{$key}----{$keys}----{$vo.name}<br/>
            </volist>
                循环数据的原始下标：c----1----tp3
                循环数据的原始下标：d----2----tp4
                循环数据的原始下标：e----3----tp5
            <br/>
            使用volist标签实现mod的操作<br/>
            <volist name='data' id='vo'>
                循环数据的原始下标：{$mod}----{$vo.name}<br/>
            </volist>
                循环数据的原始下标：0----tp1
                循环数据的原始下标：1----tp2
                循环数据的原始下标：0----tp3
                循环数据的原始下标：1----tp4
                循环数据的原始下标：0----tp5
                循环数据的原始下标：1----tp6
        </body>
        </html>
    
### 视图创建功能
    视图：在MVC中的V，主要是与用户进行具体的数据交互（将具体的内容信息输出即可），对于视图一般也成为模板
    视图创建规则：
        1、TP建议将具体的视图模板存储到相应的View目录中。
        例如：目前在后台Admin模快中粗腰使用到的一个模板，可以将具体的视图模板存储到Application/Admin/View
        2、TP建议很具具体的控制器在视图的View目录下创建同名的目录。
        例如：目前在后台Admin模快中有一个叫做Test控制器。将具体的视图模板存储到Application/Admin/View/Test目录中
        3、TP建议将具体的视图模板文件名称保持跟具体的方法名称保持一致
        例如：目前在后台Admin模快中有一个叫做Test控制器。在Test控制器下有一个叫做test方法，如果giant方法需要使用模板。建议将具体的马班名称取名叫test.html


### 搭建前台
    创建根目录
    拷贝TP源码：入口文件、重写规则、具体TP框架的源代码、具体存储公共资源文件的Public文件
    增加虚拟主机的配置      D:\wamp64\bin\apache\apache2.4.41\conf\extra\httpd-vhosts.conf
    修改本机hosts文件    C:\Windows\System32\drivers\etc\hosts文件

### 显示前台的页面
    拷贝资源文件 静态模板-final\前台中的css、img、js 拷贝到 shop\Public\Home（Home为新建文件夹）中
    修改Index控制器下的index方法 shop\Application\Home\Controller\IndexController.class.php
        public function index()
            {
                $this->display();
            }
    拷贝模板文件，静态模板-final\前台中的 detail.html和index.html拷贝到shop\Application\Home\View\Index（新建文件夹）中
    增加自定的模板替换 shop\Application\Common\Conf\config.php
        <?php
            return array(
                //'配置项'=>'配置值'
                'TMPL_PARSE_STRING'=>array(
                    '__PUBLIC_HOME__'=>'/Public/Home',
                    '__PUBLIC_ADMIN__'=>'/Public/Admin'
                ),
            );
    修改模板文件中的资源文件地址
        shop\Application\Home\View\Index中
        1、
        <link rel="stylesheet" type="text/css" href="./css/meici.css" />
        <link rel="stylesheet" type="text/css" href="./css/meici_new.css" />
        改成
        <link rel="stylesheet" type="text/css" href="__PUBLIC_HOME__/css/meici.css" />
        <link rel="stylesheet" type="text/css" href="__PUBLIC_HOME__/css/meici_new.css" />
        2、
        src="./  全部替换为  src="__PUBLIC_HOME__/
    创建方法显示详情页
        shop\Application\Home\Controller\IndexController.class.php
            public function detail(){
                $this->display();
            }
    修改详情页的资源地址
        1、
        <link rel="stylesheet" type="text/css" href="./css/meici.css" />
        <link rel="stylesheet" type="text/css" href="./css/meici_new.css" />
        <link rel="stylesheet" type="text/css" href="./css/share_style0_16.css" />
        改成
        <link rel="stylesheet" type="text/css" href="__PUBLIC_HOME__/css/meici.css" />
        <link rel="stylesheet" type="text/css" href="__PUBLIC_HOME__/css/meici_new.css" />
        <link rel="stylesheet" type="text/css" href="__PUBLIC_HOME__/css/share_style0_16.css" />
        2、
        src="./  全部替换为  src="__PUBLIC_HOME__/
### 实现前台页头页尾共用
    1、在View创建Pulic目录  shop\Application\Home\View\Public
    2、分离页头 在Public中创建  header.html 将模板中页面头部的代码剪切到 shop\Application\Home\View\Public\header.html
    3、分离页头 在Public中创建  footer.html 将模板中页面尾部的代码剪切到 shop\Application\Home\View\Public\footer.html
    4、修改index模板，载入页头页尾

        <include file='Public:header'/>    <!-- 或者  b -->
        <include file='Public:footer'/>    <!-- 或者 <include file='Public:footer'/> -->


### 显示后台的页面
    拷贝后台资源文件 静态模板-final\后台中的css、img、js 拷贝到 shop\Public\Admin（Admin为新建文件夹）中
    创建后台模块，shop\Application中，直接复制Home文件夹，并改名为Admin
    拷贝后台首页的模板文件， thinkPHP模板\静态模板-final\后台\修订 中的index.html/top.html/left.html/right.html
        到shop\Application\Admin\View\Index中
    修改Index控制器(shop\Application\Admin\Controller\IndexController.class.php)中的index方法
        改为： 
        <?php
            namespace Admin\Controller;
            use Think\Controller;
            class GoodsController extends Controller
            {
                public function index()
                {
                    $this->display();
                }
                public function top(){
                    $this->display();
                }
                public function left(){
                    $this->display();
                }
                public function right(){
                    $this->display();
                }
            }
        ?>
    修改index模板 (用U函数生成链接地址)
        src="top.html" 改为 src="{:U('top')}"
        src="left.html" 改为 src="{:U('left')}"
        src="right.html" 改为 src="{:U('right')}"
    修改首页其他模板的资源地址  (top.html/right.html/left.html)

        css/js/img 引用的相对地址前加 __PUBLIC_ADMIN__/
        1、
        <link href="css/style.css" rel="stylesheet" type="text/css" />
        <script language="JavaScript" src="js/jquery.js"></script>
        分别改为：
        <link href="__PUBLIC_ADMIN__/css/style.css" rel="stylesheet" type="text/css" />
        <script language="JavaScript" src="__PUBLIC_ADMIN__/js/jquery.js"></script>
        2、
        <img src="images/leftico02.png" />
        改为：
        <img src="__PUBLIC_ADMIN__/images/leftico02.png" />
    创建商品的控制器 (shop\Application\Admin\Controller\GoodsController.class.php)
        <?php
            namespace Admin\Controller;
            use Think\Controller;
            class GoodsController extends Controller
            {
                // 实现显示商品列表
                public function index()
                {
                    $this->display();
                }
                // 实现商品的添加入库
                public function add()
                {
                    $this->display();
                }
            }
        ?>
    拷贝商品相关的模板文件
        静态模板-final\后台中的 form.html和showlist.html拷贝到shop\Application\Home\View\Index（新建文件夹）中，
        并分别改名为 add.html 和 index.html
    修改商品模板的资源地址 (add.html 和 index.html)
        css/js/img 引用的相对地址前加 __PUBLIC_ADMIN__/
    修改left.html的导航菜单
        <li><cite></cite><a href="index.html" target="rightFrame">首页模版</a><i></i></li>
        <li class="active"><cite></cite><a href="right.html" target="rightFrame">数据列表</a><i></i></li>
        改为：
        <li><cite></cite><a href="{:U('Goods/index')}" target="rightFrame">商品列表</a><i></i></li>
        <li class="active"><cite></cite><a href="{:U('Goods/add')}" target="rightFrame">商品添加</a><i></i></li>

对后台使用模板继承
    创建父模板 shop\Application\Admin\View\Public\base.html

        <!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
        <html xmlns="http://www.w3.org/1999/xhtml">
        <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title>无标题文档</title>
        <link href="__PUBLIC_ADMIN__/css/style.css" rel="stylesheet" type="text/css" />
        <script type="text/javascript" src="__PUBLIC_ADMIN__/js/jquery.js"></script>
        </head>
        <body>
            <div class="place">
            <span>位置：</span>
            <ul class="placeul">
                <block name='position'>
                    <li><a href="#">首页</a></li>
                    <li><a href="#">数据表</a></li>
                    <li><a href="#">基本内容</a></li>
                </block>
            </ul>
            </div>
            <block name='body'></block>
        </body>
        </html>
        <block name='js'></block>
    针对shop\Application\Admin\View\Goods下的index模板实现模板继承
        具体查看shop\Application\Admin\View\Goods\index.html

TP中的模型：MVC中的（Model）具体实现对数据的增删改查
    TP数据库的配置
    1、开启PHP关于MySQL的pdo扩展
        在TP的模型中关于具体的数据库交互是使用pdo方式，因此必须开启PHP关于mysql的pdo的扩展，由于目前的PHP中已经有了关于mysql的pdo的扩展文件。可以直接在配置文件中引用具体的文件即可
        开启PHP配置文件中的pdo扩展
            去掉php.ini 中的 extension=php_pdo_mysql.dll 前的注释
            如果去掉之后还是不能开启mysqlpdo的扩展，需要检查具体extension_dir所指定目录中对应的扩展文件是否存在
        重启Apache，然后查看phpinfo函数
            public function phpinfo(){
                phpinfo();
            }
            PDO support	            enabled
            PDO drivers	            mysql, sqlite
    2、创建数据库与数据表
        create database shop;
        use shop;
        create table shop_goods(
            id int(10) unsigned not null auto_increment comment '商品ID',
            goods_name varchar(255) not null default '' comment '商品名称',
            goods_img varchar(255) not null default '' comment '商品缩略图',
            addtime int(11) not null default '0' comment '添加时间',
            goods_body text,
            primary key(id)
        )engine=innodb default charset=utf8;
    3、配置TP与数据库的连接信息
        shop\Application\Common\Conf\config.php
            /* 数据库设置 */
            'DB_TYPE'                => 'mysql', // 数据库类型
            'DB_HOST'                => '127.0.0.1', // 服务器地址 尽量不要用localhost
            'DB_NAME'                => 'shop', // 数据库名
            'DB_USER'                => 'root', // 用户名
            'DB_PWD'                 => 'root', // 密码
            'DB_PORT'                => '3306', // 端口
            'DB_PREFIX'              => 'shop_', // 数据库表前缀 防止数据表发生冲突
创建自定义模型
    创建规则 与控制器相似
        模型存储地址规则
            应用/模块/Model目录中
        模型文件命名规则
            模型名称+Model+.class.php   eg:GoodsModel.class.php
            模型名称要求使用者首字母大写的方式，并且使用驼峰方式命名，模型的作用就是实现与数据表的名称保持一致。
        模型代码创建规则 
            shop\Application\Admin\Model\GoodsModel.class.php
            设置命名空间 namespace Admin\Model;
            引入TP模型基类 use Think\Model;
            创建自定义的模型类 并且
            <?php
                // 设置命名空间
                namespace Admin\Model;
                // 引入TP模型基类
                use Think\Model;
                // 创建自定义模型类
                class GoodsModel extends Model{}
            ?>   
### 实例化模型对象
    1、常规的实例化方式
        使用new关键字来实例化模型对象,必须要使用命名空间
        shop\Application\Admin\Controller\IndexController.class.php
            public function test1(){
                // 关于使用new实例化对象必须要使用命名空间，实例化PHP内置类或者也需要命名空间
                $model=new \Admin\Model\GoodsModel();  

                // dump()是TP的内置函数，等价于 echo '<pre>' var_dump()
                dump($model);
            }
            =>  object(Admin\Model\GoodsModel)[6]
    2、使用TP内置的M函数
        public function test2(){
            /*
                M函数默认有三个参数，在使用的过程中可以对三个参数都不传递或者产地某几个
                第一个：指定具体的某一个数据表（ 默认没有前缀）
                第二个：指定具体的表前缀
                第三个：制定具体的连接信息
                后两个参数使用很少
            */
            // $model=M();  =>object(Think\Model)[6]   
            // M函数实例化TP模型基类。对于目前没有传递参数，一般使用在需要直接使用原生SQL语句
            // 不常用
            $model=M('Goods'); //=>object(Think\Model)[6] 
            // 指定了具体的操作数据表，可以使用TP模型基类的具体方式进行数据操作
            dump($model);
        }
    3、使用D函数实例化模型对象
        public function test3(){
            // 关于D使用传递参数时，需要指定具体的自定义的 模型的名称
            $model=D('Goods');
            // 对于使用 D函数 实例化模型对象 是实例化自定义的模型对象
            // D函数支持跨模块使用 只需要指定具体的参数 “模块名称/模型名称”
            // 当实例化某一个不存在的自定义的模型，会使用TP的模型基类进行实例化
            dump($model);  // =>object(Admin\Model\GoodsModel)[6]
        } 
    总结：M与D的区别，
        M永远实例化TP的模型的基类，
        D会优先实例化自定义的模型类，如果自定义的模型类不存在，就会实例化TP模型基类 
        推荐使用D函数

模型的CURD操作
    CURD：增（add、addAll）删（delete）改（save）查（find、select）TP中提供的模型
    删 改 都需要添加 where 语句
    add    写入单条数据
        // 演示add方法的使用方式 先实例化模型对象
        public function test4(){
            // 实例化模型对象
            $model = D('Goods');
            // 拼接具体要写入的数据
            // 是需要使用add进行数据写入操作，add是实现写入单条数组，需要使用一维数组
            $data = array(
                'goods_name'=>'test4',
                'addtime'=>time(),
                'goods_body'=>'test4'
            );
            // 具体实现数据写入操作
            $res=$model->add($data);
            dump($res);
        } 
    addAll    写入多条数据
        public function test5(){
            $model=D('Goods');
            $data=array(
                array('goods_name'=>'test4','addtime'=>time(),'goods_body'=>'test4'),
                array('goods_name'=>'test4','addtime'=>time(),'goods_body'=>'test4')
            );
            $res=$model->addall($data);
            dump($res);
        }
    setField
        public function test9(){
            $model=D('Goods');
            // setField 作用实现更新具体某一些字段的内容
            // 有两种使用方式
            // 第一种方式实现修改多个字段
            $model->where('id=1')->setField(array('goods_name'=>'test10','goods_body'=>'test10'));
            // 第二种方式只修改一个字段
            $model->where('id=2')->setField('goods_name','test11');       
        }
    save  
        // 使用save修改所有数据(错误) 默认不允许修改所有信息。需要有where条件
        public function test6(){
            $model=D('Goods');
            $data=array('goods_name'=>'test12');
            $res=$model->save($data);
            dump($res);
        }
        // 使用save指定修改条件
        public function test7(){
            $model=D('Goods');
            $data=array('id'=>1,'goods_name'=>'test12');
            $res=$model->save($data);
            dump($res);
        }
        // 使用save  where指定条件进行更新  连贯操作
        public function test8(){
            $model=D('Goods');
            $data=array('goods_name'=>'test13');
            // where 方法 return的是$this  因此可以连贯操作
            // where 条件可以使用数组来指定具体条件，也可以是用原生的SQL语句
            $res=$model->where("goods_name='test4'")->save($data);
            dump($res);
        }
    setInc(将某一个字段值进行增加操作)与setDec（将某一个字段值进行减少操作） 用法一样
        // setInc(将某一个字段值进行增加操作)与setDec（将某一个字段值进行减少操作） 用法一样
        // 都有两个参数，第一个是字段，第二个是增加或者减少的值，如果省略第二个参数，则增加或者减少1.
        public function test10(){
            $model=D('Goods');
            $model->where('id=6')->setInc('addtime');
            $model->where('id=4')->setDec('addtime',100);
        }
    find 和 select
        // find 获取一条数据 返回的数据格式是以为数组
        // select 获取多条数据 返回的数据格式是二位数组
        public function test11(){
            // 获取一条数据
            $model=D('Goods');
            $data=$model->where('id=1')->find();
            dump($data);
            // 获取多条数据
            $data=$model->where("goods_name='test13'")->select();
            dump($data);
        }
    delete
        public function test12(){
            $model=D('Goods');
            $model->where('id=2')->delete();
            echo $model->getLastSql();  // 获取SQL语句
            dump($res);
        }



自动完成功能
    在使用create创建数据库连接时。TP会自动对接收的内容进行增加或者修改的操作
    自动完成的使用场景
        1、部分字段在表单没有体现出来（不需要用户进行录入操作），但是对于这些字段在数据库的添加或者是修改时，有需要将具体的对应的内容写到数据库中
        2、使用create方法创建数据之后，可能有部分数据在格式上不满足要求，就需要将具体的内容转换格式之后再写入数据库
    自动完成的使用方式 与自动验证类似
        静态方式：在模型类里面通过 $_auto 属性定义处理规则
        动态方式：使用模型类的auto方法动态创建自动处理规则

数据表字段缓存
    字段缓存：将具体的字段信息存储到某一个文件当中，在下一次实例化会直接读取文件信息。不会再去分析具体的数据表的结构
    开启字段缓存功能：
        关闭调试模式  shop\index.php
            // 开启调试模式 建议开发阶段开启 true 部署阶段注释或者设为false
            define('APP_DEBUG', false);

数据表字段定义
    在自定义的模型中，将具体的字段信息保存到某一个具体的属性中（固定的属性名称）。本质也就是将具体的字段信息保存到标量中。变量会载入内存。对于内存的速度比磁盘的速度要更快，因此使用字段定义功能比字段缓存效果更好
    1、修改Goods模型    shop\Application\Admin\Model\GoodsModel.class.php
        <?php
            // 引入命名空间
            namespace Admin\Model;
            // 引入TP模型基类
            use Think\Model;
            // 创建自定义模型类
            class GoodsModel extends Model{
                // 通过属性来指定具体的主键字段信息
                protected $pk='id';
                // 实现字段定义功能，需要将每一个字段写入到对应的数组中，每一个字段就是一个具体的元素
                // 注意每一个字段都需要写入进去，如果缺少某一个字段，在进行数据交互时，缺少的字段就不能正常的进行数据交互
                protected $fields = array(
                    'id','goods_name','goods_img','addtime','goods_body'    
                );
            }
        ?>
    2、修改test13方法   shop\Application\Admin\Controller\IndexController.class.php
        // 演示在实例化模型时有一个自动分析的功能 
        public function test13(){
            $model=D('Goods');
            dump($model->getpk());  // 能够获取到嘴硬的主键名称
            dump($model);
        }

数据创建
    使用模型对象调用create方法来自动的接受提交的数据内容并且对内容进行过滤操作（很具数据表的结构信息）。
    对于create功能非常多，还可以触发很多其他功能，例如自动验证
    1、修改商品的添加模板  shop\Application\Admin\View\Goods\add.html

        <!-- form中action后面写 __SELF__ 或者 直接留空（action=''）表示提交到本页面 -->
        <form action="" method="post">  
            <ul class="forminfo">
            <li><label>商品名称</label><input name="goods_name" type="text" class="dfinput" /><i>标题不能超过30个字符</i></li>
            <li><label>图片地址</label><input name="pic" type="text" class="dfinput" value="http://www.mycodes.net" /></li>
            <li><label>内容</label><textarea name="goods_body" cols="" rows="" class="textinput"></textarea></li>
            <li><label>&nbsp;</label><input name="" type="submit" class="btn" value="确认保存"/></li>
            </ul>
        </form>
    2、修改add方法  shop\Application\Admin\Controller\GoodsController.class.php
        // 实现商品的添加入库
        public function add()
        {
            /**
             * 需要add能够 实现 显示具体的表单 以及 实现 能够处理表单的提交
             * 为了实现该功能，可以通过具体的请求方式进行区分
             * 对于显示表单，对应get方式的请求，对于处理表单的提交，对应post方式的请求
             * 在TP中有很多内置常量，其中有多和常量可以区分出具体的请求方式
             * IS_GET：判断当前请求是否为get方式，如若是，则该值为true
             * IS_POST：判断当前请求是否为post方式，如若是，则该值为true
             * IS_AJAX：判断当前请求是否为ajax方式，如若是，则该值为true
             */
            if(IS_GET){
                $this->display();
            }else{
                // post表单的提交
                $model=D('Goods');
                /**
                 * 对于create方法，默认只能处理post方式的表单提交
                 * 为类满足get方式时  建议使用 I 函数，不建议使用原生PHP方式，I 函数有安全过滤功能
                 * 1、create($_GET)
                 * 2、create(I('get.id'))  ( I('get.id') <=> $_GET['id'] )
                 */
                $data=$model->create();
                dump($data);
            }
        }

字段的映射
    为了在表单中隐藏真实的字段名称，可以使用假名
    字段映射：在自定的模型中通过某一属性（有固定的名称）指定具体的假名与真名的对应关系
    1、修改模型增加属性  www\shop\Application\Admin\Model\GoodsModel.class.php
        <?php
            // 引入命名空间
            namespace Admin\Model;
            // 引入TP模型基类
            use Think\Model;
            // 创建自定义模型类
            class GoodsModel extends Model{
                // 通过属性来指定具体的主键字段信息
                protected $pk='id';
                // 实现字段定义功能，需要将每一个字段写入到对应的数组中，每一个字段就是一个具体的元素
                // 注意每一个字段都需要写入进去，如果缺少某一个字段，在进行数据交互时，缺少的字段就不能正常的进行数据交互
                protected $fields = array(
                    'id','goods_name','goods_img','addtime','goods_body'    
                );
                // 实现字段的映射功能，是数组格式，在数组中指定假名和真名的对应关系，下标是假名，对应的具体内容就是真名
                protected $_mmap=array(
                    // 左侧是假名   右侧是真名，对应数据表的字段名称
                    'name'=>'goods_name',
                    'pic'=>'goods_img',
                    'body'=>'goods_body'
                )
            }
        ?>
    2、修改模板   shop\Application\Admin\View\Goods\add.html

            <div class="formtitle"><span>基本信息</span></div>
            <!-- form中action后面写__SELF__ 或者 直接留空（action=''）表示提交到本页面 -->
            <form action="__SELF__" method="post">  
                <ul class="forminfo">
                <li><label>商品名称</label><input name="name" type="text" class="dfinput" /><i>标题不能超过30个字符</i></li>
                <li><label>图片地址</label><input name="pic" type="text" class="dfinput" value="http://www.mycodes.net" /></li>
                <li><label>内容</label><textarea name="body" cols="" rows="" class="textinput"></textarea></li>
                <li><label>&nbsp;</label><input name="" type="submit" class="btn" value="确认保存"/></li>
                </ul>
            </form>

    3、效果 =>
        array (size=3)
        'goods_name' => string '123' (length=3)
        'goods_img' => string 'http://www.mycodes.net' (length=22)
        'goods_body' => string '123' (length=3)

自动验证功能
    自动验证是ThinkPHP模型提供的一种数据验证方法，可以在使用create创建数据对象的时候自动进行数据验证
    验证规则：
        有两种方式：
            静态方式：在模型里面通过 $_validate 属性定义验证规则；
            动态方式：使用模型类的 validate 方法动态创建自动验证规则；
            无论是什么方式，验证规则的定义是统一的规则，定义格式为：
            array(
                array(验证字段1，验证规则，错误提示，【验证条件，附加规则，验证时间】),
                array(验证字段2，验证规则，错误提示，【验证条件，附加规则，验证时间】),
            )
    验证字段：需要在表单中验证的字段名称，可以是数据表中的字段名称，也可以是表单中辅助的字段名称，例如验证码
    验证规则：与附加规则配合使用
            当附加规则指定使用哪一种方式进行验证，验证规则会在附加规则的基础上再次指定具体使用的规则；
            附加规则制定具体的大范围，为验证规则则是指定的小范围
            常见的验证规则的使用
                附加规则为regex，验证规则可以写上具体的正则表达式
                附加规则为function，验证规则可以协写上具体的函数名称（PHP内置的函数名称、TP公共函数名称、自定义公共函数名称）
                附加规则为callback，验证规则可以写上具体当前模型下具体的某一个方法名称
    错误提示：当验证不通过时，提示给用户的提示语
    验证条件（可选）：控制当前的验证规则是否执行
            self:EXISTS_VALIDATE 或者0 存在字段就验证（默认）
            self:MUST_VALIDATE 或者1 必须验证
            self:VALUE_VALIDATE 或者2 值不为空的时候验证
    附加规则（可选）：指定当前的验证规则是使用哪一种方式进行验证
            使用最多的三个：写上名称（regex、function、callback）即可
            regex  正则验证，定义验证规则是一个正则表达式（默认）
            function  函数验证，定义的验证是一个函数名
            callback  方法验证，定义的验证规则是当前模型类的一个方法
    验证时间（可选）：控制当前的验证规则具体在什么时间生效；
            常见的验证时间：
            self::MODEL_INDERT 或者1  新增数据时验证
            self::MODEL_UPDATE 或者2  编辑数据是验证
            self::MODEL_BOTH 或者3  全部情况下验证（默认）
    自动验证的案例：
        1、修改商品添加模板  shop\Application\Admin\View\Goods\add.html

            <form action="__SELF__" method="post">  
                <ul class="forminfo">
                    <li><label>商品名称</label><input name="name" type="text" class="dfinput" /><i>标题不能超过30个字符</i></li>
                    <li><label>商品价格</label><input name="goods_price" type="text" class="dfinput" /></li>
                    <li><label>图片地址</label><input name="pic" type="text" class="dfinput" value="http://www.mycodes.net" /></li>
                    <li><label>内容</label><textarea name="body" cols="" rows="" class="textinput"></textarea></li>
                    <li><label>&nbsp;</label><input name="" type="submit" class="btn" value="确认保存"/></li>
                </ul>
            </form>
        2、修改Goods模型  shop\Application\Admin\Model\GoodsModel.class.php
            // 增加属性指定具体的自动验证规则
            protected $_validate=array(
                // array(验证字段1，验证规则，错误提示，【验证条件，附加规则，验证时间】)
                array('goods_name','checkName','goods_name error',1,'callback'),
                array('goods_price','check_price','goods_price error',1,'function'),
                array('goods_body','require','goods_body error',1,'regex')
            )
        3、修改Goods模型，增加方法  shop\Application\Admin\Model\GoodsModel.class.php
            // 检查用户提交的商品名称是否符合格式要求
            public function checkName($goods_name){
                // 长度进行验证
                if(mb_strlen($goods_name,'utf8')>3){
                    return false;
                }else{
                    return true;
                }
            }
        4、增加项目的公共函数  shop\Application\Admin\Common\function.php (新建文件)
            // 检查当前体检的商品价格是否满足格式要求
            function check_price($goods_price){
                if($goods_price<=0){
                    return false;
                }else{
                    return true;
                }
            }
        5、修改index.php文件  shop\index.php 开启调试模式 如果不开启，导致不能使用最新的函数
            // 开启调试模式 建议开发阶段开启 true 部署阶段注释或者设为false
            define('APP_DEBUG', true);
        6、修改add方法   shop\Application\Admin\Controller\GoodsController.class.php
            public function add()
            {
                /**
                * 需要add能够 实现 显示具体的表单 以及 实现 能够处理表单的提交
                * 为了实现该功能，可以通过具体的请求方式进行区分
                * 对于显示表单，对应get方式的请求，对于处理表单的提交，对应post方式的请求
                * 在TP中有很多内置常量，其中有多和常量可以区分出具体的请求方式
                * IS_GET：判断当前请求是否为get方式，如若是，则该值为true
                * IS_POST：判断当前请求是否为post方式，如若是，则该值为true
                * IS_AJAX：判断当前请求是否为ajax方式，如若是，则该值为true
                */
                if(IS_GET){
                    $this->display();
                }else{
                    // post表单的提交
                    $model=D('Goods');
                    /**
                    * 对于create方法，默认只能处理post方式的表单提交
                    * 为类满足get方式时  建议使用 I 函数，不建议使用原生PHP方式，I 函数有安全过滤功能
                    * 1、create($_GET)
                    * 2、create(I('get.id'))  ( I('get.id') <=> $_GET['id'] )
                    */
                    $data=$model->create();
                    if(!$date){
                        // 说明目前有数据格式不满足要求  可以直接使用error 来提示错误信息
                        // 也可以使用dump 打印  $model->getError()可以获取具体的错误信息
                        dump($model->getError());
                    }
                    dump($data);
                }
            }

自动完成功能  有一个修改操作
    在使用create创建数据库是TP会 自动 对 接收到的内容 进行 增加 或者 修改 的操作
    自定义完成的使用场景
        1、部分字段在表单没有体现出来（不需要用户进行录入操作），例如：createtime 等
        2、使用create方法创建数据之后，可能部分数据在格式上不满足要求，就需要通过自动完成来转换数据格式
    自动完成的使用方式，与自动验证方式两者相似
        有两种方式：
            静态方式：在模型里面通过 $_auto 属性定义处理规则；
            动态方式：使用模型类的 auto 方法动态创建自动处理规则；
            无论是什么方式，是统一的规则，定义格式为：
            array(
                array(完成字段1，完成规则，【完成条件，附加规则】),
                array(完成字段2，完成规则，【完成条件，附加规则】),
            )
            完成字段：实际字段名称
            完成规则：需要处理的规则，配合附加规则完成
            完成条件/完成时间（可选）：设置自动完成时间
                设置自动完成时间包括：
                self::MODEL_INDERT 或者 1  新增数据时处理（默认）
                self::MODEL_UPDATE 或者 2  跟新数据时处理
                self::MODEL_BOTH 或者 3  所有情况下都进行处理（默认）
            附加规则（可选）：
                使用最多的三个：写上名称（function、callback）即可
                function  使用函数，表示填充的内容是一个函数
                callback  回调方法，表示填充的内容是一个其他字段的值
    演示自动完成案例（动态方式）
        1、修改add方法   shop\Application\Admin\Controller\GoodsController.class.php
            public function add()
            {
                /**
                * 需要add能够 实现 显示具体的表单 以及 实现 能够处理表单的提交
                * 为了实现该功能，可以通过具体的请求方式进行区分
                * 对于显示表单，对应get方式的请求，对于处理表单的提交，对应post方式的请求
                * 在TP中有很多内置常量，其中有多和常量可以区分出具体的请求方式
                * IS_GET：判断当前请求是否为get方式，如若是，则该值为true
                * IS_POST：判断当前请求是否为post方式，如若是，则该值为true
                * IS_AJAX：判断当前请求是否为ajax方式，如若是，则该值为true
                */
                if(IS_GET){
                    $this->display();
                }else{
                    // post表单的提交
                    $model=D('Goods');
                    /**
                    * 对于create方法，默认只能处理post方式的表单提交
                    * 为类满足get方式时  建议使用 I 函数，不建议使用原生PHP方式，I 函数有安全过滤功能
                    * 1、create($_GET)
                    * 2、create(I('get.id'))  ( I('get.id') <=> $_GET['id'] )
                    */
                    // 定义具体的自动完成规则  动态模式
                    $auto=array(
                        array('addtime','time',1,'function')
                    );
                    $data=$model->auto($auto)->create();
                    if(!$date){
                        // 说明目前有数据格式不满足要求  可以直接使用error 来提示错误信息
                        // 也可以使用dump 打印  $model->getError()可以获取具体的错误信息
                        dump($model->getError());
                    }
                    dump($data);
                }
            }
        }

统计查询方法
    1、查询统计查询的方法：
        ThinkPHP为这些统计操作提供了一系列的内置方法
        Count：统计数量，参数是要统计的字段名（可选）；
        Max：最大值，参数是要统计的字段名（必须）；
        Min：最小值，参数是要统计的字段名（必须）；
        Avg：平均值，参数是要统计的字段名（必须）；
        Sum：总分，参数是要统计的字段名（必须）；
    演示统计相关的方法的使用   shop\Application\Admin\Controller\IndexController.class.php
        // 演示统计相关的方法的使用

        public function test14(){
            $model=D('Goods');
            // 计算当前数据的总行数
            echo $model->count();
            echo '<hr/>';
            // id 最大值
            echo $model->max('id');
            echo '<hr/>';
            // id 求和
            echo $model->sum('id');
            echo '<hr/>';
            // 使用where指定具体的条件（连贯操作）进行统计查询
            echo $model->where("goods_name='test13'")->sum('id');
        }

小技巧：开启TP开发者工具
    配置文件 shop\Application\Common\Conf\config.php

    <?php
        return array(
            //'配置项'=>'配置值'
            'TMPL_PARSE_STRING'=>array(
                '__PUBLIC_HOME__'=>'/Public/Home',
                '__PUBLIC_ADMIN__'=>'/Public/Admin'
            ),
            /* 数据库设置 */
            'DB_TYPE'                => 'mysql', // 数据库类型
            'DB_HOST'                => '127.0.0.1', // 服务器地址
            'DB_NAME'                => 'shop', // 数据库名
            'DB_USER'                => 'root', // 用户名
            'DB_PWD'                 => 'root', // 密码
            'DB_PORT'                => '3308', // 端口
            'DB_PREFIX'              => 'shop_', // 数据库表前缀 防止数据表发生冲突
            'SHOW_PAGE_TRACE'        => 'TRUE',  // 开启TP的开发者工具
        );
        
模型中的事物处理
    创建测试方法
        shop\Application\Admin\Controller\IndexController.class.php
        // 演示TP中的事物功能
        public function test15(){
            $model=D('Goods');
            // 开启事物
            $model->startTrans();

            $res=$model->where('id=5')->save(array('goods_name'=>'test15'));
            $res1=$model->where('id=4')->save(array('ids'=>'test15'));
            if($res1 !== false && $res !==false){
                $model->commit(); // 提交事物
            }else{
                $model->rollback(); // 事物回滚
            }
        }
        
TP中的连贯操作 
    连贯操作即在进行 数据交互 时使用 多个方法顺序执行 最后 在调用一个 CURD操作
    连贯操作需要使用模型对象用具体的连贯方法。可以调用一个或者多个，每一个连贯方法调用没有任何顺序
    使用格式：模型对象->连贯方法->CURD操作
    常用的连贯操作方法有 where field alias order limit 等
    创建测试方法    shop\Application\Admin\Controller\IndexController.class.php

    // 演示连贯操作的使用方式
    public function test16(){
        $model=D('Goods');
        // where field alias order limit
        // field 作用是指定具体需要的字段信息
        // 关于field参数格式跟原生SQL的格式一模一样
        $model->field('id,goods_name')->select();
        echo $model->getLastSql().'<hr/>';
        // alias 指定具体表的别名
        $model->alias('a')->field('id,goods_name')->where('a.id>2')->select();
        echo $model->getLastSql().'<hr/>';
        // order 作用：指定具体的排序方式
        $model->alias('a')->order('id desc')->field('id,goods_name')->where('a.id>2')->select();
        echo $model->getLastSql().'<hr/>';
        // limit 作用：限制具体的条数  有两种用法
        // 方式1：直接设置具体的数字，指定显示多少数据
        // 方式2：直接设置一个字符串格式的数据 例如："2，3 "表示偏两条数据去三条数据   可以实现具体的分页效果
        $model->alias('a')->order('id desc')->limit(3)->field('id,goods_name')->where('a.id>2')->select();
        echo $model->getLastSql().'<hr/>';
        // 方式2：直接设置一个字符串格式的数据 例如："2，3 "表示偏两条数据去三条数据   
        // 可以实现具体的分页效果
        $model->alias('a')->order('id desc')->limit('1,2')->field('id,goods_name')->where('a.id>2')->select();
        echo $model->getLastSql().'<hr/>';
    }

连表查询
    1、创建测试数据

        #学生表
        CREATE TABLE `shop_stu` (
        `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
        `class_id` int(11) NOT NULL DEFAULT '0' COMMENT '班级ID',
        `name` varchar(255) NOT NULL DEFAULT '' COMMENT '姓名',
        PRIMARY KEY (`id`)
        ) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;
        INSERT INTO `shop_stu` VALUES ('6', '1', 'zhangsan'),('7', '2', 'lisi'),('8', '3', 'wangwu');

        #班级表
        CREATE TABLE `shop_class` (
        `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
        `class_name` varchar(255) NOT NULL DEFAULT '' COMMENT '班级名称',
        `class_room` varchar(255) NOT NULL DEFAULT '' COMMENT '教室',
        PRIMARY KEY (`id`)
        ) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;
        INSERT INTO `shop_class` VALUES ('1', 'php1', '1301'),('2', 'php2', '1302'),('3', 'java1', '1303');
    2、创建测试方法    shop\Application\Admin\Controller\IndexController.class.php
        // 演示TP中连表查询功能
        public function test17(){
            // join 方法能够实现连表查询功能
            // join指定具体的连表查询条件（具体的连接方式(左、有、内连接)、具体需要连接哪一张数据表、具体的连接字段）
            $data=M('stu')->alias('a')->field('a.*,b.class_name,b.class_name')->join('left join shop_class b on a.class_id=b.id')->select();
            dump($data);
        }

TP 中使用原生SQL语句 （读写分离）
    Query ： 专门针对查询操作（select类型的SQL语句）使用，返回具体的结果集
    Execute ： 专门针对写入操作（增加、修改、删除）使用，返回具体的受影响的行数
    针对上面两个方法使用时：模型对象->query/execute($sql);
        shop\Application\Admin\Controller\IndexController.class.php
            // 演示TP使用原生的SQL语句
            public function test18(){
                $model=M();
                $data=$model->query('select * from shop_stu');
                dump($data);
                $res=$model->execute("insert into shop_stu value(null,1,'zhaoliu')");
                dump($res);
            }

模型中的钩子函数
    钩子函数：数据操作（写入、删除、修改）时，会自动触发的方法。
    对于钩子函数分为 前置 和 后置 之分，前置
    前置：数据在操作之前执行
    后置：在操作之后执行

    钩子函数的使用方式
        写入 ： _before_insert 、 _after_insert
        修改 ： _before_update 、 _after_update
        删除 ： _before_delete 、 _after_delete
    演示钩子函数使用方法
        创建测试方法     shop\Application\Admin\Controller\IndexController.class.php
            public function test19(){
                $model=D('Goods');
                $res=$model->add(array('goods_name'=>'test19'));
            }
        修改模型类增加具体的钩子函数   www\shop\Application\Admin\Model\GoodsModel.class.php
            // 数据写入的 前置 钩子函数
            public function _before_insert(&$data,$options){
                echo '_bofore_insert<hr/>';
                dump($data);
                dump($option);
            }
            // 数据写入的 后置 钩子函数
            public function _after_insert($data,$options){
                echo '_after_insert<hr/>';
                dump($data);
                dump($option);
            }

TP中的文件上传功能
    源代码 ：shop\ThinkPHP\Library\Think\Upload.class.php
    1、实例化类的对象（可以指定具体的配置信息）
    2、使用对象调用 uploadOne / upload 实现具体的文件上传

    文件上传案例
    修改商品添加模板
        shop\Application\Admin\View\Goods\add.html
            <form action="__SELF__" method="post" enctype="multipart/form-data">  
                <ul class="forminfo">
                    <li><label>商品名称</label><input name="name" type="text" class="dfinput" /><i>标题不能超过30个字符</i></li>
                    <li><label>商品价格</label><input name="goods_price" type="text" class="dfinput" /></li>
                    <li><label>图片地址</label><input name="pic" type="file" /></li>
                    <li><label>内容</label><textarea name="body" cols="" rows="" class="textinput"></textarea></li>
                    <li><label>&nbsp;</label><input name="" type="submit" class="btn" value="确认保存"/></li>
                </ul>
            </form>
        2、修改项目入口文件 shop/index.php
            // 设置编码
            header('content-type:text/html;charset=utf-8');
        3、修改控制器中的add方法  shop\Application\Admin\Controller\GoodsController.class.php
            // 实现商品的添加入库
            public function add()
            {
                /**
                * 需要add能够 实现 显示具体的表单 以及 实现 能够处理表单的提交
                * 为了实现该功能，可以通过具体的请求方式进行区分
                * 对于显示表单，对应get方式的请求，对于处理表单的提交，对应post方式的请求
                * 在TP中有很多内置常量，其中有多和常量可以区分出具体的请求方式
                * IS_GET：判断当前请求是否为get方式，如若是，则该值为true
                * IS_POST：判断当前请求是否为post方式，如若是，则该值为true
                * IS_AJAX：判断当前请求是否为ajax方式，如若是，则该值为true
                */
                if(IS_GET){
                    $this->display();
                }else{
                    // post表单的提交
                    $model=D('Goods');
                    /**
                    * 对于create方法，默认只能处理post方式的表单提交
                    * 为类满足get方式时  建议使用 I 函数，不建议使用原生PHP方式，I 函数有安全过滤功能
                    * 1、create($_GET)
                    * 2、create(I('get.id'))  ( I('get.id') <=> $_GET['id'] )
                    */
                    // 定义具体的自动完成规则  动态模式
                    $auto=array(
                        array('addtime','time',1,'function')
                    );
                    $data=$model->auto($auto)->create();
                    if(!$data){
                        // 说明目前有数据格式不满足要求  可以直接使用error 来提示错误信息
                        // 也可以使用dump 打印  $model->getError()可以获取具体的错误信息
                        // dump($model->getError());
                        $this->error($model->getError());
                    }
                    // dump($data);
                    $res=$model->add($data);
                    if(!$res){
                        $this->error($model->getError());
                    }
                    $this->success('写入数据成功');
                }
            }
        4、修改模型使用写入的前置钩子函数实现文件的上传功能  shop\Application\Admin\Model\GoodsModel.class.php
            // 数据写入的 前置 钩子函数
            public function _before_insert(&$data,$options){
                // 实现文件上传功能
                // 1、实例化对象
                $config=array(
                    'exts'=>array('jpg','gif'), // 设置容许上传的文件后缀
                );
                $upload = new \Think\Upload($config);
                // 2、使用对象调用方法实现具体的文件上传功能
                // 关于文件上传的跟母录需要手动创建。TP不会自动创建上传根目录
                $info=$upload->uploadOne($_FILES['pic']);
                if(!$info){
                    // 说明文件上传失败
                    $this->error=$upload->getError();
                    // echo $upload->getError();
                    return false;
                }
                // dump($info);
                // exit();
                // 将具体的上传后的文件的目录信息保存保存到$data
                $data['goods_img']='Upload/'.$info['savepath'].$info['savename'];
            }

TP中分页显示
    显示出商品列表
    1、修改Index方法获取数据
        // 实现商品显示列表
        public function index(){
            $model=D('Goods');
            // 获取所有的商品信息
            $data=$model->select();
            $this->assign('data',$data);
            $this->display();
        }
    2、修改模板显示数据     shop\Application\Admin\View\Goods\index.html

         <tbody>
            <volist name='data' id='vo'>
                <tr>
                    <td><input name="" type="checkbox" value="" /></td>
                    <td>{$vo.id}</td>
                    <td>{$vo.goods_name}</td>
                    <td><img src="/{$vo.goods_img}" height="50px" width="50px"></td>
                    <td>{$vo.addtime|date='Y-m-d H:i:s',###}</td>
                    <td>{$vo.goods_body}</td>
                    <td><a href="#" class="tablelink">查看</a>     <a href="#" class="tablelink"> 删除</a></td>
                </tr> 
            </volist>   
        </tbody>
    3、具体分页的实现步骤
        1、计算出当前的总数据记录数（一共有多少条数据）
        2、实例化具体的分页类（需要指定具体的总的记录数，具体每一页显示多少条数据）
        3、调用具体show方法计算出具体的分页导航
        4、需要分局当前所在的页码显示具体的内容
    商品列表的分页
        1、修改 index 方法  shop\Application\Admin\Controller\GoodsController.class.php
            // 实现商品显示列表
            public function index(){
                $model=D('Goods');
                // 1、计算总的记录数
                $count=$model->count();
                // 2、实例化分页类对象
                $pagesize=1;
                $page=new \Think\Page($count,$pagesize);
                // 3、使用对象调用 show 方法获取分页导航信息
                $show=$page->show();
                $this->assign('show',$show);
                // 4、根据当前的页码显示具体商品信息
                $p=I('get.p');  // 定价与 $_GET['p'] 获取当前的页码
                // page 方法可以自动根据当前的页码以及每页显示的数据条数自动的就算出偏移量
                $data=$model->page($p,$pagesize)->select();
                // echo $model->getLastSql();
                // 获取所有的商品信息
                // $data=$model->select();
                $this->assign('data',$data);
                $this->display();
            }
        2、修改模板显示分页导航信息    shop\Application\Admin\View\Goods\index.html
            <div class="pagin">
                {$show}
            </div>

TP中的会话技术
    session
        TP中的session使用方式
            session 赋值: session('name','value');
            session 取值：$value=session('name');
            获取所有的session：$value=session();
            session 删除：session('name',null);
            删除所有session: sessio(null);
        session 的使用  shop\Application\Admin\Controller\IndexController.class.php
            // 演示 session 的设置操作
            public function setS(){
                session('id','234');
                $_SESSION['name']='leo';  // 按照原生方式使用
            }
            // 演示 session 的获取操作
            public function getS(){
                dump(session());
            }
            // 演示 session 的删除操作
            public function delS(){
                session('id',null);
            }
    kookie
        TP中cookie的使用方式
            Cookie的设置：
                cookie('name','value');   // 设置cookie
                cookie('name','value','60*60');    // 指定cookie保存时间
                // 还可以支持传入的方式完成复杂的cookie赋值，下面是对cookie的值设置3600秒有效期，并且加上cookie前缀think_
                // 前缀是防止出现同名
                cookie('name','value',array('expire'=>3600,'prefix'=>'think_'))
            Cookie 的获取
                $value=cookie('name');
            Cookie 的删除
                cookie('name',null);
        cookie 的使用   shop\Application\Admin\Controller\IndexController.class.php
            // 演示 cookie 的设置操作
            public function setC(){
                // 默认不指定有效期，表示cookie关闭浏览器失效
                cookie('id','56','expire=3600');
            }
            // 演示 cookie 的获取操作
            public function getC(){
                dump(cookie('id'));
            }
            // 演示 cookie 的删除操作
            public function delC(){
                cookie('id',null);
            }
TP 中的 验证码
    注意：验证码的使用需要有 gd库的支持

    查看验证码的使用方式（与文件上传和分页操作类似）    shop\ThinkPHP\Library\Think\Verify.class.php
        1、实例化验证码对象
        2、使用对象调用 entry方法 生成验证码
        3、可以使用 check方法 检验验证码是否匹配
    演示验证码的使用：
        1、生成验证码   shop\Application\Admin\Controller\IndexController.class.php
            public function test20(){
                $config=array(
                    'length'=>4,
                    'codeSet'=>'1234567890'
                );
                $config=array(
                $obj=new \Think\verify($config);
                $obj->entry();
            }
        2、实现比对操作   shop\Application\Admin\Controller\IndexController.class.php
            // 演示验证码的比对操作
            public function test21(){
                $config=array(
                    'length'=>4,
                    'codeSet'=>'1234567890'
                );
                $obj=new \Think\Verify($config);
                $code = I('get.code');
                dump($obj->check($code));
            }

登录案例
    显示登录页
        1、创建控制器方法渲染模板  shop\Application\Admin\Controller\LoginController.class.php(新建文件)
            <?php
                namespace Admin\Controller;
                use Think\Controller;
                class LoginController extends Controller{
                    public function login(){
                        $this->display();
                    }
                }
            ?>
        2、拷贝登录模板
            thinkPHP模板\静态模板-final\后台\修订\login.html ->shop\Application\Admin\View\Login(新建文件夹)中

        3、修改模板文件中的资源地址   shop\Application\Admin\View\Login    （4处）
            <link href="css/style.css" rel="stylesheet" type="text/css" />
            <script language="JavaScript" src="js/jquery.js"></script>
            <script src="js/cloud.js" type="text/javascript"></script>
            <body style="background-color:#1c77ac; background-image:url(images/light.png); background-repeat:no-repeat; background-position:center top; overflow:hidden;">

            改为：
            <link href="__PUBLIC_ADMIN__/css/style.css" rel="stylesheet" type="text/css" />
            <script language="JavaScript" src="__PUBLIC_ADMIN__/js/jquery.js"></script>
            <script src="__PUBLIC_ADMIN__/js/cloud.js" type="text/javascript"></script>
            <body style="background-color:#1c77ac; background-image:url(__PUBLIC_ADMIN__/images/light.png); background-repeat:no-repeat; background-position:center top; overflow:hidden;">
    显示验证码
        1、创建方法，生成验证法  shop\Application\Admin\Controller\LoginController.class.php
            // 实现验证码
            public function code(){
                // 实例化对象
                $obj=new \Think\Verify();
                $obj->entry();
            }
        2、修改模板文件，使用验证码  shop\Application\Admin\View\Login\login.html
            // 增加一个 li标签 并修改样式
            <li>
                <input name="" type="text" class="loginpwd" style="width: 130px;vertical-align: middle;" value="验证码" onclick="JavaScript:this.value=''"/>
                <img src="{:U('code')}" style="width: 130px;height: 50px;;vertical-align: middle;"/>
            </li>
        3、修改表单的提交方式
            1、修改模板   shop\Application\Admin\View\Login\login.html
                增加id
            2、使用js触发ajax请求
                <script>
                    $('.loginbtn').click(function(){
                        // 获取用户的输入功能
                        var username=$('#username').val();
                        var pwd=$('#pwd').val();
                        var code=$('#code').val();
                        // 触发ajax请求
                        $.ajax({
                            url:"{:U('checkLogin')}",
                            type:'post',
                            data:{username:username,pwd:pwd,code:code},
                            success:function(data){
                                // 规定返回结果需要保护status（状态码）和 msg（具体的内容）
                                if(data.status==1){
                                    // 登录成功
                                    location.href="{:U('Index/index')}";
                                }else{
                                    // 登录失败
                                    alert(data.msg);
                                }
                            }
                        })
                    })
                </script>
        4、实现登录功能
            1、创建 checkLogin方法 实现登录   shop\Application\Admin\Controller\LoginController.class.php
                // 实现用户登录操作
                public function checkLogin(){
                    // 1、接收用户提交内容
                    $username=I('post.username');
                    $pwd=I('post.pwd');
                    $code=I('post.code');
                    // 2、比对验证码
                    $obj=new \Think\Verify();
                    $res=$obj->check($code);
                    if(!res){
                        // 说明目前用户名验证码错误
                        // echo json_encode(array('status'=>0,'msg'=>'验证码错误'));
                        // exit;
                        $this->ajaxReturn(array('status'=>0,'msg'=>'验证码错误'));
                    }
                    // 3、比对用户与密码
                    if($username!='admin' || $pwd!='admin'){
                        $this->ajaxReturn(array('status'=>0,'msg'=>'用户名或者密码错误'));
                    }
                    // 4、保存用户的登录状态，使用session存储用户信息
                    session('user','admin');
                    $this->ajaxReturn(array('status'=>1,'msg'=>'OK'));
                }
            2、创建一个公共控制器实现判断操作  shop\Application\Admin\Controller\CommonController.class.php(新建文件)
                <?php
                    namespace Admin\Controller;

                    use Think\Controller;

                    class CommonController extends Controller{
                        public function __construct(){
                            parent::__construct();
                            // 判断用户是否登录
                            if(!session('user')){
                                // 说明用户没有登录
                                $this->error('没有登录'，U('Login/login'));
                            }
                        }
                    }
            3、修改目前控制器的继承关系
                1、新建shop\Application\Admin\Controller\CommonController.class.php
                    <?php
                        namespace Admin\Controller;

                        use Think\Controller;

                        class CommonController extends Controller{
                            public function __construct(){
                                parent::__construct();
                                // 判断用户是否登录
                                if(!session('user')){
                                    // 说明用户没有登录
                                    $this->error('没有登录',U('Login/login'));
                                }
                            }
                        }
                2、shop\Application\Admin\Controller\GoodsController.class.php 和 IndexController.class.php
                    继承CommonController