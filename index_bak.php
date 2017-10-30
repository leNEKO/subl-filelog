<?
// sécurité très très basique
$url = $_REQUEST["url"];
$file = basename($url);
if($file != "log.ics"){
	die("sheiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiit");
}

function my_dump($data){
	echo "<pre>" . json_encode($data,JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) . "</pre>";
}

ob_start("ob_gzhandler");

require __DIR__ . '/vendor/autoload.php';

$cal = new \Eluceo\iCal\Component\Calendar('sublime-filelog');

require __DIR__ . '/include/logreader.php';
$log = new logreader();

foreach($log->stock as $event){
	$mods = [];
	foreach($event["files"] as $file => $details){
		foreach($details as $detail){
			@$mods[$file] += $detail["mods"];
		}
	}
	$desc = [];
	arsort($mods);
	foreach($mods as $file => $mods){
		$desc[] = $file . ($mods?" : " . number_format($mods, 0, ",", " ") . " mods":"");
	}
	$desc_str = implode("\n",$desc);
	$first_date = array_shift($event["files"])[0]["date_start"];

	$cal_event = new \Eluceo\iCal\Component\Event();
	$cal_event
		->setDtStart($first_date)
		->setSummary($event["path"])
		->setUseTimezone(true)
		->setDescription($desc_str)
	;

	$cal->addComponent($cal_event);
}

// header('Content-Type: text/calendar; charset=utf-8');
// header('Content-Disposition: attachment; filename="cal.ics"');
echo $cal->render();
