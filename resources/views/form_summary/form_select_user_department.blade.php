<div class="row register-form form-summary" id="form-{{ $id ?? '' }}">
    <label class="font-weight-bold col-md-12" >Which department would you like to check?
    </label>
    <div>
        <div class="form-group col-md-12">
            <select  class="form-control input-summary" name="department" id="{{ $id ?? '' }}">
                <option value="all">All Departments</option>
                @if(isset($departmentList) and !empty($departmentList))
                    @foreach($departmentList as $department)
                        <option value="{{ $department->id }}" class="client-option">{{ $department->name }}</option>
                    @endforeach
                @endif
                @if(isset($departmentContractorList) and !empty($departmentContractorList))
                    @foreach($departmentContractorList as $department)
                        <option value="{{ $department->id }}" class="contractor-option">{{ $department->name }}</option>
                    @endforeach
                @endif
            </select>
        </div>
    </div>
</div>