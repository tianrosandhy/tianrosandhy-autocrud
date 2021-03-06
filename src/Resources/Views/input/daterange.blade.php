<?php
$base_class = ['form-control'];
if(isset($attr['class'])){
  $class = $attr['class'];
}
if(isset($class)){
  $base_class = array_merge($base_class, $class);
}


$cleaned_name = str_replace('[]', '', $name);
$old_name = $cleaned_name;
if(!isset($multiLanguage)){
  $multiLanguage = false;
}
if($multiLanguage){
  $name = $cleaned_name.'['.def_lang().'][]';
  $old_name = $old_name.'.'.def_lang();
}

$hash = md5(rand(1, 10000) . uniqid() . time());

if(strpos($name, '[]') === false){
    $name = $name.'[]';
}

if(!isset($value)){
  $value = [];
}

if(is_string($value)){
  //convert string periode to array
  $try_decode = json_decode($value, true) ?? [];
  if(count($try_decode) == 2){
    $value = array_values($try_decode);
  }
}

$monthly = $monthly ?? false;
?>
<div class="row" daterange-holder>
  <div class="col-sm-6">
    <input type="text" class="{!! implode(' ', $base_class) !!}" autocomplete="off" name="{!! $name !!}" {!! $monthly ? 'data-month-start-range="true"' : 'data-start-range="true"' !!} data-hash="{{ $hash }}" {!! isset($attr) ? array_to_html_prop($attr, ['class', 'type', 'name', 'id']) : null !!} value="{{ old($old_name.'.0', (isset($value[0]) ? $value['0'] : ''))  }}" />
  </div>
  <div class="col-sm-6" style="border-left:1px solid #ccc;">
    <input type="text" class="{!! implode(' ', $base_class) !!}" autocomplete="off" name="{!! $name !!}" {!! $monthly ? 'data-month-end-range="true"' : 'data-end-range="true"' !!} data-hash="{{ $hash }}" {!! isset($attr) ? array_to_html_prop($attr, ['class', 'type', 'name', 'id']) : null !!} value="{{ old($old_name.'.1', (isset($value[1]) ? $value[1] : '')) }}" />
  </div>
</div>
