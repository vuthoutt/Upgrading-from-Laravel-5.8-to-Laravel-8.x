<div class="row register-form form-non-user" id="{{ $id ?? '' }}">
    <div class="row register-form mb-1 col-md-12">
        <label class="col-md-3 col-form-label text-md-left font-weight-bold">Forename:</label>
        <div class="col-md-8" >
            <div class="form-group">
                <input type="text" class="form-control " name="first_name_{{ $prefix ?? '' }}[{{ $organisation_id ?? 0 }}]" id="first_name_{{ $prefix ?? '' }}" value="{{ $user->first_name ?? '' }}">
            </div>
        </div>
    </div>
    <div class="row register-form mb-1 col-md-12">
        <label class="col-md-3 col-form-label text-md-left font-weight-bold">Last Name:</label>
        <div class="col-md-8" >
            <div class="form-group">
                <input type="text" class="form-control " name="last_name_{{ $prefix ?? '' }}[{{ $organisation_id ?? 0 }}]" id="last_name_{{ $prefix ?? '' }}" value="{{ $user->last_name ?? '' }}">
            </div>
        </div>
    </div>
    <div class="row register-form mb-1 col-md-12">
        <label class="col-md-3 col-form-label text-md-left font-weight-bold">Telephone:</label>
        <div class="col-md-8" >
            <div class="form-group">
                <input type="text" class="form-control " name="telephone_{{ $prefix ?? '' }}[{{ $organisation_id ?? 0 }}]" id="telephone_{{ $prefix ?? '' }}" value="{{ $user->telephone ?? '' }}">
            </div>
        </div>
    </div>
    <div class="row register-form mb-1 col-md-12">
        <label class="col-md-3 col-form-label text-md-left font-weight-bold">Mobile:</label>
        <div class="col-md-8" >
            <div class="form-group">
                <input type="text" class="form-control " name="mobile_{{ $prefix ?? '' }}[{{ $organisation_id ?? 0 }}]" id="mobile_{{ $prefix ?? '' }}" value="{{ $user->mobile ?? '' }}">
            </div>
        </div>
    </div>
    <div class="row register-form mb-1 col-md-12">
        <label class="col-md-3 col-form-label text-md-left font-weight-bold">Email:</label>
        <div class="col-md-8" >
            <div class="form-group">
                <input type="text" class="form-control " name="email_{{ $prefix ?? '' }}[{{ $organisation_id ?? 0 }}]" id="email_{{ $prefix ?? '' }}" value="{{ $user->email ?? '' }}">
            </div>
        </div>
    </div>
</div>
