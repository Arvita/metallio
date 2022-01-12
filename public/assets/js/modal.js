var $modal = $('#ajax-modal');
$('body').on('click', '.ajax_modal',  function (ev) {


	var url = $(this).attr('data-url');
	  // create the backdrop and wait for next modal to be triggered
    $.fn.modalmanager.defaults.resize = true;
    $.fn.modal.defaults.spinner = $.fn.modalmanager.defaults.spinner =
        '<div class="loading-spinner" style="width: 200px; margin-left: -100px;">' +
        '<div class="progress progress-striped active">' +
        '<div class="progress-bar progress-bar-striped" style="width: 100%;"></div>' +
        '</div>' +
        '</div>';


    $('body').modalmanager('loading');
    setTimeout(function () {
        $modal.load(url, function () {
            $modal.modal();
        });
    }, 1000);
});

var $modal2 = $('#ajax-modal-popup');
$('body').on('click', '.ajax_modal_popup',  function (ev) {
	ev.preventDefault();
	var url = $(this).attr('data-url');
    // create the backdrop and wait for next modal to be triggered
    $.fn.modalmanager.defaults.resize = true;
    $.fn.modal.defaults.spinner = $.fn.modalmanager.defaults.spinner =
        '<div class="loading-spinner" style="width: 200px; margin-left: -100px;">' +
        '<div class="progress progress-striped active">' +
        '<div class="progress-bar progress-bar-striped" style="width: 100%;"></div>' +
        '</div>' +
        '</div>';
    $('body').modalmanager('loading');
    setTimeout(function () {
        $modal2.load(url, function () {
            $modal2.modal();
        });
    }, 1000);
});

var $modal3 = $('#modal-elfinder');
$('body').on('click', '.modal-elfinder',  function (ev) {
	ev.preventDefault();
	var url = $(this).attr('data-url');
    // create the backdrop and wait for next modal to be triggered
    $.fn.modalmanager.defaults.resize = true;
    $.fn.modal.defaults.spinner = $.fn.modalmanager.defaults.spinner =
        '<div class="loading-spinner" style="width: 200px; margin-left: -100px;">' +
        '<div class="progress progress-striped active">' +
        '<div class="progress-bar progress-bar-striped" style="width: 100%;"></div>' +
        '</div>' +
        '</div>';
    $('body').modalmanager('loading');
    setTimeout(function () {
        $modal3.load(url, function () {
            $modal3.modal();
        });
    }, 1000);
});

var $modal4 = $('#ajax-modal-confirm');
$('body').on('click', '.ajax_modal_confirm',  function (ev) {
	ev.preventDefault();
	var url = $(this).attr('data-url');
    // create the backdrop and wait for next modal to be triggered
    $.fn.modalmanager.defaults.resize = true;
    $.fn.modal.defaults.spinner = $.fn.modalmanager.defaults.spinner =
        '<div class="loading-spinner" style="width: 200px; margin-left: -100px;">' +
        '<div class="progress progress-striped active">' +
        '<div class="progress-bar progress-bar-striped" style="width: 100%;"></div>' +
        '</div>' +
        '</div>';
    $('body').modalmanager('loading');
    setTimeout(function () {
        $modal4.load(url, function () {
            $modal4.modal();
        });
    }, 1000);
});

var $modal5 = $('#ajax-modal-element');
$('body').on('click', '.ajax_modal_element',  function (ev) {
    ev.preventDefault();
    var url = $(this).attr('data-url');
    // create the backdrop and wait for next modal to be triggered
    $.fn.modalmanager.defaults.resize = true;
    $.fn.modal.defaults.spinner = $.fn.modalmanager.defaults.spinner =
        '<div class="loading-spinner" style="width: 200px; margin-left: -100px;">' +
        '<div class="progress progress-striped active">' +
        '<div class="progress-bar progress-bar-striped" style="width: 100%;"></div>' +
        '</div>' +
        '</div>';
    $('body').modalmanager('loading');
    setTimeout(function () {
        $modal5.load(url, function () {
            $modal5.modal();
        });
    }, 1000);
});

var $modal6 = $('#ajax-modal-question');
$('body').on('click', '.ajax_modal_question',  function (ev) {
    ev.preventDefault();
    var url = $(this).attr('data-url');
    // create the backdrop and wait for next modal to be triggered
    $.fn.modalmanager.defaults.resize = true;
    $.fn.modal.defaults.spinner = $.fn.modalmanager.defaults.spinner =
        '<div class="loading-spinner" style="width: 200px; margin-left: -100px;">' +
        '<div class="progress progress-striped active">' +
        '<div class="progress-bar progress-bar-striped" style="width: 100%;"></div>' +
        '</div>' +
        '</div>';
    $('body').modalmanager('loading');
    setTimeout(function () {
        $modal6.load(url, function () {
            $modal6.modal();
        });
    }, 1000);
});

