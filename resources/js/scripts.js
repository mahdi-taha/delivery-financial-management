window.addEventListener('DOMContentLoaded', () => {
    let orders = [];
    let rowNumber = 1;
    $(document).ready(function () {
        let today = new Date().toISOString().split('T')[0];
        $("#settlement_date").val(today);
    });
    $('#addOrders').click(function () {
        if (!$('#driver_id').val()) {
            return Swal.fire({
                title: 'Confirm Payment',
                text: `Please select a driver first.`,
                showCancelButton: false,
                confirmButtonText: 'OK',
                confirmButtonColor: '#36454f',
            });
        }
        $(this).prop('disabled', true);
        $('#driver_id').prop('disabled', true);
        $('#driver_percentage').prop('disabled', true);
        $('#type').prop('disabled', true);
        $('#orders_section').removeClass('d-none');
    });
    $("#driver_id").on("change", function () {
        var selectedOption = $(this).find("option:selected");
        if (selectedOption.val()) {
            // Get values from data attributes
            var driverType = selectedOption.data("type");
            var driverPercentage = selectedOption.data("percentage");
            // Display them in inputs
            $("#type").val(driverType);
            $("#driver_percentage").val(driverPercentage);
        }
    });
    $(document).on('click', '#save_settlement', function (e) {
        e.preventDefault();
        $.ajax({
            url: window.routes.settlementStore,
            type: 'POST',
            contentType: 'application/json',
            data: JSON.stringify({
                driver_id: $('#driver_id').val(),
                date: $('#settlement_date').val(),
                total_orders: orders.length,
                driver_amount: $('#driver_amount').val(),
                company_amount: $('#company_amount').val(),
                notes: $('#notes').val(),
                orders: orders
            }),
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            beforeSend: function () {
                $('#save_settlement').prop('disabled', true);
            },
            success: function (response) {
                if (response.success) {
                    const url = new URL(window.routes.settlementIndex, window.location.origin);
                    url.searchParams.set('message', response.message);
                    window.location.href = url.toString();
                }
            },
            error: function (xhr) {
                let message = 'Something went wrong';
                if (xhr.status === 422 && xhr.responseJSON?.errors) {
                    message = Object.values(xhr.responseJSON.errors)
                        .flat()
                        .join('<br>');
                } else {
                    message = xhr.responseJSON?.message || message;
                }
                Swal.fire({
                    toast: true,
                    icon: 'error',
                    title: message,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 5500,
                    timerProgressBar: true,
                });
            },
            complete: function () {
                $('#save_settlement').prop('disabled', false);
            }
        });
    });
    $(document).on('keypress', '.delivery_fee', function (e) {
        if (e.which === 13) {
            e.preventDefault();
            let row = $(this).closest('.order-row');
            let currency = row.find('.currency');
            let currencyId = currency.val();
            let currencyRate = parseFloat(currency.find('option:selected').data('rate')) || 1;
            let currencyInput = currency.find('option:selected').data('input') || '';
            let partner = row.find('.partner');
            let partnerId = partner.val();
            let partnerType = partner.find('option:selected').data('type');
            let partnerPercentage = parseFloat(partner.find('option:selected').data('percentage')) || 0;
            let partnerFixedFee = parseFloat(partner.find('option:selected').data('fixed')) || 0;
            let deliveryFeeField = row.find('.delivery_fee');
            let deliveryFeeSelected = parseFloat(deliveryFeeField.val()) || 0;
            let selectedNumber = row.find('td:first').text();
            let driverPercentage = parseFloat($("#driver_percentage").val()) || 0;
            let driverAmountField = row.find('.driver_amount');
            let companyAmountField = row.find('.company_amount');
            if (!deliveryFeeSelected || selectedNumber != rowNumber) {
                return;
            }
            if (currencyInput === 'millions') {
                deliveryFeeSelected = deliveryFeeSelected * 1000000;
            } else if (currencyInput === 'thousands') {
                deliveryFeeSelected = deliveryFeeSelected * 1000;
            } else if (currencyInput === 'hundreds') {
                deliveryFeeSelected = deliveryFeeSelected * 100;
            } else if (currencyInput === 'tens') {
                deliveryFeeSelected = deliveryFeeSelected * 10;
            }
            let subtotalSelected = deliveryFeeSelected;
            let partnerAmount = 0;
            let partnerAmountBase = 0;
            if (partnerType === 'fixed') {
                let partnerFixedFeeSelected = partnerFixedFee / currencyRate;
                partnerAmount = partnerFixedFeeSelected;
                partnerAmountBase = partnerFixedFee;
                subtotalSelected = deliveryFeeSelected - partnerFixedFeeSelected;
            } else if (partnerType === 'percentage') {
                partnerAmount = (deliveryFeeSelected * partnerPercentage / 100);
                partnerAmountBase = (deliveryFeeSelected * partnerPercentage / 100) * currencyRate;
                subtotalSelected = deliveryFeeSelected - partnerAmount;
            }
            let driverAmountSelected = subtotalSelected * driverPercentage / 100;
            let companyAmountSelected = subtotalSelected - driverAmountSelected;
            let driverAmount = driverAmountSelected * currencyRate;
            let companyAmount = companyAmountSelected * currencyRate;
            partnerAmount = Number(partnerAmount).toFixed(2);
            driverAmount = driverAmount.toFixed(2);
            companyAmount = companyAmount.toFixed(2);
            partnerAmountBase = Number(partnerAmountBase).toFixed(2);
            driverAmountField.val(driverAmount);
            companyAmountField.val(companyAmount);
            deliveryFeeField.prop('disabled', true);
            let order = {
                currency_id: currencyId,
                currency_rate: Number(currencyRate),
                delivery_fee: Number(deliveryFeeSelected),
                driver_amount: Number(driverAmount),
                company_amount: Number(companyAmount),
                contractCompanyPercentage: Number(partnerPercentage),
                contractCompanyFixedFee: Number(partnerFixedFee),
                contractCompanyAmountBase: Number(partnerAmountBase),
                contract_company_id: partnerId,
            };
            orders.push(order);
            currency.prop('disabled', true);
            partner.prop('disabled', true);
            addNewRow();
            row.removeClass('editable').addClass('completed');
            updateSummary();
        }
    });
    function addNewRow() {
        rowNumber++;
        let lastCompany = "";
        if (orders.length > 0) {
            lastCompany = orders[orders.length - 1].contract_company_id;
        }
        let html = `
    <tr class="order-row editable">
        <td>
            ${rowNumber}
        </td>
                <td>
        <select class="form-control currency">`;
        currencies.forEach(function (currency) {
            html += `
        <option value="${currency.id}" data-rate="${currency.rate}" data-input="${currency.input_mode}" ${currency.is_default ? 'selected' : ''}>
            ${currency.symbol} - ${currency.name}
        </option>
    `;
        });
        html += `</select>
        </td>
        <td>
            <select class="form-control partner">
                    <option value="">${window.lang.selectPartner}</option>`;
        companies.forEach(function (company) {
            html += `
        <option value="${company.id}"  data-type="${company.fee_type}" data-percentage="${company.percentage}" data-fixed="${company.fixed_fee}"  ${company.id == lastCompany ? 'selected' : ''}>
            ${company.name}
        </option>`;
        });
        html += `</select>
        </td>`;
        html += `  <td>
            <input type="number"
                    class="form-control delivery_fee">
        </td>
        <td>
            <input type="number"
                    class="form-control driver_amount"
                    readonly>
        </td>
        <td>
            <input type="number"
                    class="form-control company_amount"
                    readonly>
        </td>
        <td>
            <button type="button"
                    class="btn btn-danger btn-sm remove-row">
                X
            </button>
        </td>
    </tr>`;
        $('#orders_body').append(html);
        let newRow = $('#orders_body tr:last');
        if (lastCompany) {
            newRow.find('.partner')
                .val(lastCompany);
        }
        newRow.find('.delivery_fee').focus();
    }
    $(document).on('click', '.remove-row', function () {
        if ($('.order-row').length <= 1) {
            Swal.fire({
                title: 'Confirm Payment',
                text: `You must keep at least one row.`,
                showCancelButton: false,
                confirmButtonText: 'OK',
                confirmButtonColor: '#36454f',
            })
            return;
        }
        let row = $(this).closest('.order-row');
        if (row.hasClass('editable')) {
            return;
        }
        let index = row.index();
        orders.splice(index, 1);
        row.remove();
        updateNumbers();
        updateSummary();
    });
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
})