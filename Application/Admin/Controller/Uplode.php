<?php

    
    class Uplode extends Controller
    {
        public function img_upload(){
            $file = $this->request->file('file');
    
            $map = [
                'ext'=>'jpeg,jpg,png',
                'size'=>'40000000'
            ];
    
            $info = $file->validate($map)->move('public/static/uploads');
    
            if($info)
            {
                $imgSrc = $info->getSaveName();
                $imgSrc=str_replace('\\','/',$imgSrc);
                $photo = '/public/static/uploads/'.$imgSrc;
            }else{
                // 上传失败获取错误信息
                $photo = '';
                $file->getError();
            }
    
            if($photo !== ''){
                return ['code'=>1,'msg'=>'上传成功啦！！','photo'=>$photo];
    
            }else{
                return ['code'=>404,'msg'=>'上传失败啦！！'];
            }
        }
        
        public function img_upload_tt(){
            $file = $this->request->file('file');
    
            $map = [
                'ext'=>'jpeg,jpg,png,gif',
                'size'=>'40000000'
            ];
    
            $info = $file->validate($map)->move('public/static/uploads');
    
            if($info)
            {
                $imgSrc = $info->getSaveName();
                $imgSrc=str_replace('\\','/',$imgSrc);
                $photo = 'https://ygbao.magicxhx.com/public/static/uploads/'.$imgSrc;
            }else{
                // 上传失败获取错误信息
                $photo = '';
                $file->getError();
            }
    
            if($photo !== ''){
                return json(['code'=>1,'msg'=>'上传成功啦！！','photo'=>$photo]);
    
            }else{
                return json(['code'=>404,'msg'=>'上传失败啦！！']);
            }
        }
    }





?>