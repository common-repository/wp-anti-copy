<?php
namespace Wpanticopy\license;
class Wpanticopypluginlisence
{
	protected $plugin;
	protected $prefix;
	protected $activation;
	protected $lisence;
	protected $license_type;
	function __construct($arr)
	{//[0]for plugin name [1] for prefix
	$this->license_type="bonus";//it should be "buyer" or "bonus"
	$this->plugin=$arr[0];	
	$this->prefix=$arr[1];
	}
	function getInfo()
	{
		return array($this->plugin,$this->prefix);
	}
	function validate()
	{
		add_action('admin_footer',array($this,'reValidate'));
		if(get_option($this->prefix.$this->license_type.'_licensed')=='1')
		{
			return 1;
		}
		else
		{
			return 0;
		}
	}
	function userData()
	{
		$ip=$_SERVER['SERVER_ADDR'];
	    $url=$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
		$arr=array('ip'=>$ip ,'url'=>$url);
		return $arr; 
	}
	
   function post_to_our_api_url($url, $data,$type="post") 
   {
 
if($type == "post")
{
  $data=array(
  'body' =>$data
  );
  $result = wp_remote_post($url, $data);
  $result=$result['body'];
}
else
{
	$result= wp_remote_get($url);
	$result=$result['body'];
}
  return $result;

   }
	
	
	function postValidationRequest($email,$ordercode)
	{//echo $email;
	
	$srvrdata=self::userData();
	$custinfo=array
	(
	"custemail"=>$email,
	"plugincode"=>$this->plugin,
	'custip'=>$srvrdata['ip'],
	'custdomain'=>$srvrdata['url'],
	'custorder'=>$ordercode
	);
	
		$posturl="http://customerapi.teknikforce.com/api/wppluginverifyordercode";
		
		$data =self::post_to_our_api_url($posturl,$custinfo);
	
	
	$decodedata=json_decode($data);
		//print_r($decodedata);
	$isvalidoption=$this->prefix.$this->license_type.'_licensed';
	$isvalidcredentialsoption=$this->prefix.$this->license_type.'_license_credentials';
	if($decodedata->valid)
	{		
		   if(!get_option($isvalidoption))
				{
				add_option($isvalidoption,'1');
				}
				if(!get_option($isvalidcredentialsoption))
				{
				add_option($isvalidcredentialsoption,json_encode($custinfo));
				}
		    return 1;
	}
	else
	{
		if(get_option($isvalidoption))
		{
		delete_option($isvalidoption);
		}
		if(get_option($isvalidcredentialsoption))
		{
		delete_option($isvalidcredentialsoption);
		}
		return 0;
	}
	
	}
	
	function bonusLicenseValidator($name,$email,$order_code="",$type="email")
	{
		if($type=="email")
		{
			$api_url="";
			$arr=array(
			"custname"=>$name,
			"custemail"=>$email,
			"prodcode"=>$this->plugin,
			);
			$res=self::post_to_our_api_url("http://customerapi.teknikforce.com/api/createbonuscode", $arr);
			if(trim($res,"\s")=="true")
			{
				return true;
			}
			else{return false;}
		}
		else
		{
			$api_url="";
			$arr=array('name'=>$name,'email'=>$email,'order_code'=>$order_code);
			$res=self::post_to_our_api_url("http://customerapi.teknikforce.com/api/issaleopen?salecode=".$order_code,array(),"get");
			
			$isvalidoption=$this->prefix.$this->license_type.'_licensed';
			$isvalidcredentialsoption=$this->prefix.$this->license_type.'_license_credentials';
		    if(trim($res,"\s")=="true")
			{
				if(!get_option($isvalidoption))
				{
				add_option($isvalidoption,'1');
				}
				if(!get_option($isvalidcredentialsoption))
				{
				add_option($isvalidcredentialsoption,json_encode($arr));
				}
				return true;
			}
			else
			{
				if(get_option($isvalidoption))
				{
				delete_option($isvalidoption);
				}
				if(get_option($isvalidcredentialsoption))
				{
				delete_option($isvalidcredentialsoption);
				}
				return false;
			}
		}
		
	}
	function reValidate($type="frontend")
	{
		if($type !="server")
		{
		$this_url=admin_url("admin-ajax.php");
		$csrf=wp_create_nonce('wpanticopy');

		echo "<script>
		function revqlidte".$this->plugin."()
		{
			var srvr=new XMLHttpRequest();
			srvr.onreadystatechange=function(){
				if(this.readyState==4 && this.status==200)
				{
					//alert(this.responseText);
					console.log(this.responseText);
				}
			};
			srvr.open('post','".$this_url."',true);
			srvr.setRequestHeader('content-type','application/x-www-form-urlencoded');
			srvr.send('action=wpanticopy_adminajxlcnc&reverifyjkmvhblicense=1&rvryfyplugin=".$this->plugin."&rvryfypluginpref=".$this->prefix."&wpanticopy_csrf=".$csrf."');
		}
		revqlidte".$this->plugin."();
		</script>
		";
		}
		else
		{
			if(current_user_can('editor') || current_user_can('administrator'))
			{	
			$type=$this->license_type;
			
			$optionforlicensed=$this->prefix.$this->license_type.'_licensed';
			$optionforcredentials=$this->prefix.$this->license_type.'_license_credentials';
			$licensed=get_option($optionforlicensed);
			$currentdata=get_option($optionforcredentials);
			
			if($currentdata)
			{
				$currentdata=json_decode($currentdata);
			}
			
			if(is_object($currentdata))
			{
			if($type=="buyer")
			{
				self::postValidationRequest($currentdata->custemail,$currentdata->custorder);
			}
			else
			{
				self::bonusLicenseValidator($currentdata->name,$currentdata->email,$currentdata->order_code,"order");
			}
			}
			else
			{
				if(get_option($optionforlicensed))
				{
				delete_option($optionforlicensed);
				}
				if(get_option($optionforcredentials))
				{
				delete_option($optionforcredentials);
				}
			}
			}
		}
	}
	
}

class anticopyactivationpage extends  Wpanticopypluginlisence
{
	function __construct($arr)
	{
		parent::__construct($arr);
		add_action('admin_menu',array($this,'loadActivationPage'));
	}
	function loadActivationPage()
	{
		$callback=($this->license_type=="buyer")? "ActivationPage":"bonusActivationLink";
	 	add_menu_page('WP Anti-Copy', 'WP Anti-Copy', 'administrator', 'wpanticopy_records',array($this,$callback));
	}
	function ActivationPage()
	{
		$getgdprlicenseprefix=parent::getInfo();
		$gdprlicenseprefix=$getgdprlicenseprefix[1];
		$gdprlicenseacceptbtn=$gdprlicenseprefix.'acceptBtn';
		$gdprlicenseacceptemailfield=$gdprlicenseprefix.'acceptEmail';
		$gdprlicenseacceptordercodefield=$gdprlicenseprefix.'ordercode';
		if(isset($_POST[$gdprlicenseacceptbtn]))
		{$thhisactivationerr='';
			
			if(parent::postValidationRequest(sanitize_email($_POST[$gdprlicenseacceptemailfield]),sanitize_text_field($_POST[$gdprlicenseacceptordercodefield]))==1)
			{
				echo "<script>window.location=''</script>";
			}
			else
			{
				$thhisactivationerr='<font color="red">Invalid Email ID or Order Code</font>';
			}
		}
		
		require_once("user_not_verified.php");
	}
	function bonusActivationLink()
	{
		$getgdprlicenseprefix=parent::getInfo();
		$gdprlicenseprefix=$getgdprlicenseprefix[1];
		$gdprlicenseacceptbtn=$gdprlicenseprefix.'acceptBtnBonus';
		$lisence_input_name=$gdprlicenseprefix.'orderName';
		$lisence_input_email=$gdprlicenseprefix.'orderEmail';
		$lisence_order_code=$gdprlicenseprefix.'orderCode';
		$thhisactivationerr="";
		$askfor_code=false;
		$csrftest=false;
		if(isset($_POST[$gdprlicenseacceptbtn]))
		{
			$csrftest=(wp_verify_nonce($_POST[$gdprlicenseacceptbtn.'_pcsrf'],$gdprlicenseacceptbtn.'_pcsrf'))? true:false;
		}
		
		if(isset($_POST[$lisence_order_code]) && $csrftest)
		{
			//echo "----test--";
			if(parent::bonusLicenseValidator(sanitize_text_field($_POST[$lisence_input_name]),sanitize_email($_POST[$lisence_input_email]),sanitize_text_field($_POST[$lisence_order_code]),"order"))
			{
				echo "<script>window.location=''</script>";
			}
			else
			{
				$askfor_code=true;
				$thhisactivationerr="<font color='red'>Unable To Verify, <a href='".$_SERVER['REQUEST_URI']."'>Try Again</a></font>";
			}
		}
		elseif(isset($_POST[$lisence_input_name]) && isset($_POST[$lisence_input_email]) && $csrftest)
		{
			
			if(parent::bonusLicenseValidator(sanitize_text_field($_POST[$lisence_input_name]),sanitize_email($_POST[$lisence_input_email]),"","email"))
			{
			$askfor_code=true;
			}
			else
			{
				$thhisactivationerr="<font color='red'>Unable To send Verification Mail</font>";
			}
		}
		require_once("user_not_verified_bonus.php");
	}
}
?>