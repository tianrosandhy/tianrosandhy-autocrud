<?php
if(!isset($multiLanguage)){
  $multiLanguage = false;
}
if($multiLanguage){
  $name = $name.'['.def_lang().']';
}
if(!isset($value)){
  $value = old($name);
}

$config = [];
if(isset($path)){
  $config['path'] = $path;
}

//mencegah value multiple language. this input doesnt expect array value
if(is_array($value)){
	if(array_key_exists(def_lang(), $value)){
		$value = $value[def_lang()];
	}
}
?>
{!! Media::single($name, $value, $config) !!}