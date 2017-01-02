<html>
<head>
<title>Raspberry Pi Web Manager</title>
</head>
<body>
<?php
echo '<h1>Raspberry Pi Web Server</h1>';
//echo shell_exec('sudo add_domain.sh testing.com');
$sites = shell_exec("ls /etc/apache2/sites-available");
$sites = explode("\n",$sites);
$enabled = shell_exec("ls /etc/apache2/sites-enabled");
$enabled = explode("\n",$enabled);
foreach ($sites as $site){
	if (!in_array($site, array('default-ssl.conf', '000-default.conf',''))){
		$status = (in_array($site, $enabled))? 'disable' : 'enable';
		$site = str_replace('.conf','',$site);
		$toggle = '<form action="toggle_status.php" method="post" target="_blank">
		<input type="hidden" name="domain" value="'.$site.'"/>
		<input type="hidden" name="status" value="'.$status.'"/>
		<input type="submit" value="('.$status.')"/>
		</form>';
		echo '<p>'.$site.' '.$toggle.'</p>';
	}
}
?>
<form id="add_domain" method="post" action="add_domain.php" target="_blank">
	<input name="domain" placeholder="example.com"/>
	<input type="submit" value="add domain"/>
</form>
</body>
</html>