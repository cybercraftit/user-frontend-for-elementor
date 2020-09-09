var $ = jQuery;
elementor.hooks.addAction( 'panel/open_editor/widget', function( panel, model, view ) {

    /*if ( 'section' !== model.attributes.elType && 'column' !== model.attributes.elType ) {
        return;
    }*/
    var $element = view.$el.find( '.elementor-selector' );

    if ( $element.length ) {
        $element.click( function() {
            alert( 'Some Message' );
        } );
    }
} );

var fael_editor_opts = {
    'fael_form': {
        'form' : {}
    },
    'fael_user_list': {},
    'fael_post_author': {
        'value': {}
    },
    'fael_post_list' : {
        'post_type' : {},
        'post_status' : {}
    },
    'fael_post_status' : {
        'value' : {}
    },
    'fael_submit' : {
        'post_type' : {}
    }
};

var fael_editor_render = {

    fael_user_list: {},
    fael_form: {
        form: function ( value ) {
            var options = '';
            for( var k in fael_editor_opts.fael_form.form ) {
                options = options + '<option value="'+ k +'" '+ ( value.indexOf(k) != -1 ? 'selected' : '' ) +'>'+ fael_editor_opts.fael_form.form[k] +'</option>';
            }

            $('select[data-setting="form"]').html(options);
            options = '';
        }
    },
    fael_post_author: {
        value: function (value) {
            var widget_name = 'fael_post_author';
            var field = 'value';

            var options = '';
            for( var k in fael_editor_opts[widget_name][field] ) {
                options = options + '<option value="'+ k +'" '+ ( value.indexOf(k) != -1 ? 'selected' : '' ) +'>'+ fael_editor_opts[widget_name][field][k] +'</option>';
            }

            $('select[data-setting="'+ field +'"]').html(options);
            options = '';
        }
    },
    fael_post_list: {
        post_type: function (value) {
            var widget_name = 'fael_post_list';
            var field = 'post_type';

            var options = '';
            for( var k in fael_editor_opts[widget_name][field] ) {
                options = options + '<option value="'+ k +'" '+ ( value.indexOf(k) != -1 ? 'selected' : '' ) +'>'+ fael_editor_opts[widget_name][field][k] +'</option>';
            }

            $('select[data-setting="'+ field +'"]').html(options);
            options = '';
        },
        post_status: function (value) {
            var widget_name = 'fael_post_list';
            var field = 'post_status';

            var options = '';
            for( var k in fael_editor_opts[widget_name][field] ) {
                options = options + '<option value="'+ k +'" '+ ( value.indexOf(k) != -1 ? 'selected' : '' ) +'>'+ fael_editor_opts[widget_name][field][k] +'</option>';
            }

            $('select[data-setting="'+ field +'"]').html(options);
            options = '';
        }
    },
    fael_post_status: {
        value: function (value) {
            var widget_name = 'fael_post_status';
            var field = 'value';

            var options = '';
            for( var k in fael_editor_opts[widget_name][field] ) {
                options = options + '<option value="'+ k +'" '+ ( value.indexOf(k) != -1 ? 'selected' : '' ) +'>'+ fael_editor_opts[widget_name][field][k] +'</option>';
            }

            $('select[data-setting="'+ field +'"]').html(options);
            options = '';
        }
    },
    fael_submit: {
        post_type: function (value) {
            var widget_name = 'fael_submit';
            var field = 'post_type';

            var options = '';
            for( var k in fael_editor_opts[widget_name][field] ) {
                options = options + '<option value="'+ k +'" '+ ( value.indexOf(k) != -1 ? 'selected' : '' ) +'>'+ fael_editor_opts[widget_name][field][k] +'</option>';
            }
            console.log(options);
            console.log($('select[data-setting="'+ field +'"]').length);
            $('select[data-setting="'+ field +'"]').html(options);
        }
    }
};


elementor.hooks.addAction( 'panel/open_editor/widget/fael_user_list', function( panel, model, view ) {
    var $elName = 'fael_user_list';
    fael_populate_field_data($elName,model);
});
elementor.hooks.addAction( 'panel/open_editor/widget/fael_post_list', function( panel, model, view ) {
    var $elName = 'fael_post_list';
    fael_populate_field_data($elName,model);
});
elementor.hooks.addAction( 'panel/open_editor/widget/fael_form', function( panel, model, view ) {
    var $elName = 'fael_form';
    fael_populate_field_data($elName,model);
});
elementor.hooks.addAction( 'panel/open_editor/widget/fael_post_author', function( panel, model, view ) {
    var $elName = 'fael_post_author';
    fael_populate_field_data($elName,model);
});
elementor.hooks.addAction( 'panel/open_editor/widget/fael_post_status', function( panel, model, view ) {
    var $elName = 'fael_post_status';
    fael_populate_field_data($elName,model);
});
elementor.hooks.addAction( 'panel/open_editor/widget/fael_submit', function( panel, model, view ) {
    var $elName = 'fael_submit';
    fael_populate_field_data($elName,model);
});

function fael_populate_field_data($elName,model) {
    var fetch_data = [];

    for ( var f in fael_editor_opts[$elName] ) {
        if( !Object.keys(fael_editor_opts[$elName][f]).length ) {
            fetch_data.push(f);
        }
    }


    if( Object.keys(fetch_data).length ) {
        console.log(Object.keys(fetch_data));
        $.post(
            ajaxurl,
            {
                action: 'fael_fetch_data',
                widget: $elName,
                fetch_data: fetch_data
            },
            function (res) {
                var data = res.data.data;
                for( var field in data ) {
                    if( typeof fael_editor_opts[$elName][field] != 'undefined' ) {
                        fael_editor_opts[$elName][field] = data[field];
                    }
                }

                for( var field in fael_editor_render[$elName] ) {
                    fael_editor_render[$elName][field]( model.attributes.settings.attributes[field] );
                }
            }
        )
    } else {
        for( var field in fael_editor_render[$elName] ) {
            fael_editor_render[$elName][field]( model.attributes.settings.attributes[field] );
        }
    }
}
