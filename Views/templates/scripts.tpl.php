<script>
    function editTaxpayer(tpin)
    {

        var rowid = "row" + tpin;

        var cols = $("#" + rowid + " td");
        var fields = '<input type="hidden" name="editTaxpayerForm" />';
        for (var x = 0; x < cols.length - 1; x++)
        {
            var td = $(cols[x]);
            var field = $(td).attr("class");
            var value = $(td).text();
            if (field == "editEmail") {
                fields += '<input type="email" name="' + field + '" value="' + value + '" />';
            } else {
                fields += '<input type="text" name="' + field + '" value="' + value + '" />';
            }
            //alert(fields);
        }

        $("#editForm").html(fields);
        $("#editForm").submit();
    }
    function updateClock( )
    {
        var currentTime = new Date( );
        var currentHours = currentTime.getHours( );
        var currentMinutes = currentTime.getMinutes( );
        var currentSeconds = currentTime.getSeconds( );

        // Pad the minutes and seconds with leading zeros, if required
        currentMinutes = (currentMinutes < 10 ? "0" : "") + currentMinutes;
        currentSeconds = (currentSeconds < 10 ? "0" : "") + currentSeconds;

        // Choose either "AM" or "PM" as appropriate
        var timeOfDay = (currentHours < 12) ? "AM" : "PM";

        // Convert the hours component to 12-hour format if needed
        currentHours = (currentHours > 12) ? currentHours - 12 : currentHours;

        // Convert an hours component of "0" to "12"
        currentHours = (currentHours == 0) ? 12 : currentHours;

        // Compose the string for display
        var currentTimeString = currentHours + ":" + currentMinutes + ":" + currentSeconds + " " + timeOfDay;


        $("#clock").html(currentTimeString);

    }

    $(document).ready(function ()
    {
        setInterval('updateClock()', 1000);
    });
</script>