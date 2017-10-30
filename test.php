<?
require __DIR__ . '/include/logreader.php';
$log = new logreader();
foreach($log->data as $l){

	$pd = $l->path_detail;
	$pd = array_reverse($pd,true);
	var_dump($pd);
	// foreach($pd as $el){

	// }
}