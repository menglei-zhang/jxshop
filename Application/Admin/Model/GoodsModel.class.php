<?php
    namespace Admin\Model;

    class GoodsModel extends CommonModel{
        // 自定义字段
        protected $fields=array('id','goods_name','goods_sn','cate_id','market_price','shop_price','goods_img','goods_thumb','goods_body','is_hot','is_rec','is_new','addtime','isdel','is_sale','type_id','goods_number','cx_price','start','end','plcount','sale_number');
        // 自定义验证
        protected $_validate=array(
            array('goods_name','require','商品名必须填写',1),
            array('cate_id','checkCategory','分类必须填写',1,'callback'),
            array('market_price','currency','市场价格格式不正确'),
            array('shop_price','currency','本店价格格式不正确')
        );
        public function checkCategory($cate_id){
            $cate_id=intval($cate_id);
            if($cate_id>0){
                return true;
            }
            return false;
        }
        // 实现商品信息修改
        public function update($data){
            // 促销商品时间的格式化操作
            if($data['cx_price']>0){
                // 设置商品伪促销商品
                $data['start']=strtotime($data['start']);
                $data['end']=strtotime($data['end']);
            }else{
                $data['cx_price']=0.00;
                $data['start']=0.00;
                $data['end']=0.00;
            }
            $goods_id=$data['id'];
            // 解决商品货号问题
            $goods_sn=$data['goods_sn'];
            if(!$goods_sn){
                $data['goods_sn']='JX'.uniqid();
            }else{
                $res = $this->where("goods_sn = '$goods_sn' AND id != $goods_id")->find();
                if($res){
                    $this->error='货号错误';
                    return false;
                }
            }
            // 解决扩展分类的问题 先删除之前的扩展分类，在添加新的
            $extCatemodel=M('GoodsCate');
            $extCatemodel->where('goods_id='.$goods_id)->delete();

            $ext_cate_id=I('post.ext_cate_id');
            // $ext_cate_id=array_unique($ext_cate_id);
            // foreach ($ext_cate_id as $key => $value) {
            //     if($value != 0){
            //         $list[]=array('goods_id'=>$goods_id,'cate_id'=>$value);
            //     }
            // }
            // M('GoodsCate')->addAll($list);
            D('GoodsCate')->insertExtCate($ext_cate_id,$goods_id);
            // 解决图片问题
            // 实现图片上传
            // $upload=new \Think\Upload();  // 实例化对象
            // $info=$upload->uploadOne($_FILES['goods_img']);
            // if(!$info){
            //     $this->error=$upload->getError();
            // }
            // $goods_img='Uploads/'.$info['savepath'].$info['savename'];
            // // 缩略图的制作
            // $img=new \Think\Image();
            // $img->open($goods_img);
            // $goods_thumb='Uploads/'.$info['savepath'].'thumb_'.$info['savename'];
            // $img->thumb(450,450)->save($goods_thumb);
            // $data['goods_img']=$goods_img;
            // $data['goods_thumb']=$goods_thumb;
            $res = $this->uploadImg();
            if($res){
                $data['goods_img'] = $res['goods_img'];
                $data['goods_thumb'] =  $res['goods_thumb'];
            }
            // 属性修改
            // 先删除已有的数据
            $goodsAttrModel=D('GoodsAttr');
            $goodsAttrModel->where('goods_id='.$goods_id)->delete();
            $attr=I('post.attr');
            // foreach ($attr as $key => $value) {
            //     foreach ($value as $v) {
            //         $attr_list[]=array(
            //             'goods_id'=>$goods_id,
            //             'attr_id'=>$key,
            //             'attr_values'=>$v
            //         );
            //     }
            // }
            $goodsAttrModel->insertAttr($attr,$goods_id);
            // 实现商品相册图片上传以及入库
            // 将商品图片上传释放
            unset($_FILES['goods_img']);
            $upload = new \Think\Upload();
            $info=$upload->upload();
            foreach ($info as $key => $value) {
                $goods_img='Uploads/'.$value['savepath'].$value['savename'];
                // 缩略图的制作
                $img=new \Think\Image();
                $img->open($goods_img);
                $goods_thumb='Uploads/'.$value['savepath'].'thumb_'.$value['savename'];
                $img->thumb(450,450)->save($goods_thumb);
                $list[]=array(
                    'goods_id'=>$goods_id,
                    'goods_img'=>$goods_img,
                    'goods_thumb'=>$goods_thumb
                );
            }
            if($list){
                M('GoodsImg')->addAll($list);
            }

            return $this->save($data);
        }
        // 使用TP的钩子函数
        public function _before_insert(&$data){
            // 促销商品时间的格式化操作
            if($data['cx_price']>0){
                // 设置商品伪促销商品
                $data['start']=strtotime($data['start']);
                $data['end']=strtotime($data['end']);
            }else{
                $data['cx_price']=0.00;
                $data['start']=0.00;
                $data['end']=0.00;
            }
            // 添加时间
            $data['addtime']=time();
            // 处理货号
            if(!$data['goods_sn']){
                $data['goods_sn']='JX'.uniqid();
            }else{
                $res=$this->where('goods_sn='.$data['goods_sn' AND id != 'goods_id'])->find();
                if($res){
                    $this->error='货号错误';
                    return false;
                }
            }
            // 实现图片上传
            // $upload=new \Think\Upload();  // 实例化对象
            // $info=$upload->uploadOne($_FILES['goods_img']);
            // if(!$info){
            //     $this->error=$upload->getError();
            // }
            // $goods_img='Uploads/'.$info['savepath'].$info['savename'];
            // // 缩略图的制作
            // $img=new \Think\Image();
            // $img->open($goods_img);
            // $goods_thumb='Uploads/'.$info['savepath'].'thumb_'.$info['savename'];
            // $img->thumb(450,450)->save($goods_thumb);
            // $data['goods_img']=$goods_img;
            // $data['goods_thumb']=$goods_thumb;
            $res = $this->uploadImg();
            if($res){
                $data['goods_img'] = $res['goods_img'];
                $data['goods_thumb'] =  $res['goods_thumb'];
            }
        }
        public function _after_insert($data){
            $goods_id=$data['id'];
            $ext_cate_id=I('post.ext_cate_id');
            // $ext_cate_id=array_unique($ext_cate_id);
            // foreach ($ext_cate_id as $key => $value) {
            //     if($value != 0){
            //         $list[]=array('goods_id'=>$goods_id,'cate_id'=>$value);
            //     }
            // }
            // M('GoodsCate')->addAll($list);
            D('GoodsCate')->insertExtCate($ext_cate_id,$goods_id);

            // 属性入库
            $attr=I('post.attr');
            // foreach ($attr as $key => $value) {
            //     foreach ($value as $v) {
            //         $attr_list[]=array(
            //             'goods_id'=>$goods_id,
            //             'attr_id'=>$key,
            //             'attr_values'=>$v
            //         );
            //     }
            // }
            // M('GoodsAttr')->addAll($attr_list);
            D('GoodsAttr')->insertAttr($attr,$goods_id);
            // 实现商品相册图片上传以及入库
            // 将商品图片上传释放
            unset($_FILES['goods_img']);
            $upload = new \Think\Upload();
            $info=$upload->upload();
            foreach ($info as $key => $value) {
                $goods_img='Uploads/'.$value['savepath'].$value['savename'];
                // 缩略图的制作
                $img=new \Think\Image();
                $img->open($goods_img);
                $goods_thumb='Uploads/'.$value['savepath'].'thumb_'.$value['savename'];
                $img->thumb(450,450)->save($goods_thumb);
                $list[]=array(
                    'goods_id'=>$goods_id,
                    'goods_img'=>$goods_img,
                    'goods_thumb'=>$goods_thumb
                );
            }
            if($list){
                M('GoodsImg')->addAll($list);
            }
        }
        public function listData($isdel=1){
            // 1、定义每页显示的条数
            $pagesize=10;
            // 2、计算出总条数
            $where='isdel='.$isdel;
            // 接收提交的分类ID
            $cate_id=intval(I('get.cate_id'));
            if($cate_id){
                $cateModel=D('Category');
                $tree=$cateModel->getChildren($cate_id);
                // 记录商品分类ID及子分类ID
                $tree[]=$cate_id;
                // 将$tree转换成字符转格式
                $children=implode(",",$tree);
                // 获取扩展分类的商品ID
                $ext_goods_ids=M('GoodsCate')->group("goods_id")->where("cate_id in ($children)")->select();
                if($ext_goods_ids){
                    foreach ($ext_goods_ids as $key => $value) {
                        $goods_ids[]=$value['goods_id'];
                    }
                    $goods_ids=implode(',',$goods_ids);
                }
                if(!$goods_id){
                    $where .= " AND cate_id in ($children)";
                }else{
                    $where .= "AND (cate_id in ($children) OR id in ($goods_ids))";
                }
                $count = $this->where($where)->count();

            }
            // 接收提交的推荐状态
            $intro_type=I('get.intro_type');
            if(intro_type){
                if($intro_type=="is_rec" || $intro_type=="is_new" || $intro_type=="is_hot"){
                    $where .= " AND $intro_type=1";
                }
            }
            // 接收上下架
            $is_sale=intval(I('get.is_sale'));
            if($is_sale==1){
                $where .= " AND is_sale=1";
            }elseif($is_sale==2){
                $where .= " AND is_sale=2";
            }
            // 接收关键词
            $keyword=I('get.keyword');
            if($keyword){
                $where .= " AND goods_name like '%$keyword%'";
            }
            $count=$this->where($where)->count();
            // 3、生成分页导航
            $page=new \Think\Page($count,$pagesize);
            $show=$page->show();
            // 4、获取当前页码
            $p=intval(I('get.p'));
            // 5、获取具体的数据
            $data=$this->where($where)->page($p,$pagesize)->select();
            // 6、返回数据及分页导航数据
            return array('pageStr'=>$show,'data'=>$data);
        }
        public function setStatus($goods_id,$isdel=0){
            return $this->where("id=$goods_id")->setField('isdel',$isdel);
        }
        public function remove($goods_id){
            // 删除商品的图片
            $goods_info=$this->findOneById($goods_id);
            if(!$goods_id){
                return false;
            }
            unlink($goods_info['goods_img']);
            unlink($goods_info['goods_thumb']);
            // 删除商品的扩展分类
            D('GoodsCate')->where("goods_id=$goods_id")->delete();
            // 删除商品的基本信息
            $this->where("id=$goods_id")->delete();
            return true;
        }
        // 封装图片上传
        public function uploadImg(){
            // 判断是否有图片上传
            if(!isset($_FILES['goods_img']) || $_FILES['goods_img']['error'] != 0){
                return false;
            }
            $upload=new \Think\Upload();  // 实例化对象
            $info=$upload->uploadOne($_FILES['goods_img']);
            if(!$info){
                $this->error=$upload->getError();
            }
            $goods_img='Uploads/'.$info['savepath'].$info['savename'];
            // 缩略图的制作
            $img=new \Think\Image();
            $img->open($goods_img);
            $goods_thumb='Uploads/'.$info['savepath'].'thumb_'.$info['savename'];
            $img->thumb(450,450)->save($goods_thumb);
            // $data['goods_img']=$goods_img;
            // $data['goods_thumb']=$goods_thumb;
            return array('goods_img'=>$goods_img,'goods_thumb'=>$goods_thumb);
        }
        // 根据传递的参数返回热卖、推荐、新品的商品信息
        public function getRecGoods($type){
            return $this->where("is_sale=1 and isdel=1 and $type=1")->limit(5)->select();
        }
        // 促销产品获取
        public function getCrazyGoods(){
            $where='is_sale = 1 and cx_price > 0 and start < ' .time().' and end > '.time();
            return $this->where($where)->limit(5)->select();
        }
        // 获取某个字根类下的商品信息
        public function getList(){
            $cate_id=I('get.id');
            // 获取当前商品分类下的子分类
            $children=D('Admin/Category')->getChildren($cate_id);
            // 将当前分类追加到子分类中
            $children[]=$cate_id;
            $children=implode(',',$children);
            // 组装具体的查询条件
            $where="is_sale=1 and cate_id in ($children)";
            // 获取当前的页码
            $p=I('get.p');
            // 每页条数
            $pagesize=5;
            // 计算总条数
            $count=$this->where($where)->count();
            // 计算具体的分类信息
            $page=new \Think\Page($count,$pagesize);
            // // 启用锚点
            // $page->setConfig('anchorName',paixu);
            // $page->setConfig('is_anchor',true);
            // dump($page);
            $show=$page->show();
            // 根据当前所在的页码获取对应的商品数据
            // 接收字段
            $sort=I('get.sort')?I('get.sort'):'sale_number';
            $list = $this->where($where)->page($p,$pagesize)->order($sort.' desc')->select();
            return array('list'=>$list,'page'=>$show);
        }
    }