<?php
$base_class = ['form-control'];
if(isset($attr['class'])){
  $class = $attr['class'];
}
if(isset($class)){
  $base_class = array_merge($base_class, $class);
}

$cleaned_name = str_replace('[]', '', $name);

if(!isset($type)){
  $type = Input::TYPE_TEXT;
}
if($type == Input::TYPE_TAGS){
  $type = 'text';
  $attr['data-role'] = 'tagsinput';
}

if(!isset($multiLanguage)){
  $multiLanguage = false;
}
?>
@if($multiLanguage)
  @foreach(Autocrud::langs() as $lang => $langdata)
    <?php
    if(strpos($name, '[]') !== false){
      $input_name = str_replace('[]', '['.$lang.'][]', $name);
    }
    else{
      $input_name = $name.'['.$lang.']';
    }
    ?>
    <div class="input-language" data-lang="{{ $lang }}" style="{!! Autocrud::defaultLang() == $lang ? '' : 'display:none;' !!}">
      <input type="{{ strtolower($type) }}" name="{!! $input_name !!}" class="{!! implode(' ', $base_class) !!}" {!! isset($attr) ? array_to_html_prop($attr, ['class', 'type', 'name', 'id']) : null !!} value="{{ old($cleaned_name.'.'.$lang, (isset($value[$lang]) ? $value[$lang] : null)) }}" id="input-{{ $cleaned_name }}-{{ $lang }}">
    </div>
  @endforeach
@else
  <input type="{{ strtolower($type) }}" name="{{ $name }}" class="{!! implode(' ', $base_class) !!}" {!! isset($attr) ? array_to_html_prop($attr, ['class', 'type', 'name', 'id']) : null !!} id="input-{{ $cleaned_name }}" value="{{ old($cleaned_name, isset($value) ? $value : null) }}">
@endif
