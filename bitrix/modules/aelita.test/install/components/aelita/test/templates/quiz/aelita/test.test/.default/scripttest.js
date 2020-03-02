$( document ).ready(function() {

    $(document).on('change','.list_answer input[type=radio]',function(){
        var correct=$(this).data('correct');
        var parent=$(this).parents('.list_answer');
        parent.find('input[type=radio]').css('display','none');
        $(this).parents('.label').find('.precent_answer').show();

        if(correct=="Y")
        {
            $(this).parents('.label').addClass('correct').find('.correct_description').show();
        }else{
            $(this).parents('.label').addClass('error').find('.error_description').show();
            parent.find('input[data-correct=Y]').parents('.label').addClass('correct');
        }
    });



});