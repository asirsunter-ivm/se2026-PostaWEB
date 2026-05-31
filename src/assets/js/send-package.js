let currentStep = 1;
let packageId = null;
let shippingCost = 0;

// NAVIGATION
$('.btn-next').on('click', function () {
    const step = $(this).closest('.form-step');
    if (!validateStep(step)) return;

    currentStep++;
    showStep(currentStep);
    if (currentStep === 4) buildSummary();
});

$('.btn-prev').on('click', function () {
    currentStep--;
    showStep(currentStep);
});

function showStep(step) {
    $('.form-step').removeClass('active');
    $(`.form-step[data-step="${step}"]`).addClass('active');
    $('.step-bar').removeClass('active completed');
    $('.step-bar').each(function () {
        const s = $(this).data('step');
        if (s < step) $(this).addClass('completed');
        if (s === step) $(this).addClass('active');
    });
}

function validateStep(step) {
    let valid = true;
    step.find('[required]').each(function () {
        if (!$(this).val().trim()) {
            $(this).css('border-color', 'red');
            valid = false;
        } else {
            $(this).css('border-color', '');
        }
    });
    if (!valid) alert('Ju lutem plotesoni te gjitha fushat e detyrueshme.');
    return valid;
}

// BUILD SUMMARY
function buildSummary() {
    const f = $('#packageForm');
    const senderName = f.find('[name=sender_name]').val();
    const receiverName = f.find('[name=receiver_name]').val();
    const weight = parseFloat(f.find('[name=weight_kg]').val()) || 0;
    const typeOption = $('#packageType option:selected');
    const typeName = typeOption.text().split(' ($')[0];
    const basePrice = parseFloat(typeOption.data('price')) || 0;

    // Kalkulim i thjeshte (server-side rikalkulohet)
    shippingCost = (basePrice + (weight * 1.50)).toFixed(2);

    $('#summaryBox').html(`
        <div class="summary-row"><strong>Dergues:</strong> ${senderName}</div>
        <div class="summary-row"><strong>Destinatar:</strong> ${receiverName}</div>
        <div class="summary-row"><strong>Tipi:</strong> ${typeName}</div>
        <div class="summary-row"><strong>Pesha:</strong> ${weight} kg</div>
        <hr>
        <div class="summary-row total"><strong>Kostoja totale: $${shippingCost}</strong></div>
    `);
}

// CREATE PACKAGE
$('#createPackageBtn').on('click', function () {
    $(this).prop('disabled', true).text('Duke krijuar...');

    $.post('api/packages/create.php', $('#packageForm').serialize(), function (res) {
        if (res.success) {
            packageId = res.package_id;
            shippingCost = res.shipping_cost;

            $('#summaryBox').append(`
                <div class="summary-row" style="margin-top:15px;">
                    <strong>Kodi i gjurmimit:</strong>
                    <span style="color:#1F4E79;font-size:18px;">${res.tracking_code}</span>
                </div>
            `);
            $('#createActions').hide();
            $('#paymentSection').show();
            renderPayPal();
        } else {
            alert('Gabim: ' + res.message);
            $('#createPackageBtn').prop('disabled', false).text('Krijo Pakon');
        }
    }, 'json').fail(function () {
        alert('Gabim ne lidhje me serverin.');
        $('#createPackageBtn').prop('disabled', false).text('Krijo Pakon');
    });
});

// PAYPAL
function renderPayPal() {
    paypal.Buttons({
        createOrder: function () {
            return fetch('api/payment/create-order.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ package_id: packageId })
            }).then(r => r.json()).then(d => d.orderID);
        },
        onApprove: function (data) {
            return fetch('api/payment/capture-order.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ orderID: data.orderID, package_id: packageId })
            }).then(r => r.json()).then(res => {
                if (res.success) {
                    $('#paymentMsg').text('✅ Pagesa u krye me sukses! Po shkarkohet etiketa...').show();
                    setTimeout(() => {
                        window.location.href = 'api/label/generate.php?id=' + packageId;
                    }, 1500);
                } else {
                    alert('Pagesa deshtoi: ' + res.message);
                }
            });
        }
    }).render('#paypal-button-container');
}