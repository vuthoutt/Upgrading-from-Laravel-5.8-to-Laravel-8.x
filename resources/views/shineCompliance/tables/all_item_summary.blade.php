@extends('shineCompliance.tables.main_table', [
        'header' => $header
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
                    {data: 'name', name: 'name'},
                    {data: 'area_reference', name: 'area_reference'},
                    {data: 'location_reference', name: 'location_reference'},
                    {data: 'product_debris', name: 'product_debris'},
                    {data: 'total_mas_risk', name: 'total_mas_risk', class: 'total_mas_risk'},
                    {data: 'hidden_total_mas_risk', name: 'hidden_total_mas_risk', visible: false},
                ],
                stateSave: true,
                aaSorting: [[0, "asc"]],
                "bAutoWidth": false,
                "columnDefs": [
                    { "type": "num", "targets": 6 },
                    {
                        targets: [ 5 ],
                        orderData: [ 6 ]
                    },
                    {
                        "targets": [ 6 ],
                        "visible": false,
                        "searchable": false
                    }
                ],
                responsive: true,
                "pagingType": "full_numbers",
                "footerCallback": function ( row, data, start, end, display ) {
                    $(this).closest('.row').next().addClass('footer-dt-table');
                },
                initComplete: function (settings, json) {
                    $('span.total-records').html(settings._iRecordsTotal);
                    $('.total_mas_risk').css('width', '100px');
                }
            });
            $(table.table().container()).children('.row').eq(1).addClass('datatable-margin');
        });
    </script>
@endpush

