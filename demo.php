<?php 
require_once('funcs.php');
$raw_string = '
<?php 
	//single comment
	#single comment
/*	multi
	line 
	comment*/
	public static function lang($_key, $key = null, $markers = null)
	{
		global $lang;
		if($key !== null && $markers === null){
			@$str = $lang[$_key][$key];
		}
		else if($key === null && $markers === null){
			@$str = $lang[$_key];
		}
		if($str == "")
		{
			return ("No language key found");
		}
		else{
			return strval(trim($str));
		}
	}
	$$file = "log.dll ";
		$$file = "";
		$$file = \'\';
		$current = file_get_contents($file);//more comment
		$current = file_put_contents   ($file);
		$current=file_put_contents   ($file);
		$current=file_put_contents($file);
		$current .= $string." IP: ".$_SERVER["REMOTE_ADDR"].PHP_EOL;
		
		class control{
		private static $urls = array("/^create$/", "/^retrieve$/", "/^(update|update=[0-9]*)$/", "/^delete$/");
		public $page = null;
		public function __construct(){
			if(session_id() == null)
				session_start();
			$url = parse_url($_SERVER["REQUEST_URI"]);
			$matched = false;
			if($query = @$url["query"]){
				foreach (self::$urls as $pattern){
				  if(preg_match($pattern, trim(@$query))){
					  $matched = true;
					  break;
				  }
				}
				if($matched != false)
					$this->page = $query;
				else
					header("location: ./");
			}
		}	
		
		
	}
	?>
	';
//passing as a string
echo $to_color->load_color($raw_string);
/*	
	passing as a file
	echo $to_color->load_color('PRJ0.inc', 'f');
*/

?>