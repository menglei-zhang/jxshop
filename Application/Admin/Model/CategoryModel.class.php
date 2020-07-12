<?php
    namespace Admin\Model;

    class CategoryModel extends CommonModel{
        // 自定义字段
        protected $fields=array('id','cname','parent_id','isrec');
        // 自动验证
        protected $_validate=array(
            array('cname','require','分类名称必须填写'),
        );
        // 获取分类的子分类
        public function getChildren($id){
            // 先获取所有的分类信息
            $data=$this->select();
            // 对获取的信息进行格式化
            $list=$this->getTree($data,$id,1,false);
            foreach ($list as $key => $value) {
                $tree[]=$value['id'];
            }
            return $tree;
        }
        // 获取格式化之后的数据
        public function getCateTree($id=0){
            // 先获取所有的分类信息
            $data=$this->select();
            // 对获取的信息进行格式化
            $list=$this->getTree($data,$id);
            return $list;
        }
        // 格式化分类信息
        public function getTree($data,$id=0,$lev=1,$iscache=ture){
            static $list=array();  // 静态变量只会声明一次不会再次声明
            // 根据参数决定是否需要重置
            if(!$iscache){
                $list=array();
            }
            foreach ($data as $key => $value) {
                if($value['parent_id']==$id){
                    $value['lev']=$lev;
                    $list[]=$value;
                    // 使用递归的方式获取分类下的子分类
                    $this->getTree($data,$value['id'],$lev+1);
                }
            }
            return $list;
        }
        // 删除分类
        public function dels($id){
            $result=$this->where('parent_id='.$id)->find();
            if($result){
                return false;
            }
            return $this->where('id='.$id)->delete();
        }
        public function update($data){
            // 需要过滤掉设置父分类为自己或者自己下的子分类
            // 根据要修改的分类的表示 获取到自己下的所有子分类
            $tree=$this->getCateTree($data['id']);
            // 将自己添加到不能修改的数组中
            $tree[]=array('id'=>$data['id']);
            // 根据提交的parent_id的值，判断如果等于当前修改的分类ID或者是当前分类下的所有自焚类的ID不容许修改
            foreach ($tree as $key => $value) {
                if($data['parent_id']==$value['id']){
                    $this->error='不能设置子分类为父分类';
                    return false;
                }
            }
            $this->success('修改成功',U('index'));
        }
        // 获取楼层信息，包括楼层的分类信息以及商品信息
        public function getFloor(){
            // 获取顶级分类
            $data=$this->where("parent_id=0")->select();
            foreach ($data as $key => $value) {
                // 获取二级分类信息
                $data[$key]['son']=$this->where('parent_id='.$value['id'])->select();
                // 获取推荐的二级分类信息
                $data[$key]['recson']=$this->where('isrec=1 and parent_id='.$value['id'])->select();
                foreach ($data[$key]['recson'] as $k => $v) {
                    $data[$key]['recson'][$k]['goods']=$this->getGoodsByCateId($v['id']);
                }
            }
            return $data;
        }
        // 根据ID表示获取对应的商品信息
        public function getGoodsByCateId($cate_id,$limit=8){
            // 获取当页分类下面的子分类
            $children=$this->getChildren($cate_id);
            // // 当前分类的表示增加到总分类中
            $children[]=$cate_id;
            $children=implode(',',$children);
            $goods=D('Goods')->where("is_sale=1 and cate_id in ($children)")->limit($limit)->select();
            // dump($goods);
            return $goods;
        }
    }