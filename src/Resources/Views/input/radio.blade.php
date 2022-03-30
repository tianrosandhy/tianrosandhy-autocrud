<?php
if(isset($class)){
  $base_class = $class;
}

$cleaned_name = str_replace('[]', '', $name);
if(!isset($multiLanguage)){
  $multiLanguage = false;
}
if($multiLanguage){
  $name = $name.'['.def_lang().']';
}

if(is_array($source)){
  $data_source = $source;
}
else{
  $data_source = isset($source->output) ? $source->output : $source;
  if(is_callable($data_source)){
    $data_source = call_user_func($data_source, $data);
  }
}

//mencegah value multiple language. this input doesnt expect array value
if(is_array($value)){
  if(array_key_exists(def_lang(), $value)){
    $value = old($cleaned_name.'.'.def_lang(), $value[def_lang()]);
  }
}
else{
  $value = old($cleaned_name, $value);
}

if(!is_array($value)){
  $try_decode = json_decode($value, true);
  if($try_decode){
    $value = $try_decode;
  }
  else if(!empty($value)){
    $value = [$value];
  }
  else{
    $value = [];
  }
}
?>
<div class="box">
  @foreach($data_source as $vl => $lbl)
  <label class="radio-inline mr-2">
    <input type="{{ isset($type) ? $type : 'radio' }}" value="{{ $vl }}" name="{!! $name !!}" id="input-{{ $cleaned_name }}-{{ slugify($lbl) }}" {{ in_array($vl, $value) ? 'checked' : '' }}>
    <span>{{ $lbl }}</span>
  </label>
  @endforeach
</div>