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
        $('body').append('<div class="yui3-skin-sam"><div id="'+id+'"></div></div>');
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
        }
    );
}

/**
 * Alert init
 */
function createAlert(body,header)
{
    // console.log($('#alert'));

    // if ( $( "#alert" ).length === 0 )
    // {
    //     $('body').append('<div class="yui3-skin-sam"><div id="alert"></div></div>');
    // }


    AuiModalInit(body,header,'alert')
}