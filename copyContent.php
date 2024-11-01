<?php
if(!function_exists('wpAntiCopyContentCopied'))
{
function wpAntiCopyContentCopied()
{
if(isset($_POST['selectionforcopyprevention']))
{
	if(is_array($_POST['wpcopycontent']))
	{
		$selected_items_for_prevention=array_map(function($i){
			if($i=="all"|| $i=="home")
			{
				return $i;
			}
			else
			{
				return (int)$i;
			}
		},$_POST['wpcopycontent']);

		$postneedscpyprevention=implode(',',$selected_items_for_prevention);
		update_option('wpanticpy_prevent_indexes',$postneedscpyprevention);
	}
	else
	{
		update_option('wpanticpy_prevent_indexes','');
	}
}

$prevented_posts=explode(',',get_option('wpanticpy_prevent_indexes'));	
	
global $wpdb;
$table_name = $wpdb->prefix . 'anticopy';

if(isset($_GET['delete']) && isset($_GET['id'])) {

$id = sanitize_key($_GET['id']);

if (!preg_match("/^[0-9]*$/",$id))
$msg = "error:Only numbers allowed in the ID";
else {
$wpdb->delete( $table_name, array( 'post_id' => $id ) );
$msg = "Updated: Record deleted!";
}
}


$page_no =(isset($_GET['page_no']))? sanitize_key($_GET['page_no']):0;

if($page_no>0)
{
	$page1=($page_no*10)-10;
}
else
{
	$page1=0;
	$page_no=1;
}


$rows = $wpdb->get_results("SELECT `id`,`post_id`,`copied_title`,`copied_date`,sum(`no_of_copied`) as `copy_total` FROM $table_name GROUP BY `post_id` ORDER BY `id` DESC LIMIT $page1,10");
	
 ?>

<script>
jQuery(document).ready(function($){
	$(".wpcpycntntallposts").hide();
	var keepopened=0;
	$(".selectpagepost").click(function(){
		if(!keepopened)
		{
			$(".wpcpycntntallposts").show(200);
			keepopened=1;
		}
		else
		{
			$(".wpcpycntntallposts").hide(200);
			keepopened=0;
		}
	});
});
</script>
 

<br>
<div class="container-fluid">
<div class="row">
<div class="col-sm-12">
<div class="col-sm-3" style="float:left;margin-top:5px;"><h4>Copied Contents</h4></div>
<div class="col-sm-3" style="float:right;">
<div class="form-group" style="position:relative;">
<button class="form-control selectpagepost">Prevent Content From Being Copied</button>
<div class="panel panel-primary wpcpycntntallposts">
<form action="" method="post">
<div class="panel-body">
<p><strong>Select Posts and Pages</strong></p>
<label><input type="checkbox" name='wpcopycontent[]' value='all' <?php if(array_search('all',$prevented_posts)!==false){echo "checked";} ?>> Prevent All</label>
<label><input type="checkbox" name='wpcopycontent[]' value='home' <?php if(array_search('home',$prevented_posts)!==false){echo "checked";} ?>> Home Page</label>
<?php
$all_posts=get_posts(array('numberposts'=>-1));
$all_pages=get_pages();
foreach($all_posts as $post)
{
$isprevented=(array_search($post->ID,$prevented_posts) !==false)? 'checked':'';
	
echo "<label><input type='checkbox' name='wpcopycontent[]' value='".esc_attr($post->ID)."' ".$isprevented.">&nbsp; ".esc_html($post->post_title)." (Post)</label>";
}
foreach($all_pages as $page)
{
$isprevented=(array_search($page->ID,$prevented_posts)!==false)? 'checked':'';	
echo "<label><input type='checkbox' name='wpcopycontent[]' value='".esc_attr($page->ID)."' ".$isprevented.">&nbsp; ".esc_html($page->post_title)." (Page)</label>";
}
?>
</div>
<div class="panel-footer"><button class="btn btn-primary form-control selectionforcopyprevention" name="selectionforcopyprevention">Save</button></div>
</form>
</div>
</div>
</div>
</div>
</div>
<div class="row">
<div class="col-sm-12">
<div class="table-responsive">
<table class="table table-striped">
	<thead class="thead-dark">
		<tr>
			<th>#</th>
			<th>Content Title</th>
			<th>Date</th>
			<th>Number of times copied</th>
			<th>Action</th>
		</tr>
	</thead>
	<tbody>

<?php
	$i=1;
foreach ($rows as $row ){

$post_id =  $row->post_id; 
$temp_post=get_post($post_id);
?>

		<tr>
			<td><?php echo $i; ?></td>
			<td><a style="text-decoration: none" href="<?php echo esc_url($temp_post->guid); ?>" target="_BLANK"> <?php echo esc_html($temp_post->post_title); ?></a></td>
			<td><?php echo esc_html($row->copied_date); ?></td>
			<td><?php echo esc_html($row->copy_total); ?></td>
			<td>
				<a href="<?php echo esc_url(admin_url("admin.php?page=wpanticopy_viewCopiedContent&NoCopy=".$row->copy_total."&postid=".$row->post_id)); ?>">View</a> |
				<a href="<?php echo esc_url(admin_url("admin.php?page=wpanticopy_records&delete=1&id=".$row->post_id)); ?>"
				onclick="return confirm('Are you sure?')">Delete</a>
			</td>
		</tr>

<?php
$i=$i+1;
}

$num_rows=$wpdb->get_var("SELECT count(distinct(`post_id`)) AS `count_id`  FROM `".$table_name."`");
?>
<tr><td colspan=10> <center><strong>Number of records <?php echo esc_html($num_rows); ?></strong> </center> </td></tr>
	</tbody>
</table>
</div>
<br><br><br>
<center>
<ul style="display: inline;">
<?php
$page=$num_rows/10;
$page= ceil($page);

if($page_no>1)
{
	$pageno=$page_no-1;
	?>
	<li style="display: inline;">
		<a href="<?php echo esc_url(admin_url("admin.php?page=wpanticopy_records&page_no=".$pageno)); ?>" style="text-decoration: none">
			<label style="background-color: grey;color: white;height: 25px;width: 100px">Previous</label>
		</a>
	</li>
	<?php
}

for($i=1;$i<=$page;$i++){

	?>
	<li style="display: inline;">
		<a href="<?php echo esc_url(admin_url("admin.php?page=wpanticopy_records&page_no=".$i)); ?>" style="text-decoration: none">
			<label style="background-color: grey;color: white;height: 25px;width: 25px"><?php echo $i; ?></label>
		</a>
	</li>
	
	<?php
}

if($page_no<$page)
{
	$pageno=$page_no +1;
	?>
	<li style="display: inline;">
		<a href="<?php echo esc_url(admin_url("admin.php?page=wpanticopy_records&page_no=".$pageno)); ?>" style="text-decoration: none">
			<label style="background-color: grey;color: white;height: 25px;width: 100px">Next</label>
		</a>
	</li>
	<?php
}

?>

</ul>
</center>

</div></div></div>
<style>
.wpcpycntntallposts
{
	position:absolutel
	background-color:white !important;
	position:absolute;
	width:100%;
	z-index:9999;
	-webkit-box-shadow: 2px 4px 9px 0px rgba(0,0,0,0.75);
-moz-box-shadow: 2px 4px 9px 0px rgba(0,0,0,0.75);
box-shadow: 2px 4px 9px 0px rgba(0,0,0,0.75);
}
.wpcpycntntallposts .panel-body
{
	background-color:white;
	max-height:250px;overflow-y:auto;
	padding:5px;
}
.wpcpycntntallposts .panel-body label
{
	display:block;
	padding-bottom:10px !important;
	font-size:14px;
}
.selectpagepost
{
	margin-top:5px;
	min-height:50px;
}
.selectionforcopyprevention
{
	min-height:40px;
}
</style>

<?php
}
}
?>