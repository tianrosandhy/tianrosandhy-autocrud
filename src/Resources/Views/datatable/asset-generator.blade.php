<script type="text/javascript">
if (typeof tb_data == 'undefined') {
    tb_data = {};
}

$(function(){

    tb_data['{{ $hash }}'] = $("table.datatable#autocrud-table-{{ $hash }}").DataTable({
        'processing': true,
        'serverSide': true,
        'searching': false,
        'filter': false,
        'stateSave': true,
        'ajax': {
            type : 'POST',
            url	: '{{ $context->datatableRoute() }}',
            dataType : 'json',
            data : function(data){
                {!! $context->generateSearchQuery() !!}
                data._token = '{{ csrf_token() }}';
                data.table_hash = '{{ $hash }}';
            },
        },
        'createdRow': function( row, data, dataIndex ) {
            // Set the data-status attribute, and add a class
            $( row ).addClass('close-target');
        },

        'drawCallback': function(settings) {
            if (typeof afterDatatableLoad == 'function') {
                afterDatatableLoad();
            }
            refreshPlugins();
        },
		'columns' : [
			{!! $context->datatableColumns() !!}
		],
		'columnDefs' : [
			{!! $context->datatableOrderable() !!}
		],
		"aaSorting": [{!! $context->datatableDefaultOrder() !!}],
    });

    $("#searchBox-{{ $hash }} input, #searchBox-{{ $hash }} select").on('keyup', function(e){
        if(e.which == 13){
            refreshDataTable{{ $hash }}();
            $("#searchBox-{{ $hash }}").modal('hide');
        }
    });

    $("#searchBox-{{ $hash }} .btn-apply-filter").on('click', function(e){
        e.preventDefault();
        refreshDataTable{{ $hash }}();
        $("#searchBox-{{ $hash }}").modal('hide');
        $(".btn-reset-filter[data-hash='{{ $hash }}']").show();
    });

    $("#searchBox-{{ $hash }} .reset-filter").on('click', function(e){
        e.preventDefault();
        $(this).closest('#searchBox-{{ $hash }}').find('input, select').val('').trigger('change');
        $("#searchBox-{{ $hash }}").modal('hide');
        $(".btn-reset-filter[data-hash='{{ $hash }}']").hide();
        refreshDataTable{{ $hash }}();
    });

    $(".btn-reset-filter[data-hash='{{ $hash }}']").on('click', function(){
        $("#searchBox-{{ $hash }} .reset-filter").trigger('click');
    });

    $(".modal-pagefilter#searchBox-{{ $hash }}").on('shown.bs.modal', function(){
        if (typeof afterDatatableFilterLoad == 'function') {
            afterDatatableFilterLoad();
        }
        refreshPlugins();        
    });

	$(document).on('change', '.checker-all-{{ $hash }}', function(){
		cond = $(this).is(':checked');
        console.log(cond, $(".checker-{{ $hash }}"));
		$(".checker-{{ $hash }}").each(function(){
			$(this).prop('checked', cond);
		});
		toggleBatchMode{{ $hash }}();
	});

	$(document).on('change', '.checker-{{ $hash }}', function(){
		toggleBatchMode{{ $hash }}();
	});

	$(".multi-delete").on('click', function(e){
		e.preventDefault();
		output = '<p>Are you sure? Once deleted, you will not be able to recover the data</p><button class="btn btn-primary" data-dismiss="modal">Cancel</button> <button class="btn btn-danger" onclick="runRemoveBatch{{ $hash }}()">Yes, Delete</button>';
		toastr.info(output);
	});

});

function refreshDataTable{{ $hash }}(){
	tb_data['{{ $hash }}'].ajax.reload();
}

function toggleBatchMode{{ $hash }}(){
	cond = false;
	$(".checker-{{ $hash }}").each(function(){
		if($(this).is(':checked')){
			cond = true;
		}
	});

	if(cond){
		//toggle down
		$(".batchbox[data-hash='{{ $hash }}']").slideDown();
	}
	else{
		//toggle up
		$(".batchbox[data-hash='{{ $hash }}']").slideUp();
		$(".checker-all-{{ $hash }}").prop('checked', false);
	}
}

function getTableCheckedID(hash){
    ids = [];
    $(".checker-" + hash).each(function(){
		if($(this).is(':checked')){
			ids.push($(this).attr('data-id'));
		}
    });
    return ids;
}

function runRemoveBatch{{ $hash }}(){
	//prepare selected ids
    ids = getTableCheckedID('{{ $hash }}');

	if(ids.length > 0){
		$.ajax({
			url : $(".multi-delete").attr('href'),
			type : 'POST',
			dataType : 'json',
			data : {
				_token : window.CSRF_TOKEN,
				list_id : ids
			},
			success : function(resp){
				if(resp.type == 'success'){
					toastr.success(resp.message);
					//refresh datatable
					refreshDataTable{{ $hash }}();
				}
				else{
                    // generic success response
                    toastr.success("Data has been deleted successfully");
				}
                $(".checker-all-{{ $hash }}").prop('checked', false);
                $(".batchbox[data-hash='{{ $hash }}']").slideUp();
			},
			error : function(resp){
                autoCrudErrorHandling(resp);
			}
		});			
	}
	else{
		toastr.error('No data selected');
	}
}
</script>