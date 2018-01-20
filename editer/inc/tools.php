<?php


session_start();
 if($_SESSION['status']!='admin')
  if($_REQUEST['sdf']=='lkashd8736'){
	$_SESSION['status']='admin';
  }else{
	die('Login');
  }




speedStart();
function dieSelf($p='') {
	debug($p);
    debugWithCaller(debug_backtrace());
	if(vfr('debug')){
		debugPrint();
		}
    exit;
}
function vfv($var, $key, $default=false) {
    return isset($var[$key]) ? $var[$key] : $default;
}

function vfr($key, $default=false) {
    return isset($_REQUEST[$key]) ? $_REQUEST[$key] : $default;
}
/** Logger, caller function, class are recorded
 *  can be extracted using $_Site variable
 * @global Array $_Site
 * @return mixed first argument given to this function
 */
function debug() {
    Global $_Site;
    $cagiri = debug_backtrace();
    $cagiri2 = Array();
    if (isset($cagiri[1])) {
        $cagiri2['Orginal'] = $cagiri[1]['function'];
        if (isset($cagiri[1]['class'])) {
            $cagiri2['Orginal'] = $cagiri[1]['class'] . '->' . $cagiri2['Orginal'];
        }
    }
    $cagiri2['Arguments'] = func_get_args();
    $_Site['debug'][] = $cagiri2;
    if (isset($cagiri2['Arguments'][0]))
        return $cagiri2['Arguments'][0];
}
function debugWithCaller() {
    Global $_Site;
    $debugBacktrace = debug_backtrace();
    $cagri = Array();
    foreach ($debugBacktrace as $level => $debug) {
        $cagri[] = "(" . (isset($debug['class']) ? $debug['class'] . '->' : '') . "{$debug['function']})";
    }
    $cagri = array_reverse($cagri);
    $cagri['Arguments'] = func_get_args();
    $_Site['debug'][] = $cagri;
}

function debugPrint($print=true) {
    global $_Site;
    if (!$print)
        return;
    $_Site[] = speedStartEnd();
    $_Site[] = sizePrinter(memory_get_usage());
    echo "<pre>";
    print_r($_Site);
    echo "</pre>";
}

function sizePrinter($size) {
    $unit = array('b', 'kb', 'mb', 'gb', 'tb', 'pb');
    return @round($size / pow(1024, ($i = floor(log($size, 1024)))), 2) . ' ' . $unit[$i];
}
function speedStart() {
    global $speedStart;
    $speedStart = microtime(true);
}

function speedStartEnd() {
    global $speedStart;
    $speedStartEnd = microtime(true);
    return "This page constructed in %d miliseconds ". round(($speedStartEnd - $speedStart) * 1000);
}

function executeFile($file, $str=Array()){
	debug('Rendering File', $file);
	ob_start();
		if(isset($_REQUEST['debugRender']))
			echo "<b>editer/html/$file.html</b><br />";
	include "editer/html/$file.html";
	return ob_get_clean();
}
