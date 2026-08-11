let orders = [];
let rowNumber = 1;
$(document).ready(function () {
    buildOrdersArray();
    function buildOrdersArray() {
        orders = [];
        $('#orders_body .order-row').each(function () {
            let $row = $(this);
            let $currency = $row.find('.currency');
            let $partner = $row.find('.partner');
            let currencyRate = parseFloat($currency.find('option:selected').data('rate')) || 1;
            let deliveryFee = parseFloat($row.find('.delivery_fee').val()) || 0;
            let deliveryFeeBase = (deliveryFee * currencyRate).toFixed(2);
            let driverAmount = parseFloat($row.find('.driver_amount').val()) || 0;
            let driverAmountBase = (driverAmount * currencyRate).toFixed(2);;
            let companyAmount = parseFloat($row.find('.company_amount').val()) || 0;
            let companyAmountBase = (companyAmount * currencyRate).toFixed(2);
            let contractCompanyAmount = parseFloat($row.find('.partnerAmount').val()) || 0;;
            let contractCompanyAmountBase = (contractCompanyAmount * currencyRate).toFixed(2);
            let partnerOption = $partner.find('option:selected');
            let order = {
                currency_id: $currency.val(),
                contract_company_id: $partner.val(),
                delivery_fee: Number(deliveryFeeBase),
                driver_amount: Number(driverAmountBase),
                company_amount: Number(companyAmountBase),
                currency_rate: currencyRate,
                contractCompanyPercentage: parseFloat(partnerOption.data('percentage')) || 0,
                contractCompanyFixedFee: parseFloat(partnerOption.data('fixed')) || 0,
                contractCompanyAmountBase: Number(contractCompanyAmountBase),
                contract_company_id: $partner.val(),
            };
            orders.push(order);
        });
        updateNumbers();
    }
    function updateNumbers() {
        rowNumber = 0;
        $('#orders_body tr').each(function () {
            rowNumber++;
            $(this).find('td:first').text(rowNumber);
        });
    }
    function updateSummary() {
        let total_driver = 0;
        let total_company = 0;
        let total_partner = 0;
        let currencyRound = parseFloat($('#currency_rounding').val()) || 1;
        orders.forEach(function (order) {
            total_driver += order.driver_amount;
            total_company += order.company_amount;
            total_partner += order.contractCompanyAmountBase;
        });
        let roundedTotalDriver = Math.round(total_driver / currencyRound) * currencyRound;
        let roundedTotalCompany = Math.round(total_company / currencyRound) * currencyRound;
        let roundedTotalPartner = Math.round(total_partner / currencyRound) * currencyRound;
        // roundedTotalCompany = Math.round(total_company / currencyRound) * currencyRound;
        let subtotal = roundedTotalDriver + roundedTotalCompany;
        let total = subtotal + roundedTotalPartner;
        let formattedDriverTotal = new Intl.NumberFormat().format(roundedTotalDriver);
        let formattedCompanyTotal = new Intl.NumberFormat().format(roundedTotalCompany);
        let formattedPartnerTotal = new Intl.NumberFormat().format(roundedTotalPartner);
        let formattedSubtotal = new Intl.NumberFormat().format(subtotal);
        let formattedTotal = new Intl.NumberFormat().format(total);
        $('#orders_count').text(orders.length);
        $('#total').text(formattedTotal);
        $('#subtotal').text(formattedSubtotal);
        $('#driver_total').text(formattedDriverTotal);
        $('#company_total').text(formattedCompanyTotal);
        $('#partner_total').text(formattedPartnerTotal);
    }
});