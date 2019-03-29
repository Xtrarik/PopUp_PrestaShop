$(document).ready(function () {
    setInterval("openModal()", $('#frequency').val() * 1000 );
});

const openModal = function () {
    $('.modal-wrapper').show();
    setTimeout("closeModal()", $('#visibility_time').val() * 1000 );
}

const closeModal = function () {
    $('.modal-wrapper').hide();
}