


jQuery(document).ready(function ($) {
    $('.my-chat-submit-button').click(function (event) {
        event.preventDefault();
        var input = $(this).siblings('input').val();

        // Send the input to the ChatGPT API using AJAX
        $.ajax({
            url: ajax_object.ajax_url, // The AJAX endpoint URL
            type: 'POST',
            dataType: 'json',
            data: {
                action: 'my_chat_send_message',
                input: input,
            },
            beforeSend: function () {
                // Show loading indicator or disable the button
            },
            success: function (response) {
                // Handle the response from the ChatGPT API
                if (response.success) {
                    var answer = response.data.answer;
                    // Update the widget display with the answer
                    $('.my-chat-widget-answer').text(answer);
                } else {
                    // Handle the error case
                    console.log(response.data.error);
                }
            },
            error: function (xhr, status, error) {
                // Handle the AJAX request error
                console.log(error);
            },
            complete: function () {
                // Hide the loading indicator or enable the button
            }
        });
    });
});