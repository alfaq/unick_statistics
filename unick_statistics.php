<?php

/**
 * @file
 * Handles counts of node views via Ajax with minimal bootstrap.
 */

/**
* Root directory of Drupal installation.
*/
define('DRUPAL_ROOT', substr($_SERVER['SCRIPT_FILENAME'], 0, strpos($_SERVER['SCRIPT_FILENAME'], '/sites/all/modules/unick_statistics/unick_statistics.php')));
// Change the directory to the Drupal root.
chdir(DRUPAL_ROOT);

include_once DRUPAL_ROOT . '/includes/bootstrap.inc';
drupal_bootstrap(DRUPAL_BOOTSTRAP_VARIABLES);
if (variable_get('statistics_count_content_views', 0) && variable_get('statistics_count_content_views_ajax', 0)) {
  $nid = $_POST['nid'];
  if (is_numeric($nid)) {
		session_start();
		$first_time = true;
		if (isset($_SESSION['nodes'])){
			$nodes = explode(',',$_SESSION['nodes']);
			if (!in_array(arg(1),$nodes)){
				$nodes[] = arg(1);
			}else{
				$first_time = false;
			}
		}else{
			$nodes = array();
		}
		$_SESSION['nodes'] = implode(',',$nodes);
	  
		if ($first_time){
			db_merge('node_counter')
			  ->key(array('nid' => $nid))
			  ->fields(array(
				'daycount' => 1,
				'totalcount' => 1,
				'timestamp' => REQUEST_TIME,
			  ))
			  ->expression('daycount', 'daycount + 1')
			  ->expression('totalcount', 'totalcount + 1')
			  ->execute();
		}
  }
}
