<?php

/**
 * Debugger
 */

if ( ! function_exists('debug') ) {
	function debug($var = false, $showHtml = false, $showFrom = true) {
		if ($showFrom && defined(ROOT)) {
			$calledFrom = debug_backtrace();
			echo '<strong>' . substr(str_replace(ROOT, '', $calledFrom[0]['file']), 1) . '</strong>';
			echo ' (line <strong>' . $calledFrom[0]['line'] . '</strong>)';
		}

		echo "\n<pre class=\"debug\">\n";

		$var = print_r($var, true);
		if ($showHtml) {
			$var = str_replace('<', '&lt;', str_replace('>', '&gt;', $var));
		}
		echo $var . "\n</pre>\n";

	}
}