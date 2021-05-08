define([
    'uiComponent',
    'Magento_Ui/js/model/messageList'
], function (Component, messageList) {

    console.log('fsdfsd');
    return Component.extend({
        onClickMe: function() {
            messageList.addErrorMessage({message: 'fsdfsd'});
        }
    });
});
