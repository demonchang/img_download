<?php 


$teststr = '<p>VR游戏GoPro好不好玩？为大家分享一篇VR游戏GoPro下载，希望对喜欢VR游戏GoPro的小伙伴们都有所帮助。</p><p style="text-align: center"><img src="http://filedl.gao7.com/g1/M00/8F/5D/CilEmlffpXSABxaiAAAPAy6nIYU881.jpg?w=139&h=131" width="139" height="131" title="icon.jpg"/></p><p style="text-align: center;"><a href="http://pan.baidu.com/s/1i4NAWrb" target="_blank" title="">下载地址</a></p><p>《GoPro VR》是GoPro出的VR视频应用。</p><p>• 像素级别精准投影</p><p>• 360度无缝互动</p><p>• VR 模式：拆分视频使用VR眼镜观看</p><p>• 陀螺仪支持：移动您的设备在任何方向导航</p><p>• 在新设备上呈现高达60 fps</p><p>• 在线播放虚拟现实平台上360度视频或播放GoPro储存在你的智能手机自己的视频</p><p style="text-align: center"><img src="http://filedl.gao7.com/g1/M00/8F/5D/CilEmlffphuAYBIyAACaL7o9xkI815.jpg?w=344&h=548" width="344" height="548" title="GoPro安卓下载 VR游戏GoPro下载.jpg" border="0" hspace="0" vspace="0" style="width: 344px; height: 548px;"/></p>';

var_dump(pregImg($teststr));

/*＊图片下载并替换地址
*@param str $str 要替换的内同
*@param str $host 域名地址 默认不写
*@param str $imgpath 图片存储地址 请保证目录已经存在
*return  
*/
function pregImg($str, $host='', $imgpath=''){
    //匹配img标签中src引用的图片链接
    $preg = '#src="(.*?jpg|jpeg|gif|png)#';

	preg_match_all($preg ,$str ,$out);
    $res = array();
	if ($out) {
        $count = count($out[1]);
        for ($i=0; $i < $count; $i++) {
            //如果图片链接中为相对路径则加上对应的域名
            $url = ($host)?$host.$out[1][$i]:$out[1][$i];
            //将下载后的文件名称保存起来
            $res[$i] = getImage($url, '', $imgpath);
        }
    }

    if ($res) {
        $pattern = array();
        $replacement = array();
        for ($j=0; $j < count($res); $j++) { 
            //重新生成图片正则
            $pattern[] = '#'.$out[1][$j].'#';
            //替换成的下载后的图片名称
            $replacement[] = $res[$j];
        }
        $result = preg_replace($pattern, $replacement, $str);
        if (!$result) {
            return false;
        }

    }else{
        return false;
    }
    return $result;

}

/*＊下载图片
*@param str $url 图片原链接
*@param str $filename 图片别名  不传默认以时间加随机数命名
*@param str $imgpath 图片存储地址
*@param int $type 下载类型  0为默认
*return  
*/
function getImage($url, $filename='', $imgpath='', $type=0){
    if($url==''){return false;}
    if($filename==''){
        $ext=strrchr($url,'.');

        if($ext!='.gif' && $ext!='.jpg' && $ext!='.png' ){
                return false;
        }
        if ($imgpath) {
            $filename=$imgpath.time().mt_rand(0,10000).$ext;
        }else{
            $filename=time().mt_rand(0,10000).$ext;
        }
        
    }
    //文件保存路径 
    if($type){
        $ch=curl_init();
        $timeout=5;
        curl_setopt($ch,CURLOPT_URL,$url);
        curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
        curl_setopt($ch,CURLOPT_CONNECTTIMEOUT,$timeout);
        $img=curl_exec($ch);
        curl_close($ch);
    }else{
        ob_start(); 
        readfile($url);
        $img=ob_get_contents(); 
        ob_end_clean(); 
    }
    $size=strlen($img);
    //文件大小 
    $fp2=@fopen($filename,'a');
    fwrite($fp2,$img);
    fclose($fp2);
    return $filename;
}


 ?>