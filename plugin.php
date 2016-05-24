<?php

  /**
   * 插件引擎 版权所有 Happy.Yin<happy.yin@qq.com>
   * 
   * @author   Happy<happy.yin@qq.com>
   */

  $plugin_arr=array();
  $plugin_meta=array();
  $plugin_remove=array();
  $action_arr=array();
  $action_meta=array();
  $action_remove=array();
  $idx=0;

  function doPlugin($tag,$args=array()){

  	global $plugin_arr,$plugin_remove;

  	$first=array_search(current($args),$args);

  	if(empty($plugin_arr[$tag])) return $args[$first];
  	if(isset($plugin_remove[$tag])){
  		foreach($plugin_remove[$tag] as $func){
  			removePlugin($tag,$func);
  		}
  	}

  	krsort($plugin_arr[$tag]);

  	foreach($plugin_arr[$tag] as $plugins){
  		foreach($plugins as $plugins){
  			$plugins['args']=array_merge($plugins['args'],$args);
  			$args[$first]=call_user_func_array($plugins['func'],array_slice($plugins['args'],0,$plugins['args_count']));
  		}
  	}

  	return $args[$first];

  }

  function addPlugin($tag,$func,$args=array(),$sort=10){

  	global $plugin_arr,$plugin_meta,$idx;
  	$plugin_arr[$tag][$sort][++$idx]=array('func'=>$func,'args'=>$args,'args_count'=>sizeof($args));
  	$plugin_meta[$tag][$func][$idx]=$sort;

  }

  function removePlugin($tag,$func){

  	global $plugin_arr,$plugin_meta;

  	if(isset($plugin_meta[$tag][$func])){
  		foreach($plugin_meta[$tag][$func] as $idx=>$sort){
  			unset($plugin_arr[$tag][$sort][$idx]);
  		}
  		unset($plugin_meta[$tag][$func]);
  	}

  }

  function addRemovePlugin($tag,$func){

  	global $plugin_remove;
  	if(in_array($func,(array)$plugin_remove[$tag])) return ;
  	$plugin_remove[$tag][]=$func;

  }

  function doAction($tag,$args=array()){

  	global	$action_arr,$action_remove;
  	if(empty($action_arr[$tag])) return ;
  	if(isset($action_remove[$tag])){
  		foreach($action_remove[$tag] as $func){
  			removeAction($tag,$func);
  		}
  	}

  	krsort($action_arr[$tag]);

  	foreach($action_arr[$tag] as $action_sort){
  		foreach($action_sort as $action_idx){
  			$action_idx['args']=array_merge($action_idx['args'],$args);
  			call_user_func_array($action_idx['func'],array_slice($action_idx['args'],0,$action_idx['args_count']));
  		}
  	}

  }

  function addAction($tag,$func,$args=array(),$sort=10){

  	global $action_arr,$action_meta,$idx;
  	$action_arr[$tag][$sort][++$idx]=array('func'=>$func,'args'=>$args,'args_count'=>sizeof($args));
  	$action_meta[$tag][$func][$idx]=$sort;

  }

  function removeAction($tag,$func){

  	global $action_arr,$action_meta;

  	if(isset($action_meta[$tag][$func])){
  		foreach($action_meta[$tag][$func] as $idx=>$sort){
  			unset($action_arr[$tag][$sort][$idx]);
  		}
  		unset($action_meta[$tag][$func]);
  	}

  }

  function addRemoveAction($tag,$func){

  	global $action_remove;
  	if(in_array($func,(array)$action_remove[$tag])) return ;
  	$action_remove[$tag][]=$func;

  }

  function getPluginNum($tag){

  	global $plugin_arr,$plugin_meta,$plugin_remove;
  	return array('plugin_arr'=>count($plugin_arr),
  				 'plugin_meta'=>count($plugin_meta),
  				 'plugin_remove'=>count($plugin_remove)
  				);

  }

  function getPluginMeta(){

  	global $plugin_meta;
  	return $plugin_meta;

  }
