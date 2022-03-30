<?php
if(!isset($multiLanguage)){
  $multiLanguage = false;
}
if($multiLanguage){
  $name = $name.'['.def_lang().']';
}
if(!isset($value)){
  $value = null;
}

//mencegah value multiple language. this input doesnt expect array value
if(is_array($value)){
  if(array_key_exists(def_lang(), $value)){
    $value = $value[def_lang()];
  }
}

$config = [
  'value' => old($name, (isset($value) ? $value : null)),
  'name' => $name,
  'horizontal' => true
];

if(isset($path)){
  $config['path'] = $path;
}
$accept = $attr['accept'] ?? null;

$max_size = (file_upload_max_size() / 1024 /1024);
$type = $type ?? 'single';
?>
@if($type == 'single')
  @include ('autocrud::upload-file.file-dropzone')
@else
  @include ('autocrud::upload-file.file-dropzone-multiple')
@endif