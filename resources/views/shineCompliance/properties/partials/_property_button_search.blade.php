<div class="row mr-bt-top">
    <div class="col-md-12 button-search-left pr-0 pl-0 ">
        <div class="form-button-left" >
            <a href="{{ $backRoute }}" style="text-decoration: none">
                <button type="submit" class="btn shine-compliance-button fs-8pt">
                    <strong>{{ __('Back') }}</strong>
                </button>
            </a>
        </div>
        <div class="form-button-search pr-0" style="margin-right: {{isset($class_css)? $class_css : "-15px"}}">
            @if(isset($is_locked) && $is_locked)
            @else
                @if($addRoute == false)
                @else
                    <a href="{{ $addRoute ?? '' }}">
                        <button class="btn light_grey_gradient_button fs-8pt" data-toggle="modal" data-target="{{ $addRoute ?? '' }}" aria-hidden="true" >
                            <strong>{{ __('Add') }}</strong>
                        </button>
                    </a>
                @endif
            @endif
            <form class="search_form" action="{{$search_action ?? ''}}" style="margin-left:20px;max-width:300px">
                <input type="text" placeholder="Search.." name="search_value" class="search_value" value="{{request()->input('q')}}" style="height: 28px;">
            </form>
            @if(isset($addFilter) && $addFilter)
                <a href="javascript:void(0)" style="text-decoration: none;margin-left: 15px">
                    <button class="btn light_grey_gradient_button fs-8pt" id="filter" style="margin-right: 0px">
                        <strong>{{ __('Filters') }}</strong>
                    </button>
                </a>
            @endif
        </div>
    </div>
</div>
@push('javascript')
<script>
    $(document).ready(function(){
        $(function() {
            var timer;
            $('.search_value').keyup(function () {
                var that = $(this);
                var text = $(this).val();
                var action = $(this).closest('form').prop('action');
                var urlParams = new URLSearchParams(window.location.search); //get all parameters
                var page = urlParams.get('page'); //extract the foo parameter - this will return NULL if foo isn't a parameter

                if(page) { //check if foo parameter is set to anything
                    var url = new URL(action);
                    url.searchParams.set("page", "1"); // setting your param
                    action = url.href;
                    console.log(action)
                }
                clearTimeout(timer);
                var ms = 500; // milliseconds
                var val = this.value;
                timer = setTimeout(function(){ searchData(text, action, that); }, ms);
            });
            @if(isset($addFilter) && $addFilter)
                $('.filter_options').change(function () {
                    var that = $('.search_value');
                    var text = $('.search_value').val();
                    var action = $('.search_value').closest('form').prop('action');
                    clearTimeout(timer);
                    var ms = 500; // milliseconds
                    var val = $('.search_value').value;
                    timer = setTimeout(function(){ searchData(text, action, that); }, ms);
                });
            @endif
        });
    });
    function searchData(query, action, that){
        $('#overlay').fadeIn();
        var data = {};
        data.q = query;
        if($('body').find('.filter').length){
            var list_filter_name = ['asset_class','property_status','identified_risks','system_type','equipment_types','status','accessibility'];
            $.each(list_filter_name, function(k,v){
                if($('body').find('input[type="checkbox"][name="'+v+'[]"]').length){
                    var checked_ids = [];
                    $.each($('body').find('input[type="checkbox"][name="'+v+'[]"]'), function(k1,v1){
                        if($(v1).prop('checked')){
                            checked_ids.push($(v1).val());
                        }
                    });
                    if(checked_ids.length > 0){
                        data[v] = checked_ids.join(",");
                    }
                } else if($('body').find('select[name="'+v+'[]"]').length){
                    var checked_ids = [];
                    checked_ids = $('body').find('select[name="'+v+'[]"]').val();
                    if(checked_ids.length > 0){
                        data[v] = checked_ids.join(",");
                    }
                }
            })
        }
        $('body').find('.list-data').html('');
        $.ajax({
            type: "GET",
            url: action,
            data: data,
            cache: false,
            success: function (html) {
                var append = html;
                $('body').find('.list-data').html($(append).find('.list-data').children());
                $('#overlay').fadeOut();
            }
        });
    }
</script>
@endpush
