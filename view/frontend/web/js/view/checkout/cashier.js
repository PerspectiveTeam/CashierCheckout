define([
    'jquery',
    'Magento_Ui/js/form/element/select',
    'mage/url',
    'postbox',
    'Magento_Checkout/js/model/full-screen-loader',
    'mage/storage',
    'Magento_Checkout/js/model/error-processor',
    'Magento_Checkout/js/model/quote',
    'mage/translate',
    'Perspective_CashierCheckout/js/lib/select2/select2'
], function (
    $,
    Select,
    url,
    postbox,
    fullScreenLoader,
    storage,
    errorProcessor,
    quote
) {
    'use strict';
    return Select.extend({

        defaults: {
            template: 'Perspective_CashierCheckout/checkout/cashier'
        },

        initialize: function () {
            this._super();
            return this;
        },

        initObservable: function () {
            this._super();
            return this;
        },

        select2: function (element) {
            $(element).select2({
                placeholder: $.mage.__(''),
                dropdownAutoWidth: true,
                width: '100%',
                minimumInputLength: 0,
                language: $('html').attr('lang'),
                data: [{id: 0, text: $.mage.__('Choose Salesman')}],
                ajax: {
                    url: url.build('rest/V1/cashiercheckout/cashiers'),
                    type: "POST",
                    dataType: 'json',
                    contentType: "application/json",
                    delay: 1000,
                    data: function (params) {
                        var query = JSON.stringify({
                            term: params.term
                        })
                        return query;
                    },
                    processResults: function (data) {
                        return {
                            results: JSON.parse(data)
                        };
                    }
                }
            });
        },

        getPreview: function () {
            return $('[name="' + this.inputName + '"] option:selected').text();
        },

        setDifferedFromDefault: function () {
            this._super();
            postbox.publish('selectedCashier', this.value());
            if (this.getPreview() && parseInt(this.value()) !== 0) {
                try {
                    this.setCashier(this.value());
                } catch (e) {
                    console.log(e);
                    // если будет эксепшен, то еще не выбран шиппинг и керриер метод
                }
            }
        },
        setCashier: function (cashier_id) {
            fullScreenLoader.startLoader();

            storage.post(
                url.build('rest/V1/cashiercheckout/setcashier'),
                JSON.stringify({
                    cashierId: cashier_id,
                    quoteId: quote.getQuoteId()
                })
            ).done(
                function (response) {
                    // quote.setTotals(response.totals);
                    fullScreenLoader.stopLoader();
                }
            ).fail(
                function (response) {
                    errorProcessor.process(response);
                    fullScreenLoader.stopLoader();
                }
            );
        }
    });
});
