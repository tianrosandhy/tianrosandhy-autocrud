<div class="modal fade modal-pagefilter" id="exportBox-{{ $hash }}" data-target="#autocrud-table-{{ $hash }}">
	<div class="modal-dialog modal-xl">
		<div class="modal-content">
			<div class="modal-header">
				<h6>Export Data</h6>
				<button data-bs-dismiss="modal" class="close btn-close"></button>
			</div>
			<div class="modal-body modal-searchbox">
				<!-- for datatable export components -->
                <form action="{{ $context->exportRoute() }}" method="post">
                    @csrf
                    <div class="export-filter-box my-2">
                        <div class="row">
                            @foreach($structure as $struct)
                                @if($struct->searchable() && !$struct->hideOnExport())
                                <div class="col-lg-4 col-md-6">
                                    <div class="form-group">
                                        <label>Filter {{ $struct->name() }}</label>
                                        @include ('autocrud::datatable.dynamic-input', ['name' => 'keywords'])
                                    </div>
                                </div>
                                @endif
                            @endforeach
                        </div>
                    </div>

                    <button type="submit" class="btn btn-rounded btn-secondary btn-apply-filter"><i class="iconify" data-icon="ic:outline-filter-alt"></i> Export Data</button>
                </form>
			</div>
		</div>
	</div>
</div>
