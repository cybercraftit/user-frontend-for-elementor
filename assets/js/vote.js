jQuery(document).ready(function($) {
    $('.'+ cc_vote.text_domain +'-vote-action').on('click', function(e) {
        var $this = $(this);
        e.preventDefault();
        $.ajax({
            type: 'get',
            url: $this.attr('href'),
            beforeSend: function() {
                $this.remove();
                if( !$('.'+ cc_vote.text_domain +'-vote-button').length || $this.hasClass( cc_vote.text_domain + '-cancel-button') ) {
                    $('.'+ cc_vote.text_domain +'-vote').slideUp();
                }
                if (typeof $this.data('action') !== 'undefined') window.open($this.data('action'));
            },
            success: function(data) {}
        });
    });
});