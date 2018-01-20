<?
require('editer/inc/tools.php');
$layout = 'ajax';
$config =Array('loc'=>'editer/inc/db.sql', 'handler'=>0);
switch(vfr('t')){
	default:
		$layout= 'master'; 
		$str = executeFile('mainPage');
		break;
	case 'directoryListing':
		$dir   = vfr('dir', '.');
		$search= vfr('search', '*');
		$files = Array();
    	$dirs  = Array();
		$filesG = glob("{$dir}/{$search}");
		foreach($filesG as $filename)
          if(!is_dir($filename)){
			$files[] =Array('name'=>$filename, 'size'=>filesize($filename), 'dir'=>is_dir($filename));
          }else{
			$dirs[] =Array('name'=>$filename, 'size'=>filesize($filename), 'dir'=>is_dir($filename));
          }
		$str = json_encode(array_merge($dirs,$files));
		break;
	case 'read':
		$file= vfr('file');
		$r['file']     = $file;
		$r['contents'] = file_get_contents($file);
		$str           = json_encode($r);
		break;
	case 'save':
		$file					= vfr('file');
		$content			= vfr('content');
    	if(!is_writable($file)){
          $str = '"File is not writable!"';
          break;
        }
		$contentOld	= file_get_contents($file);
		dbSaveFile($file, $contentOld);
		file_put_contents($file, $content);
		$str = '"Saved"';
		break;
	case 'history':
		$dir     = vfr('dir');
		$filename= vfr('filename');
		$str 			= json_encode(dbListFiles("$dir/$filename"));
		break;
	case 'loadFromHistory':
		$id  = vfr('id');
		$str = dbGetFile($id);
		break;
		
}
if($layout=='ajax'){
	header('Content-Type: application/json; charset=utf-8');
	echo $str;
}else{
	echo executeFile('design', Array("str"=>$str));
}
function dbInit(){
	Global $config;
	if(!$config['handle'])
		  $config['handle']	= new	SQLite3($config['loc']);
}
function dbGetFile($fileid){
	return qr("select * from files where id='$fileid'");
}
function dbSaveFile($file,  $contents){	Global $config;
global $config;
dbInit();
$statement = $config['handle']->prepare('INSERT INTO FILES(name, dir, ctime, content) VALUES( :name, :dir, date("now"), :contents);');
$statement->bindValue(':name', $file);
$statement->bindValue(':dir', '');
$statement->bindValue(':contents', $contents);
$result = $statement->execute();

}

function	db_query($query){	Global $config;	
	 return $config['handle']->query($query);	
}	
function	qr($query){ 
		 $result = db_query($query);
	 		return		$result->fetchArray(SQLITE3_ASSOC);	}
function	qrs($query){
		 $result = db_query($query);
		 $rs = Array();
		 while($r =	$result->fetchArray(SQLITE3_ASSOC))
		 		$rs[] = $r;
		 return $rs;}