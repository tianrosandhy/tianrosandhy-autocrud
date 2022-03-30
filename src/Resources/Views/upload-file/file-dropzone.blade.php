<?php
if(!isset($hash)){
	$hash = sha1(md5(time() . rand(1, 10000000) . uniqid() ));
}
if(!isset($name)){
	$name = 'image';
}
?>
<input type="hidden" data-hash="{{ $hash }}" name="{{ $name }}" class="dropzone_uploaded listen_uploaded_file" value="{{ isset($value) ? $value : '' }}">
<div class="row">
	<div class="{{ isset($horizontal) ? 'col-sm-6' : 'col-sm-12' }}">
		<div style="padding-bottom:.5em;">
			<?php
			$max_size = (file_upload_max_size(config('autocrud.max_filesize.file.file')) / 1024 /1024);
			?>
			<span style="opacity:.5; font-size:.7em; padding:0 .75em;">Maximum Upload Size : {{ number_format($max_size, 2) }} MB</span>
		</div>
		<div class="dropzone custom-dropzone dz-clickable filedropzone" data-hash="{{ $hash }}" upload-limit="{{ intval($max_size) }}" {!! isset($attr['accept']) ? 'accept="'.$attr['accept'].'"' : '' !!} data-target="{{ route('autocrud.post-document') }}"></div>
	</div>
	<div class="{{ isset($horizontal) ? 'col-sm-6' : 'col-sm-12' }}">
		<div class="uploaded-holder" data-hash="{{ $hash }}">
			@if(isset($value))
				<?php
				$parse = json_decode($value, true);
				$filepath = null;
				$filename = null;
				if(!$parse && strlen($value) > 0){
					$split = explode('/', $value);
					if(count($split) == 1){
						// try spliting with backslash
						$split = explode('\\', $value);
					}

					$filename = $split[count($split)-1];
					$filepath = Storage::url($value);
				}
				else{
					$filepath = $parse['path'] ?? null;
					$filename = $parse['filename'] ?? null;
				}
				?>
				@if(isset($filename))
					<div class="uploaded">
						<a href="{{ $filepath ?? '#' }}" download class="file-alias">{{ $filename }}</a><span class="remove-asset-file" data-hash="{{ $hash }}">&times;</span>
					</div>
				@endif
			@endif
		</div>		
	</div>
</div>
