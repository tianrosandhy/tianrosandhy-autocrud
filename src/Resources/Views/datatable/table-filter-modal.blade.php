<div class="modal fade modal-pagefilter" id="searchBox-{{ $hash }}" data-target="#autocrud-table-{{ $hash }}">
	<div class="modal-dialog modal-xl">
		<div class="modal-content">
			<div class="modal-header">
				<h6>Filter Data</h6>
				<button data-bs-dismiss="modal" class="close btn-close"></button>
			</div>
			<div class="modal-body modal-searchbox">
				<!-- for datatable components -->
				<div class="search-box my-2">
					<div class="row">
						@foreach($structure as $struct)
							@if($struct->searchable() && !$struct->hideOnDatatable())
							<div class="col-lg-4 col-md-6">
								<div class="form-group">
									<label>Search {{ $struct->name() }}</label>
									@include ('autocrud::datatable.dynamic-input')
								</div>
							</div>
							@endif
						@endforeach
					</div>
				</div>

				<button type="button" class="btn btn-rounded btn-secondary btn-apply-filter"><i class="iconify" data-icon="ic:outline-filter-alt"></i> Apply Filter</button>

				<a href="#" class="btn btn-rounded btn-danger reset-filter">
					<i class="iconify" data-icon="ic:outline-filter-alt"></i>
					Reset Filter
				</a>

			</div>
		</div>
	</div>
</div>
