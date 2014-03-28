<?php 

class OptionParser{
	var $options = array();
	var $args = array();
	var $shorts = array();
	var $longs = array();
	
	public function __construct($pArr){
		extract(FPX::contract(array(
			"optional" => array(
				"description",
				"version",
				"options"
			)
		)));
		
		$this->description = $description;
		$this->version = $version;
		
		if($options){
			foreach($options as $option){
				$this->addOption($option);
			}
		}
		
	}
	
	
	public function addOption($pArr){
		extract(FPX::contract(array(
			"required" => array(
				array("short"), // requires either short or long
				array("long")
			),
			"optional" => array(
				"type",
				"desc",
				"run"
			)
		)));
		if($option['short']){
			$this->shorts[$option['short']] = $option;
		}
		if($option['long']){
			$this->longs[$option['long']] = $option;
		}
		$this->options[] = $pArr;
	}
	
	public function run($argv){
		if(!array_key_exists("h", $this->shorts) && !array_key_exists("help", $this->longs)){
			$this->addOption(array(
				"short" => "h",
				"long" => "help",
				"type" => "switch",
				"desc" => "Show this help message",
			));
		}
			
		$this->program_name = array_shift($argv);
		
		$shortopts = "";
		$longopts = array();
		foreach($this->options as $details){
			$short = $details['short'];
			switch($details['type']){
				case "switch":
					$short ? $shortopts .= $short : null;
					if($details['long']) $longopts[] = $details['long'];
					break;
				case "required_arg":
					$short ? $shortopts .= $short.":" : null;
					if($details['long']) $longopts[] = $details['long'].":";
					break;
				case "optional_arg":
					$short ? $shortopts .= $short."::" : null;
					if($details['long']) $longopts[] = $details['long']."::";
					break;
				default: //default to optional_arg
					$short ? $shortopts .= $short."::" : null;
					if($details['long']) $longopts[] = $details['long']."::";
			}
		}
		
		$getopt = getopt($shortopts, $longopts);
//		DEBUG::activate();
		DEBUG::setStyle("console");
		DEBUG::writeln("shorts: ". $shortopts);
		DEBUG::lvar_dump("longs: ", $longopts);
		DEBUG::lvar_dump("options passed in", $getopt);
		
		if( isset($getopt['h']) || // help requested
				isset($getopt['help']) // no command and command required
		){
			$this->usage();
			exit(0);
		}
		
		foreach($getopt as $name=>$value){
			foreach($this->options as $option){
				$short = $option['short'];
				$long = $option['long'];
				if( ($short && $name == $short) || ($long && $name == $long) ){ // if we match either the short or long name
					
					// run a function if it exists
					$func = $option['run'];
					if($func && is_callable($func)){
						$func($value);
					}
					
					// store the value in both the long and short name space
					if($short && !isset($this->args[$short])){ // if the short name exists and isn't set yet, use it
						$this->args[$short] = $value;
					}else if($short && isset($this->args[$short])){ // if the short naem exists and there's something there, append an array
						$this->args[$short] = array_merge((array)$this->args[$short], (array)$value);
					}
					if($long && !isset($this->args[$long])){ // if the $long name exists and isn't set yet, use it
						$this->args[$long] = $value;
					}else if($long && isset($this->args[$long])){ // if the $long naem exists and there's something there, append an array
						$this->args[$long] = array_merge((array)$this->args[$long], (array)$value);
					}
				}
			}
		}
		DEBUG::lvar_dump("args: ", $this->args);
		
		
	}
	
	public function get($str){
		return $this->args[$str];
	}
	
	public function has($str){
		return isset($this->args[$str]);
	}
	
	/**
	 * Print out the help message
	 * 
	 */
	public function usage(){
		echo "\nNAME";
		echo "\n\t$this->program_name - ". $this->description;
		echo "\n";
		if($this->version){
			echo "\nVERSION";
			echo "\n\t". $this->version;
			echo "\n";
		}
		echo "\nSYNOPSIS";
		$synopsis = "\n\t $this->program_name [options] ";
		
		echo $synopsis;
		echo "\n";
		
		$option_title = "\nOPTIONS";
		echo $option_title;
		foreach($this->options as $config){
			$str = "\n\t";
			$str .= $config['short'] ? " -{$config['short']}" : "";
			$str .= $config['long'] ? ($config['short'] ? ",": "") . " --{$config['long']} " : '';
			$str .= " \t\t- {$config['desc']}";
			echo $str;
		}
		echo "\n";
	}
	
}


?>