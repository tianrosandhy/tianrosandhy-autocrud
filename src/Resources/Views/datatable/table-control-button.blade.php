<div class="btn-control pb-3">
    <div class="btn-control-inner" style="display:inline-block; padding-right:.5em;">
        <button type="button" class="btn btn-secondary btn-rounded" data-bs-toggle="modal" data-bs-target="#searchBox-{{ $hash }}"><i class="iconify" data-icon="ic:baseline-manage-search"></i> Filter Data</button>
        <button type="button" class="btn btn-warning btn-rounded btn-reset-filter" data-hash="{{ $hash }}" style="display:none">Reset Filter</button>

        @if (method_exists($context, 'exportRoute')) 
        <button type="button" class="btn btn-info btn-rounded" data-bs-toggle="modal" data-bs-target="#exportBox-{{ $hash }}">Export Data</button>
        @endif
    </div>

	@if(method_exists($context, 'batchDeleteRoute'))
		<?php
		$batch_delete_url = $context->batchDeleteRoute();
		?>
		@if(isset($batch_delete_url))
		<div class="btn-control-inner" style="display:inline-block;">
			<a href="{{ $batch_delete_url }}" class="btn btn-danger btn-rounded multi-delete batchbox" data-hash="{{ $hash }}" style="display:none;">
				<i class="iconify" data-icon="ic:outline-delete-forever"></i> Remove Selected
			</a>
		</div>
		@endif
	@endif
</div>