(function() {
    tinymce.create("tinymce.plugins.payapi_payment_button", {
        init : function(ed, url) {
            ed.addButton("payapi_button", {
                title : "Payment Button",
                cmd : "payapi_button",
                image : "https://cdn.payapi.io/v1/image/icons/bank_card_back_side-128.png"
            });
            ed.addCommand("payapi_button", function() {
                payapi_js.handler(ed);
            });
        },
        createControl: function _createControl(n, cm)
        {
            return null;
        },
        getInfo: function _getInfo()
        {
            return {
                longname : "Payment Button",
                author : "florin@payapi.io",
                version : "1"
            };
        }
    });
    tinymce.PluginManager.add("payapi_payment_button", tinymce.plugins.payapi_payment_button);
})();