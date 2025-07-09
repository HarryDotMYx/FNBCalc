<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>F&B Allowance Calculator</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Nunito&display=swap" rel="stylesheet">

    <style>
        body {
            font-family: 'Nunito', sans-serif;
            background-color: #f8f9fc;
        }

        .header {
            background-color: #4e73df;
            color: white;
            padding: 1rem 2rem;
            display: flex;
            align-items: center;
        }

        .header img {
            height: 60px;
            margin-right: 15px;
        }

        .header-title {
            font-size: 1.8rem;
            font-weight: 700;
        }

        .amount-input {
            margin-bottom: 10px;
        }

        .hidden {
            display: none;
        }

        .card {
            box-shadow: 0 0.15rem 1.75rem 0 rgba(58,59,69,.15);
        }

        /* F&B Scrollable Card Styling */
        .card-fixed-height {
            height: 500px;
            display: flex;
            flex-direction: column;
            overflow: hidden;
        }

        .card-body-scrollable {
            flex: 1;
            overflow-y: auto;
            padding: 1.5rem;
        }

        .card-footer-sticky {
            background-color: white;
            padding: 1rem 1.5rem;
            position: sticky;
            bottom: 0;
            z-index: 10;
            border-top: 1px solid #e3e6f0;
        }
    </style>
</head>
<body>

<!-- Header -->
<div class="header">
    <img src="css/logo.png" alt="Logo">
    <div class="header-title">F&B Utilization Calculator</div>
</div>

<!-- Main Container -->
<div class="container my-3">
    <div class="row g-4">
        <!-- Left Side: Simple Total Calculator -->
        <div class="col-md-6">
            <div class="card p-4">
                <h5>Simple Total Calculator</h5>
                <div id="simpleFields">
                    <input type="number" step="any" class="form-control amount-input" name="simple[]" placeholder="Enter amount and press Enter">
                </div>
                <div class="mt-3 d-flex justify-content-between align-items-center">
                    <h6>Total: <span id="simpleTotal" class="text-primary">RM 0.00</span></h6>
                    <button type="button" class="btn btn-secondary" id="clearSimple">Clear</button>
                </div>
            </div>
        </div>

        <!-- Right Side: Discount Calculator -->
        <div class="col-md-6">
            <div class="card card-fixed-height">
                <div class="card-body card-body-scrollable">
                    <form id="fnbForm">
                        <h5>F&B Discount Calculator</h5>
                        <div class="mb-3">
                            <label for="guestCount" class="form-label">Number of Guests</label>
                            <input type="number" class="form-control" id="guestCount" required>
                        </div>

                        <div class="mb-3">
                            <label for="cafe" class="form-label">Select Cafe</label>
                            <select class="form-select" id="cafe" required>
                                <option value="mosaic">Mosaic / Tanjung Ria Kitchen</option>
                                <option value="laundry">Laundry</option>
                                <option value="nagisa">Nagisa</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Enter Bill Amounts (Food/Laundry only)</label>
                            <div id="amountFields">
                                <input type="number" step="any" class="form-control amount-input" name="amount[]" placeholder="Enter amount and press Enter">
                            </div>
                        </div>

                        <div class="form-check mb-2">
                            <input type="checkbox" class="form-check-input" id="alcoholCheck">
                            <label class="form-check-label" for="alcoholCheck">Include Alcohol Drinks?</label>
                        </div>
                        <div class="mb-3 hidden" id="alcoholField">
                            <label for="alcoholAmount" class="form-label">Alcohol Amount (No Discount)</label>
                            <input type="number" class="form-control" id="alcoholAmount" placeholder="e.g. 120">
                        </div>

                        <div class="form-check mb-2">
                            <input type="checkbox" class="form-check-input" id="roomCheck">
                            <label class="form-check-label" for="roomCheck">Include Room Charges?</label>
                        </div>
                        <div class="mb-3 hidden" id="roomField">
                            <label for="roomAmount" class="form-label">Room Amount (No Discount)</label>
                            <input type="number" class="form-control" id="roomAmount" placeholder="e.g. 300">
                        </div>
                    </form>
                </div>

                <div class="card-footer-sticky">
                    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center">
    <div class="container-fluid">
  <div class="row">
    <div class="col-md-6">
      <h6>Total: <span id="totalBefore" class="text-dark">RM 0.00</span></h6>
    </div>
    <div class="col-md-6">
      <h6>Final Bill: <span id="finalBill" class="text-success">RM 0.00</span></h6>
    </div>
  </div>
  <div class="row">
    <div class="col-md-6">
      <h6>Discount Given: <span id="discountAmount" class="text-warning">RM 0.00</span></h6>
    </div>
    <div class="col-md-6">
      <h6>Discount Rate: <span id="discountRate" class="text-info">0%</span></h6>
    </div>
  </div>
</div>

    <button type="button" class="btn btn-secondary mt-3 mt-md-0" id="clearBtn">Clear</button>
</div>

                </div>
            </div>
        </div>
    </div>
</div>

<!-- JS CDN -->
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

<script>
    function calculateSimpleTotal() {
        const values = $("input[name='simple[]']").map(function () {
            return parseFloat(this.value) || 0;
        }).get();
        const total = values.reduce((sum, val) => sum + val, 0);
        $('#simpleTotal').text("RM " + total.toFixed(2));
    }

    function calculateDiscount() {
    const guestCount = parseInt($('#guestCount').val()) || 0;
    const cafe = $('#cafe').val();
    const amounts = $("input[name='amount[]']").map(function () {
        return parseFloat(this.value) || 0;
    }).get();
    const alcohol = parseFloat($('#alcoholAmount').val()) || 0;
    const room = parseFloat($('#roomAmount').val()) || 0;

    const foodTotal = amounts.reduce((sum, val) => sum + val, 0);
    const totalBefore = foodTotal + alcohol + room;

    let discountRate = 0;
    if (cafe === 'nagisa') {
        discountRate = 0.25;
    } else if (cafe === 'mosaic' || cafe === 'laundry') {
        discountRate = guestCount <= 10 ? 0.5 : 0.2;
    }

    const discountAmount = foodTotal * discountRate;
    const discountedFood = foodTotal - discountAmount;
    const finalAmount = discountedFood + alcohol + room;

    $('#totalBefore').text("RM " + totalBefore.toFixed(2));
    $('#finalBill').text("RM " + finalAmount.toFixed(2));
    $('#discountAmount').text("RM " + discountAmount.toFixed(2));
    $('#discountRate').text((discountRate * 100).toFixed(0) + "%");
}


    $(document).ready(function () {
        // --- Left Side ---
        $('#simpleFields').on('keydown', 'input', function (e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                const value = $(this).val();
                if (value !== '') {
                    const newField = $('<input>')
                        .attr('type', 'number')
                        .attr('step', 'any')
                        .attr('name', 'simple[]')
                        .addClass('form-control amount-input')
                        .attr('placeholder', 'Enter next amount and press Enter');
                    $('#simpleFields').append(newField);
                    newField.focus();
                }
                calculateSimpleTotal();
            }
        }).on('input', 'input', calculateSimpleTotal);

        $('#clearSimple').on('click', function () {
            $('#simpleFields').html('<input type="number" step="any" class="form-control amount-input" name="simple[]" placeholder="Enter amount and press Enter">');
            $('#simpleTotal').text("RM 0.00");
        });

        // --- Right Side ---
        $('#amountFields').on('keydown', 'input', function (e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                const value = $(this).val();
                if (value !== '') {
                    const newField = $('<input>')
                        .attr('type', 'number')
                        .attr('step', 'any')
                        .attr('name', 'amount[]')
                        .addClass('form-control amount-input')
                        .attr('placeholder', 'Enter next amount and press Enter');
                    $('#amountFields').append(newField);
                    newField.focus();
                }
                calculateDiscount();
            }
        }).on('input', 'input', calculateDiscount);

        $('#guestCount, #cafe, #alcoholAmount, #roomAmount').on('input change', calculateDiscount);

        $('#alcoholCheck').on('change', function () {
            $('#alcoholField').toggleClass('hidden', !this.checked);
            if (!this.checked) $('#alcoholAmount').val('');
            calculateDiscount();
        });

        $('#roomCheck').on('change', function () {
            $('#roomField').toggleClass('hidden', !this.checked);
            if (!this.checked) $('#roomAmount').val('');
            calculateDiscount();
        });

        $('#fnbForm').on('keydown', function (e) {
            if (e.key === 'Enter' && !$(e.target).is('textarea')) {
                e.preventDefault();
                calculateDiscount();
            }
        });

        $('#clearBtn').on('click', function () {
            $('#fnbForm')[0].reset();
            $('#amountFields').html('<input type="number" class="form-control amount-input" name="amount[]" placeholder="Enter amount and press Enter">');
            $('#alcoholField').addClass('hidden');
            $('#roomField').addClass('hidden');
            $('#totalBefore').text("RM 0.00");
            $('#finalBill').text("RM 0.00");
        });
    });
</script>

</body>
</html>
