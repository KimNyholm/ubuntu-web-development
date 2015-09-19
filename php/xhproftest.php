<?php

  $XHPROF_ROOT = "/var/www/html/xhprof/";
  include_once $XHPROF_ROOT . "/xhprof_lib/utils/xhprof_lib.php";
  include_once $XHPROF_ROOT . "/xhprof_lib/utils/xhprof_runs.php";
  xhprof_enable();

  function aFunction(){
    echo '<p>Date and time is ' . date('Y-m-d H:m:s') . '.</p>';
  }

  // Your code here
  echo '<p>Testing xhprof</p>';
  aFunction();

  $xhprof_data = xhprof_disable();
  $profiler_namespace = 'XHProfTest';
  $xhprof_runs = new XHProfRuns_Default();
  $run_id = $xhprof_runs->save_run($xhprof_data, $profiler_namespace);

  // url to the XHProf UI libraries (change the host name and path)
  $profiler_url = sprintf('/xhprof/xhprof_html/index.php?run=%s&amp;source=%s', $run_id, $profiler_namespace);
  echo '<a href="'. $profiler_url .'" target="_blank">Profiler output</a>';

?>
