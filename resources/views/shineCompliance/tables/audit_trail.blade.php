@extends('shineCompliance.tables.main_table',[
        'header' => $header
    ])
@section('datatable_content')
@overwrite
@push('javascript')
    <script type="text/javascript">
        $(document).ready(function () {
            var table = $('#<?= isset($tableId) ? $tableId : '' ?>').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('shineCompliance.audit_trail') }}",
                columns: [
                    {data: 'shine_reference', name: 'shine_reference'},
                    {data: 'object_reference', name: 'object_reference'},
                    {data: 'action_type', name: 'action_type'},
                    {data: 'user_name', name: 'user_name'},
                    {data: 'date', name: 'date'},
                    {data: 'date_hour_display', name: 'date_hour_display'},
                    {data: 'comments', name: 'comments'},
                ],
                "columnDefs": [ {
                    "targets": 5,
                    "orderable": false
                } ],
                // stateSave: true,
                "order": [],
                responsive: true,
                "pagingType": "full_numbers",
                "footerCallback": function (row, data, start, end, display) {
                    $(this).closest('.row').next().addClass('footer-dt-table');
                }
            });
            $(table.table().container()).children('.row').eq(1).addClass('datatable-margin');
        });
    </script>
@endpush
