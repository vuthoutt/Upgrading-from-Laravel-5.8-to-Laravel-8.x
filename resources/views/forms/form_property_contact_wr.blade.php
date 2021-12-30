
<div class="row register-form mb-1">
    <label class="col-md-3 col-form-label text-md-left font-weight-bold">Property Contact:</label>
    <div class="col-md-5" >
        <div class="form-group">
            @if(isset($edit))
                @if($list_users)
                    @php
                      $arr_user = [];
                    @endphp
                    @foreach($list_users_wk as $team)
                            <select class="form-control property_contact mb-3" name="property_contact[]" data-select="1" data-old-val="{{$team}}" >
                                <option value="" >------ Please select an option -------</option>
                                <option value="nonuser" {{"nonuser" == $team ? "selected" : ''}} {{in_array('nonuser', $arr_user) ? "style=display:none" : ''}}>Property Contact (Non User)</option>
                                @if($list_users)
                                    @foreach($list_users as $user)
                                        <option value="{{$user->id}}" {{$user->id == $team ? "selected" : ''}} {{in_array($user->id, $arr_user) ? "style=display:none" : ''}}> {{$user->full_name ?? ''}} </option>
                                    @endforeach
                                @endif
                            </select>
                        @php
                            $arr_user[] = $team;
                        @endphp
                    @endforeach
                    @php
                        //dd($work_request->workContact);
                    @endphp
                    @if(count($arr_user) < count($list_users) +1)
                        <select class="form-control property_contact mb-3" name="property_contact[]" data-select="1" data-old-val="" >
                            <option value="" >------ Please select an option -------</option>
                            <option value="nonuser"  {{in_array('nonuser', $arr_user) ? "style=display:none" : ''}} >Property Contact (Non User)</option>
                            @if($list_users)
                                @foreach($list_users as $user)
                                    <option value="{{$user->id}}" {{in_array($user->id, $arr_user) ? "style=display:none" : ''}}> {{$user->full_name ?? ''}} </option>
                                @endforeach
                            @endif
                        </select>
                    @endif
                @endif
            @else
                <select class="form-control property_contact mb-3" name="property_contact[]" data-select="0" data-old-val="" >
                    <option value="" >------ Please select an option -------</option>
                    <option value="nonuser">Property Contact (Non User)</option>
                    @if($list_users)
                        @foreach($list_users as $val)
                            <option value="{{$val->id}}"> {{$val->full_name ?? ''}} </option>
                        @endforeach
                    @endif
                </select>
            @endif
        </div>
    </div>
</div>
<div id="form-nonuser" {{!isset($hide) || $hide ? "style=display:none" : ''}}>
    <div class="row register-form mb-1">
        <label class="col-md-3 col-form-label text-md-left font-weight-bold">Forename:</label>
        <div class="col-md-5" >
            <div class="form-group">
                <input type="text" class="form-control " name="first_name" id="first_name" value="{{ isset($work_request->workContact->first_name) ? $work_request->workContact->first_name : "" }}">
            </div>
        </div>
    </div>
    <div class="row register-form mb-1">
        <label class="col-md-3 col-form-label text-md-left font-weight-bold">Last Name:</label>
        <div class="col-md-5" >
            <div class="form-group">
                <input type="text" class="form-control " name="last_name" id="last_name" value="{{ isset($work_request->workContact->last_name) ? $work_request->workContact->last_name : "" }}">
            </div>
        </div>
    </div>
    <div class="row register-form mb-1">
        <label class="col-md-3 col-form-label text-md-left font-weight-bold">Telephone:</label>
        <div class="col-md-5" >
            <div class="form-group">
                <input type="text" class="form-control " maxlength="40" name="telephone" id="telephone" value="{{ isset($work_request->workContact->telephone) ? $work_request->workContact->telephone : "" }}">
            </div>
        </div>
    </div>
    <div class="row register-form mb-1">
        <label class="col-md-3 col-form-label text-md-left font-weight-bold">Mobile:</label>
        <div class="col-md-5" >
            <div class="form-group">
                <input type="text" class="form-control " maxlength="40" name="mobile" id="mobile" value="{{ isset($work_request->workContact->mobile) ? $work_request->workContact->mobile : "" }}">
            </div>
        </div>
    </div>
    <div class="row register-form mb-1">
        <label class="col-md-3 col-form-label text-md-left font-weight-bold">Email:</label>
        <div class="col-md-5" >
            <div class="form-group">
                <input type="text" class="form-control " name="email" id="email" value="{{ isset($work_request->workContact->email) ? $work_request->workContact->email : "" }}">
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    max = 10;
    $(document).ready(function(){
        $('body').on('change', '.property_contact', function(){
            // max option = 10
            var number_select = $('.property_contact').length;
            if(number_select == max){
                return;
            }
            var this_val = $(this).val();
            var is_selected_before = $(this).data('select');
            var old_val = $(this).data('old-val');
            if(this_val){
                $(this).data('old-val', this_val);
                $(this).data('select', 1);
                // if is_selected_before == true and not last select and change to a new value then check other dropdown has the same value to remove, and not append new dropdown
                //hide all other select == this val, show old_val for other select
                var current_position = $(".property_contact" ).index($(this));
                if(is_selected_before && current_position != (number_select - 1) && (old_val != this_val)){
                    $.each($(this).nextAll(), function(k,v){
                        var other_selected = $(v).find(':selected').val();
                        //if last element then not remove
                        if(other_selected == this_val){
                            console.log($(".property_contact" ).index($(v)), (number_select - 1), $(v));
                            if($(".property_contact" ).index($(v)) == (number_select - 1)){
                                $(v).find("option:first").prop('selected', true);
                            } else {
                                //when append new select need to reset data options
                                // var add_parent = $(v).closest('.form-group');
                                // var add_clone_select = $('.property_contact').last().clone();
                                // // var new_id = $('.property_contact').length;
                                // // $(clone_select).prop('id', 'property_contact' + new_id);
                                // console.log($(this).last().val());
                                // $(add_clone_select).prop('select', 0);
                                // $(add_clone_select).prop('old-val', '');
                                // $(add_clone_select).find('option[value="' + old_val + '"]').show();
                                // $(add_clone_select).find('option[value="' + this_val + '"]').hide();
                                // $(add_clone_select).find('option[value="' + $('.property_contact').last().val() + '"]').hide();
                                // $(add_clone_select).find("option:first").prop('selected', true);
                                // $(add_parent).append(add_clone_select);
                                $(v).remove();
                            }
                        }
                        $(v).find('option[value="' + this_val + '"]').hide();
                        $(v).find('option[value="' + old_val + '"]').show();
                    });
                }

                //is last select and number option > 2
                if(current_position == (number_select - 1) && current_position < $(this).find('option').length - 2){
                    //when append new select need to reset data options
                    var parent = $(this).closest('.form-group');
                    var clone_select = $(this).clone();
                    // var new_id = $('.property_contact').length;
                    // $(clone_select).prop('id', 'property_contact' + new_id);
                    $(clone_select).prop('select', 0);
                    $(clone_select).prop('old-val', '');
                    $(clone_select).find('option[value="' + this_val + '"]').hide();
                    $(parent).append(clone_select);
                    // var is_show_nonuser_form = $(parent).find('option[value="nonuser"]').hide();
                }
            }
            console.log(this_val, old_val);
            //check show hide nonuser
            if(old_val == 'nonuser'){
                //hide
                $('#form-nonuser').hide();
            }
            if(this_val == 'nonuser'){
                //show
                $('#form-nonuser').show();
            }
        });
    });
</script>
