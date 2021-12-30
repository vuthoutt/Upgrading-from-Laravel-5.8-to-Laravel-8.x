@extends('shineCompliance.tables.main_table', [
        'header' => ['Summary','Reference','Hazard Type','Area/floor','Room/location Reference', 'FRA', '']
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
                    {data: 'hazards_name', name: 'hazards_name'},
                    {data: 'hazards_reference', name: 'hazards_reference'},
                    {data: 'hazards_type_description', name: 'hazards_type_description'},
                    {data: 'area_reference', name: 'area_reference'},
                    {data: 'location_reference', name: 'location_reference'},
                    {data: 'total_risk', name: 'total_risk'},
                    {data: 'hidden_total_risk', name: 'hidden_total_risk'},
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
                }
            });
            $(table.table().container()).children('.row').eq(1).addClass('datatable-margin');
        });
    </script>
@endpush
