$(document).ready(function() {

    /**
     * Datepicker init
     */
    $('.js-datepicker').datepicker({
        "dateFormat" :"yy-mm-dd"
    });


    /**
     *  TabView init
     */
    YUI().use(
        'aui-tabview',
        function(Y) {
            new Y.TabView(
                {
                    srcNode: '.tabBox'
                }
            ).render();
        }
    );
});

/**
 * input-file
 * div .fileContent
 */
$(document).on('change', ':file', function() {

    var input = $(this);
    var label = input.val().replace(/\\/g, '/').replace(/.*\//, '');
    input.parents('div .fileContent').children('input').val(label);
});

/**
 * Loader
 */
function showLoader(){
        var html = "<div class='preloader'><div id='imageLoader'></div></div>";
        $('body').append(html);
}

function hideLoader(){
    $(".preloader #imageLoader").fadeOut(); // Usuwamy grafikę ładowania
    $(".preloader").delay(350).fadeOut("slow"); // Usuwamy diva przysłaniającego stronę
}



/**
 * AuiEditor init
 */
function AuiEditorInit(id) {
    $("#"+id)
        .before("<textarea id='"+id+"Textarea' hidden></textarea>")
        .before("<textarea id='"+id+"Annotations' hidden></textarea>")
    ;

    YUI().use(
        'aui-ace-editor',
        function (Y) {
            var editor = new Y.AceEditor(
                {
                    boundingBox: '#'+id,
                    mode: 'javascript',
                    width: '100%',
                }
            );
            editor.render();

            editor.getSession().on('change', function () {

                var annotations = editor.getSession().getAnnotations();

                var str = "";
                for (var key in annotations) {
                    if (annotations.hasOwnProperty(key))
                        str += annotations[key].text + "on line " + " " + (annotations[key].row + 1) + "<br><br>";
                }

                $('#'+id+'Annotations').val(str);

                $('#'+id+'Textarea').val(editor.getSession().getValue());
            });

            $('#'+id+'Textarea').on('change', function () {
                editor.getSession().setValue($('#'+id+'Textarea').val());
            });
        }
    );
}

/**
 * AuiModal init
 */
function AuiModalInit(body,header,id, events)
{
    if ( $( "#"+id ).length === 0 )
    {
        $('body').append('<div class="yui3-skin-sam" ><div id="'+id+'" style="top: 10px;"></div></div>');
    } else {
        $('#'+id).html('');
    }

    YUI().use(
        'aui-modal',
        function(Y) {
            var modal = new Y.Modal(
                {
                    bodyContent: body,
                    centered: true,
                    headerContent: header,
                    modal: true,
                    render: '#'+id,
                    width: 650
                }
            ).render();

            if(typeof events !== 'undefined') {
                events(modal);
            }

            $('.modal-dialog').css('top', 10);
        }
    );
}

/**
 * Alert init
 */
function createAlert(body,header)
{
    AuiModalInit(body,header,'alert')
}