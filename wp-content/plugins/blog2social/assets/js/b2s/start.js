jQuery.noConflict();

jQuery(document).on('click', '.b2s-mail-btn', function () {
    if (isMail(jQuery('#b2s-mail-update-input').val())) {
        jQuery.ajax({
            url: ajaxurl,
            type: "POST",
            dataType: "json",
            cache: false,
            data: {
                'action': 'b2s_post_mail_update',
                'email': jQuery('#b2s-mail-update-input').val(),
                'lang': jQuery('#user_lang').val()
            }
        });
        jQuery('.b2s-mail-update-area').hide();
        jQuery('.b2s-mail-update-success').show();
    } else {
        jQuery('#b2s-mail-update-input').addClass('error');
    }
    return false;
});


jQuery(window).on("load", function () {
    jQuery('.b2s-faq-area').show();
    if (typeof wp.heartbeat == "undefined") {
        jQuery('#b2s-heartbeat-fail').show();
    }
    jQuery.ajax({
        url: ajaxurl,
        type: "POST",
        dataType: "json",
        cache: false,
        data:{
            'action' : 'b2s_get_faq_entries'
        },
        error: function () {
            jQuery('.b2s-faq-area').hide();
            return false;
        },
        success: function (data) {
            if (data.result == true) {
                jQuery('.b2s-loading-area-faq').hide();
                jQuery('.b2s-faq-content').html(data.content);
            } else {
                jQuery('.b2s-faq-area').hide();
            }
        }
    });

});

/* Content-Widget */
(function (){
    if(jQuery('.b2s-dashboard-multi-widget').length > 0)
    {
        var data = [];

        jQuery.ajax({
            url: ajaxurl,
            type: "GET",
            dataType: "json",
            data: {
                'action': 'b2s_get_multi_widget_content',
            },
            success: function (content) {
                data = content;

                widget.data('position',new Date().getSeconds() % data.length);
                show();

                setInterval(function(){
                    jQuery('.b2s-dashboard-multi-widget .glyphicon-chevron-left').trigger("click");
                },30000);
            }

        });

        var widget = jQuery('.b2s-dashboard-multi-widget');

        jQuery('.b2s-dashboard-multi-widget .glyphicon-chevron-right').on("click",function(){
            widget.data('position',widget.data('position')*1+1);
            show(widget);
        });

        jQuery('.b2s-dashboard-multi-widget .glyphicon-chevron-left').on("click",function(){
            widget.data('position',widget.data('position')*1-1);
            show(widget);
        });

        function show()
        {
            if(widget.data('position') <0)
            {
                widget.data('position',data.length - 1);
            }
            else if(widget.data('position') > data.length-1)
            {
                widget.data('position',0);
            }

            var id = widget.data('position');

            widget.find('.b2s-dashboard-multi-widget-content').html(data[id]['content']);
            widget.find('.b2s-dashboard-h5').text(data[id]['title']);
        }
    }
})();

/* Aktivity-Chart*/
jQuery(document).ready(function(){
        function drawBasic() {
            jQuery('#chart_div').html("<div class=\"b2s-loading-area\">\n" +
                "        <br>\n" +
                "        <div class=\"b2s-loader-impulse b2s-loader-impulse-md\"></div>\n" +
                "        <div class=\"clearfix\"></div>\n" +
                "    </div>");
            jQuery.ajax({
                url: ajaxurl,
                type: "GET",
                dataType: "json",
                data: {
                    'action': 'b2s_get_stats',
                    'from': jQuery('#b2s-activity-date-picker').val()
                },
                success: function (content) {
                    jQuery('#chart_div').html("<canvas id=\"b2s_activity_chart\" style=\"max-width:690px !important; max-height:320px !important;\"></canvas>");
                    var ctx = document.getElementById("b2s_activity_chart").getContext('2d');
                    var published = [];
                    var published_colors = [];
                    var scheduled = [];
                    var scheduled_colors = [];

                    function dateToYMD(date) {
                        var d = date.getDate();
                        var m = date.getMonth() + 1;
                        var y = date.getFullYear();
                        return '' + y + '-' + (m <= 9 ? '0' + m : m) + '-' + (d <= 9 ? '0' + d : d);
                    }

                    function dateToDMY(date) {
                        var d = date.getDate();
                        var m = date.getMonth() + 1;
                        var y = date.getFullYear();
                        return '' + (d <= 9 ? '0' + d : d) + '.' + (m <= 9 ? '0' + m : m) + '.' + y;
                    }

                    jQuery(Object.keys(content)).each(function () {
                        if (published.length > 0) {
                            var diff = parseInt((new Date(published[published.length - 1].x).getTime() - new Date(this).getTime()) / (24 * 3600 * 1000));

                            while (diff < -1) {
                                var date = new Date(published[published.length - 1].x.toString());
                                var newDate = new Date(date.setTime(date.getTime() + 86400000));
                                published.push({x: dateToYMD(newDate), y: 0});
                                published_colors.push('rgba(121,178,50,0.8)');
                                scheduled_colors.push('rgba(192,192,192,0.8)');
                                scheduled.push({x: dateToYMD(newDate), y: 0});

                                diff = parseInt((new Date(published[published.length - 1].x).getTime() - new Date(this).getTime()) / (24 * 3600 * 1000));
                            }
                        }

                        published.push({x: this.toString(), y: content[this][0]});
                        published_colors.push('rgba(121,178,50,0.8)');
                        scheduled_colors.push('rgba(192,192,192,0.8)');
                        scheduled.push({x: this.toString(), y: content[this][1]});
                    });

                    var unit = "day";
                    if(published.length > 100)
                    {
                        unit = "month";
                    }

                    var myChart = new Chart(ctx, {
                        type: 'bar',
                        data: {
                            datasets: [{
                                label: jQuery("#chart_div").data('text-published'),
                                data: published,
                                backgroundColor: published_colors
                            }, {
                                label: jQuery("#chart_div").data('text-scheduled'),
                                data: scheduled,
                                backgroundColor: scheduled_colors
                            }]
                        },
                        options: {
                            tooltips: {
                                callbacks: {
                                    title: function(tooltipItem) {
                                        if(jQuery("#chart_div").data('language') == "de") {
                                            var date = new Date(tooltipItem[0].xLabel);
                                            return dateToDMY(date);
                                        }
                                        else{
                                            return tooltipItem[0].xLabel
                                        }
                                    }
                                }
                            },
                            scales: {
                                xAxes: [{
                                    type: "time",
                                    time: {
                                        unit: unit
                                    }
                                }
                                ],
                                yAxes: [{
                                    ticks: {
                                        beginAtZero: true
                                    }
                                }]
                            }
                        }
                    });
                }

            });
        }
        drawBasic();

        jQuery('#b2s-activity-date-picker').b2sdatepicker({
            'autoClose': true,
            'toggleSelected': true,
            'minutesStep': 15
        });
        jQuery('#b2s-activity-date-picker').on("selectDate", function(){
            setTimeout(drawBasic);
        });
});



function isMail(mail) {
    var regex = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
    return regex.test(mail);
}

