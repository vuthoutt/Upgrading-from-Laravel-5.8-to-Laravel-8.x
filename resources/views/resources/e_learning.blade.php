@extends('shineCompliance.layouts.app')
@section('content')
@include('partials.nav', ['breadCrumb' => 'e_learning','data' => ''])

<div class="container prism-content">
    <h3 class="ml-3">
        E-Learning Details
    </h3>
    <div class="main-content row">
        <!-- Show picture E Learning -->
{{--         @if(!\CompliancePrivilege::checkPermission(JR_RESOURCES,JR_E_LEARNING_AWARENESS) and \CommonHelpers::isSystemClient())
        @else
            <div class="col-md-3">
                <div class="property-opt" style="height: 304px;">
                    <a class="text-decoration-none" href="#" >
                        <div class="unit-operative">
                            <img class="img-client-operative" src="{{ asset('img/ukata_santia.png') }}" alt="Property Image" style="width:100%">
                        </div>
                        <div class="property-opt-des">
                            <em class="des-field"></em>
                        </div>
                        <div class="name-field" title="">
                            <strong class="name">Asbestos Awareness Training</strong>
                        </div>
                    </a>
                    <div class="download-operative">
                        <div style="float: left">
                            <button name="submit" id="e_enroll" class="btn btn_e_learning light_grey_gradient" style="width: 115px">
                                <strong>Enroll</strong>
                            </button>
                        </div>
                        <div style="float: right;margin-right: -5px">
                            <button id="e_begin" class="btn btn_e_learning light_grey_gradient" style="width: 115px" {{ $disable ?? '' }}>
                                <strong>Begin</strong>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        @endif --}}

        @if(!\CompliancePrivilege::checkPermission(JR_RESOURCES,JR_E_LEARNING_SITE_OPERATIVE) and \CommonHelpers::isSystemClient())
        @else
            <div class="col-md-3">
                <div class="property-opt">
                    <a class="text-decoration-none" href="#" >
                        <div class="unit-operative">
                            <img class="img-client-operative" src="{{ asset('img/shine_vision.png') }}" alt="Property Image" style="width:100%">
                        </div>
                        <div class="property-opt-des">
                            <em class="des-field"></em>
                        </div>
                        <div class="name-field" title="">
                            <strong class="name">Site Operative View Training</strong>
                        </div>
                    </a>
                    <div class="download-operative">
                        <a href="#" id="training_begin" class="btn btn_e_learning light_grey_gradient" style="width: 100%">
                           <strong>Begin</strong>
                       </a>
                   </div>
               </div>
           </div>
        @endif

        @if(!\CompliancePrivilege::checkPermission(JR_RESOURCES,JR_E_LEARNING_PROJECT_MANAGER) and \CommonHelpers::isSystemClient())
        @else
           <div class="col-md-3">
                <div class="property-opt">
                    <a class="text-decoration-none" href="#" >
                        <div class="unit-operative">
                            <img class="img-client-operative" src="{{ asset('img/shine_vision.png') }}" alt="Property Image" style="width:100%">
                        </div>
                        <div class="property-opt-des">
                            <em class="des-field"></em>
                        </div>
                        <div class="name-field" title="">
                            <strong class="name">Project Manager Training</strong>
                        </div>
                    </a>
                    <div class="download-operative">
                        <a href="#" id="training_project_begin" class="btn btn_e_learning light_grey_gradient" style="width: 100%">
                           <strong>Begin</strong>
                       </a>
                   </div>
                </div>
            </div>
        @endif
        <!-- END picture E Learning-->

        <!-- Confirm Modal -->
        <div class="modal" id="confirm-enroll" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header red_gradient">
                        <h5 >Confirmation </h5>
                    </div>
                    <div class="modal-body"></div>
                    <div class="modal-footer">
                        <button type="submit" class="btn light_grey_gradient">Confirm</button>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal" id="pleaseWaitDialog" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header red_gradient">
                        <h5>Processing...</h5>
                    </div>
                    <div class="modal-body" style="text-align: center;">
                        <div class="spinner-border text-warning" role="status">
                          <span class="sr-only">Loading...</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="container" id="export-preview">
        <div class="videoWrapper">
            <iframe frameborder="0" marginheight="0" marginwidth="0" src="" style="width: 100%;height: 602px;"></iframe>
        </div>
        <div class="row-fluid offset-top10">
            <a href="javascript:GoBack();" class="btn  light_grey_gradient mt-3 ml-3" id="btnGoBack">Go Back</a>
        </div>
    </div>
</div><!--/.container-->

@endsection
@push('javascript')
<script>
    $(document).ready(function(){

        $("#export-preview").hide();

        $('#confirm-enroll').click(function(){
            $('#confirm-enroll').modal('hide');
        })
        $('#training_begin').click(function(){
            //setting url for iframe
            var url = "{{ asset("/resources/training-video/site_operative?file_name=ShineVision_SiteOp_eLearning_V2.mp4") }}";
            $("#export-preview iframe").attr("src", url);
            $("#export-preview").show();
            $(".main-content").hide();
        })

        $('#training_project_begin').click(function(){
            //setting url for iframe
            var url = "{{ asset("/resources/training-video/project?file_name=SV_FILM_02_Project_Management_BSL_Final_1.mp4") }}";
            $("#export-preview iframe").attr("src", url);
            $("#export-preview").show();
            $(".main-content").hide();
        })
    });

    $("#export-form").show();

    function GoBack() {
        $("#export-preview iframe").attr("src", "");
        $("#export-preview").hide();
        $(".main-content").show();
    }

        $('#e_enroll').click(function(){
            $('#pleaseWaitDialog').modal('show');
            $.ajax
            ({
                type: "GET",
                url: "/resources/e-learning/enroll/"+{{ \Auth::user()->id }},
                dataType: "JSON",
                cache: false,
                success: function (result) {
                    $('#pleaseWaitDialog').modal('hide');
                    if(result.status_code != {{ STATUS_OK }}){
                        $('#confirm-enroll').find('.modal-body').html('There was a problem processing your request. Please try again later.')
                    } else {
                        $('#confirm-enroll').find('.modal-body').html(result.msg);
                        $('#e_begin').prop("disabled", false);
                    }
                    $('#confirm-enroll').modal('show');
                }
            });
        });

        $('#e_begin').click(function(e){
            e.preventDefault();
            $('#pleaseWaitDialog').modal('show');

            $.ajax
            ({
                type: "GET",
                url: "/resources/e-learning/begin/"+{{ \Auth::user()->id }},
                dataType: "JSON",
                cache: false,
                success: function (result) {
                    $('#pleaseWaitDialog').modal('hide');
                    if (result.error) {
                        $('#confirm-enroll').find('.modal-body').html('There was a problem processing your request. Please try again later.');
                        $('#confirm-enroll').modal('show');
                    } else {
                        if (result.url) {
                            //setting url for iframe
                            $("#export-preview iframe").attr("src", result.url);
                            $("#export-preview").show();
                            $(".main-content").hide();
                        }
                    }
                }
            });
        });
</script>
@endpush
