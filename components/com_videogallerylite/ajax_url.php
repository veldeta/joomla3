<?php
/**
 * @package Huge IT Video GalleryLite
 * @author Huge-IT
 * @copyright (C) 2014 Huge IT. All rights reserved.
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * @website		http://www.huge-it.com/
 **/

if(!defined('_JEXEC')) {

define('_JEXEC',1);

} else {

    defined('_JEXEC') or die('Restircted access');

}

if (stristr( $_SERVER['SERVER_SOFTWARE'], 'win32' )) {
    define( 'JPATH_BASE', realpath(dirname(__FILE__).'\..\..' ));
	} else define( 'JPATH_BASE', realpath(dirname(__FILE__).'/../..' ));
	define( 'DS', DIRECTORY_SEPARATOR );
	require_once ( JPATH_BASE.DS.'includes'.DS.'defines.php' );
	require_once ( JPATH_BASE.DS.'includes'.DS.'framework.php' );
	$app =JFactory::getApplication('site');
	
	$app->initialise();
	jimport( 'joomla.user.user' );
	jimport( 'joomla.user.helper' );
        
        $db =JFactory::getDBO();
        
        if(isset($_POST['task']) && $_POST['task']=="load_videos_content"){
            
            $page = 1;
            
        
            if(!empty($_POST["page"]) && is_numeric($_POST['page']) && $_POST['page']>0){
                $paramssld='';
                $db5 = JFactory::getDBO();
                $query5 = $db->getQuery(true);
                $query5->select('*');
                $query5->from('#__huge_it_videogallery_params');
                $db->setQuery($query5);
                $options_params = $db5->loadObjectList();
                foreach ($options_params as $rowpar) {
                    $key = $rowpar->name;
                    $value = $rowpar->value;
                    $paramssld[$key] = $value;
                }
                $page = intval($_POST["page"]);
                if(isset($_POST['perpage']) && !empty($_POST['linkbutton'])) {
                    $num = intval($_POST['perpage']);
                }
                $start = $page * $num - $num;
                $start = intval($start);
                if(isset($_POST['galleryid']) && !empty($_POST['linkbutton'])) {
                    $idofgallery = intval($_POST['galleryid']);
                }
                
                $query = $db->getQuery(true);
                $query->select('*');
                $query->from('#__huge_it_videogallery_videos');
                $query->where('videogallery_id ='.$idofgallery);
                $query ->order('#__huge_it_videogallery_videos.ordering asc');
                $db->setQuery($query,$start,$num);
                $page_images = $db->loadObjectList();
                $output = '';
                $append = '';
                
                  foreach($page_images as $key=>$row){
                    $link = str_replace('__5_5_5__','%',$row->sl_url);
                    $video_name=str_replace('__5_5_5__','%',$row->name);
                    $id = intval($row->id);
                    $descnohtml=strip_tags(str_replace('__5_5_5__','%',$row->description));
                    $appendload = appendload($row,$key,$paramssld);
                    $result = substr($descnohtml, 0, 50);
                    
                    
                    
                    $imagerowstype=$row->sl_type;
                    if($row->sl_type == ''){$imagerowstype='image';}
                    switch($imagerowstype){
                        case 'image':
                            $imgurl=explode(";",$row->image_url); 
                            if($row->image_url != ';'){ 
                                $video='<img id="wd-cl-img'.$key.'" src="'.$imgurl[0].'" alt="" />';
                             } else {
                            $video='<img id="wd-cl-img'.$key.'" src="images/noimage.jpg" alt="" />';
                            }
                            break;
                                case 'video':
                                    $videourl=get_video_gallery_id_from_url($row->image_url);
                                    
                                       if($videourl[1]=='youtube'){
                                    if(empty($row->thumb_url)){
                                            $thumb_pic='http://img.youtube.com/vi/'.$videourl[0].'/mqdefault.jpg';
                                        }else{
                                            $thumb_pic=$row->thumb_url;
                                        }
                                
                                $video='<img src="'.$thumb_pic.'" alt="" />';                             
                            
                                }else {
                                $hash = unserialize(get("http://vimeo.com/api/v2/video/".$videourl[0].".php"));
                                if(empty($row->thumb_url)){
                                        $imgsrc=$hash[0]['thumbnail_large'];
                                    }else{
                                        $imgsrc=$row->thumb_url;
                                    }
                            
                                $video='<img src="'.$imgsrc.'" alt="" />';
                            
                            }
                                break;
                    }
                    
                     if(str_replace('__5_5_5__','%',$row->sl_url)=='' ){
                $button='';
            }else{
                if ($row->link_target=="on"){
                    $target='target="_blank"';
                }else{
                    $target='';
                }
                if(isset($_POST['linkbutton']) && !empty($_POST['linkbutton'])){
                    $linkbutton = strip_tags($_POST['linkbutton']);
                    }
                $button='<div class="button-block"><a href="'.str_replace('__5_5_5__','%',$row->sl_url).'" '.$target.' >'.$linkbutton.'</a></div>';
            }
            $append.= $appendload;
            $output.='<div class="element_'.$idofgallery.' " id="huge_it_videogallery_pupup_element_'.$row->id.'_child"  tabindex="0"  data-symbol="'.$video_name.'"  data-category="alkaline-earth">';
            $output.='<input type="hidden" class="pagenum" value="'.$page.'" />';
            $output.='<div class="image-block_'.$idofgallery.'">';
            $output.=$video;
            $output.='<div class="videogallery-image-overlay"><a href="#'.$id.'"></a></div>';
            $output.='</div>';
            $output.='<div class="title-block_'.$idofgallery.'">';
            $output.='<h3>'.$video_name.'</h3>';
            $output.=$button;
            $output.='</div>';
            $output.='</div>';
        
     }
     
      $response = array("success"=>$output,"append"=>$append);
                        echo json_encode($response);
                        die();
                  }
            }else ///////////////////////////////////////////////////////////////////////////////////////////////
if(isset($_POST['task']) && $_POST['task']=="load_videos_lightbox"){
    $page = 1;
    if(!empty($_POST["page"]) && is_numeric($_POST['page']) && $_POST['page']>0){
        $page = intval($_POST["page"]);
        if(isset($_POST['perpage']) && !empty($_POST['perpage'])) {
            $num = intval($_POST['perpage']);
        }
        $start = $page * $num - $num; 
		$start = intval($start);
        if(isset($_POST['galleryid']) && !empty($_POST['galleryid'])) {
            $idofgallery = intval($_POST['galleryid']);
        }
        
        $query = $db->getQuery(true);
        $query->select('*');
        $query->from('#__huge_it_videogallery_videos');
        $query->where('videogallery_id ='.$idofgallery);
        $query ->order('#__huge_it_videogallery_videos.ordering asc');
        $db->setQuery($query,$start,$num);
        $page_images = $db->loadObjectList();
        $output = '';
        foreach($page_images as $key=>$row)
    {
        $link = str_replace('__5_5_5__','%',$row->sl_url);
        $video_name=str_replace('__5_5_5__','%',$row->name);
        $descnohtml=strip_tags(str_replace('__5_5_5__','%',$row->description));
        $result = substr($descnohtml, 0, 50);
        ?>
                <?php 
                    $imagerowstype=$row->sl_type;
                    if($row->sl_type == ''){$imagerowstype='image';}
                    switch($imagerowstype){
                        case 'image':
                ?>                                  
                            <?php $imgurl=explode(";",$row->image_url); ?>
                            <?php  
                             if($row->image_url != ';'){ 
                            $video='<a href="'.$imgurl[0].'" title="'.$video_name.'"><img id="wd-cl-img'.$key.'" src="'.$imgurl[0].'" alt="'.$video_name.'" /></a>';
                            } 
                            else { 
                            $video='<img id="wd-cl-img'.$key.'" src="images/noimage.jpg" alt="" />';
                           
                            } ?>

                <?php
                        break;
                        case 'video':
                ?>
                        <?php
                            $videourl=get_video_gallery_id_from_url($row->image_url);
                            if($videourl[1]=='youtube'){
                                    if(empty($row->thumb_url)){
                                            $thumb_pic='http://img.youtube.com/vi/'.$videourl[0].'/mqdefault.jpg';
                                        }else{
                                            $thumb_pic=$row->thumb_url;
                                        }
                                
                                $video='<a class="youtube huge_it_videogallery_item group1"  href="https://www.youtube.com/embed/'.$videourl[0].'" title="'.$video_name.'">
                                            <img src="'.$thumb_pic.'" alt="'.$video_name.'" />
                                            <div class="play-icon '.$videourl[1].'-icon"></div>
                                        </a>';                             
                            
                                }else {
                                $hash = unserialize(get("http://vimeo.com/api/v2/video/".$videourl[0].".php"));
                                if(empty($row->thumb_url)){
                                        $imgsrc=$hash[0]['thumbnail_large'];
                                    }else{
                                        $imgsrc=$row->thumb_url;
                                    }
                            
                                $video='<a class="vimeo huge_it_videogallery_item group1" href="http://player.vimeo.com/video/'.$videourl[0].'" title="'.$video_name.'">
                                    <img src="'.$imgsrc.'" alt="" />
                                    <div class="play-icon '.$videourl[1].'-icon"></div>
                                </a>';
                            
                            }
                        ?>
                <?php
                        break;
                    }
                ?>
            
          
         <?php if(str_replace('__5_5_5__','%',$row->name)!=""){
                if ($row->link_target=="on"){
                   $target= 'target="_blank"';
                }else{
                    $target= '';
                }
               $linkimg='<div class="title-block_'.$idofgallery.'"><a href="'.$link.'"'.$target.'>'.$video_name.'</a></div>';
            
            } else{
                $linkimg='';
            }?>
      
    
    <?php

            $output.='<div class="element_'.$idofgallery.'" tabindex="0" data-symbol="'.$video_name.'"  data-category="alkaline-earth">';
            $output.='<input type="hidden" class="pagenum" value="'.$page.'" />';
            $output.='<div class="image-block_'.$idofgallery.'">';
            $output.=$video;
            $output.=$linkimg;
            $output.='</div>';
            $output.='</div>';
     }
        echo json_encode(array("success"=>$output));
        die();
    }
}if(isset($_POST['task']) && $_POST['task']=="load_videos_thumbnail"){
        global $wpdb;
    $page = 1;
    if(!empty($_POST["page"]) && is_numeric($_POST['page']) && $_POST['page']>0){

        $page = intval($_POST["page"]);
        if(isset($_POST['perpage']) && !empty($_POST['perpage'])) {
            $num = intval($_POST['perpage']);
        }
        $start = $page * $num - $num;
        $start = intval($start);
        if(isset($_POST['galleryid']) && !empty($_POST['galleryid'])) {
            $idofgallery = intval($_POST['galleryid']);
        }
        $query = $db->getQuery(true);
        $query->select('*');
        $query->from('#__huge_it_videogallery_videos');
        $query->where('videogallery_id ='.$idofgallery);
        $query ->order('#__huge_it_videogallery_videos.ordering asc');
        $db->setQuery($query,$start,$num);
        $page_images = $db->loadObjectList();
        $output = '';
        foreach($page_images as $key=>$row){
            $video_name=str_replace('__5_5_5__','%',$row->name);
            $video_thumb=$row->thumb_url;
            $videourl=get_video_gallery_id_from_url($row->image_url);
         $imagerowstype=$row->sl_type; 
                    if($row->sl_type == ''){$imagerowstype='image';}
                    
                    switch($imagerowstype){
                        case 'image': 
                        $video='<a class="group1" href="'.$videourl[0].'"></a>
                        <img src="'.$row->image_url.'" alt="'.$video_name.'" />';
                    ?>                                  
                         
                    <?php 
                        break;
                        case 'video':

            if($videourl[1]=='youtube'){
                if(empty($row->thumb_url)){
                                            $thumb_pic='http://img.youtube.com/vi/'.$videourl[0].'/mqdefault.jpg';
                                        }else{
                                            $thumb_pic=$row->thumb_url;
                                        }
                $video = '<a  class="youtube huge_it_videogallery_item group1"  href="https://www.youtube.com/embed/'.$videourl[0].'" title="'.$video_name.'"></a>
                                    <img src="'.$thumb_pic.'" alt="'.$video_name.'" />';
            }else {

                $hash = unserialize(get("http://vimeo.com/api/v2/video/".$videourl[0].".php"));
                                    
                                    if(empty($row->thumb_url)){
                                        $imgsrc=$hash[0]['thumbnail_large'];
                                    }else{
                                        $imgsrc=$row->thumb_url;
                                    }
                $video = '<a  class="vimeo huge_it_videogallery_item group1" href="http://player.vimeo.com/video/'.$videourl[0].'" title="'.$video_name.'"></a>
                                    <img src="'.$imgsrc.'" alt="'.$video_name.'" />';
            }
                break;
            }
            $icon=youtube_or_vimeo($row->image_url);
            if($video_thumb != ''){
                 $thumb = '<div class="playbutton '.$icon.'-icon"></div>';
           
            }else{
                 $thumb ="";
            }

            if(isset($_POST['thumbtext']) && !empty($_POST['thumbtext']) && strip_tags($_POST['thumbtext'])){
                $thumbtext = $_POST['thumbtext'];
            }

            $output .='
                <li class="huge_it_big_li">
                    <input type="hidden" class="pagenum" value="'.$page.'" />
                        '.$video.'

                    <div class="overLayer"></div>
                    <div class="infoLayer">
                        <ul>
                            <li>
                                <h2>
                                    '.$video_name.'
                                </h2>
                            </li>
                            <li>
                                <p>
                                    '.$thumbtext.'
                                </p>
                            </li>
                        </ul>
                    </div>
                    
                </li>
            ';
        
        }
        echo json_encode(array("success"=>$output));
        die();
    }
} elseif(isset($_POST['task']) && $_POST['task']=="load_videos_justified"){
        
    $page = 1;
    if(!empty($_POST["page"]) && is_numeric($_POST['page']) && $_POST['page']>0){
        $page = intval($_POST["page"]);
        if(isset($_POST['perpage']) && !empty($_POST['perpage'])) {
            $num = intval($_POST['perpage']);
        }
        $start = $page * $num - $num;
        $start = intval($start);
        if(isset($_POST['galleryid']) && !empty($_POST['galleryid'])) {
            $idofgallery = intval($_POST['galleryid']);
        }
        $query = $db->getQuery(true);
        $query->select('*');
        $query->from('#__huge_it_videogallery_videos');
        $query->where('videogallery_id ='.$idofgallery);
        $query ->order('#__huge_it_videogallery_videos.ordering asc');
        $db->setQuery($query,$start,$num);
        $page_images = $db->loadObjectList();
        $output = '';
        foreach($page_images as $key=>$row){
            $video_name=str_replace('__5_5_5__','%',$row->name);
            $video_thumb=$row->thumb_url;
         $videourl=get_video_gallery_id_from_url($row->image_url);
         $imagerowstype=$row->sl_type; 
                    if($row->sl_type == ''){$imagerowstype='image';}
                    
                    switch($imagerowstype){
                        case 'image': 
                        
                      $video='<a class="group1" href="'.$videourl.'" title="'.$video_name.'">
                                    <img id="wd-cl-img'.$key.'" alt="'.$video_name.'" src="<?php echo get_huge_image('.$videourl.','.$image_prefix.'); ?>"/>
                                </a>
                                <?php } else { ?>
                                <img alt="'.$video_name.'" id="wd-cl-img'.$key.'" src="images/noimage.jpg"  />'  
                    ?>                                  
                         
                    <?php 
                        break;
                        case 'video':

            if($videourl[1]=='youtube'){
                if(empty($row->thumb_url)){
                                            $thumb_pic='http://img.youtube.com/vi/'.$videourl[0].'/mqdefault.jpg';
                                        }else{
                                            $thumb_pic=$row->thumb_url;
                                        }
                $video = '<a class="youtube huge_it_videogallery_item group1"  href="https://www.youtube.com/embed/'.$videourl[0].'" title="'.$video_name.'">
                                                <img  src="'.$thumb_pic.'" alt="'.$video_name.'" />
                                                <div class="play-icon '.$videourl[1].'-icon"></div>
                                        </a>';
            }else {

                $hash = unserialize(get("http://vimeo.com/api/v2/video/".$videourl[0].".php"));
                                    
                                    if(empty($row->thumb_url)){
                                        $imgsrc=$hash[0]['thumbnail_large'];
                                    }else{
                                        $imgsrc=$row->thumb_url;
                                    }
                $video = '<a class="vimeo huge_it_videogallery_item group1" href="http://player.vimeo.com/video/'.$videourl[0].'" title="'.$video_name.'">
                                                <img alt="'.$video_name.'" src="'.$imgsrc.'"/>
                                                <div class="play-icon '.$videourl[1].'-icon"></div>
                                        </a>';
            }
                break;
            }
            ?>

            <?php
            $icon=youtube_or_vimeo($row->image_url);
            if($video_thumb != ''){
                 $thumb = '<div class="playbutton '.$icon.'-icon"></div>';
           
            }else{
                 $thumb ="";
            }



            $output .=$video.'<input type="hidden" class="pagenum" value="'.$page.'" />';
                
            
        
        }
        ?>

        <?php
        echo json_encode(array("success"=>$output));
        die();
    }
}elseif(isset($_POST['task']) && $_POST['task']=="load_videos_justified"){
    $page = 1;
    if(!empty($_POST["page"]) && is_numeric($_POST['page']) && $_POST['page']>0){
        $page = intval($_POST["page"]);
        if(isset($_POST['perpage']) && !empty($_POST['perpage'])) {
            $num = intval($_POST['perpage']);
        }
        $start = $page * $num - $num;
        $start = intval($start);
        if(isset($_POST['galleryid']) && !empty($_POST['galleryid'])) {
            $idofgallery = intval($_POST['galleryid']);
        }
        $query = $db->getQuery(true);
        $query->select('*');
        $query->from('#__huge_it_videogallery_videos');
        $query->where('videogallery_id ='.$idofgallery);
        $query ->order('#__huge_it_videogallery_videos.ordering asc');
        $db->setQuery($query,$start,$num);
        $page_images = $db->loadObjectList();
        $output = '';
        foreach($page_images as $key=>$row){
            $video_name=str_replace('__5_5_5__','%',$row->name);
            $video_thumb=$row->thumb_url;
            $videourl=get_video_gallery_id_from_url($row->image_url);
            $imagerowstype=$row->sl_type;
                    if($row->sl_type == ''){$imagerowstype='image';}
                    
                    switch($imagerowstype){
                        case 'image': 
                        
                      $video='<a class="group1" href="'.$videourl.'" title="'.$video_name.'">
                                    <img id="wd-cl-img'.$key.'" alt="'.$video_name.'" src="<?php echo get_huge_image('.$videourl.','.$image_prefix.'); ?>"/>
                                </a>
                                <?php } else { ?>
                                <img alt="'.$video_name.'" id="wd-cl-img'.$key.'" src="images/noimage.jpg"  />'  
                    ?>                                  
                         
                    <?php 
                        break;
                        case 'video':

            if($videourl[1]=='youtube'){
                if(empty($row->thumb_url)){
                                            $thumb_pic='http://img.youtube.com/vi/'.$videourl[0].'/mqdefault.jpg';
                                        }else{
                                            $thumb_pic=$row->thumb_url;
                                        }
                $video = '<a class="youtube huge_it_videogallery_item group1"  href="https://www.youtube.com/embed/'.$videourl[0].'" title="'.$video_name.'">
                                                <img  src="'.$thumb_pic.'" alt="'.$video_name.'" />
                                                <div class="play-icon '.$videourl[1].'-icon"></div>
                                        </a>';
            }else {

                $hash = unserialize(get("http://vimeo.com/api/v2/video/".$videourl[0].".php"));
                                    
                                    if(empty($row->thumb_url)){
                                        $imgsrc=$hash[0]['thumbnail_large'];
                                    }else{
                                        $imgsrc=$row->thumb_url;
                                    }
                $video = '<a class="vimeo huge_it_videogallery_item group1" href="http://player.vimeo.com/video/'.$videourl[0].'" title="'.$video_name.'">
                                                <img alt="'.$video_name.'" src="'.$imgsrc.'"/>
                                                <div class="play-icon '.$videourl[1].'-icon"></div>
                                        </a>';
            }
                break;
            }
            ?>

            <?php
            $icon=youtube_or_vimeo($row->image_url);
            if($video_thumb != ''){
                 $thumb = '<div class="playbutton '.$icon.'-icon"></div>';
           
            }else{
                 $thumb ="";
            }
            $output .=$video.'<input type="hidden" class="pagenum" value="'.$page.'" />';
        }
        ?>

        <?php
        echo json_encode(array("success"=>$output));
        die();
    }
}elseif(isset($_POST['task']) && $_POST['task']=="load_videos"){
        global $wpdb;
    $page = 1;
    if(!empty($_POST["page"]) && is_numeric($_POST['page']) && $_POST['page']>0){
        $page = intval($_POST["page"]);
        if(isset($_POST['perpage']) && !empty($_POST['perpage'])) {
            $num = intval($_POST['perpage']);
        }
        $start = $page * $num - $num;
        $start = intval($start);
        if(isset($_POST['galleryid']) && !empty($_POST['galleryid'])) {
            $idofgallery = intval($_POST['galleryid']);
        }
         $query = $db->getQuery(true);
        $query->select('*');
        $query->from('#__huge_it_videogallery_videos');
        $query->where('videogallery_id ='.$idofgallery);
        $query ->order('#__huge_it_videogallery_videos.ordering asc');
        $db->setQuery($query,$start,$num);
        $page_images = $db->loadObjectList();
        $output = '';
        foreach($page_images as $key=>$row){
            $video_name=str_replace('__5_5_5__','%',$row->name);
             $video_desc=str_replace('__5_5_5__','%',$row->description);
            $video_thumb=$row->thumb_url;
            if($video_thumb==''){
                $thumbimglink='';
            }else{
                $thumbimglink='<img class="thumb_image" style="cursor: pointer;" src="'.$video_thumb.'" alt="" />';
            }
         $videourl=get_video_gallery_id_from_url($row->image_url);
            if(isset($_POST['width']) && !empty($_POST['width'])) {
                $post_width = intval($_POST['width']);
            }
            if(isset($_POST['height']) && !empty($_POST['height'])) {
                $post_height = intval($_POST['height']);
            }
            if($videourl[1]=='youtube'){
                $iframe = '<iframe class="video_view9_img" width="'.$post_width.'" height="'.$post_height.'" src="//www.youtube.com/embed/'.$videourl[0].'" style="border: 0;" allowfullscreen></iframe>';
            }else {
                $iframe = '<iframe class="video_view9_img" width="'.$post_width.'" height="'.$post_height.'" src="//player.vimeo.com/video/'.$videourl[0].'"  style="border: 0;" allowfullscreen></iframe>';
            }
            $icon=youtube_or_vimeo($row->image_url);
            if($video_thumb != ''){
                 $thumb = '<div class="playbutton '.$icon.'-icon"></div>';
           
            }else{
                 $thumb ="";
            }
            if($_POST['position']==1){


            $output .='
                <div class="video_view9_container">
                    <input type="hidden" class="pagenum" value="'.$page.'" />
                    <div class="video_view9_vid_wrapper">

                        <div class="thumb_wrapper" onclick="thevid=document.getElementById("thevideo"); thevid.style.display="block"; this.style.display="none">
                            
                            '.$thumbimglink.$thumb.'
                        </div>
                        <div id="thevideo" style="display: block;">
                            '.$iframe.'
                        </div>
                    </div>
                    <h1 class="video_new_view_title">'.$video_name.'</h1>
                    <div class="video_new_view_desc">'.$video_desc.'</div>
                </div>
                <div class="clear"></div>
            ';
        }elseif($_POST['position']==2){


            $output .='
                <div class="video_view9_container">
                    <input type="hidden" class="pagenum" value="'.$page.'" />
                    <h1 class="video_new_view_title">'.$video_name.'</h1>
                    <div class="video_view9_vid_wrapper">

                        <div class="thumb_wrapper" onclick="thevid=document.getElementById("thevideo"); thevid.style.display="block"; this.style.display="none">
                            
                            '.$thumbimglink.$thumb.'
                        </div>
                        <div id="thevideo" style="display: block;">
                            '.$iframe.'
                        </div>
                    </div>
                    
                    <div class="video_new_view_desc">'.$video_desc.'</div>
                </div>
                <div class="clear"></div>
            ';
            }elseif($_POST['position']==3){


            $output .='
                <div class="video_view9_container">
                    <input type="hidden" class="pagenum" value="'.$page.'" />
                    <h1 class="video_new_view_title">'.$video_name.'</h1>
                    <div class="video_new_view_desc">'.$video_desc.'</div>
                    <div class="video_view9_vid_wrapper">

                        <div class="thumb_wrapper" onclick="thevid=document.getElementById("thevideo"); thevid.style.display="block"; this.style.display="none">
                            
                            '.$thumbimglink.$thumb.'
                        </div>
                        <div id="thevideo" style="display: block;">
                            '.$iframe.'
                        </div>
                    </div>
                    
                    
                </div>
                <div class="clear"></div>
            ';
            }
        }
        echo json_encode(array("success"=>$output));
        die();
    }
}


              
           
           
            /*FUNCTIONS*/
            
function get_video_gallery_id_from_url($url){
    if(strpos($url,'youtube') !== false || strpos($url,'youtu') !== false){ 
        if (preg_match('%(?:youtube(?:-nocookie)?\.com/(?:[^/]+/.+/|(?:v|e(?:mbed)?)/|.*[?&]v=)|youtu\.be/)([^"&?/ ]{11})%i', $url, $match)) {
            return array ($match[1],'youtube');
        }
    }else {
        $vimeoid =  explode( "/", $url );
        $vimeoid =  end($vimeoid);
        return array($vimeoid,'vimeo');
    }
}

function get($url) {
    if (ini_get('allow_url_fopen')) return file_get_contents($url);
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $result = curl_exec($ch);
    curl_close($ch);
    return $result;
    }
    

        function youtube_or_vimeo($videourl){
    if(strpos($videourl,'youtube') !== false || strpos($videourl,'youtu') !== false){   
        if (preg_match('%(?:youtube(?:-nocookie)?\.com/(?:[^/]+/.+/|(?:v|e(?:mbed)?)/|.*[?&]v=)|youtu\.be/)([^"&?/ ]{11})%i', $videourl, $match)) {
            return 'youtube';
        }
    }
    elseif(strpos($videourl,'vimeo') !== false && strpos($videourl,'video') !== false) {
        $explode = explode("/",$videourl);
        $end = end($explode);
        if(strlen($end) == 8)
            return 'vimeo';
    }
    return 'image';
}
function appendload($row,$key,$paramssld){
    $changePopup = $row->id ;
    if(isset($_POST['galleryid']) && !empty($_POST['galleryid'])) {
        $idofgallery = intval($_POST['galleryid']);
    }
    $link = $row->sl_url;
    ob_start();?>
    <li class="pupup-element" id="huge_it_videogallery_pupup_element_<?php echo $row->id; ?>">
        <div class="heading-navigation">
            <div style="display: inline-block; float: left;">
                <div class="left-change" ><a href="#<?php echo $changePopup - 1; ?>" data-popupid="#<?php echo $row->id; ?>"><</a></div>
                <div class="right-change" ><a href="#<?php echo $changePopup + 1; ?>" data-popupid="#<?php echo $row->id; ?>">></a></div>
            </div>
            <?php $changePopup=$changePopup+1; ?>
            <a href="#close" class="close"></a>
            <div style="clear:both;"></div>
        </div>
        <div class="popup-wrapper_<?php echo $idofgallery; ?>">
            <div class="image-block_<?php echo $idofgallery; ?>">
                <?php
                $imagerowstype=$row->sl_type;
                if($row->sl_type == ''){$imagerowstype='image';}
                switch($imagerowstype){
                    case 'image':
                        ?>
                        <?php 	if($row->image_url != ';'){ ?>
                        <img id="wd-cl-img<?php echo $key; ?>" src="<?php echo $imgurl[0]; ?>" alt="" />
                    <?php } else { ?>
                        <img id="wd-cl-img<?php echo $key; ?>" src="images/noimage.jpg" alt="" />
                        <?php
                    } ?>

                        <?php
                        break;
                    case 'video':
                        ?>
                        <?php
                        $videourl=get_video_gallery_id_from_url($row->image_url);
                        if($videourl[1]=='youtube'){?>
                            <div class="hg_iframe_class"><div class="hg_iframe_class_sub"></div><iframe class="hg_iframe_class" src="//www.youtube.com/embed/<?php echo $videourl[0]; ?>" style="border: 0;" allowfullscreen></iframe></div>
                            <?php
                        }else {
                            ?>
                            <div class="hg_iframe_class"><div class="hg_iframe_class_sub"></div><iframe  src="//player.vimeo.com/video/<?php echo $videourl[0]; ?>?title=0&amp;byline=0&amp;portrait=0"  style="border: 0;" allowfullscreen></iframe></div>
                            <?php
                        }
                        ?>
                        <?php
                        break;
                }
                ?>
                <?php if(str_replace('__5_5_5__','%',$row->sl_url)==''){
                    $viwMoreButton='';
                }else{
                    if ($row->link_target=="on"){
                        $target='target="_blank"';
                    }else{
                        $target='';
                    }
                    $viwMoreButton='<div class="button-block"><a href="'.str_replace('__5_5_5__','%',$row->sl_url).'" '.$target.' >View More</a></div>';
                }




                ?>
            </div>
            <div class="right-block">
                <h3 class="title"><?php echo str_replace('__5_5_5__','%',$row->name); ?></h3>
                <div class="description"><?php echo str_replace('__5_5_5__','%',$row->description); ?></div>
                    <?php echo $viwMoreButton; ?>

                <div style="clear:both;"></div>
            </div>
            <div style="clear:both;"></div>
        </div>
    </li>

    <?php
    return ob_get_clean();

}