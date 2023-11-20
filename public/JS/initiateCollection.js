var $collectionHolder;

var $addImageButton = $('<button type="button" class="add_image_link btn-success btn">Ajouter une Image</button>');
var $newLinkLi = $('<li class="custom-file-input"></li>').append($addImageButton);

jQuery(document).ready(function () {
    $collectionHolder = $('ul.images');

    $collectionHolder.find('li.fieldImage').each(function () {
        addImageFormDeleteLink($(this));
    });

    $collectionHolder.append($newLinkLi);

    $collectionHolder.data('index', $collectionHolder.find('input').length);

    $addImageButton.on('click', function (e) {
        addImageForm($collectionHolder, $newLinkLi);
    });
});
// var $collectionVideoHolder;
//
// var $addVideoButton = $('<button type="button" class="add_video_link btn-success btn">Ajouter une Vidéo</button>');
// var $newLinkVideoLi = $('<li class="fieldIFrame"></li>').append($addVideoButton);
//
// jQuery(document).ready(function () {
//     $collectionVideoHolder = $('ul.videos');
//
//     $collectionVideoHolder.find('li.fieldIFrame').each(function () {
//         addVideoFormDeleteLink($(this));
//     });
//
//     $collectionVideoHolder.append($newLinkVideoLi);
//
//     $collectionVideoHolder.data('index', $collectionVideoHolder.find('input').length);
//
//     $addVideoButton.on('click', function (e) {
//         addVideoForm($collectionVideoHolder, $newLinkVideoLi);
//     });
// });

function addImageForm($collectionHolder, $newLinkLi) {
    var prototype = $collectionHolder.data('prototype');

    var index = $collectionHolder.data('index');

    var newForm = prototype;

    newForm = newForm.replace(/__name__/g, index);

    $collectionHolder.data('index', index + 1);

    var $newFormLi = $('<li class="fieldImage"></li>').append(newForm);
    $newLinkLi.before($newFormLi);

    addImageFormDeleteLink($newFormLi);
}
function addImageFormDeleteLink($tagFormLi) {
    var $removeFormButton = $('<button type="button" class="btn btn-danger col-4 text-center">X</button>');
    $tagFormLi.append($removeFormButton);

    $removeFormButton.on('click', function(e) {
        $tagFormLi.remove();
    });
}
// function addVideoForm($collectionVideoHolder, $newLinkVideoLi) {
//     var prototype = $collectionVideoHolder.data('prototype');
//
//     var index = $collectionVideoHolder.data('index');
//
//     var newForm = prototype;
//
//     newForm = newForm.replace(/__name__/g, index);
//
//     $collectionVideoHolder.data('index', index + 1);
//
//     var $newFormLi = $('<li class="fieldIFrame"></li>').append(newForm);
//     $newLinkVideoLi.before($newFormLi);
//
//     addVideoFormDeleteLink($newFormLi);
// }
// function addVideoFormDeleteLink($tagFormLi) {
//     var $removeFormButton = $('<button type="button" class="col-4 btn btn-danger">X</button>');
//     $tagFormLi.append($removeFormButton);
//
//     $removeFormButton.on('click', function(e) {
//         $tagFormLi.remove();
//     });
// }