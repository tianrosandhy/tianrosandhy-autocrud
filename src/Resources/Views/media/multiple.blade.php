<?php
$hash = md5(sha1(rand(1, 100000) . uniqid() . time()));
$container_hash = $hash;
$value = $value ?? [];
if(!is_array($value)){
	$value = [$value];
}
?>
<div class="media-multiple-holder" container-hash="{{ $container_hash }}">
	<div class="multi-media-container">
		@foreach($value as $img)
		<?php
		$hash = md5(sha1(rand(1, 100000) . uniqid() . time()));
		$media = Media::getByJson($img);
		?>
		<div class="square-image input-image-holder text-center" data-hash="{{ $hash }}">
			<input type="hidden" name="{{ $name }}" class="listen-image-upload" value="{{ $img }}">
			<img data-fallback="{{ defaultImage() }}" src="{{ isset($media->id) ? $media->url('thumb') : $media->fallback() }}" class="media-item">
			<a href="#" class="trigger-upload-image"></a>
			<div class="multi-closer">
				<span class="iconify" data-icon="uim:multiply"></span>
			</div>
		</div>
		@endforeach
	</div>
	<div class="square-image add">
		<div>
			<span class="iconify" data-icon="uim:plus-square"></span>
			<div>
				<small>Add Images</small>
			</div>
		</div>
	</div>
	<div class="clearfix"></div>
</div>
<template id="media-multiple-single-item-{{ $container_hash }}">
	<div class="square-image input-image-holder text-center" data-hash="CUSTOM_HASH">
		<input type="hidden" name="{{ $name }}" class="listen-image-upload">
		<img data-fallback="{{ defaultImage() }}" src="{{ defaultImage() }}" class="media-item">
		<a href="#" class="trigger-upload-image"></a>
		<div class="multi-closer remove-image">
			<span class="iconify" data-icon="uim:multiply"></span>
		</div>
	</div>
</template>