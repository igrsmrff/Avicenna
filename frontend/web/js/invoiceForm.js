$( document ).ready(function(){

    var selectOption = [];
    var selectCounter = 0;

    $('#invoiceentriesamount-1-entry_id').children().each(function () {
        var optionObject = {};
        optionObject.value = this.value;
        optionObject.html = this.innerHTML;
        selectOption[selectCounter] = optionObject;
        selectCounter++;
    });

    var newElementCounter = 0;
    var lastNumberOfInputStr =  $('.amount:last').attr( "name" ).substr(21,2);
    var lastNumberOfInputNumber = +lastNumberOfInputStr;
    if ( Number.isNaN(lastNumberOfInputNumber) ) newElementCounter = +lastNumberOfInputStr.substr(0,1) + 1;

    $('body').on( "click", ".entries-btn-append", function() {
        $('.entries-btn-append')
            .removeClass( 'entries-btn-append' )
            .removeClass( 'btn-success' )
            .addClass('entries-btn-delete')
            .addClass('btn-danger')
            .empty()
            .html('Remove');

        var newEntry =
            '<div class="row entries-group-input">'+
                '<div class="col-xs-6">' +
                    '<div class="form-group field-invoiceentriesamount-'+ newElementCounter + '-entry_id">' +
                        '<label class="control-label" for="invoiceentriesamount-'+ newElementCounter + '-entry_id">Entry</label>' +
                        '<select id="invoiceentriesamount-'+ newElementCounter + '-entry_id" class="form-control" name="InvoiceEntriesAmount['+ newElementCounter + '][entry_id]"></select>' +
                        '<div class="help-block"></div>' +
                    '</div>' +
                '</div>' +
                '<div class="col-xs-3">'+
                    '<div class="form-group field-invoiceentriesamount-'+ newElementCounter + '-amount">'+
                        '<label class="control-label" for="invoiceentriesamount-'+ newElementCounter + '-amount">Amount $</label>'+
                        '<input type="text" id="invoiceentriesamount-'+ newElementCounter + '-amount" class="form-control amount" name="InvoiceEntriesAmount[' + newElementCounter + '][amount]">'+
                        ' <div class="help-block"></div>'+
                    '</div>'+
                '</div>'+
                '<div class="col-xs-1">'+
                    '<div style="height: 65px">'+
                    '<button type="button" class="btn btn-success entries-btn-append" style="margin-top: 25px">Add more</button>'+
                    '</div>'+
                '</div>'+
            '</div>';

        $('.entries-paretn-div').append(newEntry) ;

        $.each(selectOption, function(index, value){
            $('#invoiceentriesamount-'+ newElementCounter + '-entry_id')
                .append('<option value=' + this.value + '>'+ this.html +'</option>');
        });

        $('#invoice-form').yiiActiveForm('add', {
            id: 'invoiceentriesamount-' + newElementCounter + '-entry_id',
            name: '[' + newElementCounter + '][entry_id]',
            container: '.field-invoiceentriesamount-'+ newElementCounter + '-entry_id',
            input: '#invoiceentriesamount-' + newElementCounter + '-entry_id',
            error: '.help-block',
            validate:  function (attribute, value, messages, deferred, $form) {
                yii.validation.required(value, messages, {message: "Entry cannot be blank"});
            }
        });

        $('#invoice-form').yiiActiveForm('add', {
            id: 'invoiceentriesamount-' + newElementCounter + '-amount',
            name: '[' + newElementCounter + '][amount]',
            container: '.field-invoiceentriesamount-'+ newElementCounter + '-amount',
            input: '#invoiceentriesamount-' + newElementCounter + '-amount',
            error: '.help-block',
            validate:  function (attribute, value, messages, deferred, $form) {
                yii.validation.required(value, messages, {message: "Amount $ cannot be blank"});
            }
        });

        $('#invoice-form').yiiActiveForm('add', {
            id: 'invoiceentriesamount-' + newElementCounter + '-amount',
            name: '[' + newElementCounter + '][amount]',
            container: '.field-invoiceentriesamount-'+ newElementCounter + '-amount',
            input: '#invoiceentriesamount-' + newElementCounter + '-amount',
            error: '.help-block',
            validate:  function (attribute, value, messages, deferred, $form) {
                yii.validation.compare(value, messages, {
                    message: "Amount $ must be an integer",
                    operator: '>=',
                    compareValue: 0
                });
            }
        });

        $('#invoice-form').yiiActiveForm('add', {
            id: 'invoiceentriesamount-' + newElementCounter + '-amount',
            name: '[' + newElementCounter + '][amount]',
            container: '.field-invoiceentriesamount-'+ newElementCounter + '-amount',
            input: '#invoiceentriesamount-' + newElementCounter + '-amount',
            error: '.help-block',
            validate:  function (attribute, value, messages, deferred, $form) {
                yii.validation.compare(value, messages, {
                    message: "Amount $ can not be zero",
                    operator: '!==',
                    compareValue: '0'
                });
            }
        });


        newElementCounter++;
    });

    $('body').on( "click", ".entries-btn-delete", function() {
        $(this)
            .parent()
            .parent()
            .parent()
            .remove();
        DiscountPersent();
    });


    var ValidateValue = function( data ) {
        if (Number.isNaN(+data)){
            return +0;
        } else {
            if( +data == 0)return +0;
            if( +data < 0)return +0;
            return Math.ceil((+data)*100)/100;
        }
    };

    var CalculateAmount = function () {
        var sum = +0;
        $('.amount').each(function () {
            this.value = ValidateValue(this.value);
             sum = ValidateValue(this.value) + sum;
        });
        $('.sub_total_amount').val(ValidateValue(sum));
        $('#invoice-sub_total_amount').val(ValidateValue(sum));
        return +sum;
    };

    var vat_percentage = 1 + ValidateValue($('#invoice-vat_percentage').val())/100 ;

    var Discount = function() {
        var sum = ValidateValue(vat_percentage) * CalculateAmount();
        var discount_amount = ValidateValue( $('.discount_amount').val() );
        $('.discount_amount').val( discount_amount);
        var discount_amount_percent = ValidateValue(discount_amount/sum * 100);
        $('.discount_amount_percent').val( discount_amount_percent );
        var totalAmount = ValidateValue(sum - discount_amount);
        $('.totalAmount').val( totalAmount );
    };

    var DiscountPersent = function() {
        var sum = ValidateValue(vat_percentage) * CalculateAmount();
        var discount_amount_percent = ValidateValue( $('.discount_amount_percent').val() );
        $('.discount_amount_percent').val(discount_amount_percent);
        var discount_amount = ValidateValue(discount_amount_percent * 0.01 * sum);
        $('.discount_amount').val( discount_amount );
        var totalAmount =  ValidateValue(sum - discount_amount);
        $('.totalAmount').val( totalAmount );
    };

    $('body').on( "change",'.amount' ,function () {
        DiscountPersent();
    });

    $('body').on( "change", ".discount_amount_percent", function() {
        DiscountPersent();
    });

    $('body').on( "change", ".discount_amount", function() {
        Discount();
    });
});



