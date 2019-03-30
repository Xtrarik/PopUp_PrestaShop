$(document).ready(function () {
    //setInterval("openModal()", 5* 1000 );
    setInterval( "letsgo()", 5* 1000 );

});

const letsgo = function (){
    $.ajax({
        url: mp_ajax,
        data:{} ,
        method:'post',
        success: function(data)
        {
            var modalInfos = JSON.parse(data)[Math.floor(Math.random()*JSON.parse(data).length)];
            $('#customer_firstname').html(modalInfos['firstname']);
            $('#product_name').html(modalInfos['name']);
            $('#product_img').attr('src', modalInfos['img']);
            openModal()
        }
    });
}

const openModal = function () {


    $('.modal-wrapper').show('slow', function(){
        setTimeout("closeModal()", 3 * 1000 )
    });
}

const closeModal = function () {
    $('.modal-wrapper').hide('slow');
}
$(document).ready(function() {

});

// $(document).ready(function () {
//     setInterval("openModal()", $('#frequency').val() * 1000 );
// });
//
// const openModal = function () {
//     $('.modal-wrapper').show('slow', function(){
//             setTimeout("closeModal()", $('#visibility_time').val() * 1000 )
//     });
// }
//
// const closeModal = function () {
//     $('.modal-wrapper').hide('slow');
// }

