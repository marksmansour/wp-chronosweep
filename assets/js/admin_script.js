jQuery(function ($) {
    $('body').on('change', '.receive_sms_admin, .email_special_offer_admin', function () {
        if ($(this).prop('checked')) {
            $(this).val(1);
        }else{
            $(this).val(0);
        }
    });

    $('body').on('click', '.addToWinnerBtn', function(e) {
        var ajax_url = $(this).attr('data-admin-ajax');
		e.preventDefault();
        var userId = $('input[name="userId"]:checked').val();
        var getOrderId = $('input[name="userId"]:checked').attr('data-order-id');
        var orderId = $('.'+getOrderId).val();
        var endDate = $('.endDate').val();
        var productId = $('.productId').val();
        var liveDrawLink = $('.liveDrawLink').val();
        var certificateLink = $('.certificateLink').val();
        var donationValue = ($('.donationValue').val()) ? $('.donationValue').val() : '';

        if(userId){
            if(liveDrawLink && certificateLink){
                var formElements = 'donationValue='+donationValue+'&certificateLink='+certificateLink+'&liveDrawLink='+liveDrawLink+'&orderId='+orderId+'&userId='+userId+'&endDate='+endDate+'&productId='+productId+'&action=addProductWinner';
                $.ajaxq.abort('addProductWinner');
                $.ajaxq('addProductWinner', {
                    url: ajax_url,
                    dataType: 'json',
                    data: formElements,
                    type: 'post',
                    success: function(data) {
                        if (data.status == true) {
                            $('.userId'+data.status).prop('checked', true);
                            $.toast({
                                text: data.message,
                                icon: 'success',
                                position: 'top-right',
                                stack: 15,
                            });
                        }else{
                            $.toast({
                                text: data.message,
                                icon: 'error',
                                position: 'top-right',
                                stack: 15,
                            });
                        }
                    }
                })
            }else{
                $.toast({
                    text: 'Please check live Draw Link & Certificate Link',
                    icon: 'error',
                    position: 'top-right',
                    stack: 15,
                });
            }
        }else{
            $.toast({
                text: 'Please select user',
                icon: 'error',
                position: 'top-right',
                stack: 15,
            });
        }

    });
});