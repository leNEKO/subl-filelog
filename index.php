<?
require __DIR__ . '/vendor/autoload.php';
require __DIR__ . '/include/logreader.php';

if(!$cur_date = @$_GET["date"]){
	$tmp = new DateTime();
	$cur_date = $tmp->format("Y-m-d");
}
$logs = new logreader($cur_date);
$period = $logs->get();

ob_start();
	?>
	<table class="table table-sm table-hover">
		<thead>
			<tr>
				<th class="text-right text-nowrap">
					Date
				</th>
				<th class="text-right text-nowrap">
					Time
				</th>
				<th class="text-right">
					File
				</th>
				<th>
					Path
				</th>
			</tr>
		</thead>
		<tbody>
			<?
			$last = "";
			$last_date = false;
			$dates = [];
			foreach($period as $log){
				$date = $log->datetime->format("Y M d");
				$time = $log->datetime->format("H:i:s");
				if($last != $log->path){
					$path = dirname($log->path);
					$file = basename($log->path);
					?>
					<tr>
						<td class="text-right text-nowrap">
							<?
							if($date != $last_date){
								$dates[] = $date;
								?>
								<a name="<?=$date;?>">
									<?= $date; ?>
								</a>
							<? };
							$last_date = $date;
							?>
						</td>
						<td class="text-right text-nowrap">
							<?= $time; ?>
						</td>
						<td class="text-right">
							<?= $file; ?>
						</td>
						<td>
							<?
							preg_match_all("/.*\/public_html\/((\w|-|\.)*)/", $path, $match);
							preg_match_all("/.*\/mnt\/((\w|-|\.)*)\/((\w|-|\.)*)/", $path, $match2);
							if(isset($match[1][0])){
								echo "<b>" . $match[1][0] . "</b>";
							}elseif(isset($match2[1][0])){
								echo $match2[1][0] . "/<b>" . $match2[3][0] . "</b>";
							}else{
								echo $path;
							}
							?>
						</td>
					</tr>
				<? };
				$last = $log->path;
				?>
			<? }; ?>
		</tbody>
	</table>
	<?
$table = ob_get_clean();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
	<!-- Required meta tags -->
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

	<!-- Bootstrap CSS -->
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta/css/bootstrap.min.css" integrity="sha384-/Y6pD6FV/Vv2HJnA6t+vslU6fwYXjCFtcEpHbNJ0lyAFsXTsjBbfaDjzALeQsN6M" crossorigin="anonymous">

</head>
<body>
	<div class="container-fluid">
		<br/>
		<div class="row flex-xl-nowrap">
			<div class="col-2 bd-sidebar">
				<form action="" class="form">
					<input name="date" class="form-control" value="<?=$cur_date;?>" onchange="form.submit();">
				</form>
				<br/>
				<div class="list-group">
					<? foreach($dates as $date){ ?>
						<a href="#<?=$date;?>" class="list-group-item"><?=$date;?></a>
					<? }; ?>
				</div>
			</div>
			<div class="col">
				<?= $table; ?>
			</div>
		</div>
	</div>


	<!-- Optional JavaScript -->
	<!-- jQuery first, then Popper.js, then Bootstrap JS -->
	<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.11.0/umd/popper.min.js" integrity="sha384-b/U6ypiBEHpOf/4+1nzFpr53nxSS+GLCkfwBdFNTxtclqqenISfwAzpKaMNFNmj4" crossorigin="anonymous"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta/js/bootstrap.min.js" integrity="sha384-h0AbiXch4ZDo7tp9hKZ4TsHbi047NrKGLO3SEJAg45jXxnGIfYzk4Si90RDIqNm1" crossorigin="anonymous"></script>
	<script src="https://cdn.rawgit.com/marcosesperon/jquery.rowspanizer.js/1323d33f/jquery.rowspanizer.min.js" type="text/javascript"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.7.1/js/bootstrap-datepicker.min.js" type="text/javascript"></script>
	<script type="text/javascript">
		$(function(){
			$('.table').rowspanizer();
		});
	</script>
</body>
</html>