<!-- Modal -->
<div class="modal fade" id="{{ isset($modal_id) ? $modal_id : '' }}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header {{ isset($color) ? CommonHelpers::getNavColor($color) : 'red_gradient' }}">
                <h5 class="modal-title" id="exampleModalLabel">shineReflect  - {{ $title ?? '' }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ $url }}" method="POST" enctype="multipart/form-data" id="form_{{ $contractor_key}}">
            @csrf
            <div class="modal-body" style="padding-left: 30px;min-height:140px ">
                <input type="hidden" class="form-control" name="project_id"  value="{{ $project->id ?? null }}">
                <input type="hidden" class="form-control" name="doc_cat"  value="{{ $doc_cat ?? null }}">
                <input type="hidden" class="form-control" name="contractor_key"  value="{{ ($doc_cat == CONTRACTOR_DOC_CATEGORY) ? $contractor_key : \Auth::user()->client_id }}">
                <input type="hidden" class="form-control" name="client_id"  value="{{ $project->client_id }}">
                <input type="hidden" class="form-control" name="added_by"  value="{{ \Auth::user()->id }}">
                @include('form_search.search_document',['title' => 'Document Title:', 'name' => 'name','id' => 'form-name'.$contractor_key , 'width' => 7,'width_label' => 4 , 'data' => '', 'required' => true, 'required_html' => 'required'])
                <span class="invalid-feedback font-weight-bold modal-err-text" role="alert" id="name-err{{ $contractor_key ?? '' }}"></span>
                @if($doc_types)
                @include('forms.form_dropdown',['title' => 'Document Type:', 'data' => $doc_types ,'width_label' => 4, 'width' => 7, 'name' => 'type', 'key'=> 'id', 'value'=>'doc_type', 'compare_value' => '' ])
                @endif
                <div class="row register-form">
                    <label  class="col-md-4 col-form-label text-md-left font-weight-bold">Document Value:</label>
                    <div class="col-md-3">
                        <div class="form-group">
                             <input class="form-control" type="text" name="mark_value" id="mark_value{{ $contractor_key ?? '' }}" placeholder="&pound;00.00"/>
                             <input type="hidden" name="doc_value" id="dcValue{{ $contractor_key ?? '' }}"/>
                        </div>
                    </div>
                </div>
                <div class="row register-form">
                    <label class="col-md-4 font-weight-bold" for="document_file">Document:</label>
                    <input class="col-md-8" type="file" class="form-control-file" id="form-document_file{{ $contractor_key ?? '' }}" name="document_file" value="" >
                    <span class="invalid-feedback font-weight-bold modal-err-text" role="alert" id="document_file-err{{ $contractor_key ?? '' }}"></span>
                    </input>
                </div>
                @if( (\Auth::user()->client_type == 0 || \Auth::user()->client_id == $project->client_id) )
                    @include('forms.form_datepicker',['title' => 'Due Date:', 'name' => 'deadline', 'width_label' => 4, 'id' => 'deadline'.( $contractor_key ?? '') ])
                @endif
                 @if( (\Auth::user()->client_type == 0 || \Auth::user()->client_id == $project->client_id)
                and ( in_array($doc_cat, [TENDER_DOC_CATEGORY,PLANNING_DOC_CATEGORY,PRE_START_DOC_CATEGORY,SITE_RECORDS_DOC_CATEGORY,COMPLETION_DOC_CATEGORY]) ) )
                    <div class="row register-form">
{{--                    @if(!is_null($project_contractors))--}}
{{--                        <label class="col-md-4 font-weight-bold">Contractor Permissions:</label>--}}
{{--                        <div class="col-md-8">--}}
{{--                            @foreach($project_contractors as $key => $contractor)--}}
{{--                                <div class="custom-control custom-checkbox">--}}
{{--                                    <input type="checkbox" class="custom-control-input" id="{{ $contractor_key ?? '' }}contractor{{ $key }}" name="contractors[{{ $key }}]" value="{{ $contractor->id }}">--}}
{{--                                    <label class="custom-control-label" for="{{ $contractor_key ?? '' }}contractor{{ $key }}">{{ $contractor->name }}</label>--}}
{{--                                </div>--}}
{{--                            @endforeach--}}
{{--                        </div>--}}
{{--                    @endif--}}
                    </div>
                @endif
            </div>
            <div class="mb-4" style="text-align: center;">
                <button type="button" class="btn light_grey_gradient" data-dismiss="modal">Cancel</button>
                <button id="add_document_{{ $contractor_key ?? '' }}" class="btn light_grey_gradient shine_submit_modal" >Add</button>
            </div>
            </form>
        </div>
    </div>
</div>
@push('javascript')

<script type="text/javascript">
$(document).ready(function () {
        $('#mark_value{{ $contractor_key ?? '' }}').change(function () {
            var num = $(this).val();
            if(num.length == 0){
                num = '0';
            }
            num = addPeriod(num);
            $(this).val("£" + num);

        })

        function addPeriod(nStr) {
            //Get only Number + add to value
            var pattern = /[0-9]{1,3}(,\d{3})*(\.\d{2})?/g;
            nStr = nStr.match(pattern);

            nStr += '';
            x = nStr.split('.');
            x1 = x[0];
            x2 = x.length > 1 ? '.' + x[1] : '.00';
            //Add decimal
            x1 = x1.replace(/\,/g, '');
            x2 = x2.match(/\.\d{2}/);
            $('#dcValue{{ $contractor_key ?? '' }}').val(x1 + x2);

            var rgx = /(\d+)(\d{3})/;
            while (rgx.test(x1)) {
                x1 = x1.replace(rgx, '$1' + ',' + '$2');
            }
            return x1 + x2;
        }

        $('#add_document_{{ $contractor_key ?? '' }}').click(function(e){
            e.preventDefault();
            $('.invalid-feedback').html("");
            $('#form-plan_document').removeClass('is-invalid');
            $('#form-name').removeClass('is-invalid');
            var deadline = $('#deadline{{ $contractor_key ?? '' }}').val();

            // if (deadline === undefined) {
            //     deadline = '01/01/1970';
            // }
            var form_data = new FormData($('#form_{{ $contractor_key ?? '' }}')[0]);
            form_data.append('deadline', deadline);

            $.ajaxSetup({
               headers: {
                   'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
               }
             });
            $.ajax({
               url: "{{ $url }}",
               method: 'post',
                contentType: false,
                processData: false,
                async: false,
               dataType: "json",
               data: form_data,
               success: function(data){
                     if(data.errors) {
                         $.each(data.errors, function(key, value){
                             $('#'+key+'-err{{ $contractor_key ?? '' }}').html(value[0]);
                             $('#form-'+key+'{{ $contractor_key ?? '' }}').addClass('is-invalid');
                             $('.invalid-feedback').show();
                         });
                     } else {
                        location.reload();
                     }
                 }
               });
            });

        $("#{{ isset($modal_id) ? $modal_id : '' }}").on("hidden.bs.modal", function () {
            $('.invalid-feedback').hide();
            $('#form-name{{ $contractor_key ?? '' }}').removeClass('is-invalid');
            $('#form-document_file{{ $contractor_key ?? '' }}').removeClass('is-invalid');
        });
    });
</script>
@endpush
