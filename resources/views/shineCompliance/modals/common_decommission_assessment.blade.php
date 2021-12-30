<!-- Modal -->
<div class="modal fade pr-0" id="{{ isset($modal_id) ? $modal_id : '' }}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content" style="border-radius:0px !important;">
            <div class="modal-header {{ isset($color) ? CommonHelpers::getComplianceNavColor($color) : 'red_color' }}" style="color: #fff; border-radius:0px !important;">
                <h5 class="modal-title" id="exampleModalLabel">shine{{ isset($color) ? 'Arc' : 'Prism' }} - {{$header ?? ''}}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{$url ?? '#'}}" method="POST" enctype="multipart/form-data">
                @csrf
                <meta name="csrf-token" content="{{ csrf_token() }}" />
                <div class="modal-body" style="padding-left: 20px;min-height:50px;text-align: center;">
                    <strong class="">Do you want to decommission {{$reference ?? ''}} ?</strong>
                </div>
                <div class="mb-4" style="text-align: center;">
                    <button type="button" class="btn light_grey_gradient_button fs-8pt" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn light_grey_gradient_button shine_document_submit fs-8pt decommission_btn">Submit</button>
                </div>
            </form>
        </div>
    </div>
</div>
@push('javascript')
    <script type="text/javascript">
        $(document).ready(function () {
            $('.decommission_btn').click(function(e){
                $('#{{$modal_id}}').modal('hide');
            });
        });
    </script>
@endpush
