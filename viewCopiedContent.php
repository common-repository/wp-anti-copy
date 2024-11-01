<?php
if(!function_exists('wpantiCopyviewCopiedContent'))
{
function wpantiCopyviewCopiedContent()
{
global $wpdb;
$table_name = $wpdb->prefix . 'anticopy';

$no_of_copied= sanitize_key($_GET['NoCopy']); 
$postid = sanitize_key($_GET['postid']);
$post_data = get_post( $postid); 
$copied_title =$post_data->post_title;
$post_url= $post_data->guid;
?>

<script>
function displayAllContentFromCopiedData(id)
{
	const doc=document.querySelectorAll("span#spanrplccpycntnt"+id)[0];
	const content=document.querySelectorAll("textarea#wpanticopyremainingcontent"+id)[0].value;
	doc.innerHTML=" "+content;
}
</script>
<div class="container-fluid">
<div class="col-sm-12">
<br>
<div class="text-right">
<a style="text-decoration: none;" onclick="window.history.back();"><button class="btn btn-primary">Go Back</button></a>
</div>
<br>
<div class="table-responsive">
<table class="table table-striped table-dark">

	<tr>
		<td><h4>Title of Copied Content:</h4></td>
		<td><a style="text-decoration: none; color: white;" href="<?php echo esc_url($post_url); ?>"><h4><?php echo esc_html((strlen($copied_title)>0)? $copied_title:"No Title");?></h4></a></td>
	</tr>
	<tr>
		<td>The number of times user tried to copy:</td>
		<td><?php echo esc_html($no_of_copied);?></td>
	</tr>

	

</table>
</div>
<?php

$rows = $wpdb->get_results($wpdb->prepare("SELECT copied_content,copied_date,copied_ip FROM $table_name WHERE post_id=%d ORDER BY copied_date DESC",array($postid)));

 $totalcount=0;
 foreach ($rows as $row ){
	 ++$totalcount;
	 $copied_content_todisplay=esc_html($row->copied_content);
	 $copied_content_arr=explode(" ",$copied_content_todisplay);

	 $tempcopied_content=$copied_content_todisplay;
	 if(count($copied_content_arr)>15)
	 {
		$tempcopied_content=implode(" ",array_slice($copied_content_arr,0,15));
		$temp_hidden_content=implode(" ",array_splice($copied_content_arr,0,15));

		$tempcopied_content .="<span style='cursor:pointer;' id='spanrplccpycntnt".esc_html($totalcount)."'>...<strong onclick='displayAllContentFromCopiedData(".esc_html($totalcount).")'>Read More</strong><textarea id='wpanticopyremainingcontent".esc_html($totalcount)."' style='display:none;'>".esc_textarea($temp_hidden_content)."</textarea></span>";
	 }
?>
<hr class="table table-dark"/>
<div class="table-responsive">
<table class="table table-striped">
	<tr>
		<td width="100px">Copied Content:</td>
		<td><?php echo $tempcopied_content;?></td>
	</tr>
	<tr>
		<td width="100px">IP Address:</td>
		<td><?php echo esc_html($row->copied_ip);?></td> 
	</tr>
	<tr>
		<td width="100px">Copied Date:</td>
		<td><?php echo esc_html($row->copied_date);?></td>
	</tr>
</table>
</div>
	
<?php

}

?>

</div>
</div>
<?php

}
}
?>
