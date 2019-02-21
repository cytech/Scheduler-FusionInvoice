$("#saveCalendarEvent").submit(function (e) {
    e.preventDefault();
    var title = $("#title").val();
    var description = $("#description").val();
    var start = $("#from").val();
    var end = $("#to").val();
    var category = $("#category").val();
    var eventData;
    $.ajax({
        url: '{!! route("scheduler.updateevent") !!}',
        type: "POST",
        dataType: 'json',
        data: $("#saveCalendarEvent").serialize(),
        cache: false,
        success: function (data) {
            if (data.type === 'success') {
                pnotify('{{trans('Scheduler::texts.event_created')}}', 'success');
                eventData = {
                    id: parseInt(data.data),
                    oid: parseInt(data.dataoid),
                    title: title,
                    description: description,
                    start: start,
                    end: end,
                    color: data.bg_color,
                    textColor: data.text_color,
                    category: category
                };
                $('#calendar').fullCalendar('renderEvent', eventData, true); // stick? = true
                $('#calendar').fullCalendar('unselect');
                $('#calEventDialog').dialog('close');
            } else {
                pnotify('{{trans('Scheduler::texts.unknown_error')}}', 'error');
            }
        },
        error: function (data) {
            var errors = '';
            for (datas in data.responseJSON) {
                errors += data.responseJSON[datas] + '<br>';
            }
            pnotify(errors, 'error');
        }
    });
});
