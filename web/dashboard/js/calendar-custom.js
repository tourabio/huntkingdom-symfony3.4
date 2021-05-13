var Script = function () {

    document.addEventListener('DOMContentLoaded', () => {

        // Get the object from the window object
        var eve = window.eve;

        // Now you have a JavaScript object
        console.log(eve);
    });
    /* initialize the external events
     -----------------------------------------------------------------*/

    $('#external-events div.external-event').each(function() {

        // create an Event Object (http://arshaw.com/fullcalendar/docs/event_data/Event_Object/)
        // it doesn't need to have a start or end
        var eventObject = {
            title: $.trim($(this).text()) // use the element's text as the event title
        };

        // store the Event Object in the DOM element so we can get to it later
        $(this).data('eventObject', eventObject);

        // make the event draggable using jQuery UI
        $(this).draggable({
            zIndex: 999,
            revert: true,      // will cause the event to go dashboard to its
            revertDuration: 0  //  original position after the drag
        });

    });


    /* initialize the calendar
     -----------------------------------------------------------------*/

    var date = new Date();
    var d = date.getDate();
    var m = date.getMonth();
    var y = date.getFullYear();
    var v= ["non reserve", null, null];
    for (var i = 0; i < eve.length; i++) {
        eve[i][0]=eve[i][0].toString();
        eve[i][1]=new Date(eve[i][1],eve[i][2],eve[i][3]);
        eve[i][2]=new Date(eve[i][4],eve[i][5],eve[i][6]);
    }
    for (var j = eve.length; j < 100; j++) {
        eve.push(v);
    }


    $('#calendar').fullCalendar({
        header: {
            left: 'prev,next today',
            center: 'title',
            right: 'month,basicWeek,basicDay'
        },
        editable: true,
        droppable: true, // this allows things to be dropped onto the calendar !!!
        drop: function(date, allDay) { // this function is called when something is dropped

            // retrieve the dropped element's stored Event Object
            var originalEventObject = $(this).data('eventObject');

            // we need to copy it, so that multiple events don't have a reference to the same object
            var copiedEventObject = $.extend({}, originalEventObject);

            // assign it the date that was reported
            copiedEventObject.start = date;
            copiedEventObject.allDay = allDay;

            // render the event on the calendar
            // the last `true` argument determines if the event "sticks" (http://arshaw.com/fullcalendar/docs/event_rendering/renderEvent/)
            $('#calendar').fullCalendar('renderEvent', copiedEventObject, true);

            // is the "remove after drop" checkbox checked?
            if ($('#drop-remove').is(':checked')) {
                // if so, remove the element from the "Draggable Events" list
                $(this).remove();
            }

        },
        events: [
            {
                title: eve[0][0],
                start: eve[0][1],
                end: eve[0][2]
            },
            {
                title: eve[1][0],
                start: eve[1][1],
                end: eve[1][2]
            },
            {
                title: eve[2][0],
                start: eve[2][1],
                end: eve[2][2]
            },
            {
                title: eve[3][0],
                start: eve[3][1],
                end: eve[3][2]
            },
            {
                title: eve[4][0],
                start: eve[4][1],
                end: eve[4][2]
            },
            {
                title: eve[5][0],
                start: eve[5][1],
                end: eve[5][2]
            },
            {
                title: eve[5][0],
                start: eve[5][1],
                end: eve[5][2]
            },
            {
                title: eve[6][0],
                start: eve[6][1],
                end: eve[6][2]
            },
            {
                title: eve[6][0],
                start: eve[6][1],
                end: eve[6][2]
            },
            {
                title: eve[7][0],
                start: eve[7][1],
                end: eve[7][2]
            },
            {
                title: eve[8][0],
                start: eve[8][1],
                end: eve[8][2]
            },
            {
                title: eve[9][0],
                start: eve[9][1],
                end: eve[9][2]
            },
            {
                title: eve[10][0],
                start: eve[10][1],
                end: eve[10][2]
            },
            {
                title: eve[11][0],
                start: eve[11][1],
                end: eve[11][2]
            },
            {
                title: eve[12][0],
                start: eve[12][1],
                end: eve[12][2]
            },
            {
                title: eve[13][0],
                start: eve[13][1],
                end: eve[13][2]
            },
            {
                title: eve[14][0],
                start: eve[14][1],
                end: eve[14][2]
            }
        ]
    });

}();