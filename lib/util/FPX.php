<?php


/**
 * Function Parameter Extension
 * 
 * Allows you to explicitly declare contracts on parameters. 
 * 
 * To save time, this can be deactivated in production so that the 
 * checks are skipped. 
 * 
 * @author kenstclair
 *
 */
class FPX extends DEBUG{
	
	static $debug = false;
	static $buffer = false;
	static $thebuffer = array();
	
	/**
	 * Create a contract for the parameters when they are passed to the function as an array. 
	 * The contract is formatted as:
	 * array(
	 *    'required'=>array(array('required', 'group'), array('alternate', 'required', 'group'),
	 *    'optional'=>array('optional', 'params')
	 * )
	 * where only one of the required groups must have all of its parameters satisfied. 
	 * The optional list will restrict the parameters to only those listed in the contract,
	 * to help catch typos and let you know when you've used an invalid parameter. 
	 * 
	 * A fun idiom:
	 * public static function inventoryDelivery($pArr){
		  extract(FPX::contract(array(
				"required" => array('start_date', 'end_date'),
				"optional" => array('rollup', 'report_format')
		  )));
	 * Now all your defined variables are ready for use, just as if
	 * you'd declared them normally. Use of extract() is ok because
	 * we define the params, and contract() is returning our defs. 
	 * 
	 * If required params are missing, all variables will be killed
	 * and extract will have nothing. If extra params are included, 
	 * they will be removed regardless of whether FPX is activate()d. 
	 * 
	 * FPX can always be used as a passthrough that filters out 
	 * unwanted params. 
	 * 
	 * @param unknown_type $arr
	 */
	public static function contract($structure){
		$required = ($structure['required'] ? $structure['required'] : array());
		$optional = ($structure['optional'] ? $structure['optional'] : array());
		$all_available_params = array_merge(static::flatten_array($required, array()), static::flatten_array($optional, array()));
		$params = static::getCallersArgs(2);
		DEBUG::lvar_dump('all_available_params: ', $all_available_params);
		if(!is_array($params)) ERROR::writeln("params not formatted as an array<br />". static::formatParamsError($required, $optional));
//		DEBUG::lvar_dump('getCallersArgs ', $params);
				
		//check optionals first
		foreach($params as $name=>$value){
			if(!in_array($name, $all_available_params)){
				DEBUG::writeln("unsetting $name because it is not allowed");
				if(static::$debug) ERROR::lvar_dump("incorrect param used: '$name'. Not allowed. Try: ",$all_available_params);
				unset($params[$name]);
			}
		}
		if(!static::$debug) return $params;//"not checking";

//		DEBUG::activate();
		//required params are by group, at least one group must satisfy all their params
		if(isset($structure['required'])){
			$reqGroupSatisfied = false;
			
			if( is_array($required[0]) ){// alternate required groups
				foreach($required as $set){
	//				$set = explode(',', $set); // use arrays instead of comma separated. 	
					$reqGroupSatisfied = static::require_params($set, $params) || $reqGroupSatisfied;
					if(!$reqGroupSatisfied){
						$missing_required_params[] = static::getMissingRequiredParams($set, $params);
					}
				}
			}else{ // only one required group. simpler syntax
				$reqGroupSatisfied = static::require_params($required, $params);
//				DEBUG::lvar_dump("reqGroupSatisfied: ", $reqGroupSatisfied);
				if(!$reqGroupSatisfied){
					$missing_required_params = static::getMissingRequiredParams($required, $params);
				}
			}
			if(!$reqGroupSatisfied){
//				DEBUG::writeln("reqGroupNotSatisfied");
//				ERROR::writeln("Missing required parameter: $missing_required_params <br />". static::formatParamsError($required, $optional));
				ERROR::lvar_dump("Missing required parameter from: ", $required );
				
				return array(); 
			}
		}
		

		return $params;
	}
	
	public static function flatten_array($array, $ret_arr){
		foreach($array as $value){
			if(is_array($value)){
				$ret_arr = static::flatten_array($value, $ret_arr);
			}else{
				$ret_arr[] = $value;
			}
		}	
		return $ret_arr;
	}
	
	protected static function formatParamsError($required, $optional){
		$funcName = static::getCallingFunction(3);
		
		$reqStr = "Required Params: ". implode(" || ", $required);
		$optStr = (!empty($optional) ? "Optional Params: ". implode(", ", $optional) : "");
		$yourStr = "Your Params: ". implode(", ", array_keys(static::getCallersArgs(3)));
		$errorStr = "<b>$funcName</b> Usage <br /> $reqStr <br /> $optStr <br /> $yourStr ";
		return $errorStr;
	}
	
	protected static function getCallersArgs($level){
		$trace=debug_backtrace(false);
		$caller=$trace[$level];
		$args = $caller['args'];
		
		return $args[0];
	} 
	
	protected static function getCallingFunction($level){
		$trace=debug_backtrace(false);
		$caller=$trace[$level];
		
		$func = ($caller['class'] ? 
				$caller['class'].$caller['type'].$caller['function'] :
				$caller['function']);
		
		return $func;
	}
	
	public static function arrayCheck($arr){
		return is_array($arr);
	} 
	
	public static function require_params($arrOfNames, $arrOfParams){
		$exists = true;
//			DEBUG::lvar_dump("require_params called with names: ",  $arrOfNames );
//			DEBUG::lvar_dump("and params: ",  $arrOfParams );
		foreach($arrOfNames as $name){
			$exists = array_key_exists($name, $arrOfParams) && $exists;
		}
//		DEBUG::lvar_dump("returning: ", $exists);
		return $exists;
	}
	
	protected static function getMissingRequiredParams($arrOfNames, $arrOfParams){
		$missing = array();
		foreach($arrOfNames as $name){
			if(array_key_exists($name, $arrOfParams)){
				$missing[] = $name;
			}
		}
		return implode(", ", $missing);
	}
	
	public static function restrict_params_to($arrOfNames, $arrOfParams){
		$conforms = true;
		foreach($arrOfParams as $key=>$param){
			$conforms = in_array($key, $arrOfNames) && $conforms;
		}
		return $conforms;
	}
	
	protected static function getUnwantedParams($arrOfNames, $arrOfParams){
		$extra = array();
		foreach($arrOfParams as $key=>$param){
			if(!in_array($key, $arrOfNames)){
				$extra[] = $key;
			}
		}
		return implode(", ", $extra);
	}
	
	public static function pdefault(&$param, $default, $replacement_type="full"){
		switch ($replacement_type){
			case "isset": // will only replace if it's not set at all
				$replace = !isset($param);
				break;
			case "full": // will replace variables that are set but falsy
				$replace = !$param;
				break;
			case "empty": 
				$replace = empty($param);
				break;
			default: // use replacement_type as a function name
				$replace = $replacement_type($param);
		}

		if($replace){
			$calling_func = self::getCallingFunction(2);
			DEBUG::writeln($calling_func. ": defaulting '$param' to '$default'");
			$param = $default;
		}
	}
	
	
	
}