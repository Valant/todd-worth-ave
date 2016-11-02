// <![CDATA[
jQuery(document).ready(function($){
    var i;
    var rt_record_count = parseInt($('#loadvessel_record_count').val());
    var rt_total      = parseInt($('#loadvessel_page_count').val());
    var rt_count      = 1;
    var rt_percent    = 0;
    var rt_successes  = 0;
    var rt_errors     = 0;
    var rt_failedlist = '';
    var rt_resulttext = '';
    var rt_timestart  = new Date().getTime();
    var rt_timeend    = 0;
    var rt_totaltime  = 0;
    var rt_continue   = true;

    // Create the progress bar
    $("#loadvessel-bar-percent").html( "0%" );

    // Stop button
    $("#loadvessel-stop").click(function() {
        rt_continue = false;
        $('#loadvessel-stop').val(yatco_opt.i18n_stopping);
    });

    // Clear out the empty list element that's there for HTML valpage_idation purposes
    $("#loadvessel-debuglist li").remove();

    // Called after each resize. Updates debug information and the progress bar.
    function loadvesselUpdateStatus( page_id, success, response ) {
        var percent =  Math.round( ( rt_count / rt_total ) * 1000 ) / 10 + "%";
        $("#loadvessel-progressbar-value").width( percent );
        $("#loadvessel-bar-percent").html( percent );
        rt_count = rt_count + 1;

        if ( !success ) {
            rt_errors = rt_errors + 1;
            rt_failedlist = rt_failedlist + ',' + page_id;
            $("#loadvessel-debug-failurecount").html(rt_errors);
            $("#loadvessel-debuglist").append("<li>" + response.error + "</li>");
        }
        else {
            rt_successes = rt_successes + 10;
            $("#loadvessel-debug-successcount").html(rt_successes);
            $.each(response, function(index, val) {
                if(val.status == 'failed')
                    $("#loadvessel-debuglist").append("<li>#" + val.VesselID + " " + val.Boatname + " failed to load.</li>");
                else
                    $("#loadvessel-debuglist").append("<li>#" + val.VesselID + " " + val.Boatname + " successfully " + val.status + "</li>");
            });
        }
    }

    // Called when all images have been processed. Shows the results and cleans up.
    function loadvesselFinishUp() {
        rt_timeend = new Date().getTime();
        rt_totaltime = Math.round( ( rt_timeend - rt_timestart ) / 1000 );

        $('#loadvessel-stop').hide();

        if ( rt_errors > 0 ) {
            rt_resulttext = yatco_opt.i18n_failures;
            rt_resulttext = rt_resulttext.replace('%1$s', rt_successes );
            rt_resulttext = rt_resulttext.replace('%2$s', rt_totaltime );
            rt_resulttext = rt_resulttext.replace('%3$s', rt_errors );
        } else {
            rt_resulttext = yatco_opt.i18n_nofailures;
            rt_resulttext = rt_resulttext.replace('%1$s', rt_successes );
            rt_resulttext = rt_resulttext.replace('%2$s', rt_totaltime );
        }

        $("#message").html("<p><strong>" + rt_resulttext + "</strong></p>");
        $("#message").show();
    }

    // Regenerate a specified image via AJAX
    function reparse( page_id, postIds ) {
        $.ajax({
            type: 'POST',
            url: ajaxurl,
            dataType: "json",
            data: { action: "yatco_reparse_data", page_id: page_id, post_ids: postIds },
            success: function( response ) {

                if ( response !== Object( response ) ) {
                    response = new Object;
                    response.success = false;
                    response.error = yatco_opt.i18n_fatal_error;
                    loadvesselUpdateStatus( page_id, false, response );
                }else{

                    loadvesselUpdateStatus( page_id, true, response );

                    if ( rt_total != rt_count && rt_continue ) {
                        reparse( rt_count );
                    }
                    else {
                        loadvesselFinishUp();
                    }
                }
            },
            error: function( response ) {

                loadvesselUpdateStatus( page_id, false, response );

                if ( rt_total != rt_count && rt_continue ) {
                    reparse( rt_count );
                }
                else {
                    loadvesselFinishUp();
                }
            }
        });
    }
    $('#reparse-start').click(function(event) {


        var postIds = $('#reparse-post-id').val().split(',');

        rt_percent = 0;
        rt_count = 1;

        if (postIds.length && postIds[0] != '') {

            $('#loadvessel_progressbar').show();
            $('#loadvessel-start').hide();

            rt_continue = false;
            rt_record_count = postIds.length;
            rt_total = 1;

            reparse(rt_count, postIds);
        } else {
            rt_continue = true;
            rt_record_count = parseInt($('#loadvessel_record_count').val());
            rt_total      = parseInt($('#loadvessel_page_count').val());
            if (confirm('Are you sure you want to run process on whole database?')) {
                $('#loadvessel_progressbar').show();
                $('#loadvessel-start').hide();
                reparse( rt_count );
            }
        }


    });



});
// ]]>