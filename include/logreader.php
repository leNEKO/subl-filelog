<?
class log{
	public $datetime;
	public $action;
	public $path;
	public $mods;

	function __construct($string){
		if($string){
			$log = preg_replace("/ : (\d+) actions/"," --- $1", $string);
			$tmp = explode(" --- ",$log);

			$this->datetime = DateTime::createFromFormat("Y-m-d H:i:s,u", $tmp[0]);
			$this->action = @trim($tmp[1]);
			$this->path = trim($tmp[2]);
			$this->mods = (int)@$tmp[3];
		}
	}
}

class logreader {

	public $logs = [];
	public $paths = [];
	public $data = [];
	public $stock = [];
	public $date;
	public $date_path;

	function __construct($date){
		$this->date = $date;
		// chemin du fichier pour cette date
		$tmp = explode("-", $date);
		unset($tmp[2]);
		$this->date_path = implode("/",$tmp);
		$this->read();
	}

	function test($path = "/home/neko/sublime-filelog"){

		$cmd = "find $path -type f -exec cat {} \;";
		$tmp = explode(PHP_EOL,shell_exec($cmd));
		foreach($tmp as $line){
			if(trim($line)){
				$this->logs[] = $line;
			}
		}

		$this->paths();

		$stock = [];

		foreach($this->logs as $log){
			if($log){

				$detail = explode("---", $log);
				foreach($detail as $key => $val){
					$detail[$key] = trim($val);
				}
				$datetime_str = $detail[0];
				$datetime = DateTime::createFromFormat("Y-m-d H:i:s,u", $datetime_str);

				$path_info = explode(" : ", $detail[2]);
				$action = $detail[1];

				if(isset($path_info[1])){
					@$mods = (int)$path_info[1];
				}else{
					$mods = 0;
				}
				$full_path = trim($path_info[0]);
				$file_path = basename($full_path);
				$path_el = explode("/",dirname($full_path));
				krsort($path_el);
				$path = implode("\\",$path_el);

				if(@$last_path != $path){
					if(isset($prev)){
						$stock[] = $prev;
						unset($prev);
					}
				}

				$prev["path"] = $path;
				$prev["files"][$file_path][] = [
					"date_start" => $datetime,
					"path" => $full_path,
					"mods" => $mods
				];

				$last_path = $path;
			}
		}
		$stock[] = $prev;

		$this->stock = $stock;
	}

	function read($path = "/home/neko/sublime-filelog"){
		$path = $path . "/" . $this->date_path;
		$cmd = "find $path -type f -follow -exec cat {} \;";
		$tmp = explode(PHP_EOL,shell_exec($cmd));
		foreach($tmp as $line){
			if(trim($line)){
				$this->logs[] = new log($line);
			}
		}
	}

	function get(){
		// analyse de la date
		$pa = ["Y","m","d"];
		$date_arr = explode("-", $this->date);
		$pa_out = [];

		for($i = 0 ; $i < count($date_arr) ; $i++){
			$pa_out[] = $pa[$i];
		}
		$format = implode("-", $pa_out);

		$output = [];
		foreach($this->logs as $log){

			$day = $log->datetime->format($format);
			if($day == $this->date){
				$output[] = $log;
			}
		}

		return $output;
	}

	// tentative d'algo pour la détection statistique d'identification d'info pertinante dans un chemin
	function paths(){
		foreach($this->logs as $log_str){

			if($log_info = new log($log_str)){
				$this->data[] = $log_info;
			}

			$path_el = explode("/", $log_info->path);
			$path_str = "";
			foreach($path_el as $el){
				if($el){
					$path_str .= "/" . $el;
					@$this->paths[$path_str]++;
				}
			}
		}
		arsort($this->paths);

		foreach($this->data as $key => $log_info){
			$path_el = explode("/", $log_info->path);
			$path_str = "";
			foreach($path_el as $el){
				if($el){
					$path_str .= "/" . $el;
					$qte = @$this->paths[$path_str];
					$this->data[$key]->path_detail[$el] = $qte;
				}
			}
			// var_dump($this->data[$key]);
		}
	}
}