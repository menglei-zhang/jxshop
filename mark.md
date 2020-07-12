### 虚拟主机配置
    创建根目录 -> 创建wwwroot目录（存放入口文件、重写规则以及资源文件） -> 
    配置虚拟主机 -> 修改本地host指向 -> 重启Apache -> 访问测试
### 项目部署
    拷贝TP框架源代码(Thinkphp) -> 拷贝入口文件(index.php)及重写规则文件 -> 修改入口文件 -> 访问生成Application文件
### 创建后台模块
    拷贝Home文件，改名Admin -> 修改Index控制器的命名空间 -> 访问测试

### URL地址优化
    隐藏项目的入口文件 
        开启Apache重写模块 -> 设置虚拟主机容许重写 -> 拷贝TP重写规则文件 -> 修改项目的配置设置为重写模式 -> 测试访问
    设置Home为默认的模块
        修改配置项设置默认模块 -> 增加设置容许访问的模块 -> 访问测试 -> 测试使用U函数生成URL地址
### 后台页面展示
    拷贝后台资源文件 -> 拷贝后台首页模板
创建数据分类表
    创建表 -> 配置数据库信息
修改控制器的继承关系 -> 创建分类的控制器
创建公共模型 -> 创建分类模型（一个数据表对应一个模型）
实现分类模板 -> 
数据入库 -> 自定义字段 -> 创建自动验证 -> 

商品分类数据添加
    拷贝模板 -> 修改资源地址 -> 页面展示 -> 数据入库（自定义字段、自动验证）

    public function add(){
        if(IS_GET){
            $this->display();
        }else{
            // 使用D函数实例化模型对象
            $model=D('Category');
            // 使用create创建数据库连接
            $data=$model->create();
            if(!$data){
                $this->error($model->getError());
            }
            // CURD：增（add、addAll）删（delete）改（save）查（find、select）
            $insertid=$model->add($data);
            if(!$insertid){
                $this->error('数据写入失败');
            }
            $this->success('写入成功');
        }
    }

### 实现可以添加为子分类
    控制器中调用模型方法获取数据 -> 
    在模型中创建getCateTree方法获取数据 -> 
    在模型中创建getTree方法格式化数据 -> 
    模板中对数据进行循环展示
### 模板继承
    在视图中创建Public目录，创建公共目录
    使用继承 <extend name="Public:base"/>
### 商品分类的列表显示

    
### 商品分类的删除
    修改分类列表的删除链接
    控制器中实现删除
    模型中实现删除

### 商品分类的编辑
    显示出要编辑的分类信息
        先给分类的列表增加修改的链接地址
        创建edit方法显示出分类的信息
        在公共模板中封装方法获取数据
        拷贝edit.html模板
        修改数据获取所有的分类信息
        模板中进行默认的数据显示


<td align="center">
    <img src="__PUBLIC_ADMIN__/Images/<eq name='vo.is_sale' value='1'>yes.gif<else />no.gif</eq> "/>
</td>

foreach ($list as $key => $value) {
    $tree[]=$value['id'];
}

$children=implode(',',$tree);




1、控制器获取信息
    $cate=D('Category')->getCateTree();
    $this->assign('cate',$cate);
2、控制器获取信息 --> 模板显示信息
    <volist name="cate" id="vo">
        <option value="{$vo.id}">|{$vo.lev|str_repeat="--",###}{$vo.cname}</option>
    </volist>
3、在模型中接收提交的分类ID
    $cate_is=intval(I('get.cate_id'));
    if($cate_id){
        // 拼接where字句
        // 根据提交的分类ID表示查询出商品表中
    }





1、控制器中调用分页方法
    $model=D('Goods');
    $data=$model->listData();
    $this->assign('data',$data);
    $this->display();
2、模型中显示分页的方法
    public function listData(){
        // 1、定义每页显示的条数
        $pagesize=5;
        // 2、计算出总条数
        $where='isdel=1';
        $count=$this->where($where)->count();
        // 3、计算出分页导航
        $page=new \Think\Page($count,$pagesize);
        $show=$page->show();
        // 4、获取当前页码
        $p=intval(I('get.p'));
        // 5、获取具体的数据
        $data=$this->where($where)->page($p,$pagesize)->select();
        // 6、返回数据及分页导航数据
        return array('pageStr'=>$show,'data'=>$data);
    }


{$time|date="Y-m-d H:i:s",###}
data=("Y-m-d H:i:s",$time)

