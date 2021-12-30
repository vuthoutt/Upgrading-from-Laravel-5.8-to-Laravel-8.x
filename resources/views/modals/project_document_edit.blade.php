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
            <form action="{{ $url }}" method="POST" enctype="multipart/form-data" id="form_{{ $unique_value ?? '' }}">
            @csrf
            <div class="modal-body" style="padding-left: 30px;min-height:140px ">
                <input type="hidden" class="form-control" name="project_id"  value="{{ $project->id ?? null }}">
                <input type="hidden" class="form-control" name="id"  value="{{ $data->id ?? null }}">
                <input type="hidden" class="form-control" name="doc_cat"  value="{{ $doc_cat ?? null }}">
                <input type="hidden" class="form-control" name="contractor_key"  value="{{ ($doc_cat == 5) ? $contractor_key : \Auth::user()->client_id }}">
                <input type="hidden" class="form-control" name="client_id"  value="{{ $project->client_id }}">
                <input type="hidden" class="form-control" name="added_by"  value="{{ \Auth::user()->id }}">
                <input type="hidden" class="form-control" name="document_present"  value="{{ $data->document_present }}">

                @include('form_search.search_document',['title' => 'Document Title:', 'name' => 'name','id' => 'form-name'.$unique_value , 'data' => $data->name])
                <span class="invalid-feedback font-weight-bold modal-err-text" role="alert" id="name-err{{ $unique_value ?? '' }}"></span>
                @include('forms.form_dropdown',['title' => 'Document Type:', 'data' => $doc_types ,'width_label' => 4, 'width' => 7, 'name' => 'type', 'key'=> 'id', 'value'=>'doc_type', 'compare_value' => $data->type ])
                <div class="row register-form">
                    <label  class="col-md-4 col-form-label text-md-left font-weight-bold">Document Value:</label>
                    <div class="col-md-3">
                        <div class="form-group">
                             <input class="form-control" type="text" name="mark_value" id="edit_mark_value{{ $unique_value ?? '' }}" value="{{ number_format($data->value, 2) }}" placeholder="&pound;00.00"/>
                             <input type="hidden" name="doc_value" id="edit_dcValue{{ $unique_value ?? '' }}"/>
                        </div>
                    </div>
                </div>
                <div class="row register-form">
                    <label class="col-md-4 font-weight-bold" for="document_file">Document:<span style="color: red;"> *</label>
                    <input class="col-md-8" type="file" class="form-control-file" id="form-document_file{{ $unique_value ?? '' }}" name="document_file" value="">
                    <span class="invalid-feedback font-weight-bold modal-err-text" role="alert" id="document_file-err{{ $unique_value ?? '' }}"></span>
                    </input>
                </div>

                @if( (\Auth::user()->client_type == 0 || \Auth::user()->client_id == $project->client_id))
                    @include('forms.form_datepicker',['title' => 'Due Date:', 'name' => 'deadline_picker', 'width_label' => 4, 'id' => 'deadline'.( $unique_value ?? '') , 'data' => \CommonHelpers::convertTimeStampToTime($data->deadline) ])
                @endif
                @if( (\Auth::user()->client_type == 0 || \Auth::user()->client_id == $project->client_id) and ( in_array($doc_cat, [TENDER_DOC_CATEGORY,PLANNING_DOC_CATEGORY,PRE_START_DOC_CATEGORY,SITE_RECORDS_DOC_CATEGORY,COMPLETION_DOC_CATEGORY]) ) )
                    <div class="row register-form">
{{--                    @if(!is_null($project_contractors))--}}
{{--                        <label class="col-md-4 font-weight-bold">Contractor Permissions:</label>--}}
{{--                        <div class="col-md-8">--}}
{{--                            @foreach($project_contractors as $key => $contractor)--}}
{{--                                <div class="custom-control custom-checkbox">--}}
{{--                                    <input type="checkbox" class="custom-control-input" id="{{ $unique_value ?? '' }}edit_contractor{{ $key }}" name="contractors[{{ $key }}]" value="{{ $contractor->id }}" {{ in_array($contractor->id, $data->contractor_array) ? 'checked' : '' }}>--}}
{{--                                    <label class="custom-control-label" for="{{ $unique_value ?? '' }}edit_contractor{{ $key }}">{{ $contractor->name }}</label>--}}
{{--                                </div>--}}
{{--                            @endforeach--}}
{{--                        </div>--}}
{{--                    @endif--}}
                    </div>
                @endif
            </div>
            <div class="mb-4" style="text-align: center;">
                <button type="button" class="btn light_grey_gradient" data-dismiss="modal">Cancel</button>
                <button type="submit" class="btn light_grey_gradient shine_submit_modal" id="edit_document_{{ $unique_value ?? '' }}">Save</button>
            </div>
            </form>
        </div>
    </div>
</div>
@push('javascript')

<script type="text/javascript">
$(document).ready(function () {
        $('body').on('change','#edit_mark_value{{ $unique_value ?? '' }}',function(){
            var num = $(this).val();
            if(num.length == 0){
                num = '0';
            }
            num = addPeriod(num);
            $(this).val("£" + num);
        })
        $('#edit_mark_value{{ $unique_value ?? '' }}').trigger('change');
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
            $('#edit_dcValue{{ $unique_value ?? '' }}').val(x1 + x2);

            var rgx = /(\d+)(\d{3})/;
            while (rgx.test(x1)) {
                x1 = x1.replace(rgx, '$1' + ',' + '$2');
            }
            return x1 + x2;
        }

        $('#edit_document_{{ $unique_value ?? '' }}').click(function(e){
            e.preventDefault();
            $('.invalid-feedback').html("");
            $('#form-plan_document').removeClass('is-invalid');
            $('#form-name').removeClass('is-invalid');
            var deadline = $('#deadline{{ $unique_value ?? '' }}').val();
            var name = $('#survey-search-form-name{{$unique_value ?? ''}}').val();
            // if (deadline === undefined) {
            //     deadline = '01/01/1970';
            // }
            var form_data = new FormData($('#form_{{ $unique_value ?? '' }}')[0]);
            form_data.append('deadline', deadline);
            form_data.append('name', name);

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
                             $('#'+key+'-err{{ $unique_value ?? '' }}').html(value[0]);
                             $('#form-'+key+'{{ $unique_value ?? '' }}').addClass('is-invalid');
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
            $('#form-name{{ $unique_value ?? '' }}').removeClass('is-invalid');
            $('#form-document_file{{ $unique_value ?? '' }}').removeClass('is-invalid');
        });


    });
</script>
@endpush
