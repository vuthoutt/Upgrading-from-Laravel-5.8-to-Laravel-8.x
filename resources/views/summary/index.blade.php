@extends('layouts.app')
@section('content')
@include('partials.nav', ['breadCrumb' => 'summary','data' => $summary->description, 'color' => 'red'])
<div id="overlay" style="display:none;">
    <div class="spinner"></div>
    <br/>
    Loading...
</div>
<div class="container prism-content">
    @include('shineCompliance.summary.partials._summary_button')

    <div class="d-flex" id="asdasd">
        <!-- Sidebar -->
        <div class="bg-light border-summary" id="sidebar-wrapper">
            <div class="sidebar-heading">
                <h3>Asbestos Summary</h3>
            </div>
            <div class="nav list-group list-group-flush">
                @foreach($all_summaries as $data_summary)
                    @if(\CompliancePrivilege::checkPermission(JR_REPORTING, $data_summary->id,JOB_ROLE_ASBESTOS) and \CommonHelpers::isSystemClient())
                    <a href="{{ route('summary.'.$data_summary->value) }}" class="list-group-item list-group-item-action bg-light nav-link {{ \Route::currentRouteName() == 'summary.'.$data_summary->value ? 'active' : '' }}">
                        {{ $data_summary->description ?? '' }}
                    </a>
                    @endif
                @endforeach
                @if(!\CommonHelpers::isSystemClient())
                    <a href="{{ route('summary.sampleSummary') }}" class="list-group-item list-group-item-action bg-light nav-link {{ \Route::currentRouteName() == 'summary.sampleSummary' ? 'active' : '' }}">
                        Sample Summary
                    </a>
                    @if(\Auth::user()->client_id == LIFE_ENVIRONMENTAL_CLIENT)
                        <a href="{{ route('summary.surveySummary') }}" class="list-group-item list-group-item-action bg-light nav-link {{ \Route::currentRouteName() == 'summary.surveySummary' ? 'active' : '' }}">
                            Survey Summary
                        </a>
                        <a href="{{ route('summary.projectSummary') }}" class="list-group-item list-group-item-action bg-light nav-link {{ \Route::currentRouteName() == 'summary.projectSummary' ? 'active' : '' }}">
                            Project Summary
                        </a>
                    @endif
                @endif
            </div>
        </div>
        <!-- /#sidebar-wrapper -->
        <!-- Page Content -->
        <div id="page-content-wrapper">
            <div class="container-fluid">
                <div>
                    <h3 class="text-center mt-4">{{ $summary->description }}</h3>
                    <form method="POST" action="{{ url()->current() }}" id="form-export" {{ ($summary->value == 'areaCheck') || ($summary->value == 'roomCheck') ? 'target=_blank' : '' }}>
                        <meta name="csrf-token" content="{{ csrf_token() }}" />
                        <input type="hidden" name="summary_title" value="{{ $summary->description }}">
                        @csrf
                        @yield('summary_content')
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="container" id="export-preview" style="padding-top: 150px">
    <div class="row container-fluid ">
        <iframe frameborder="0" marginheight="0" marginwidth="0" src="" style="width: 100%;" id="pdf-preview"></iframe>
    </div>
    <div class="container-fluid offset-top10">
        <a href="javascript:void(0);" class="btn  light_grey_gradient" id="btnGoBack">Go Back</a>
        <a target="_blank" class="btn light_grey_gradient" id="bttExportFile">External PDF File</a>
        <a target="_blank" class="btn light_grey_gradient" id="btnCSVFile">External CSV File</a>
    </div>
</div>
@endsection
@push('javascript')
<script>
$(document).ready(function(){
    $('#export-preview').hide();
    $('#btnGenPdf').click(function(e){
        $('.prism-content').hide();
        $('#export-preview').show();
        $('#overlay').fadeIn();
        e.preventDefault();
        var form_data = new FormData($('#form-export')[0]);
        if ($('#reason_asbestos').length) {
            var reason_asbestos = CKEDITOR.instances['reason_asbestos'].getData();
            form_data.append('reason_asbestos', reason_asbestos);
        }
        $.ajaxSetup({
          headers: {
              'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          }
        });
        $.ajax({
            url: "{{ route('ajax.summary_service') }}",
            method: 'post',
            data: form_data,
            processData: false,
            contentType: false,
            success: function(data){
                  if(data) {
                        $("#pdf-preview").attr("src", data.link);
                        $("#bttExportFile").attr("href", '/pdf/generate-pdf?file_path='+ data.html_file
                                                + '&next_number=' + data.next_number
                                                + '&summary_type=' + data.summary_type + '&property_id=' + data.property_id
                                                + '&tagheader=' + data.tagheader);
                        $('#overlay').fadeOut();
                  }
                }
        });
    })

    $('#btnGenPdfChart').click(function(e){
        $('.prism-content').hide();
        $('#export-preview').show();
        $('#overlay').fadeIn();
        e.preventDefault();
        var next_month = $('#select-quarter').find(':selected').attr('data-time-next');
        var quater_name = $('#select-quarter').find(':selected').html();

        var form_data = new FormData($('#form-export')[0]);
        form_data.append('next_month', next_month);
        form_data.append('quater_name', quater_name);
        $.ajaxSetup({
          headers: {
              'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          }
        });
        $.ajax({
            url: "{{ route('ajax.summary_service') }}",
            method: 'post',
            data: form_data,
            processData: false, contentType: false,
            success: function(data){
                  if(data) {
                        $("#pdf-preview").attr("src", data.link);
                        $("#bttExportFile").attr("href", '/pdf/generate-pdf?file_path='+ data.html_file
                                                + '&next_number=' + data.next_number
                                                + '&summary_type=' + data.summary_type + '&property_id=' + data.property_id
                                                + '&tagheader=' + data.tagheader);
                        $('#overlay').fadeOut();
                  }
                }
        });
    })

    $('#btnGoBack').click(function() {
        $('.prism-content').show();
        $('#export-preview').hide();
    })
        $('#btnCSVFile').on('click', function () {

            var csv = '';

            $("iframe").contents().find("#tableGenerate table").each(function () {
                csv += generateTableToSCV($(this));
                csv += '\n';
            });
            var pdfTitle = $.trim($("iframe").contents().find("#pdf-title").text());
            csvData = 'data:application/csv;charset=utf-8,' + encodeURIComponent(csv);

            if (navigator.userAgent.search("Chrome") >= 0) {
                $(this)
                    .attr({
                        'download': pdfTitle + '.csv',
                        'href': csvData,
                        'target': '_blank'
                    });
            } else {
                var IEwindow = window.open();
                IEwindow.document.write('sep=,\r\n' + csv);
                IEwindow.document.close();
                IEwindow.document.execCommand('SaveAs', true, pdfTitle + ".csv");
                IEwindow.close();
            }
        });

        function generateTableToSCV($table) {
        var $rows = $table.find('tr:has(td)'),
            // Temporary delimiter characters unlikely to be typed by keyboard
            // This is to avoid accidentally splitting the actual contents
            tmpColDelim = String.fromCharCode(11), // vertical tab character
            tmpRowDelim = String.fromCharCode(0), // null character

            // actual delimiter characters for CSV format
            colDelim = '","',
            rowDelim = '"\r\n"',
            // Grab text from table into CSV formatted string
            csv = '"' + $rows.map(function (i, row) {
                    var $row = $(row),
                        $cols = $row.find('td');
                    return $cols.map(function (j, col) {
                        var $col = $(col),
                            text = $col.text();
                        return text.replace('"', '""').trim(); // escape double quotes

                    }).get().join(tmpColDelim);
                }).get().join(tmpRowDelim)
                    .split(tmpRowDelim).join(rowDelim)
                    .split(tmpColDelim).join(colDelim) + '"';
        return csv;
    }
});
</script>
@endpush
