@section('javascript')
    {{--{!! Html::style('assets/addons/Scheduler/Assets/css/jquery-ui-peppergrinder.min.css') !!}--}}
    {!! Html::style('assets/addons/Scheduler/Assets/css/jquery-ui-cupertino.min.css') !!}
    <style>
        .xdsoft_datetimepicker {
            z-index: 99999;
        }

        .xdsoft_datetimepicker .xdsoft_label {
            z-index: 99999;
        }

        .reminder_date {
            position: relative;
            z-index: 10000;
        }

        /* .ui-dialog .ui-dialog-content {
             border: 0;
             padding: 20px;
             font-size:18px;
             color: #000000;
             background-color: #ebebeb;
             overflow: auto;
         }*/

        .ui-widget {
            position: relative;
            z-index: 10000;
        }

        .ui-pnotify {
            z-index: 1041
        }

    </style>
    {!! HTML::style('assets/addons/Scheduler/Assets/fullcalendar/dist/fullcalendar.min.css') !!}
    {{-- bug introduced in laravel collective 5.5 https://github.com/LaravelCollective/html/issues/504 }}
    {{--{!! Html::style('assets/addons/Scheduler/Assets/fullcalendar/dist/fullcalendar.print.min.css',['media'=>'print']) !!}--}}
    <link href="/assets/addons/Scheduler/Assets/fullcalendar/dist/fullcalendar.print.min.css" rel="stylesheet" type="text/css" media="print" />
    {!! HTML::script('assets/addons/Scheduler/Assets/moment/min/moment.min.js') !!}
    {{-- customized to allow month view sort by category/start--}}
    {!! HTML::script('assets/addons/Scheduler/Assets/js/fullcalendar.mod.min.js') !!}
    {!! HTML::script('assets/addons/Scheduler/Assets/jquery-validation/dist/jquery.validate.min.js') !!}

    <script>
        $(document).ready(function () {
            $(".readonly").keydown(function (e) {
                e.preventDefault();
            });
            $("#addReminderCreate").click(function (event) {
                event.preventDefault();
                $("#addReminderCreate").html('<i class="fa fa-plus"></i>{{ trans('Scheduler::texts.add_another_reminder') }}');
                $("#addReminderShow").append($(".addReminderView").html());
            });
            $("#updateReminderCreate").click(function (event) {
                event.preventDefault();
                $("#updateReminderCreate").html('<i class="fa fa-plus"></i>{{ trans('Scheduler::texts.add_another_reminder') }}');
                $("#updateReminderShow").append($(".addReminderView").html());
            });

            @include('Scheduler::partials._js_saveCalendarEvent_js')
            @include('Scheduler::partials._js_updateCalendarEvent_js')

            /* init first - init first */
            $('#calEventDialog').dialog({autoOpen: false});
            $('#editEvent').dialog({autoOpen: false});
            $('#create-workorder').dialog({autoOpen: false});
            $(".from").datetimepicker({
                format: 'Y-m-d H:i',
                defaultTime: '08:00',
                step: {!! config('schedule_settings.timestep') !!},//15
                onClose: function (selectedDate) {
                    $(".to").datetimepicker({minDate: selectedDate});
                }
            });

            $('.to').datetimepicker({
                format: 'Y-m-d H:i',
                step: {!! config('schedule_settings.timestep') !!},
                onClose: function (selectedDate) {
                    $(".from").datetimepicker({maxDate: selectedDate});
                }
            });

            $(document).on('mousedown', '.reminder_date', function () {
                $(this).datetimepicker({
                    format: 'Y-m-d H:i',
                    defaultDate: '+1970/01/08', //plus 1 week
                    step: {!! config('schedule_settings.timestep') !!}
                });
            });

            $(document).on('click', '.delete_reminder', function () {
                $(this).parent().parent().remove();
            });

            $('#calendar').fullCalendar({
                themeSystem: '{!! config('schedule_settings.fcThemeSystem') !!}', //'jquery-ui' 'bootstrap3' 'standard'
                header: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'month,agendaWeek,agendaDay,listMonth,listWeek,listDay'
                },
                buttonText: {
                    month: 'Month',
                    week: 'Week',
                    day: 'Day',
                    listMonth: 'Month List',
                    listWeek: 'Week List',
                    listDay: 'Day List'
                },
                aspectRatio : {!! config('schedule_settings.fcAspectRatio') !!},//1.35 default
                // add button to day cell header
                @if(config('schedule_settings.createWorkorder'))
                //defaultView: 'agendaWeek',
                viewRender: function (view, element) {
                    // Clean up any previously added "dropdowns"
                    //$('.dropdown').remove();
                    // Only add "drowdowns" if the current view is the weekly view
                    //if (view.name === 'agendaWeek') {
                    // Add the "button" to the day headers
                    //var $headers = $('.fc-day-number.fc-sun,.fc-day-number.fc-mon,.fc-day-number.fc-tue, .fc-day-number.fc-wed, .fc-day-number.fc-thu, .fc-day-number.fc-fri, .fc-day-number.fc-sat');
                    var $headers = $('.fc-day-top');
                    $headers.css('position', 'relative');
                    //$headers.prepend("<div class='dropdown' style='position: absolute; left: 0'><i class='fa fa-plus'></i> </div>");
                    $headers.prepend("<div id='cwo'><button type='button' id='createWorkorder' class='btn btn-link btn-sm ' style='position: absolute; left: 0' title='{{ trans('Scheduler::texts.create_workorder') }}'><i class='fa fa-file-text-o' ></i></button> </div>");
                },
                @endif
                defaultDate: "{!! date('Y-m-d') !!}",
                @if($status == 'last')
                defaultDate: "{!! date('Y-m-d', strtotime("first day of previous month")) !!}",
                @elseif($status == 'next')
                defaultDate: "{!! date('Y-m-d', strtotime("first day of next month")) !!}",
                @else
                defaultDate: "{!! date('Y-m-d') !!}",
                @endif
                selectable: true,
                selectHelper: true,
                select: function (start, end) {
                    @if (!empty(config('workorder_settings.version')))
                    $("#title").autocomplete({
                        appendTo: "#calEventDialog",
                        source: "/scheduler/ajax/employee",
                        minLength: 2
                    }).autocomplete("widget").addClass("fixed-height");
                    @endif
                    $("#title").val('');
                    $("#description").val('');
                    // changed - momentjs object returning day before
                    //$("#from").val(dateFormat(start, "yyyy-mm-dd 08:00"));
                    $("#from").val(start.format('YYYY-MM-DD 08:00'));
                    $("#to").val(dateFormat(end, "yyyy-mm-dd 09:00"));
                    $("#category").val(2); //default to general appointment
                    $("#addReminderShow").html('');
                    $('#calEventDialog').dialog({
                        width: 500,
                        position: {my: 'center top', at: 'center+100 top+200', of: window}
                    });

                },
                dayClick: function (date, jsEvent, view) {
                    // IF BUTTON ICON SELECTED
                    if ($(jsEvent.target).hasClass("fa-file-text-o")) {

                        $.ajax(
                            {
                                url: '/scheduler/getResources/' + date.format('YYYY-MM-DD'),
                                type: 'get',
                                dataType: 'json',
                                //async: false,
                                cache: false,
                                success: function (data) {
                                    $.each(data.available_employees, function (k, v) {
                                        var cb = $('<input/>', {
                                            'type': 'checkbox',
                                            'id': 'worker',
                                            'name': 'workers[]',
                                            'value': k
                                        });
                                        if (v.indexOf("___D") >= 0) {//if driver ___D passed from json
                                            v = v.replace("___D", "");
                                            $("#wtable").append($('<label/>', {
                                                'style': 'display:block;color:blue',
                                                'text': v
                                            }).prepend(cb))
                                        } else {
                                            $("#wtable").append($('<label/>', {
                                                'style': 'display:block',
                                                'text': v
                                            }).prepend(cb))
                                        }
                                    });
                                    $.each(data.available_resources, function (k, v) {
                                        var cb = $('<input/>', {
                                            'type': 'checkbox',
                                            'id': 'resource',
                                            'name': 'resources[]',
                                            'value': v.id
                                        });
                                        $("#rtable").append($('<label/>', {
                                            'style': 'display:block',
                                            'text': v.name
                                        }).prepend(cb))
                                    });

                                }
                            });


                        $("#client_name").autocomplete({
                            appendTo: "#create-workorder",
                            source: "/scheduler/ajax/customer",
                            minLength: 3
                        }).autocomplete("widget").addClass("fixed-height");

                        $('#create-workorderform').validate({
                            submitHandler: function (form) {
                                form.submit();
                            }
                        });

                        $('#create-workorder').dialog({
                            width: 650,
                            position: {my: 'center top', at: 'center+100 top', of: window},
                            closeOnEscape: true,
                            buttons: {
                                "{{trans('Scheduler::texts.create_workorder')}}": function () {
                                    $('form#create-workorderform').submit();//send to validate
                                },
                                "{{trans('Scheduler::texts.cancel')}}": function () {
                                    $(this).dialog("close");
                                }
                            },
                            open: function () {
                                //$("#customer").val("");
                                //$("#jobsummary").val("");
                                $("#job_date").val(date.format('YYYY-MM-DD'));
                                $("#start_time").val(date.format('YYYY-MM-DD 08:00'));
                                $("#end_time").val(date.format('YYYY-MM-DD 09:00'));
                                //$("#will_call").val(0);
                            },
                            close: function () {
                                //$("#customer").autocomplete("instance").term = null;
                                $('#wtable').empty();
                                $('#rtable').empty();
                            }
                        });

                        $('#create-workorder').dialog('open');

                    } else {
                        $('#calEventDialog').dialog('open');
                    }
                },
                eventClick: function (event, element) {
                    // added to link to workorder
                    if (event.url) {
                        window.open(event.url, '_parent');
                        return false;
                    }
                    if (event.isrecurring === '1') {
                        pconfirm_def.text = '{{ trans('Scheduler::texts.recurring_event_warning') }}';
                        pconfirm_def.confirm.buttons = [{
                            text: 'OK, I understand...',
                            click: function (notice) {
                                notice.remove();
                            }
                        },
                            null];

                        new PNotify(pconfirm_def).get().on('pnotify.confirm', function () {
                            //Do Nothing
                        }).on('pnotify.cancel', function () {
                            //Do Nothing
                        });
                    }

                    $("#reminderShowFormCalendar").html('');
                    $("#updateReminderShow").html('');

                    if (event.reminder) {
                        var reminderHtml = '';
                        for (var key in event.reminder) {

                            reminderHtml += '<div class="reminder_delete_div"><div class="form-group">' +
                                '<hr class="col-sm-10 hr-clr-green"/>' +
                                '<span class="col-sm-1 pull-right reminder-cross delete_reminder" style="cursor: pointer"><i class="fa fa-times-circle"></i></span>' +
                                '</div><div class="form-group">' +
                                '<label for="reminder_date" class="col-sm-3 control-label">{{ trans('Scheduler::texts.reminder_date') }}</label>' +
                                '<div class="col-sm-9">' +
                                '<input type="text" name="reminder_date[]" class="form-control reminder_date " style="cursor: pointer" readonly value="' + event.reminder[key].reminder_date + '">' +
                                '<input type="hidden" name="reminder_id[]"  value="' + event.reminder[key].reminder_id + '">' +
                                '</div></div><div class="form-group">' +
                                '<label for="reminder_location" class="col-sm-3 control-label">{{ trans('Scheduler::texts.reminder_location') }}</label>' +
                                '<div class="col-sm-9">' +
                                '<input type="text" name="reminder_location[]" class="form-control" value="' + event.reminder[key].reminder_location + '">' +
                                '</div></div><div class="form-group">' +
                                '<label for="reminder_text" class="col-sm-3 control-label">{{ trans('Scheduler::texts.reminder_text') }}</label>' +
                                '<div class="col-sm-9">' +
                                '<textarea name="reminder_text[]" class="form-control" >' + event.reminder[key].reminder_text + '</textarea>' +
                                '</div></div></div>'
                        }
                        $("#reminderShowFormCalendar").html(reminderHtml);
                    }
                    $("#editTitle").val(event.title);
                    $("#editDescription").val(event.description);
                    $("#editID").val(event.id);
                    $("#editOID").val(event.oid);
                    $("#editStart").val(event.start._i);
                    $("#editEnd").val(event.end._i);
                    $("#editCategory").val(event.category);//defined inside laravel form
                    $('#editEvent').dialog({
                        width: 500,
                        position: {my: 'center top', at: 'center+100 top+200', of: window}
                    });
                    $('#editEvent').dialog('open');

                },
                // added mouseover
                eventMouseover: function (event, jsEvent) {
                    var rstr = "";
                            @if(config('schedule_settings.createWorkorder'))
                    var wrstr = "Workers: ";
                    if (event.willcall === '1') {
                        wrstr = "<span style='color:magenta'>Workers: </span>";
                    }
                    var erstr = "Resources: ";
                    if (event.hasOwnProperty("resource")) {
                        $.each(event.resource, function (key, value) {
                            if (value.resource_fid === '2' && value.resource_value) { //fid 2 is workers and not empty
                                wrstr += " " + value.resource_value;
                            }
                            if (value.resource_fid === '3') { //fid 3 is Resources
                                erstr += " " + value.resource_value;
                            }
                        });
                    }
                    if ((wrstr === "Workers: ") || (wrstr === "<span style='color:magenta'>Workers: </span>")) {
                        wrstr = "";
                    }
                    if (erstr === "Resources: ") {
                        erstr = "";
                    }
                    rstr = wrstr + "<br>" + erstr;
                            @endif
                    var tooltip = '<div class="tooltipevent" style="width:200px;background:#eee;position:absolute;z-index:10001;">'
                        + dateFormat(event.start, "mmm dd HH:MM", "Mountain") + ' to ' + dateFormat(event.end, "HH:MM", "Mountain") + '<br>'
                        + event.description
                        + '<br>'
                        + rstr
                        + '</div>';
                    $("body").append(tooltip);
                    $(this).mouseover(function (e) {
                        $(this).css('z-index', 10000);
                        $('.tooltipevent').fadeIn('500');
                        $('.tooltipevent').fadeTo('10', 1.9);
                    }).mousemove(function (e) {
                        $('.tooltipevent').css('top', e.pageY - $('.tooltipevent').height() / 2);//was + 10
                        $('.tooltipevent').css('left', e.pageX + 15);
                    });
                },

                eventMouseout: function (event, jsEvent) {
                    $(this).css('z-index', 8);
                    $('.tooltipevent').remove();
                },

                //editable: true,

                eventLimit: parseInt({!! config('schedule_settings.eventLimit') !!}), // true allows "more" link when too many events

                events: [
                        @foreach($events as $event)
                    {
                        //schedule
                        id: "{!! $event->id !!}",
                        title: "{!! $event->title !!}",
                        description: "{!! addslashes($event->description) !!}",
                        isrecurring: "{!! $event->isRecurring !!}",
                        category: "{!! $event->category_id !!}",
                        @if($event->category_id)
                        color: "{!! $catbglist[$event->category_id] !!}",
                        textColor: "{!! $cattxlist[$event->category_id] !!}",
                        @endif
                        url: "{!! $event->url !!}",
                        willcall: "{!! $event->will_call !!}",
                        //occurrences
                        oid: "{!! $event->oid !!}",
                        start: "{!! date('Y-m-d H:i', strtotime($event->start_date )) !!}",
                        end: "{!! date('Y-m-d H:i', strtotime($event->end_date )) !!}",
                        //resources
                        @if(!$event->resources->isEmpty())
                        resource: [
                                @foreach($event->resources as $resource)
                            {
                                resource_fid: "{!! $resource->fid !!}",
                                resource_value: "{!! $resource->value !!}"
                            },
                            @endforeach

                        ],
                        @endif
                        //reminders
                        @if(!$event->reminders->isEmpty())
                        reminder: [
                                @foreach($event->reminders as $reminder)
                            {
                                reminder_date: "{!! date('Y-m-d H:i', strtotime($reminder->reminder_date)) !!}",
                                reminder_location: "{!! $reminder->reminder_location !!}",
                                reminder_text: "{!! $reminder->reminder_text !!}",
                                reminder_id: "{!! $reminder->id !!}"
                            },
                            @endforeach

                        ]
                        @endif


                    },
                    @endforeach

                    // coreevents
                    @if($coreevents)
                        @foreach($coreevents as $coreevent){
                        id: "{!! $coreevent->id !!}",
                        allDay: true,
                        url: "{!! $coreevent->url !!}",
                        title: "{!! $coreevent->title !!}",
                        description: "{!! addslashes($coreevent->title) !!}",
                        @if($coreevent->category_id)
                        color: "{!! $catbglist[$coreevent->category_id] !!}",
                        textColor: "{!! $cattxlist[$coreevent->category_id] !!}",
                        @endif
                        start: "{!! $coreevent->start !!}",
                    },
                    @endforeach
                    @endif


                ],
                // customized fullcalendar.mod.js to allow month view sort by category/start
                //eventOrder sorts events with same dates/times
                eventOrder: "category,start"
            });
            @if(Session::has('success'))
            pnotify('{!! Session::get('success') !!}', 'success');
            @endif
        });

        var dateFormat = function () {
            var token = /d{1,4}|m{1,4}|yy(?:yy)?|([HhMsTt])\1?|[LloSZ]|"[^"]*"|'[^']*'/g,
                timezone = /\b(?:[PMCEA][SDP]T|(?:Pacific|Mountain|Central|Eastern|Atlantic) (?:Standard|Daylight|Prevailing) Time|(?:GMT|UTC)(?:[-+]\d{4})?)\b/g,
                timezoneClip = /[^-+\dA-Z]/g,
                pad = function (val, len) {
                    val = String(val);
                    len = len || 2;
                    while (val.length < len) val = "0" + val;
                    return val;
                };

            // Regexes and supporting functions are cached through closure
            return function (date, mask, utc) {
                var dF = dateFormat;

                // You can't provide utc if you skip other args (use the "UTC:" mask prefix)
                if (arguments.length === 1 && Object.prototype.toString.call(date) === "[object String]" && !/\d/.test(date)) {
                    mask = date;
                    date = undefined;
                }
                // Passing date through Date applies Date.parse, if necessary
                date = date ? new Date(date) : new Date;
                if (isNaN(date)) throw SyntaxError("invalid date");

                mask = String(dF.masks[mask] || mask || dF.masks["default"]);

                // Allow setting the utc argument via the mask
                if (mask.slice(0, 4) === "UTC:") {
                    mask = mask.slice(4);
                    utc = true;
                }

                var _ = utc ? "getUTC" : "get",
                    d = date[_ + "Date"](),
                    D = date[_ + "Day"](),
                    m = date[_ + "Month"](),
                    y = date[_ + "FullYear"](),
                    H = date[_ + "Hours"](),
                    M = date[_ + "Minutes"](),
                    s = date[_ + "Seconds"](),
                    L = date[_ + "Milliseconds"](),
                    o = utc ? 0 : date.getTimezoneOffset(),
                    flags = {
                        d: d,
                        dd: pad(d),
                        ddd: dF.i18n.dayNames[D],
                        dddd: dF.i18n.dayNames[D + 7],
                        m: m + 1,
                        mm: pad(m + 1),
                        mmm: dF.i18n.monthNames[m],
                        mmmm: dF.i18n.monthNames[m + 12],
                        yy: String(y).slice(2),
                        yyyy: y,
                        h: H % 12 || 12,
                        hh: pad(H % 12 || 12),
                        H: H,
                        HH: pad(H),
                        M: M,
                        MM: pad(M),
                        s: s,
                        ss: pad(s),
                        l: pad(L, 3),
                        L: pad(L > 99 ? Math.round(L / 10) : L),
                        t: H < 12 ? "a" : "p",
                        tt: H < 12 ? "am" : "pm",
                        T: H < 12 ? "A" : "P",
                        TT: H < 12 ? "AM" : "PM",
                        Z: utc ? "UTC" : (String(date).match(timezone) || [""]).pop().replace(timezoneClip, ""),
                        o: (o > 0 ? "-" : "+") + pad(Math.floor(Math.abs(o) / 60) * 100 + Math.abs(o) % 60, 4),
                        S: ["th", "st", "nd", "rd"][d % 10 > 3 ? 0 : (d % 100 - d % 10 !== 10) * d % 10]
                    };

                return mask.replace(token, function ($0) {
                    return $0 in flags ? flags[$0] : $0.slice(1, $0.length - 1);
                });
            };
        }();

        // Some common format strings
        dateFormat.masks = {
            "default": "ddd mmm dd yyyy HH:MM:ss",
            shortDate: "m/d/yy",
            mediumDate: "mmm d, yyyy",
            longDate: "mmmm d, yyyy",
            fullDate: "dddd, mmmm d, yyyy",
            shortTime: "h:MM TT",
            mediumTime: "h:MM:ss TT",
            longTime: "h:MM:ss TT Z",
            isoDate: "yyyy-mm-dd",
            isoTime: "HH:MM:ss",
            isoDateTime: "yyyy-mm-dd'T'HH:MM:ss",
            isoUtcDateTime: "UTC:yyyy-mm-dd'T'HH:MM:ss'Z'"
        };

        // Internationalization strings
        dateFormat.i18n = {
            dayNames: [
                "Sun", "Mon", "Tue", "Wed", "Thu", "Fri", "Sat",
                "Sunday", "Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday"
            ],
            monthNames: [
                "Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec",
                "January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"
            ]
        };

        // For convenience...
        Date.prototype.format = function (mask, utc) {
            return dateFormat(this, mask, utc);
        };
    </script>
    <style>
        #calendar {
            font-family: "Lucida Grande", Helvetica, Arial, Verdana, sans-serif;
            font-size: 14px;
            margin: 0 auto 0 0;
        }
    </style>
@stop