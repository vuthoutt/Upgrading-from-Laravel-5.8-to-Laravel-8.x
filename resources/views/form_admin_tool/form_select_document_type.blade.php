<div class="row register-form form-summary d-none {{$class ?? ' '}}" id="form-{{ $id ?? '' }}">
    <label class="font-weight-bold col-md-12" > {{ isset($title) ? $title : 'Where would you like to look?' }}
    </label>
    <div>
        <div class="form-group col-md-12">
            <select  class="mt-3 form-control input-summary" name="{{ $id ?? '' }}" id="{{ $id ?? '' }}">
                <option value="property_plan" data-type="property">Property Plan (PP)</option>
                <option value="property_historical" data-type="property">Property Historical Document (HD)</option>
                <option value="survey_sc" data-type="survey">Survey Sample Certificate (SC)</option>
                <option value="survey_ac" data-type="survey">Survey Air Test Certificate (AC)</option>
                <option value="survey_plan" data-type="survey">Survey Plan (SP)</option>
{{--                <option value="tender_doc" data-type="project">Tender Document (TD)</option>--}}
{{--                <option value="contractor_doc" data-type="project">Contractor Document (OD)</option>--}}
                {{-- <option value="gsk_doc" data-type="project">GSK Document (GD)</option> --}}
                <option value="preconstruction_doc" data-type="project">Pre-construction Doc (DD)</option>
                <option value="design_doc" data-type="project">Design Doc (DD)</option>
                <option value="commercial_doc" data-type="project">Commercial Doc (FD)</option>
                <option value="planning_doc" data-type="project">Planning Doc (PD)</option>
                <option value="prestart_doc" data-type="project">Pre-Start Doc (SD)</option>
                <option value="site_rec_doc" data-type="project">Site Record Doc (RD)</option>
                <option value="completion_doc" data-type="project">Completion Doc (CD)</option>
                <option value="incident_doc" data-type="incident">Supporting Document (IRD)</option>
            </select>
            <span class="invalid-feedback" role="alert">
            <strong>The {{$name ?? ''}} field is required.</strong>
        </span>
        </div>
    </div>
</div>
