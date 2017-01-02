<?php

$domain = $_POST['domain'];
echo '<p>Creating folder structure...</p>';
echo '<ul id="files">';
echo '<li>';
$vhost = add_dir("/var/www/vhosts/$domain", $domain);
echo '<ul>';
	echo '<li>';
	$http = add_dir($vhost.'/http', 'HTTP');
		echo '<ul>';
			echo '<li>';
			$css = add_dir($http.'/css', 'CSS');
			echo '</li>';
			echo '<li>';
			$sass = add_dir($http.'/sass', 'SASS');
			echo '</li>';
			echo '<li>';
			$js = add_dir($http.'/js', 'JS');
			echo '</li>';
			echo '<li>';
			$img = add_dir($http.'/img', 'IMG');
			echo '</li>';
			echo '<li>';
			$img = add_dir($http.'/favicons', 'FAVICONS');
			echo '</li>';
		echo '</ul>';
	echo '</li>';
	echo '<li>';
	$logs = add_dir($vhost.'/logs', 'LOGS');
	echo '</li>';
echo '</ul>';
echo '</li>';
echo '<p>Changing folder permissions...</p>';
//temporary
shell_exec("sudo chown -R pi:www-data $vhost");
shell_exec("sudo chmod -R 775 $vhost");
echo '<p>Adding default files...</p>';
$i_content = str_replace('[[DOMAIN]]',$domain,file_get_contents('default_files/index.php'));

$index = write_file($http.'/index.php',$i_content,'index.php');
echo '<br />';
$sass_content=file_get_contents('default_files/style.sass');
$style = write_file($sass.'/style.sass',$sass_content,'style.sass');
echo '<br />';
$scripts = write_file($js.'/scripts.js','','scripts.js');
$compass_config = file_get_contents('default_files/config.rb');
echo '<br />';
$compass = write_file($http.'/config.rb', $compass_config, 'config.rb');
//correct permissions (create ftp user in here somewhere)
shell_exec("sudo chown -R pi:piserv $vhost");
shell_exec("sudo chmod -R 755 $vhost");

echo '<p>Creating hostfile...</p>';
echo n_2_p(shell_exec("sudo new_host.sh $domain"));
echo '<p>Enabling site</p>';
echo n_2_p(shell_exec("sudo enable_site.sh $domain"));
echo '<a href="//'.$domain.'" target="_blank">View the site</a>';
?>
<?php 
function add_dir($dir, $name = null){
	if (!$name){$name = $dir;}
	if (file_exists($dir)){echo $name;return $dir;}
	shell_exec("sudo mkdir $dir");
	//test if successful (shell_exec returns null)
	if (file_exists($dir)){
		echo $name;
		return $dir;
	}else{
	 echo 'Could not create '.$dir;
		return false;
	}
}
function write_file($file, $content, $name = null){
	if (!$name){$name = $file;}
	if (file_exists($file)){echo $name;return $file;}
	shell_exec("sudo echo '$content' >> $file");
	if (file_exists($file)){
		echo $name;
		return $file;
	}else{
	 echo 'Could not create '.$file;
		return false;
	}
	
}
function n_2_p($text){
$para = explode("\n", $text);
return '<p>'.implode("</p><p>",$para);
}
?>