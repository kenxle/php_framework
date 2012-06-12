<?php

/**
 * A class for easy benchmarking of sections of a page. 
 * 
 * Activate to run benchmarks:
 * BENCHMARK::activate();
 * 
 * Create benchmark points with
 * BENCHMARK::createPoint($desc);
 * 
 * Then print the report of each point by calling:
 * BENCHMARK::createReport();
 * 
 * @author kenstclair
 *
 */
class BENCHMARK {
	static $active = false;
	static $points = array();
	
	public static function activate(){
		static::$active = true;
	}
	
	public static function deactivate(){
		static::$active = false;
	}
	
	public static function createPoint($description){
		if(!static::$active) return;
		$arr = array(
			"description" => $description,
			"time" => microtime(true)
		);
		static::$points[] = $arr;
		return $arr;
	}
	
	public static function createReport(){
		if(!static::$active) return;
		$point1 = static::$points[0];
		$lastpoint = $point1;
		?>
		<style>
			#benchmark_report td, #benchmark_report th{
				padding: 7px;
			}
			
			#benchmark_report {
				display: none;
			}
		</style>
		<table id="benchmark_report">
		<tr>
			<th>description</th>
			<th>time</th>
			<th>elapsed since last point</th>
			<th>elapsed since first point</th>
		</tr>
		<?
		foreach(static::$points as $point){
			?><tr>
				<td>
					<?=$point['description'];?>
				</td>
				<td>
					<?=$point['time'];?>
				</td>
				<td>
					<?=$point['time'] - $lastpoint['time'];?>
				</td>
				<td>
					<?=$point['time'] - $point1['time'];?>
				</td>
			</tr><?
			$lastpoint = $point;
		}
		?></table><?
	}
}