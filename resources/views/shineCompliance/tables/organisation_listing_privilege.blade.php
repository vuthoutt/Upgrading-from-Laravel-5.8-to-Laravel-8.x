@extends('shineCompliance.tables.main_table', [
        'header' => ["Client", "Shine Reference", "Client Type", "Details", "Policy Documents", "Departments", "Training Records Table"]
    ])
@section('datatable_content')
@overwrite

@push('javascript')

    <script type="text/javascript">
        $(document).ready(function(){
            console.log('#<?= isset($tableId) ? $tableId : '' ?>', 3333);
            var table = $('#<?= isset($tableId) ? $tableId : '' ?>').on('preXhr.dt', function ( e, settings, data ) {
                $(this).closest('.parent-organisation-listing').find('.save-organisation-listing').hide();
            } ).DataTable({
                processing: true,
                serverSide: true,
                "ajax": {
                    "url": "{!! route('shineCompliance.ajax_organisation_role.compliance', ['tab' => $type, 'id' => $job_id]) !!}",
                    "method":"GET",
                },
                columns: [
                    {data: 'name', name: 'name'},
                    {data: 'reference', name: 'reference'},
                    {data: 'client_type', name: 'client_type'},
                    {data: 'details', name: 'details'},
                    {data: 'policy', name: 'policy'},
                    {data: 'departments', name: 'departments'},
                    {data: 'training_records', name: 'training_records'},
                ],
                'columnDefs': [ {
                    'targets': [3,4,5,6], /* column index */
                    'orderable': false, /* true or false */
                }],
                stateSave: true,
                "order": [],
                responsive: true,
                "pagingType": "full_numbers",
                "footerCallback": function ( row, data, start, end, display ) {
                    $(this).closest('.parent-organisation-listing').find('.save-organisation-listing').show();
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
