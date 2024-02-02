define([
    'jquery',
    'uiComponent',
    'Magento_Customer/js/customer-data'
], function ($,Component, customerData) {
    return Component.extend({
        initialize: function () {
            this._super();
            this.attributes = customerData.get('customer-attributes');
        },

        getHobbies: function() {
            return this.attributes().hobby.options;
        },

        getHobbyName: function() {
            let result = '', index = this.getHobbyIndex();

            $.each(this.getHobbies(), function(k, item) {
                if (index === item.value) {
                    result = $.mage.__('Hobby: ') + item.label
                    return false;
                }
            });

            return result;
        },

        getHobbyIndex: function() {;
            return this.attributes().hobby.value
        }
    });
});
