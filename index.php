<?php
/*
PLUGIN NAME:WP Anti-copy
Plugin URI: http://www.teknikforce.com
Description: This Plugin is use to monitor copied content theft.
AUTHOR:TEKNIKFORCE
VERSION:1.0
*/

if(!defined('ABSPATH')){exit;}
$cmntmkrpref='wpanticopy_';

if(!function_exists('wpcopycntnt_add_div'))
{
function wpcopycntnt_add_div($content)
{
  $id=get_the_id();
  $posts=explode(',',get_option('wpanticpy_prevent_indexes'));
  
  $is_existspost=((is_numeric(array_search($id,$posts))? true:false)|| (is_numeric(array_search('all',$posts))? true:false));

  $inlinepreventer=($is_existspost)? "wpcopyContentPrevention(event)":"wpanticopySendCopiedData(this)";
  return "<div contentid='".esc_attr($id)."' oncopy='".$inlinepreventer."' id='Copied_iD'>".$content."</div>";
}
}

add_filter('the_content','wpcopycntnt_add_div');


require_once("copy-sequence/plugin.php");

    $gdprwpvar=new \Wpanticopy\license\Wpanticopypluginlisence(array('wpanticopy','wpanticopy_'));
    if($gdprwpvar->validate()==1)
    {
    add_action('admin_menu','wpCopyContentMenuandSubmenupages'); 
    }
    else
    {
    new \Wpanticopy\license\anticopyactivationpage(array('wpanticopy','wpanticopy_'));
    }

    add_action('wp_ajax_wpanticopy_adminajxlcnc',function(){
      if(isset($_POST['reverifyjkmvhblicense']) && isset($_POST['rvryfyplugin']) && isset($_POST['rvryfypluginpref']) && isset($_POST["wpanticopy_csrf"]) && wp_verify_nonce($_POST["wpanticopy_csrf"],'wpanticopy'))
      {
        $ob=new \Wpanticopy\license\Wpanticopypluginlisence(array(sanitize_text_field($_POST['rvryfyplugin']),sanitize_text_field($_POST['rvryfypluginpref'])));
        $ob->reValidate("server");
      }
      wp_die();
    });

if(!function_exists('wpCopyContentMenuandSubmenupages'))
{
function wpCopyContentMenuandSubmenupages() {
	require_once('copyContent.php');
	require_once('viewCopiedContent.php');
  add_menu_page('WP Anti-Copy', 
    'WP Anti-Copy', 
    'administrator', 
    'wpanticopy_records', 
    'wpAntiCopyContentCopied' 
    );
  add_submenu_page('copied',
    'wpanticopy_records', 
    'Copied Contents', 
    'administrator', 
    'wpanticopy_viewCopiedContent', 
    'wpantiCopyviewCopiedContent' 
    );
}
}
//--------------Scripts-------------  
add_action( 'wp_enqueue_scripts',function() 
{
  wp_register_script('myFunction', plugins_url('copyscript.js', __FILE__), array('jquery'),'1.1', true);   
  wp_enqueue_script('myFunction');
});

add_action('wp_footer',function()
{
  $ajax_callback=admin_url('admin-ajax.php');
  echo "<input type='hidden' id='cpycntntajxurl' value='".esc_url($ajax_callback)."'><input type='hidden' id='wpanticopycsrf' value='".wp_create_nonce('wpanticopy')."'>";
});

add_action('admin_enqueue_scripts',function(){
  $pages=array('wpanticopy_records','wpanticopy_viewCopiedContent');
  if(isset($_GET['page']) && in_array($_GET['page'],$pages))
  {
    wp_enqueue_script('jquery');

    wp_register_script('wpanticopy_bootstrap_script',plugins_url('assets/bootstrap/js/bootstrap.min.js',__FILE__),array('jquery'));
    wp_enqueue_script('wpanticopy_bootstrap_script');

    wp_register_style('wpanticopy_bootstrap_style',plugins_url('assets/bootstrap/css/bootstrap.min.css',__FILE__));
    wp_enqueue_style('wpanticopy_bootstrap_style');
  }

});

register_activation_hook(__FILE__,  'wpanticopy_activate');
register_deactivation_hook( __FILE__, 'anticopy_remove_database' );

if(!function_exists('wpanticopy_activate'))
{
function wpanticopy_activate() {
  global $wpdb;
  $table = $wpdb->prefix . 'anticopy';
  $charset = $wpdb->get_charset_collate();
    $charset_collate = $wpdb->get_charset_collate();
    
    $sql = "CREATE TABLE IF NOT EXISTS $table (
        id int(15) NOT NULL AUTO_INCREMENT,
        post_id bigint(20) NOT NULL,
        copied_title varchar(225)  NOT NULL,
        copied_content longtext NOT NULL,
        no_of_copied int(15) NOT NULL,
        copied_date timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
        copied_ip varchar(225) NOT NULL,
        PRIMARY KEY  (id)
        ) $charset_collate;";

    $wpdb->query($sql);
    
	add_option('wpanticpy_prevent_indexes','');
}
} 
add_action('wp_footer','wpAntiCopy_cpspecificpageprevent');


if(!function_exists('wpAntiCopy_cpspecificpageprevent'))
{
function wpAntiCopy_cpspecificpageprevent()
{
	$posts=explode(',',get_option('wpanticpy_prevent_indexes'));
	$is_existsall=array_search("all",$posts);
	$content="<script>document.body.oncopy=function(e){e.preventDefault();};</script>";
	if($is_existsall !==false)
	{
		echo $content;
	}
	if((is_home() || is_front_page())&&(array_search('home',$posts) !==false))
	{
			echo $content;
	}
	elseif((array_search(get_the_id(),$posts)!==false)&& !(is_home() || is_front_page()))
	{
			echo $content;
	}
}
}
//copy handle ajax
add_action('wp_ajax_wpanticopy_copied','wpanticopy_copied_action');
add_action('wp_ajax_nopriv_wpanticopy_copied','wpanticopy_copied_action');
if(!function_exists('wpanticopy_copied_action'))
{
function wpanticopy_copied_action(){
  require_once("insert.php");
  wp_die();
}
}
//bottom logos
add_action('admin_footer',function(){
  $pages=array('wpanticopy_records','wpanticopy_viewCopiedContent');

  if(isset($_GET['page']) && in_array($_GET['page'],$pages))
  {
  echo '<span class="pull-right" style="bottom:0px;right:0px;margin-bottom:35px;margin-right:10px;position:absolute"><a href="https://teknikforce.com" target="_BLANK"><img src="'.esc_url(plugins_url('assets/img/tekniklogo.png',__FILE__)).'" style="cursor:pointer"></a></span>';
  }
});
?>
