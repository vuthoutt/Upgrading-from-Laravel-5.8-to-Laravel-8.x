<!-- Modal -->
<div class="modal fade" id="{{ isset($modal_id) ? $modal_id : '' }}" tabindex="-1" role="dialog" data-keyboard="false" data-backdrop="static" >
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header {{ isset($color) ? CommonHelpers::getNavColor($color) : 'red_gradient' }}">
                <h5 class="modal-title" id="exampleModalLabel">{{ $header }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form method="POST" id="form-approval-wr-{{ $type }}" class="form-shine">
            @csrf
            <meta name="csrf-token" content="{{ csrf_token() }}" />
            <div class="modal-body" style="padding-left: 35px;padding-top: 30px;text-align: center;">
                <input type="hidden" id="work-id-{{ $type }}" value="">
                <input type="hidden" id="sor-id-{{ $type }}" value="">
                <strong id="title-work-{{ $type }}" ></strong>
                <div class="spanWarningInsufficient mt-3" style="  width: 90% !important;margin-left: 30px;white-space: none;padding: 15px">
                    <span>You will need to manually create this job!</span>
                </div>
                <div class="mt-3 mb-4" id="job_orchard">
                    <h5 id="title_api"></h5>
                    <div class="progress mt-3 mb-3" style="width: 50%;margin-left:25%">
                      <div class="progress-bar progress-bar-striped bg-warning" id="progress_bar" role="progressbar" style="width: 0%" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
                    </div>
                    <strong id="text_process">Processing ... </strong>
                </div>
            </div>
            <div class="spanWarningSurveying error-mess" style="   width: 90% !important;margin-left: 30px;white-space: pre-line;">
                <span>Failed to creating Job. Please try again!</span>
                <p id="error_orchard" class="mt-2">Error</p>
            </div>
            <div class="more_infor row mt-2" style="width: 90% !important;margin-left: 30px;">
                <button type="button" href="#" id="more_info" class="btn light_grey_gradient">More info</button>
            </div>
            <div class="mt-3" style="text-align: center;" id="wr_button-{{ $type }}">
                <button type="button" class="btn light_grey_gradient" data-dismiss="modal">Cancel</button>
                <button type="button" id="create_job" class="btn light_grey_gradient">Submit</button>
            </div>
            <div class="mb-4"></div>
            </form>
        </div>
    </div>
</div>
@push('javascript')

<script type="text/javascript">
$(document).ready(function(){
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    // triggered when modal is about to be shown
    $('#{{ isset($modal_id) ? $modal_id : '' }}').on('show.bs.modal', function(e) {
        var work_id = $(e.relatedTarget).data('work-id');
        var work_ref = $(e.relatedTarget).data('work-ref');
        var sor_code = $(e.relatedTarget).data('sor-code');
        console.log(sor_code);
        $("#sor-id-{{ $type }}").val(sor_code);
        if(sor_code > 0){
            $('.spanWarningInsufficient').hide();
        } else {
            $('.spanWarningInsufficient').show();
        }
        var type = $(e.relatedTarget).data('type');

        $('#title-work-{{ $type }}').html('Are you sure that you want to approval work ' + work_ref + ' ?');
        $('#form-approval-wr-{{ $type }}').attr('action', '/work-request/approval/' + work_id);
        $('#work-id-{{ $type }}').val(work_id);
    });
    $("#more_info").click(function(){
        $("#error_orchard").toggle();
    });

    $('#job_orchard').hide();
    $('#error_orchard').hide();
    $('.error-mess').hide();
    $('.more_infor').hide();
    $('#create_job').click(function(e){
        var sor_code = $("#sor-id-{{ $type }}").val();
        if(sor_code > 0){
            var work_id = $('#work-id-{{ $type }}').val();
            var work_id = $('#work-id-{{ $type }}').val();
            var user_id = {{ \Auth::user()->id ?? 0 }};
            $('#job_orchard').show();
            $('#title-work-{{ $type }}').hide();
            $('#wr_button-{{ $type }}').hide();
            $("#title_api").html('Find default fields from SOR User Code');
            $('#progress_bar').css('width','2%');
            $.ajax
            ({
                type: "GET",
                url: "/orchard/api_no1?work_id=" + work_id,
                cache: false,
                success: function (result) {
                    if(result){

                        if(result.error == false){
                            $('#progress_bar').css('width','17%');
                            createJobRecord(
                                result.description1,
                                result.expenseCode,
                                result.tradeCode,
                                result.priorityCode,
                                result.sorTypeCode,
                                result.volumeCDE,
                                result.sorNum,
                                result.jobID,
                                work_id,
                                user_id
                            );
                        } else {
                            $('.error-mess span').html(result.message);
                            $('.error-mess').show();
                            $('.more_infor').show();
                            $('#error_orchard').html(result.orchard_err_message);
                            $('#job_orchard').hide();
                        }
                    }
                }
            });
        } else {
            console.log(222);
            console.log($("#form-approval-wr-{{ $type }}"));
            $("#form-approval-wr-{{ $type }}")[0].submit();
        }
    });

    // 2) Create Job Record
    function createJobRecord(description1, expenseCode, tradeCode, priorityCode, sorTypeCode, volumeCDE, sorNum, jobID, work_id,user_id) {
        $("#title_api").html('Create Job Record');
        $('#progress_bar').css('width','18%');
        $.ajax
        ({
            type: "POST",
            url: "/orchard/api_no2",
            data:{description1:description1,
                    expenseCode:expenseCode,
                    tradeCode:tradeCode,
                    priorityCode:priorityCode,
                    sorTypeCode:sorTypeCode,
                    volumeCDE:volumeCDE,
                    sorNum:sorNum,
                    work_id:work_id,
                    jobID:jobID,
                    userID:user_id},
            cache: false,
            success: function (result) {
                if(result){

                    if(result.error == false){
                        $('#progress_bar').css('width','34%');
                        addExtendedText(result.job_number, result.departmentCode, jobID, work_id);
                    } else {
                        $('.error-mess span').html(result.message);
                        $('.error-mess').show();
                        $('.more_infor').show();
                        $('#error_orchard').html(result.orchard_err_message);
                        $('#job_orchard').hide();
                    }
                }
            }
        });
    }

    // 3)  Add Extended Text
    function addExtendedText(job_number, departmentCode, jobID, work_id) {
        $("#title_api").html('Add Extended Text');
        $('#progress_bar').css('width','50%');
        $.ajax
        ({
            type: "GET",
            url: "/orchard/api_no3?job_number=" + job_number
                + "&departmentCode=" + departmentCode
                + "&work_id=" + work_id
                + "&jobID=" + jobID,
            cache: false,
            success: function (result) {
                if(result){

                    if(result.error == false){
                        $('#progress_bar').css('width','51%');
                        determineContact(result.job_number, result.departmentCode, jobID, work_id);
                    } else {
                        $('.error-mess span').html(result.message);
                        $('.error-mess').show();
                        $('.more_infor').show();
                        $('#error_orchard').html(result.orchard_err_message);
                        $('#job_orchard').hide();
                    }
                }
            }
        });
    }

    // 4)    Determine contract required (+JOB_CONTRACT).
    function determineContact(job_number, departmentCode, jobID, work_id) {
        $("#title_api").html('Determine contract required');
        $('#progress_bar').css('width','52%');
        $.ajax
        ({
            type: "GET",
            url: "/orchard/api_no4?job_number=" + job_number + "&departmentCode=" + departmentCode + "&work_id=" + work_id + "&jobID=" + jobID,
            cache: false,
            success: function (result) {
                if(result){

                    if(result.error == false){
                        $('#progress_bar').css('width','69%');
                        retrieveTimestamp(result.job_number, result.departmentCode, result.contractNumber, jobID, work_id);
                    } else {
                        $('.error-mess span').html(result.message);
                        $('.error-mess').show();
                        $('.more_infor').show();
                        $('#error_orchard').html(result.orchard_err_message);
                        $('#job_orchard').hide();
                    }
                }
            }
        });
    }

    // 5)   Retrieve current timestamp.
    function retrieveTimestamp(job_number, departmentCode, contractNumber, jobID, work_id) {
        $("#title_api").html('Retrieve current timestamp');
        $('#progress_bar').css('width','70%');
        $.ajax
        ({
            type: "GET",
            url: "/orchard/api_no5?job_number=" +job_number + "&contractNumber=" + contractNumber + "&departmentCode=" + departmentCode + "&work_id=" + work_id + "&jobID=" + jobID,
            cache: false,
            success: function (result) {
                if(result){

                    if(result.error == false){
                        $('#progress_bar').css('width','87%');
                        updateJobRecord(result.job_number, result.timestamp, result.departmentCode, contractNumber, jobID, work_id);
                    } else {
                        $('.error-mess span').html(result.message);
                        $('.error-mess').show();
                        $('.more_infor').show();
                        $('#error_orchard').html(result.orchard_err_message);
                        $('#job_orchard').hide();
                    }
                }
            }
        });
    }

    // 6)  Update Job Record with the correct contract (+JOB) and take off hold.
    function updateJobRecord(job_number, time_stamp, departmentCode, contractNumber, jobID, work_id) {
        $("#title_api").html('Update Job Record with the correct contract (+JOB) and take off hold');
        $('#progress_bar').css('width','88%');
        $.ajax
        ({
            type: "GET",
            url: "/orchard/api_no6?job_number=" +job_number +  "&timestamp=" + time_stamp +  "&departmentCode=" + departmentCode +  "&contractNumber=" + contractNumber + "&work_id=" + work_id + "&jobID=" + jobID,
            cache: false,
            async : true,
            success: function (result) {
                if(result){

                    if(result.error == false){
                        $('#progress_bar').css('width','90%');
                        $('#progress').hide();
                        $('#text_process').hide();
                        $('#title_api').html('Created Job successfully!');
                        approveWorkRequest(work_id);
                        // window.location.href = 'work_request.php';
                    } else {
                        $('.error-mess').show();
                        $('#job_orchard').hide();
                    }
                }
            }
        });
    }
    function approveWorkRequest(work_id) {
        $.ajaxSetup({
          headers: {
              'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          }
        });
        $.ajax
        ({
            type: "POST",
            url: "/work-request/approval/" + work_id,
            cache: false,
            async : true,
            success: function (result) {
                $('#progress_bar').css('width','100%');
                $('#progress').hide();
                $('#text_process').hide();
                $('#title_api').html('Approval Work Request Successful!');
                $("#{{ isset($modal_id) ? $modal_id : '' }}").modal('hide');
            }
        });
    }

    $("#{{ isset($modal_id) ? $modal_id : '' }}").on("hidden.bs.modal", function () {
        location.reload();
    });
});
</script>
@endpush
