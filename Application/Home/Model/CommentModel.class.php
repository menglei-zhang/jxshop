<?php
    namespace Home\Model;
    use Think\Model;
    class CommentModel extends Model{
        // 自定义字段
        protected $fields=array('id','user_id','goods_id','addtime','content','star','good_number');
        public function _before_insert(&$data){
            $data['addtime']=time();
            $data['user_id']=session('user_id');
        }
        // 印象入库
        public function _after_insert($data){
            // 选择印象入库
            $old=I('post.old');
            foreach ($old as $key => $value) {
                M('Impression')->where('id='.$value)->setInc('count');
            }
            $name=I('post.name');
            // 将name转换成数组格式
            $name=explode(',',$name);
            // 对数组name进行去重操作
            $name=array_unique($name);
            foreach ($name as $key => $value) {
                if(!$value){
                    continue;
                }
                // 判断当前的印象在数据库中是否存在，如果存在，更新count值，如果不存在直接写入
                $where=array('goods_id'=>$data['goods_id'],'name'=>$value);
                $model=M('Impression');
                $res=$model->where($where)->find();
                if($res){
                    $model->where($where)->setInc('count');
                }else{
                    $where['count']=1;
                    $model->add($where);
                }
            }
            //实现商品表中评论总数增加
            M('Goods')->where('id='.$data['goods_id'])->setInc('plcount');
        }
        // 获取评论信息
        public function getList($goods_id){
            // 获取当前页
            $p=I('get.p');
            $pagesize=6;
            $count=$this->where('goods_id='.$goods_id)->count();
            $page=new \Think\Page($count,$pagesize);
            // 启用锚点
            $page->setConfig('is_anchor',true);
            // dump($page);
            $show=$page->show();
            $list=$this->alias('a')->field('a.*,b.username')->join('left join jx_user b on a.user_id=b.id')->where("a.goods_id=".$goods_id)->page($p,$pagesize)->select();
            return array('list'=>$list,'page'=>$show);
        }
    }



?>