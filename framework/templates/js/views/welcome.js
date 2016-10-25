define([
    'text!/templates/welcome.hbs'
],
    function(tmpl) {
        return Backbone.View.extend({
            template: Handlebars.compile(tmpl),
            events: {
            },
            initialize: function() {
                this.render();
            },
            render: function() {
                this.$el.html( Handlebars.compile( tmpl)(this));
                return this;
            }
        });
    }
);
