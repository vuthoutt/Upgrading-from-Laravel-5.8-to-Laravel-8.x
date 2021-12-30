<div class="{{ $row_col ?? 'col-md-12'}} pr-0 pl-0" id="accordionExample{{ isset($tableId) ? $tableId : '' }}">
    <div class="card mt-4">
        <div class="card-header table-header {{ isset($normalTable) ? 'normal-table' : ''}}">
            <input type="hidden" id="type-{{ isset($tableId) ? $tableId : '' }}" value="{{  isset($normalTable) ? 'normal-table' : false }}" />
            <input type="hidden" id="{{isset($tableId) ? $tableId : ''}}order-table" value="{{$order_table ?? false}}" />

            @if(isset($normalTable) || isset($count))
                <h6 class="table-title">{{ $title }} {{ isset($count) ? '('.$count.')' : '' }}</h6>
            @elseif(isset($notCountable))
                <h6 class="table-title">{{ $title }}</h6>
            @else
                <h6 class="table-title">{{ $title }} ({{ isset($data) ? count($data) : 0 }})</h6>
            @endif
            <div class="btn collapse-table table-collapse-button {{ ($collapsed == false ) ? '' : 'collapsed' }}" data-toggle="collapse" data-target="#collapse-{{ isset($tableId) ? $tableId : '' }}" aria-expanded="false" aria-controls="collapse-{{ isset($tableId) ? $tableId : '' }}">
                <i class="fa fa-lg " aria-hidden="true"></i>
            </div>
            @if($plus_link == true)
            <div class="btn collapse-table table-plus-button">
                @if(isset($modal_id))
                    <i class="fa fa-plus" data-toggle="modal" data-target="#{{ isset($modal_id) ? $modal_id : '' }}" aria-hidden="true"></i>
                @else
                   <a href="{{ isset($link) ? $link : '#' }}" style="text-decoration: none;color: inherit"><i class="fa fa-plus" aria-hidden="true"></i></a>
                @endif
            </div>
            @endif
        </div>
        <div id="collapse-{{ isset($tableId) ? $tableId : '' }}" class="collapse {{ ($collapsed == false ) ? 'show' : '' }}" data-parent="#accordionExample{{ isset($tableId) ? $tableId : '' }}">
            <div class="card-body" {{ isset($normalTable) ? '' : "style=padding-bottom:7px;margin-bottom:-24px !important" }} >
                <table id="{{ isset($tableId) ? $tableId : '' }}" class="table table-striped table-bordered shineDatatable {{ isset($normalTable) ? 'normal-table-content' : ''}}" style="width: 100%">
                    @if($header !== false)
                    <thead>
                        <tr>
                            @if(isset($header) and count($header) > 0)
                                @foreach($header as $head)
                                    <th>{{ $head }}</th>
                                @endforeach
                            @endif
                            @if(isset($add_select) && $add_select)
                                <th>
                                    <div class="custom-control custom-checkbox" style="text-align: center;">
                                        <input type="checkbox" class="custom-control-input" id="select_all" name="select_invoice" />
                                        <label class="custom-control-label" for="select_all"></label>
                                    </div>
                                </th>
                            @endif
                        </tr>
                    </thead>
                    @endif
                    <tbody>
                        @yield('datatable_content')
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

@push('javascript')

<script type="text/javascript">
    $(document).ready(function(){
    jQuery.extend( jQuery.fn.dataTableExt.oSort, {
        "date-uk-pre": function ( a ) {
            if (a == null || a == "") {
                return 0;
            }
            var ukDatea = a.split('/');
            return (ukDatea[2] + ukDatea[1] + ukDatea[0]) * 1;
        },

        "date-uk-asc": function ( a, b ) {
            return ((a < b) ? -1 : ((a > b) ? 1 : 0));
        },

        "date-uk-desc": function ( a, b ) {
            return ((a < b) ? 1 : ((a > b) ? -1 : 0));
        }
    } );

    var is_normal_table = document.getElementById('type-<?= isset($tableId) ? $tableId : '' ?>').value;
    var order_table = document.getElementById('{{isset($tableId) ? $tableId : ''}}order-table').value;
    if (is_normal_table == 'normal-table') {
        var table = $('#<?= isset($tableId) ? $tableId : '' ?>').on( 'processing.dt', function ( e, settings, processing ) {
            $('#processingIndicator').css( 'display', processing ? 'block' : 'none' );
        } ).DataTable({
            stateSave: true,
            "order": [],
            searching: false,
            paging:false,
            bInfo:false,
            responsive: true,
            "footerCallback": function ( row, data, start, end, display ) {
                // $(this).closest('.row').next().addClass('footer-dt-table');
            }
        });
        $(table.table().container()).children('.row').eq(1).addClass('datatable-margin');
    } else {
        //fix this one
        if (order_table == 'published') {
            var table = $('#<?= isset($tableId) ? $tableId : '' ?>').on( 'processing.dt', function ( e, settings, processing ) {
                $('#processingIndicator').css( 'display', processing ? 'block' : 'none' );
            } ).DataTable({
                stateSave: true,
                "order": [],
                responsive: true,
                "pagingType": "full_numbers",
                "footerCallback": function ( row, data, start, end, display ) {
                    $(this).closest('.row').next().addClass('footer-dt-table');
                }
            });
        } else if(order_table == 'approval') {
            var table = $('#<?= isset($tableId) ? $tableId : '' ?>').on( 'processing.dt', function ( e, settings, processing ) {
                $('#processingIndicator').css( 'display', processing ? 'block' : 'none' );
            } ).DataTable({
                stateSave: true,
                aaSorting: [[5, "desc"]],
                responsive: true,
                "pagingType": "full_numbers",
                "footerCallback": function ( row, data, start, end, display ) {
                    $(this).closest('.row').next().addClass('footer-dt-table');
                }
            });
        } else if(order_table == 'lastDate') {
            var table = $('#<?= isset($tableId) ? $tableId : '' ?>').on( 'processing.dt', function ( e, settings, processing ) {
                $('#processingIndicator').css( 'display', processing ? 'block' : 'none' );
            } ).DataTable({
                stateSave: true,
                aaSorting: [[6, "asc"]],
                responsive: true,
                "pagingType": "full_numbers",
                "footerCallback": function ( row, data, start, end, display ) {
                    $(this).closest('.row').next().addClass('footer-dt-table');
                }
            });
        } else if(order_table == 'survey-table') {
            var table = $('#<?= isset($tableId) ? $tableId : '' ?>').on( 'processing.dt', function ( e, settings, processing ) {
                $('#processingIndicator').css( 'display', processing ? 'block' : 'none' );
            } ).DataTable({
                stateSave: true,
                aaSorting: [[2, "asc"]],
                responsive: true,
                "pagingType": "full_numbers",
                "footerCallback": function ( row, data, start, end, display ) {
                    $(this).closest('.row').next().addClass('footer-dt-table');
                }
            });
        } else if(order_table == 'wr-table') {
            var table = $('#<?= isset($tableId) ? $tableId : '' ?>').on( 'processing.dt', function ( e, settings, processing ) {
                $('#processingIndicator').css( 'display', processing ? 'block' : 'none' );
            } ).DataTable({
                stateSave: true,
                aaSorting: [[4, "asc"]],
                responsive: true,
                "pagingType": "full_numbers",
                "footerCallback": function ( row, data, start, end, display ) {
                    $(this).closest('.row').next().addClass('footer-dt-table');
                }
            });
        } else if(order_table == 'survey-approve-table') {
            var table = $('#<?= isset($tableId) ? $tableId : '' ?>').on( 'processing.dt', function ( e, settings, processing ) {
                $('#processingIndicator').css( 'display', processing ? 'block' : 'none' );
            } ).DataTable({
                stateSave: true,
                aaSorting: [[1, "asc"]],
                responsive: true,
                "pagingType": "full_numbers",
                "footerCallback": function ( row, data, start, end, display ) {
                    $(this).closest('.row').next().addClass('footer-dt-table');
                }
            });
        } else if(order_table == 'survey-approve-table-2') {
            var table = $('#<?= isset($tableId) ? $tableId : '' ?>').on( 'processing.dt', function ( e, settings, processing ) {
                $('#processingIndicator').css( 'display', processing ? 'block' : 'none' );
            } ).DataTable({
                stateSave: true,
                aaSorting: [[3, "desc"]],
                responsive: true,
                "pagingType": "full_numbers",
                columnDefs: [
                   { type: 'date-uk', targets: 3 }
                ],
                "footerCallback": function ( row, data, start, end, display ) {
                    $(this).closest('.row').next().addClass('footer-dt-table');
                }
            });
        } else if(order_table == 'my-project') {
            var table = $('#<?= isset($tableId) ? $tableId : '' ?>').on( 'processing.dt', function ( e, settings, processing ) {
                $('#processingIndicator').css( 'display', processing ? 'block' : 'none' );
            } ).DataTable({
                stateSave: true,
                aaSorting: [[3, "desc"]],
                responsive: true,
                "pagingType": "full_numbers",
                "footerCallback": function ( row, data, start, end, display ) {
                    $(this).closest('.row').next().addClass('footer-dt-table');
                }
            });
        } else if(order_table == 'mas-risk') {
            var table = $('#<?= isset($tableId) ? $tableId : '' ?>').on( 'processing.dt', function ( e, settings, processing ) {
                $('#processingIndicator').css( 'display', processing ? 'block' : 'none' );
            } ).DataTable({
                stateSave: true,
                aaSorting: [[0, "asc"]],
                responsive: true,
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
                "pagingType": "full_numbers",
                "footerCallback": function ( row, data, start, end, display ) {
                    $(this).closest('.row').next().addClass('footer-dt-table');
                }
            });
        } else if(order_table == 'mas-risk-child') {
            var table = $('#<?= isset($tableId) ? $tableId : '' ?>').on('processing.dt', function (e, settings, processing) {
                $('#processingIndicator').css('display', processing ? 'block' : 'none');
            }).DataTable({
                stateSave: true,
                aaSorting: [[0, "asc"]],
                responsive: true,
                "columnDefs": [
                    {"type": "num", "targets": 5},
                    {
                        targets: [4],
                        orderData: [5]
                    },
                    {
                        "targets": [5],
                        "visible": false,
                        "searchable": false
                    }
                ],
                "pagingType": "full_numbers",
                "footerCallback": function (row, data, start, end, display) {
                    $(this).closest('.row').next().addClass('footer-dt-table');
                }
            });
        } else if (order_table == 'project-doc') {
            var table = $('#<?= isset($tableId) ? $tableId : '' ?>').on( 'processing.dt', function ( e, settings, processing ) {
                $('#processingIndicator').css( 'display', processing ? 'block' : 'none' );
            } ).DataTable({
                stateSave: true,
                aaSorting: [[0, "asc"]],
                responsive: true,
                "pagingType": "full_numbers",
                "footerCallback": function ( row, data, start, end, display ) {
                    $(this).closest('.row').next().addClass('footer-dt-table');
                }
            });

            var completedTemplate = '<label class="offset-md-3 col-md-3 mr-1 col-form-label text-right font-weight-bold">\n' +
                    @if (($canCompletedStage ?? false))
                    '    {{ $stage_completed_label ?? '' }}\n'+
                    @else
                    '  <span style="color: lightgrey;"> {{ $stage_completed_label ?? '' }} </span> \n'+
                    @endif
                '</label>\n' +
                '<div class="col-md-2 mt-1 ml-1">\n' +
                '    <label class="switch">\n' +
                @if(($canCompletedStage ?? false) && ($check_user ?? false))
                '        <input type="checkbox" name="{{ isset($tableId) ? $tableId : '' }}-completed" class="primary stage-completed" id="{{ isset($tableId) ? $tableId : '' }}-completed" {{ ($project->progress_stage ?? 0) == ($doc_cat ?? 1) ? '' : 'checked' }}>\n' +
                @endif
                '        <span class="slider round" id=""></span>\n' +
                '    </label>\n' +
            $(table.table().container()).find('.dataTables_filter').first().addClass('row');
            $(completedTemplate).prependTo($(table.table().container()).find('.dataTables_filter'));

            $('body').on('click', '#select_all', function() {
                // Get all rows with search applied
                var rows = table.rows({ 'search': 'applied' }).nodes();
                // Check/uncheck checkboxes for all rows in the table
                $('input[type="checkbox"]', rows).prop('checked', this.checked);

            });
            $('#accordionExample{{ isset($tableId) ? $tableId : '' }}').on('change', '.stage-completed', function (e) {
                console.log($(this).is(':checked'));

                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                    }
                });
                $.ajax({
                    url: "{{ route('project.post_change_progress', $project->id ?? 0) }}",
                    method: 'post',
                    async: false,
                    data: {progress_stage: '{{ $doc_cat ?? 0 }}', is_completed: $(this).is(':checked') ? 1 : 0},
                    success: function(data){
                        location.reload();
                    }
                });
            });
        } else {
            var table = $('#<?= isset($tableId) ? $tableId : '' ?>').on( 'processing.dt', function ( e, settings, processing ) {
                $('#processingIndicator').css( 'display', processing ? 'block' : 'none' );
            } ).DataTable({
                stateSave: true,
                aaSorting: {!! $order_table ?? '[[0, "asc"]]' !!},
                responsive: true,
                "pagingType": "full_numbers",
                "footerCallback": function ( row, data, start, end, display ) {
                    $(this).closest('.row').next().addClass('footer-dt-table');
                }
            });

            $('body').on('click', '#select_all', function() {
                // Get all rows with search applied
                var rows = table.rows({ 'search': 'applied' }).nodes();
                // Check/uncheck checkboxes for all rows in the table
                $('input[type="checkbox"]', rows).prop('checked', this.checked);

            });
        }
        $(table.table().container()).children('.row').eq(1).addClass('datatable-margin');
    }

});
</script>
@endpush
