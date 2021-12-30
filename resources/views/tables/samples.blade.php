@extends('tables.main_table', [
                'header' => ["Sample/link ID", "External reference", "Items", "Product/debris type", "Asbestos Type", "Room/location Reference"]
    ])
@section('datatable_content')
    @if(isset($data) and count($data) > 0)
        <form method="POST" action="{{ route('item.post_add') }}" id="form-sample">
        <meta name="csrf-token" content="{{ csrf_token() }}" />
        <input type="hidden" name="data-checked">
        <input type="hidden" name="data-unchecked">
        @foreach($data as $key => $dataRow)
                <tr>
                    <td><a href="{{ route('sample_getEdit',['survey_id' => $survey->id, 'sample_id' => $dataRow->sample_id,'position' => $key, 'pagination_type' => TYPE_SAMPLE]) }}">{{ $dataRow->reference }}</a></td>
                    <td style="width: 140px"><a href="{{ route('sample_getEdit',['survey_id' => $survey->id, 'sample_id' => $dataRow->sample_id,'position' => $key, 'pagination_type' => TYPE_SAMPLE]) }}">{{ $dataRow->description }}</a></td>
                    <td>
                        @if(!empty($dataRow->item_reference) and !empty($dataRow->item_ids))
                            @foreach($dataRow->item_reference as $key => $item_reference)
                                <a href="{{ route('item.index', ['id' =>  $dataRow->item_ids[$key]]) }}">{{ $item_reference }}</a>
                                @if(!$loop->last)
                                    ,
                                @endif
                            @endforeach
                        @endif
                    </td>
                    <td>{{ \CommonHelpers::getProductDebisText($dataRow->product_debris) }}</td>
                    <td style="width: 130px;">{{ ($dataRow->original_state == 1) ? 'No ACM Detected' : \CommonHelpers::getAsbestosTypeText($dataRow->asbestos_type) }}</td>
                    <td><a href="{{ route('property.surveys',['survey_id' =>  $survey->id,'section' => SECTION_ROOM_LOCATION_SUMMARY, 'location' => $dataRow->location_id ] ) }}">{{ $dataRow->location_reference }}</a></td>
                    <td style="text-align: center;">
                        <div class="custom-control custom-checkbox">
                            <input type="checkbox" class="custom-control-input" name="data[]" id="{{ $dataRow->sample_id }}" value="{{ $dataRow->sample_id }}" {{ ($dataRow->is_real == 1) ? 'checked' : '' }}>
                            <label class="custom-control-label" for="{{ $dataRow->sample_id }}"></label>
                        </div>
                    </td>
                </tr>
        @endforeach
        </form>
    @endif
@overwrite

@push('javascript')

<script type="text/javascript">
$(document).ready(function(){

    $("#save-sample1").click(function(){
        var list_check = [];
        var list_uncheck = [];
        $('input[name="data[]"]').each(function(k,v){
            if($(v).prop("checked") == true){
                list_check.push($(v).val());
            } else {
                list_uncheck.push($(v).val());
            }
        })

        jQuery.ajaxSetup({
          headers: {
              'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          }
        });
        $.ajax({
          type: 'POST',
          url : "{{ route('ajax.pick_sample') }}",
          data: { check : list_check, uncheck : list_uncheck },
          success: function(response) {
            location.reload();
          },
        });
    })
});
</script>
@endpush
