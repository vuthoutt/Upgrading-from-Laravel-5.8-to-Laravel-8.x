<div class="row register-form form-summary" id="form-{{ $id ?? '' }}">
    <label class="font-weight-bold col-md-12" >Which organisation would you like to check?
    </label>
    <div>
        <div class="form-group col-md-12">
            <select  class="form-control input-summary" name="select_organisation" id="{{ $id ?? '' }}">
                <option value="all">All Organisations</option>
                @if(isset($clients) and !is_null($clients))
                    @foreach($clients as $client)
                        <option value="{{ $client->id }}" data-client-type="{{ $client->client_type }}">{{ $client->name }}</option>
                    @endforeach
                @endif
            </select>
        </div>
    </div>
</div>