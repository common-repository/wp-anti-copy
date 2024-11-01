<?php
if(!function_exists('wpantiCopyGetUserIpAddr'))
{
function wpantiCopyGetUserIpAddr(){
    if(!empty($_SERVER['HTTP_CLIENT_IP'])){

        $ip = $_SERVER['HTTP_CLIENT_IP'];
    }elseif(!empty($_SERVER['HTTP_X_FORWARDED_FOR'])){

        $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
    }else{
        $ip = $_SERVER['REMOTE_ADDR'];
    }
    return $ip;
}
}
if(isset($_POST['text_id']) && isset($_POST['text_copy']) && isset($_POST['wpcopycontentcsef']) && wp_verify_nonce($_POST['wpcopycontentcsef'],'wpanticopy'))
{
global $wpdb;
$text_id =(int) sanitize_text_field($_POST['text_id']);
$text_copy = trim(sanitize_text_field($_POST['text_copy']));
$now = date("Y-m-d H:i:s");
$copied_ip = wpantiCopyGetUserIpAddr();

$table_name = $wpdb->prefix . 'anticopy';

$post_id = get_post( $text_id ); 
$post_title= $post_id->post_title;

$content_check = $wpdb->get_var(
$wpdb->prepare("SELECT `id` FROM $table_name WHERE `copied_content`=%s and `post_id`=%d and `copied_ip`=%s",array($text_copy,$text_id,$copied_ip))
);

if ($content_check && is_numeric($content_check) && $content_check>0)
{
    
       $wpdb->query($wpdb->prepare("update `".$table_name."` set `no_of_copied`=`no_of_copied`+1 where `id`=%d",array($content_check)));  
}
else 
{
     $wpdb->insert($table_name, array('post_id' => $text_id, 'copied_title' => $post_title, 'copied_content' => $text_copy, 'no_of_copied' => 1,'copied_date'=>$now,'copied_ip'=>$copied_ip),array('%d','%s','%s','%d','%s','%s'));
}
}

?>