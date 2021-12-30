<!-- Modal -->
<div class="modal fade" id="{{$modal_id}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header {{ isset($color) ? CommonHelpers::getComplianceNavColor($color) : 'red_color' }}">
                <h5 class="modal-title" id="exampleModalLabel">shineArc - {{ $header ?? '' }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ $url }}" method="GET" class="form-shine">
            @csrf
            <div class="modal-body" >
                <strong class="ml-5">Which type of Decommission Warning would you like to use?</strong>
                    @foreach($data as $key => $row)
                    <div class="form-check mt-3 mb-2 ml-5">
                        <label class="form-check-label" for="radio{{$key}}">
                            <input type="radio" class="form-check-input" id="radio{{$key}}" name="decommissioned_reason_prop" value="{{ $row->id }}" {{ $loop->first ? "checked=checked" : ''}}>
                            {{ $row->description ?? '' }}
                        </label>
                    </div>
                    @endforeach
            </div>
            <div class="mb-3" style="text-align: center;">
                <button type="button" class="btn light_grey_gradient" data-dismiss="modal">Cancel</button>
                <button type="submit" class="btn light_grey_gradient" >Submit</button>
            </div>
            </form>
        </div>
    </div>
</div>
