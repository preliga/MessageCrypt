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
    YUI().use(
        'aui-ace-editor',
        function (Y) {
            new Y.AceEditor(
                {
                    boundingBox: '#'+id,
                    mode: 'javascript',
                    width: '100%',
                }
            ).render();
        }
    );
}
