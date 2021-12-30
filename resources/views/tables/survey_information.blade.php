<div class="col-md-12 pr-0 pl-0" id="accordionExample">
    <div class="card mt-5">
        <div class="card-header table-header">
            <h6 class="table-title">{{ $title }}</h6>
            <div class="btn collapse-table table-collapse-button " data-toggle="collapse" data-target="#collapse-{{ isset($type) ? $type : '' }}" aria-expanded="false" aria-controls="collapse-{{ isset($tableId) ? $tableId : '' }}">
                <i class="fa fa-lg " aria-hidden="true"></i>
            </div>
        </div>
        <div id="collapse-{{ isset($type) ? $type : '' }}" class="collapse show" data-parent="#accordionExample">
            <div class="card-body">
                {!! (isset($data_content) and !is_null($data_content)) ? $data_content : 'No Information.' !!}
            </div>
        </div>
    </div>
</div>

<div class="col-md-12 offset-top20">
    @if($data->is_locked == SURVEY_UNLOCKED)
        @if($data->status == COMPLETED_SURVEY_STATUS)
            <div class="spanWarningSurveying" style="width: 400px !important;">
                <strong>
                    <em>Survey is view only because technical activity is complete</em>
                </strong>
            </div>
        @else
            @if($type !== 'method-table')
                @if($canBeUpdateSurvey)
                    <div class="row">
                        <a href="{{ route('survey-information', ['survey_id' => $data->id, 'type' => $type]) }}" class="btn btn_long_size light_grey_gradient"><strong>Edit</strong></a>
                    </div>
                @endif
            @endif
        @endif
    @endif
</div>
