<?php
/**
 * @package Gallery
 * @author Huge-IT
 * @copyright (C) 2014 Huge IT. All rights reserved.
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * @website		http://www.huge-it.com/
 * */

defined('_JEXEC') or die('Restircted access');


function shortcode($id_cat){
require_once JPATH_SITE.'/components/com_gallery/helpers/helper.php';


//   
$cis_class = new GalleriesHelper;
$cis_class->gallery_id = $id_cat;


$cis_class->type = 'component';
$cis_class->class_suffix = '';

echo  $cis_class->render_html(); 


}