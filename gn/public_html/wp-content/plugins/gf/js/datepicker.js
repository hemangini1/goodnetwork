jQuery(function() {
    jQuery("#_crowdfundingfromdatepicker").datepicker({
        changeMonth: true,
        onClose: function(selectedDate) {
            var maxDate = new Date(Date.parse(selectedDate));
            maxDate.setDate(maxDate.getDate() + 1);
            jQuery("#_crowdfundingtodatepicker").datepicker("option", "minDate", maxDate);
        }
    });
    jQuery("#_crowdfundingtodatepicker").datepicker({
        changeMonth: true,
        onClose: function(selectedDate) {
            jQuery("#_crowdfundingfromdatepicker").datepicker("option", "maxDate", selectedDate);
        }
    });
});
