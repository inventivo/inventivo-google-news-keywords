<?php
/*
Plugin Name: Inventivo Google News Keywords
Plugin URI: http://www.inventivo.de
Description: Generates and adds Google News Keywords based on your posts tags.
Author: Nils Harder
Version: 1.0.0
Author URI: http://www.inventivo.de
License: Lizens GPLv2
*/
function inventivo_seo_keywords(){
if(is_single()){
	echo "<meta name='news_keywords' content='"; $posttags = get_the_tags(); if ($posttags) { foreach($posttags as $tag) { echo $tag->name . ", "; } }  echo "' />\n";
	}
}
add_action('wp_head','inventivo_seo_keywords'); 
add_action('admin_menu', 'inventivoGNK_menu');
function inventivoGNK_menu(){
	add_options_page('inventivoGNK Options', 'inventivo Google News Keywords Meta Tag', 'manage_options', 'inventivoGNK', 'inventivoGNK_options');
}


function inventivoGNK_options(){
$option_name = 'inventivoGNK';
	if (!current_user_can('manage_options')) {
		wp_die( __('You do not have sufficient permissions to access this page.') );
	}
	if(isset($_POST['inventivoGNK_active_supportlink'])){
		$option = array();
		if ($_POST['inventivoGNK_active_supportlink']=='on') { $option['supportlink'] = true; }
		update_option($option_name, json_encode($option));
		$outputa .= '<div class="updated"><p><strong>'.__('Einstellungen gespeichert.', 'menu' ).'</strong></p></div>';
	}
	$option = array();
	$option_string = get_option($option_name);
	if ($option_string===false) {
		$option = array();
		$option['supportlink'] = array('supportlink'=>true);
		$option_string = get_option($option_name);
	}
	$option = json_decode($option_string, true);
	$active_supportlink	= ($option['supportlink']==true) ? 'checked="checked"' : '';
	$outputa .= '
	<div style="width:400px;float:left;">
		<h2>'.__( 'inventivo Google News Keywords Meta Tag', 'menu' ).'</h2>
	<h3>Keine weiteren Einstellungen n&ouml;tig</h3>
	<p>Das Plugin pr&uuml;ft ob sich Tags in einem Beitrag befinden und erzeugt daraus automatisch das <strong>Google News Keyword MetaTag</strong>.</p>
	</div>';
	$outputa .= '<div style="margin-left:50px;border-left:thin solid #ccc;border-right:thin solid #ccc;border-bottom:thin solid #ccc;padding:3px;width:200px;float:left;box-shadow: 0 1px 1px #999;">
<div>
<h2>inventivo.de Feed</h2>';
$rss = fetch_feed( "http://inventivo.de/feed/" );
if(!is_wp_error($rss)){
$maxitems = $rss->get_item_quantity( 5 ); 
$rss_items = $rss->get_items( 0, $maxitems );
}
$outputa .= '<ul>';
if($maxitems == 0){
$outputa .= '<li>Keine Eintr&auml;ge</li>';
} else {
foreach ($rss_items as $item){
$outputa .= '<li>
    <a href="'.esc_url($item->get_permalink()).'" title="'.esc_html( $item->get_title() ).'" target="_blank">';
$outputa .= esc_html( $item->get_title() );
$outputa .= '</a></li>';
}
}
	echo $outputa;
}