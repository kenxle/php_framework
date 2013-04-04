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
	 * @param unknown_type $arr
	 */
	public static function contract($structure){
		$required = ($structure['required'] ? $structure['required'] : array());
		$optional = ($structure['optional'] ? $structure['optional'] : null);
		$params = static::getCallersArgs(2);
		if(!static::$debug) return $params;//"not checking";
		if(!is_array($params)) ERROR::writeln("params not formatted as an array<br />". static::formatParamsError($required, $optional));
//		DEBUG::lvar_dump('getCallersArgs ', $params);
				
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
				if(!$reqGroupSatisfied){
					$missing_required_params = static::getMissingRequiredParams($required, $params);
				}
			}
			if(!$reqGroupSatisfied){
				ERROR::writeln("Missing required parameter: $missing_required_params <br />". static::formatParamsError($required, $optional));
				return false;
			}
		}
		
		//add all the required params to the optionals list, and check that all included params were listed
		$optionalAll = $optional;
		if( is_array($required[0]) ){//alternate required gruops
			foreach($required as $set){
				foreach ($set as $paramName){
					$optionalAll[] = $paramName; 
				}
			}
		}else{// only one required group. simpler syntax
			foreach ($required as $paramName){
				$optionalAll[] = $paramName; 
			}
		}
		if($structure['optional'] === null){ // if no optionals specified, allow all. 
			$optionalGroupSatisfied = true;
		}else{
			$optionalGroupSatisfied = static::restrict_params_to($optionalAll, $params);
			if(!$optionalGroupSatisfied){
				$extra_names = static::getUnwantedParams($optionalAll, $params);
			}
		}
		if(!$optionalGroupSatisfied){
			ERROR::writeln("Param used that was not in the contract: $extra_names <br />". static::formatParamsError($required, $optional));
			return false;
		}
		return $params;
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
		foreach($arrOfNames as $name){
//			DEBUG::writeln("arraykeyexists called with name $name");
//			DEBUG::lvar_dump("and array: ",  $arrOfParams );
			$exists = array_key_exists($name, $arrOfParams) && $exists;
		}
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
	
	
}