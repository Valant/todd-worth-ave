(function($) {

    function setupPage() {


        var page = {

            page: 0,
            pages: 0,
            total: 0,
            processed: 0,

            action: null,
            url: null,

            btnStart: $('#data-action-start'),
            btnStop: $('#data-action-stop'),
            progressbar: $('#loadvessel_progressbar'),
            progressPercent: $('#loadvessel-bar-percent'),
            progressPercentValue: $('#loadvessel-progressbar-value'),
            debuglist: $('#loadvessel-debuglist'),

            doStop: false,

            init: function() {
                page.btnStart.click(page.run);
                page.btnStop.click(page.stop);
            },
            run: function() {
                page.total = 0;
                page.pages = 0;
                page.page = 1;
                page.processed = 0;
                var actionChecked = $('input[name="action"]:checked');
                if (!actionChecked.length) {
                    alert('need to select action');
                    return;
                }
                page.action = actionChecked.val();
                page.progressbar.show();
                page.btnStart.hide();
                page.btnStop.show();
                page.doStop = false;
                page.query();
            },
            stop: function () {
                page.doStop = true;
            },
            query: function() {
                $.ajax({
                    url: ajaxurl,
                    dataType: 'json',
                    data: {
                        action: page.action,
                        page: page.page
                    },
                    success: function(data) {
                        page.pages = data.totalPages;
                        page.total = data.total;
                        page.processed += data.count;
                        page.updateProgress();
                        if (page.doStop) {
                            alert('Stopped');
                            page.stopped();
                            return;
                        }
                        if (page.page == page.pages || !data.count) {
                            page.finished();
                        } else {
                            page.page++;
                            page.query();
                        }
                    },
                    error: function() {
                        page.stopped();
                        alert('Stopped due to an error');
                    }
                });
            },
            updateProgress: function() {
                var percent =  Math.round( ( page.processed / page.total ) * 1000 ) / 10 + "%";
                page.progressPercentValue.width( percent );
                page.progressPercent.html( percent );

                $('#loadvessel-debug-successcount').text(page.processed);

            },
            stopped: function() {
                page.btnStart.show();
                page.btnStop.hide();
            },
            finished: function() {
                page.stopped();
                alert('Done');
            }
        };


        page.init();

    }





    $(document).ready(function(){
        if ($('#data-actions-settings').length) {
            setupPage();
        }
    });

})(jQuery);