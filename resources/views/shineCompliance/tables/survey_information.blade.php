<div class=" {{$row_col ?? 'col-md-12'}} pr-0 pl-0" id="accordionExample{{ isset($tableId) ? $tableId : '' }}">
    <div class="card mt-4 discard-border-radius">
        <div class="card-header table-header discard-border-radius {{ isset($normalTable) ? 'normal-table' : ''}}">
            <h6 class="table-title">{{ $title }}</h6>
            <div class="btn collapse-table table-collapse-button " data-toggle="collapse" data-target="#collapse-{{ isset($type) ? $type : '' }}" aria-expanded="false" aria-controls="collapse-{{ isset($tableId) ? $tableId : '' }}">
                <i class="fa fa-lg " aria-hidden="true"></i>
            </div>
        </div>
        <div id="collapse-{{ isset($type) ? $type : '' }}" class="collapse show" data-parent="#accordionExample">
            <div class="card-body">
                {!! (isset($data_content) and !empty($data_content)) ? $data_content : 'No Information.' !!}
            </div>
        </div>
    </div>
</div>

<div class="col-md-12 offset-top20">
    @if($data->is_locked == SURVEY_UNLOCKED and $canBeUpdateSurvey)
        @if($data->status == COMPLETED_SURVEY_STATUS)
            <div class="spanWarningSurveying" style="width: 400px !important;">
                <strong>
                    <em>Survey is view only because technical activity is complete</em>
                </strong>
            </div>
        @else
            @if($type !== 'method-table')
                <div class="row">
                    <a href="{{ route('shineCompliance.survey-information', ['survey_id' => $data->id, 'type' => $type]) }}" class="btn btn_long_size light_grey_gradient"><strong>Edit</strong></a>
                </div>
            @endif
        @endif
    @endif
</div>
