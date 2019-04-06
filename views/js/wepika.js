$(document).ready(function () {
            //start of the modal window appearance sequence - value recovered thanks to the hidden input of wepika.tpl
    setInterval( "showModal()", 5 * 1000 );
});

const showModal = function (){
    $.ajax({
        url: mp_ajax,
        data:{},
        method:'post',
        success: function(data)
        {
            console.log(JSON.parse(data));
            var modalInfos = JSON.parse(data);
                    //Simple verification on the firstname and lastname of the last buyer
            if(modalInfos['firstname'] != null && modalInfos['lastname'] != null){
                $('#customer_firstname').html(modalInfos['firstname']);
                $('#customer_lastname').html(modalInfos['lastname']);
                $('#country').html(modalInfos['country']);
                $('#city').html(modalInfos['city']);
                $('#product_name').html(modalInfos['name']);
                $('#date').html((modalInfos['date'] <= 1)? modalInfos['date']+" jour" :modalInfos['date']+ " jours");
                $('#product_img').attr('src', modalInfos['img']);
                        //call the modal window
                openModal();
            }

        }
    });
};

const openModal = function () {
    $('.modal-wrapper').show('slow', function(){
        setTimeout("closeModal()", 2 * 1000 )       //value recovered thanks to the hidden input of wepika.tpl
    });
};

const closeModal = function () {
    $('.modal-wrapper').hide('slow');
};

// $('#frequency').val()
// $('#visibility_time').val()