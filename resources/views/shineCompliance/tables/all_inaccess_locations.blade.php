@extends('shineCompliance.tables.main_table',[
        'row_col' => 'col-md-12'
    ])
@section('datatable_content')

@overwrite

@push('javascript')
    <script type="text/javascript">
        $(document).ready(function(){
            var table = $('#<?= isset($tableId) ? $tableId : '' ?>').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{!! url()->full() !!}",
                columns: [
                    {data: 'property_name', name: 'property_name'},
                    {data: 'reference', name: 'reference'},
                    {data: 'description', name: 'description'},
                    {data: 'state', name: 'state'},
                    {data: 'all_item', name: 'all_item'},
                ],
                stateSave: true,
                "order": [],
                responsive: true,
                "pagingType": "full_numbers",
                "footerCallback": function ( row, data, start, end, display ) {
                    $(this).closest('.row').next().addClass('footer-dt-table');
                },
                initComplete: function (settings, json) {
                    $('span.total-records').html(settings._iRecordsTotal);
                }
            });
            $(table.table().container()).children('.row').eq(1).addClass('datatable-margin');
        });
    </script>
@endpush
