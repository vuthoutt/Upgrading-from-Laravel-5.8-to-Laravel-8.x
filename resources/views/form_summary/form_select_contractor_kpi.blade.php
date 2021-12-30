<div class="row register-form form-summary" id="form-{{ $id ?? '' }}">
    <label class="font-weight-bold col-md-12" >Which Contractor would you like to include into the summary?
        <br/>(Please pick up to a maximum of 3)
    </label>
    <div>
        <div class="form-group col-md-12">
            <select  class="form-control input-summary mb-4" name="contractor[]" id="{{ $id ?? '' }}">
                <option value=""></option>
                @if(isset($clients) and !is_null($clients))
                    @foreach($clients as $client)
                        <option value="{{ $client->id }}">{{ $client->name }}</option>
                    @endforeach
                @endif
            </select>

            <select  class="form-control input-summary mb-4" name="contractor[]" id="{{ $id ?? '' }}">
                <option value=""></option>
                @if(isset($clients) and !is_null($clients))
                    @foreach($clients as $client)
                        <option value="{{ $client->id }}">{{ $client->name }}</option>
                    @endforeach
                @endif
            </select>

            <select  class="form-control input-summary" name="contractor[]" id="{{ $id ?? '' }}">
                <option value=""></option>
                @if(isset($clients) and !is_null($clients))
                    @foreach($clients as $client)
                        <option value="{{ $client->id }}">{{ $client->name }}</option>
                    @endforeach
                @endif
            </select>
        </div>
    </div>
</div>