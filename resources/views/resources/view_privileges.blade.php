<div id="mytree" class="bstree" style="margin-left: 60px;">
    <ul>
        <li data-id="root" data-level="0"><span>Everything</span>
            <ul>
                {{dd($data)}}
                @foreach($data as $key => $value)
                <input id="bstree-data" type="hidden" name="bstree-data" data-ancestors="">
                {{dd($value)}}
                    <li  data-level="{{$key}}"><span class="font-weight-bold" >{{ $value}}</span>
                        @foreach($value as $key1 => $value1)
                            <li  data-level="{{$key1}}"><span class="font-weight-bold" >{{ $value1 }}</span>
                        @endforeach
                    </li>
                @endforeach
            </ul>
        </li>
    </ul>
</div>


@push('javascript')

<script type="text/javascript">
    $(document).ready(function(){
        $("#clientKey").change(function () {
            var client_id =  $(this).val();
            $("#surveyor").html("");
            $("#secondSurveyor").html("");
            $("#qualityKey").html("");
            $("#analystKey").html("");
            $("#consultantKey").html("");
            $.ajax
            ({
                type: "GET",
                url: "/ajax/client-users/" + client_id,
                data: {},
                cache: false,
                success: function (html) {
                    if (html) {
                        $('#secondSurveyor').append($('<option>', {
                            value: 0,
                            text : ''
                        }));
                        $.each( html, function( key, value ) {
                            $('#surveyor').append($('<option>', {
                                value: value.id,
                                text : value.first_name + ' ' + value.last_name
                            }));

                            $('#secondSurveyor').append($('<option>', {
                                value: value.id,
                                text : value.first_name + ' ' + value.last_name
                            }));

                            $('#qualityKey').append($('<option>', {
                                value: value.id,
                                text : value.first_name + ' ' + value.last_name
                            }));

                            $('#analystKey').append($('<option>', {
                                value: value.id,
                                text : value.first_name + ' ' + value.last_name
                            }));

                            $('#consultantKey').append($('<option>', {
                                value: value.id,
                                text : value.first_name + ' ' + value.last_name
                            }));
                        });
                    }
                }
            });
        });

      $hiddenInput = $('#bstree-data');
          $('#mytree').bstree({
            dataSource: $hiddenInput,
            initValues: $hiddenInput.data('ancestors'),
            onDataPush: function (values) {
                return;
              var def = '<strong class="pull-left">Values,&nbsp;</strong>'
              for (var i in values) {
                def += '<span class="pull-left">' + values[i] + '&nbsp;</span>'
              }
              $('#status').html(def)
            },
            updateNodeTitle: function (node, title) {
              // return title
              // return '[' + node.attr('data-id') + '] ' + title + ' ( ' + node.attr('data-level') + ' )'
            }
          });
          $('#btn-submit1').click(function(){
              setValueInput();
              $('#form_add_survey').submit();
          });
          // lock_list = $('#mytree').find("[data-location-locked="1"]");
          // $.each(li_list, function (k, v) {
          //   alert($(v).data('id'));
          //   });
           //get value checked item/location/area
           //list_area
          function getCheckedNode(type) {
              var arr_result = [];
              if (type) {
                  var li_list = $('#mytree').find("[data-level='" + type + "']");
                  $.each(li_list, function (k, v) {
                      var check = false;
                      var id = $(v).data('id');
                      if (id) {
                        //area and location need to find any checked checkbox then count as checked
                        if(type == 'item'){
                            check = $(v).find("input[id='bstree-checkbox-" + id + "']:first").prop('checked');
                        } else {
                            check = $(v).find('input.bstree-checkbox:checkbox:checked').length > 0;
                        }
                      }
                      if (check) {
                          arr_result.push(id);
                      }
                  });
                  return arr_result;
              }
          }
          //set value for input
        function setValueInput(){
            // get area
            var arr_area = getCheckedNode('area');
            var arr_location = getCheckedNode('location');
            var arr_item = getCheckedNode('item');
            if(arr_area) {
                $('input:hidden[name="list_area[]"]').val(arr_area);
            }
            if(arr_location){
                $('input:hidden[name="list_location[]"]').val(arr_location);
            }
            if(arr_item){
                $('input:hidden[name="list_item[]"]').val(arr_item);
            }
        }


        // remove locked locations
        removeInputLocked();
    });

    //checked item base on setting survey
    function checkSettingSurvey(that){
        //if uncheck setting then trigger click these checkbox bolong to that setting
        var is_checked_setting = $(that).prop('checked');
        // console.log(is_checked_setting);
        // return;
        var check = false;
        var list_setting = $(that).closest('.setting-survey').find('input[type=checkbox]');
        $.each(list_setting, function(k,v){
            if($(v).prop('checked') == true){
                check = true;
                return false;
            }
        })


            var element_id = $(that).prop('id');
            var arr_checked = [];
            var arr_unchecked_item = []; // for location inacc setting
            var list_input = $('#bstree-checkbox-root').closest('li').find('.bstree-children input[type=checkbox]');// get all input excep everything
            $.each(list_input, function(k,v){
                if($(v).prop('checked') == true){
                    arr_checked.push(v);
                } else {
                    if($(v).closest('li').data('item-id')){
                        arr_unchecked_item.push(v);
                    }
                }
            })
            // alert(element_id);
            if(element_id){
               var arr_location_id = [];
               var list_ele = $('#mytree').find('.'+element_id);
               //before trigger click, need to get all checked checkbox then set to them selected after
                // cause when select 2 setting survey, the second setting trigger click maybe unchecked inputs that the perious setting do
                // case setting for inacc location, need to get un checked input because it will un check all items inside those inacc locations
               if(list_ele){
                    $.each(list_ele, function(k,v){
                        var id = $(v).data('id');
                        if(id){
                            //click setting else uncheck setting
                            if(is_checked_setting == true){
                                // only item has no child
                                // var is_has_child = $(v).data('item-id');
                                // console.log($(v).find("input[id='bstree-checkbox-" + id + "']:first").prop('checked'));
                                if(element_id == 'inaccessibleRoom'){
                                    //when trigger click state change then need to revert the state to correct trigger
                                    var cur_state = $(v).find("input[id='bstree-checkbox-" + id + "']:first").prop('checked') ? false :true;
                                    $(v).find("input[id='bstree-checkbox-" + id + "']:first").prop('checked',cur_state);
                                }
                                $(v).find("input[id='bstree-checkbox-" + id + "']:first").trigger('click');
                                // console.log(v);
                                //set prop check for location or location and area
                                // console.log($(v).parent('li'));
                                $.each(arr_checked, function(k,v){
                                    $(v).prop('checked',true);
                                });
                                if(element_id == 'inaccessibleRoom'){
                                    $(v).find("input[id='bstree-checkbox-" + id + "']:first").data('inaccess-location', '1');
                                    // console.log($(v).data('inaccess-location'));
                                    $.each(arr_unchecked_item, function(k,v){
                                        $(v).prop('checked',false);
                                    });
                                }

                            } else {
                                //find all and update item that has been checked from that setting from checked to unchecked
                                // get list location id of all those items to check later
                                // case check location setting, need check to find any childs and checked input
                                // if have no then trigger click else do nothing
                                if(element_id == 'inaccessibleRoom'){
                                    var list_item_inside = $(v).find('.bstree-children input[type=checkbox]');
                                    var uncheck = true;
                                    $.each(list_item_inside, function(k1,v1){
                                       if($(v1).prop('checked')){
                                           uncheck = false;
                                           return false;
                                       }
                                    });
                                    $(v).find("input[id='bstree-checkbox-" + id + "']:first").data('inaccess-location',null);
                                    if(uncheck && $(v).find("input[id='bstree-checkbox-" + id + "']:first").prop('checked')){
                                        $(v).find("input[id='bstree-checkbox-" + id + "']:first").trigger('click');
                                    }
                                } else {
                                    //for setting item
                                    var location_id = $(v).data('item-location-id');
                                    if(location_id){
                                        arr_location_id.push(location_id);
                                    }
                                    // console.log(v);
                                    $(v).find("input[id='bstree-checkbox-" + id + "']:first").prop('checked',false);
                                }
                            }
                        }
                    });
               }

                //check if there is no checked item inside then set that location check = true and trigger click
                if(arr_location_id.length > 0){
                    $.each(unique(arr_location_id), function(i, value){
                        var list_item_inside = $('li[data-location-id="'+value+'"]').find('.bstree-children input[type=checkbox]');
                        // console.log(list_item_inside);
                        var uncheck = true;
                        $.each(list_item_inside, function(k1,v1){
                            if($(v1).prop('checked')){
                                uncheck = false;
                                return false;
                            }
                        });
                        var location = $('li[data-location-id="'+value+'"]').find("input[id='bstree-checkbox-" + value + "']:first");
                        // console.log(unique(arr_location_id));
                        // console.log(value);
                        // console.log(uncheck);
                        // console.log($(location).prop('checked'));
                        // console.log($(location).data('inaccess-location'));
                        if(location && uncheck  && !$(location).data('inaccess-location')){
                            if($(location).prop('checked')){
                                $(location).trigger('click');
                            } else {
                                $(location).prop('indeterminate', false);
                            }
                        }
                    });
                }
            }
    }
    //for unique an array
    function unique(array){
        return $.grep(array,function(el,index){
            return index == $.inArray(el,array);
        });
    }

    function removeInputLocked(){
        var li_list = $('#mytree').find("[data-location-locked=1]");
        $.each(li_list, function(k,v){
            var id = $(v).data('id');
            console.log($(v).find("input[id='bstree-checkbox-" + id + "']:first"));
            $(v).find("input[id='bstree-checkbox-" + id + "']:first").remove();
        })
    }
</script>
@endpush