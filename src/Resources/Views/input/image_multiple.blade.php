<?php
if(strpos($name, '[]') === false){
  $name = $name.'[]';
}
if(!isset($multiLanguage)){
  $multiLanguage = false;
}
if($multiLanguage){
  $name = $name.'['.def_lang().']';
}

$cleaned_name = str_replace('[]', '', $name);
if(!isset($value)){
  $value = old($cleaned_name);
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

if(is_string($value) && !empty($value)){
  $value = explode('|', $value);
}
?>
<div>
{!! Media::multiple($name, $value, $config) !!}
</div>
