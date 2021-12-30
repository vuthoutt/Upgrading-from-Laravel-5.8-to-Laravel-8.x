$(document).ready(function(){

    $(function () {
        $('[data-toggle="tooltip"]').tooltip()
    })
    $("#organisation").change(function(){
        $('#department').find('option').remove();
        var organisation = $("#organisation").val();
        $.ajax({
            type: "GET",
            url: "/client-departments/" + organisation,
            data: {},
            cache: false,
            success: function (html) {
                $.each( html, function( key, value ) {
                    $('#department').append($('<option>', {
                        value: value.id,
                        text : value.name
                    }));
                });
                if (organisation == 1) {
                    $('#department').append($('<option>', {
                        value: -1,
                        text : 'Other'
                    }));
                }
            }
        });
    });
            var departmentOther = $("#department").val();
            if (departmentOther == -1){
                $("#departmentOther").val("");
                $("#departmentOther").show();
            }else{
                $("#departmentOther").val("");
                $("#departmentOther").hide();
            }
    $("#department").change(function(){
            var departmentOther = $("#department").val();
            if (departmentOther == -1){
                $("#departmentOther").val("");
                $("#departmentOther").show();
            }else{
                $("#departmentOther").val("");
                $("#departmentOther").hide();
            }
        });


    // keep active tab on refresh action
    $('a[data-toggle="tab"]').on('shown.bs.tab', function(e) {
    localStorage.setItem('activeTab', $(e.target).attr('href'));
    });
    var activeTab = localStorage.getItem('activeTab');
    if (activeTab) {
       $('#myTab a[href="' + activeTab + '"]').tab('show');
    }

     $('.panel-collapse').on('show.bs.collapse', function () {
        $(this).siblings('.panel-heading').addClass('active');
      });

      $('.panel-collapse').on('hide.bs.collapse', function () {
        $(this).siblings('.panel-heading').removeClass('active');
      });

    $(".alert").fadeTo(3000, 500).slideUp(500, function(){
        $(".alert").slideUp(500);
    });

    $('.shine_submit_modal').click(function() {
     $(this).prop('disabled',true);
        setTimeout(function(){
          $('.shine_submit_modal').prop('disabled', false);
        },1000);
     });

    $('.form-shine').submit(function(){
        $(this).find('button[type=submit]').prop('disabled', true);
        setTimeout(function(){
          $(this).find('button[type=submit]').prop('disabled', false);
        },1000);
    });
      // back to top btn
      $(window).scroll(function () {
         if ($(this).scrollTop() > 50) {
             $('#back-to-top').fadeIn();
         } else {
             $('#back-to-top').fadeOut();
         }
     });
     // scroll body to 0px on click
     $('#back-to-top').click(function () {
         $('body,html').animate({
             scrollTop: 0
         }, 400);
         return false;
     });

     //for autocomplete in navigation
    $('.search-item').on('change',function (e) {

        // update query
    });


    $( ".search-item" ).autocomplete({
        source: function( request, response ) {
            var options = [];
            $('.check1:checked').each(function(){
                options.push($(this).val());
            });
            $.ajax({
                dataType: "json",
                type : 'Get',
                url: $(".search-item" ).data('url'),
                data:{
                    q : $( ".search-item" ).val(),
                    options : options.join(',')
                },
                success: function(data) {
                    $('input.suggest-user').removeClass('ui-autocomplete-loading');
                    if(data.length == 0){
                        var no_result = {label: "No Result",value:"","desc":""};
                        data.push(no_result);
                        console.log(data);
                    }
                    response( data) ;
                },
                error: function(data) {
                    $('input.suggest-user').removeClass('ui-autocomplete-loading');
                }
            });
        },
        minLength: 1,
        delay: 0,
        open: function() {
            $("ul.ui-menu").width( $(this).innerWidth() +100 );
        },
        close: function() {},
        focus: function(event,ui) {},
        select: function(event, ui) {
            //redirect to
            if(ui.item.value != "No Result"){
                window.location.href = ui.item.value;
            }
            return false;
        }
    })
    .autocomplete( "instance" )._renderItem = function( ul, item ) {
        return $( "<li>" )
            .append( "<div style='font: menu;font-size: 12px;line-height: 16px;'>" + item.label + "<br><span>" + item.desc + "</span></div>" )
            .appendTo( ul );
    };

    //check/uncheck all search
    //Toogle Checkbox
    var filterCheckbox = true;
    $("#toogle-filter-checkboxes").click(function () {
        var val = $(this).val();
        var checkBoxes = $(".check1");
        if (val == 0) {
            checkBoxes.prop("checked", true);
            $(this).html("<strong>Un-check All</strong></a>");
            $(this).val(1);
            $('.ul-search .check1').each(function () {
                let box = $(this);
                localStorage.setItem('search-' + box.val(), true);
            });
        }
        else {
            checkBoxes.prop("checked", false);
            $(this).html("<strong>Check All</strong></a>");
            $(this).val(0);
            localStorage.clear();
        }
    });

    $('.dropdown-toggle1').on('click', function (e) {
        // $(this).next().toggle();
    });
    $('.dropdown-menu').on('click', function (e) {
        e.stopPropagation();
    });

    // Store checked item search
    storeCheckboxesSearch();
    function storeCheckboxesSearch() {
        // Select all the document elements with class .chbox
        let cboxes = document.querySelectorAll('.ul-search .check1');

        // Responsible to handle the user click on the selected checkboxes.
        let handleCheckBoxEvent = function (e) {
            var checkedBoxes = [];
            // If the checkbox is checked
            if (e.target.checked) {
                // Then save the value in the local storage.
                localStorage.setItem('search-' + e.target.value, e.target.checked);
            } else {
                // Else, if the checkbox is unchecked, remove the value from the local storage
                localStorage.removeItem('search-' + e.target.value);
            }

            // change check/ uncheck all button when check/uncheck item search
            $('.ul-search .check1:checked').each(function () {
                checkedBoxes.push(this.value);
            });
            if (checkedBoxes.length > 0) {
                $("#toogle-filter-checkboxes").html("<strong>Un-check All</strong></a>");
                $("#toogle-filter-checkboxes").val(1);
            } else {
                $("#toogle-filter-checkboxes").html("<strong>Check All</strong></a>");
                $("#toogle-filter-checkboxes").val(0);
            }
        };

        // If more than 0 checkboxes found
        if (0 < cboxes.length) {
            var hasChecked = false;
            // Iterate over the found elements
            cboxes.forEach(
                cb => {
                    // If the current iterated checkbox name exists in the local storage
                    if (localStorage.getItem('search-' + cb.value)) {
                        // Add the checked property so the checkbox appears as checked.
                        cb.setAttribute('checked', 'checked');
                        hasChecked = true;
                    }

                    // Add an event listener to the current iterated checkbox and bind it with the handleCheckBoxEvent function.
                    cb.addEventListener(
                        'change',
                        handleCheckBoxEvent
                    );
                }
            );
            if (hasChecked) {
                $("#toogle-filter-checkboxes").html("<strong>Un-check All</strong></a>");
                $("#toogle-filter-checkboxes").val(1);
            } else {
                $("#toogle-filter-checkboxes").html("<strong>Check All</strong></a>");
                $("#toogle-filter-checkboxes").val(0);
            }
        }
    }

    // store check/uncheck all search
    function storeCheckAllSearch() {
        if (localStorage.getItem('check-all-search')) {
            $('.ul-search .check1').each(function () {
                let box = $(this);
                box.prop('checked', true);
            });
            $("#toogle-filter-checkboxes").html("<strong>Un-check All</strong></a>");
            $("#toogle-filter-checkboxes").val(1);
        } else {
            localStorage.clear();
            $("#toogle-filter-checkboxes").html("<strong>Check All</strong></a>");
            $("#toogle-filter-checkboxes").val(0);
        }
    }
});

// function callBackAutoComplete(res){
//
// }
