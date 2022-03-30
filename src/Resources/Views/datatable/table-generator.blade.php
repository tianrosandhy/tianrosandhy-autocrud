<div class="card card-body">
    @include ('autocrud::datatable.table-control-button')
    @include ('autocrud::datatable.table-filter-modal')
    @include ('autocrud::datatable.table-export-modal')

    <table class="table datatable" id="autocrud-table-{{ $hash }}" data-id="tb_data_{{ $hash }}">
        <thead>
            <tr>
                @if (method_exists($context, 'beforeTableHead'))
                    @foreach ($context->beforeTableHead() as $row)
                    <th data-field="{{ $row['field'] ?? '' }}" data-orderable="{{ false }}" data-searchable="{{ false }}">{!! $row['label'] ?? '' !!}</th>
                    @endforeach
                @endif

                @foreach($structure as $struct)
                    @if ($struct->hideOnDatatable() || !$struct->isVisible())
                        @continue
                    @endif
                    <th data-field="{{ $struct->getField() }}" data-orderable="{{ $struct->getOrderable() }}" data-searchable="{{ $struct->getSearchable() }}" data-inputtype="{{ $struct->getInputType() }}">{{ $struct->getName() }}</th>
                @endforeach
                <th data-field="action"></th>
            </tr>
        </thead>
        <tbody></tbody>    
    </table>

</div>
