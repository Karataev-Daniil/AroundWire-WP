jQuery(document).ready(function($) {
    var formStarted = false; 
    var formSubmitted = false;

    $('.wpcf7-form .input-block input').one('input', function() {
        if (!formStarted) {
            formStarted = true;

            if (typeof dataLayer !== 'undefined') {
                dataLayer.push({
                    event: "form_start_trigger",
                    eventModel: {
                        form_id: $('input[name="_wpcf7"]').val(),
                        form_name: "Contact Form",
                        form_destination: window.location.href,
                        form_length: $('.wpcf7-form .input-block input').length,
                        first_field_id: $(this).attr('id'),
                        first_field_name: $(this).attr('name'),
                        first_field_type: $(this).attr('type'),
                        first_field_position: $('.wpcf7-form .input-block input').index(this) + 1
                    }
                });
            }
        }
    });

    document.addEventListener('wpcf7mailsent', function(event) {
        if (!formSubmitted) {
            formSubmitted = true;

            if (typeof dataLayer !== 'undefined') {
                dataLayer.push({
                    event: "form_submit_trigger",
                    eventModel: {
                        form_id: event.detail.contactFormId,
                        form_name: "Contact Form",
                        form_destination: window.location.href,
                        form_length: $('.wpcf7-form .input-block input').length
                    },
                    gtm: {
                        uniqueEventId: Date.now(),
                        priorityId: 6
                    }
                });
            }
        }
    }, false);
});
