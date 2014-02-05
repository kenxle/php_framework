<?php
/**
 * 
 * A class to help with command line argument parsing. 
 * 
 * @example <pre>$opts = new CommandParser($argv, array(
	"desc" => "k3 is a command line utility for managing projects ".
		"built in the k3 framework.",

	"version" => "0.0.1",
		
	"commands" => array(

		"test" => array(
			"help" => "Help text for test command",
			"desc" => "Run the test suite for this app",
			"options" => array(),
			"run" => function(){
				echo "I'm running test";
			}
		)
	),
	
	"options" => array(
		"v" => array( //shortname
			"also" => "verbose", //longname
			"type" => "switch", //switch, required_param, optional_param
			"desc" => "Run in verbose mode" //help statement
			
		)
	)
));

if($opts->hasGlobalOption("v")){
	echo "has global option v";
}
$opts->run();</pre>
 * @author ken.stclair
 *
 */
class CommandParser{
	var $config;
	var $program_name;
	var $args;
	var $global_args = array();
	var $command_args = array(); // flags and switches
	var $command_params = array(); // params without flags
	var $command;
	
	public function __construct($argv, $config){
		$this->config = $config;
		$this->program_name = array_shift($argv);
		$this->args = $argv;
		$args = $this->args; //duplicate array
		
		$shortopts = "";
		$longopts = array();
		foreach($this->config['options'] as $short => $details){
			switch($details['type']){
				case "switch":
					$shortopts .= $short;
					if($details['also']) $longopts[$short] = $details['also'];
					break;
				case "required_param":
					$shortopts .= $short.":";
					if($details['also']) $longopts[$short.":"] = $details['also'].":";
					break;
				case "optional_param":
					$shortopts .= $short."::";
					if($details['also']) $longopts[$short."::"] = $details['also']."::";
					break;
				default: //default to optional_param
					$shortopts .= $short."::";
					if($details['also']) $longopts[$short."::"] = $details['also']."::";
			}
		}
		foreach($this->config['commands'] as $short => $command_details){
			foreach($command_details['options'] as $short => $details){
				switch($details['type']){
					case "switch":
						$shortopts .= $short;
						if($details['also']) $longopts[$short] = $details['also'];
						break;
					case "required_param":
						$shortopts .= $short.":";
						if($details['also']) $longopts[$short.":"] = $details['also'].":";
						break;
					case "optional_param":
						$shortopts .= $short."::";
						if($details['also']) $longopts[$short."::"] = $details['also']."::";
						break;
					default: //default to optional_param
						$shortopts .= $short."::";
						if($details['also']) $longopts[$short."::"] = $details['also']."::";
				}
			}
		}
		$options = getopt($shortopts, $longopts);
//		DEBUG::activate();
		DEBUG::setStyle("console");
		DEBUG::writeln("shorts: ". $shortopts);
		DEBUG::lvar_dump("longs: ", $longopts);
		DEBUG::lvar_dump("options passed in", $options);
		// go through global options and see if any are present in command line args
		foreach($this->config['options'] as $short=>$details){
			if(array_key_exists($short, $options)){
				$this->global_args[$short] = $options[$short];
			}
			if(array_key_exists($details['also'], $options)){
				$this->global_args[$details['also']] = $options[$details['also']];
			}
		}
		
		// this doesn't work because getopt is hacking off everything after the command
//		// go through command options and see if any are present in command line args
//		foreach($this->config['commands'] as $command => $command_details){
//			foreach($command_details['options'] as $short => $details){
//				if(array_key_exists($short, $options)){
//					$this->command_args[$short] = $options[$short];
//				}
//				if(array_key_exists($details['also'], $options)){
//					$this->command_args[$details['also']] = $options[$details['also']];
//				}
//			}
//		}
		
		//FIXME this is not checking against the config. it grabs all command params.
		$grab_command_args = false;
		$command_args = array();
		$command = "";
		foreach($argv as $param){
			$value = false;
			if($grab_command_args){
//				$options = $this->config['commands'][$command]['options'];
				if(strpos($param, "--") === 0){
					$param = substr($param, 2);
				}else if(strpos($param, "-") === 0){
					$param = substr($param, 1);
				}
				if(strpos($param, "=") !== false){
					$value = substr($param, strpos($param, "=")+1);
					$param = substr($param, 0, strpos($param, "="));
//					DEBUG::writeln("param separated: $param");
//					DEBUG::writeln("value separated: $value");
				}else{
					ERROR::writeln("Command arguments with values currently only support a separator of '=' like -a=foo");
				}
				$command_args[$param] = $value;
			}
			
			
			if(strpos($param, "-") !== 0 && strpos($param, "--") !== 0){
				$grab_command_args = true;
//				if(!$grab_command_args)$command = $param;
			}
			
		}
		DEBUG::lvar_dump("command_args", $command_args);
		$this->command_args = $command_args;
		
		
//		// prune the flags out of argv
//		$pruneargv = array();
//		foreach ($options as $option => $value) {
//			$with_hyphens = (strlen($option) > 1 ? "--" : "-"). $option;
//			DEBUG::lvar_dump("array_search returns from $with_hyphens, $argv", array_search($with_hyphens, $argv) );
//			$key = array_search($with_hyphens, $argv);
//			if($key !== false){
//				unset($argv[$key]);
//			}
//		}
		
		// prune the flags out
		foreach($argv as $key=>$value){
			if(strpos($value, "-") === 0)
				unset($argv[$key]);
			if(strpos($value, "--") === 0)
				unset($argv[$key]);
			
		}
		
//		DEBUG::activate();
		DEBUG::lvar_dump("argv", $argv);
		$this->command = array_shift($argv);
		$this->command_params = $argv;
//		//next after global flags is command
//		$this->command = array_shift($args);
//		
//		//what's left are command args
//		$this->command_args = $args; //TODO parse this out, handle -f=value, make it not naive/stupid
	}
	
	
	
	public function run(){
		if( empty($this->args) ||
				$this->command == "help" ||
				empty($this->command)){
			$this->usage();
			return 0;
		}
		
		$func = $this->config['commands'][$this->command]['run'];
		if(is_callable($func)){
			$func($this->command_args);
		}else{
			ERROR::lvar_dump("Command '{$this->command}' contains a function that isn't a callable", $func);
		}
		
		
		
	}
	
	public function usage(){
		echo "\nNAME";
		echo "\n\t$this->program_name - ". $this->config['desc'];
		echo "\n";
		if($this->config['version']){
			echo "\nVERSION";
			echo "\n\t". $this->config['version'];
			echo "\n";
		}
		echo "\nSYNOPSIS";
		echo "\n\t $this->program_name [global options] command [command options] [arguments...]";
		echo "\n";
		echo "\nGLOBAL OPTIONS";
		echo "\n\t-h, --help \t- Show this message";
		foreach($this->config['options'] as $option=>$config){
			$dashes = strlen($option) >1 ? "--" : "-"; //TODO add output for new "also" attribute, the long form
			echo "\n\t$dashes$option \t\t- {$config['desc']}";
		}
		echo "\n";
		echo "\nCOMMANDS";
		echo "\n\thelp \tShow a list of commands or help for one command";
		foreach($this->config['commands'] as $command=>$config){
			echo "\n\t$command \t{$config['desc']}";
		}
		echo "\n";
	}
	
	public function hasGlobalArg($str){
		return array_key_exists($str, $this->global_args);
	}
	
	public function hasCommandArg($str){
		return array_key_exists($str, $this->command_args);
	}
	
	public function getGlobalArg($str){
		return $this->global_args[$str];
	}
	
	public function getCommandArg($str){
		return $this->command_args[$str];
	}
}


