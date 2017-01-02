<?php
$domain = $_POST['domain'];
$status = $_POST['status'];
echo '<h1>'.$status.' '.$domain.'</h1>';
if ($status === 'enable'){
	echo shell_exec("sudo enable_site.sh $domain");
}elseif ($status==='disable'){
	echo shell_exec("sudo disable_site.sh $domain");
}
//header('location:/');
?>