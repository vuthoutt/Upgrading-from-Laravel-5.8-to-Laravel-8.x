@extends('shineCompliance.tables.main_table', [
        'header' => ["Client", "Shine Reference", "Link"]
    ])
@section('datatable_content')
    @if(isset($data) and count($data) > 0)
        @foreach($data as $dataRow)
{{--        <tr>--}}
{{--            <td><a href="#">{{$dataRow->name}}</a></td>--}}
{{--            @if($type == JOB_ROLE_VIEW)--}}
{{--                <td style="text-align: center;">--}}
{{--                    <div class="custom-control custom-checkbox">--}}
{{--                        <input type="checkbox" class="custom-control-input select-add-group" name="select-add-group[]" id="{{ $dataRow->id }}-{{$type}}-select-add-group" value="{{ $dataRow->id }}"--}}
{{--                            {{ ($dataRow->is_selected == 1) ? 'checked' : '' }}--}}
{{--                        >--}}
{{--                        <label class="custom-control-label" for="{{ $dataRow->id }}-{{$type}}-select-add-group"></label>--}}
{{--                    </div>--}}
{{--                </td>--}}
{{--            @endif--}}
{{--            <td style="text-align: center;">--}}
{{--                <div class="custom-control custom-checkbox">--}}
{{--                    <input type="checkbox" class="custom-control-input select-all-group" name="select-all-group[]" id="{{ $dataRow->id }}-{{$type}}-select-all-group" value="{{ $dataRow->id }}"--}}
{{--                            {{ ($dataRow->is_selected == 1) ? 'checked' : '' }}--}}
{{--                    >--}}
{{--                    <label class="custom-control-label" for="{{ $dataRow->id }}-{{$type}}-select-all-group"></label>--}}
{{--                </div>--}}
{{--            </td>--}}
{{--            <td><a href="#" class="view-client">View</a></td>--}}
{{--        </tr>--}}
        @endforeach
    @endif
@overwrite

@push('javascript')

    <script type="text/javascript">
        $(document).ready(function(){
            //
            // $.ajaxSetup({
            //     headers: {
            //         'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            //     }
            // });

            var table = $('#<?= isset($tableId) ? $tableId : '' ?>').on('preXhr.dt', function ( e, settings, data ) {
                $(this).closest('.parent-client-listing').find('.load-client-group').html('');
                $(this).closest('.parent-client-listing').find('.load-client-group').hide();
            } ).DataTable({
                processing: true,
                serverSide: true,
                "ajax": {
                    "url": "{!! route('shineCompliance.ajax_client_role.compliance', ['tab' => $type, 'id' => $job_id]) !!}",
                    "method":"GET",
                    // "data": {
                    //     "client_id": 0,
                    //     "page": 0,
                    //     "offset": 0,
                    //     "limit": 15
                    // },
                    // "dataSrc":""
                },
                {{--ajax: "{{route('shineCompliance.ajax_client_role.compliance', ['tab' => $type])}}",--}}
                columns: [
                    {data: 'name', name: 'name'},
                    {data: 'reference', name: 'reference'},
                    {data: 'view', name: 'view'},
                ],
                'columnDefs': [ {
                    'targets': [2], /* column index */
                    'orderable': false, /* true or false */
                }],
                stateSave: true,
                "order": [],
                responsive: true,
                "pagingType": "full_numbers",
                "footerCallback": function ( row, data, start, end, display ) {
                    console.log(123);
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
