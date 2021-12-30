<!-- Modal -->
<div class="modal fade" id="{{ isset($modal_id) ? $modal_id : '' }}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header {{ isset($color) ? CommonHelpers::getNavColor($color) : 'red_gradient' }}">
                <h5 class="modal-title" id="exampleModalLabel">{{ $header }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form method="POST" class="form-shine" action="{{ $url ?? '' }}">
            @csrf
            <div class="modal-body" style="padding-left: 35px">
                <input type="hidden" name="record_id" value="{{ $id }}">
                <table id="{{$table_id}}" class="display" style="border: 1px solid #e5e5e5 !important;padding: 10px;border-radius: 4px;width: 100%;">
                    <thead>
                        <tr>
                            <th>Info</th>
                            <th>Comment</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if(isset($data) and count($data) > 0)
                            @foreach($data as $dataRow)
                                <tr>
                                    <td style="width:150px;padding-top: 20px">
                                        <input type="radio" name="comment_id" value="{{ $dataRow->id }}">
                                        Date {{ $dataRow->created_at->format('d/m/Y') ?? '01/01/1970' }}
                                        @if(!is_null($dataRow->parent_reference))
                                        <br>
                                         Survey Ref:   {{ $dataRow->parent_reference ?? '' }}
                                        @endif
                                        <br>
                                        <br>
                                    </td>
                                    <td>
                                        {{ $dataRow->comment }}
                                    </td>
                                </tr>
                            @endforeach
                        @endif
                    </tbody>
                </table>
            </div>
                <div class="mb-4" style="text-align: center;">
                    <button type="button" class="btn light_grey_gradient" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn light_grey_gradient">Select</button>
                </div>
            </form>
        </div>
    </div>
</div>
@push('javascript')

<script type="text/javascript">
$(document).ready( function () {
    $('#{{$table_id}}').DataTable({
        "lengthChange": false,
        "ordering": false,
        pageLength: 2,
        "pagingType": "simple"
    });
} );
</script>
@endpush