<div>
    <form action="/api/cards/update/{{ $card->id }}" method="post" id="editCardForm">
        @csrf
        <!-- Modal Body -->
        <div class="modal-body mx-5 mx-xl-15 my-7">
            <!-- Name On Card -->
            <div class="d-flex flex-column mb-7 fv-row">
                <label class="d-flex align-items-center fs-6 fw-semibold form-label mb-2">
                    <span class="required">Name On Card</span>
                </label>
                <input type="text" class="form-control form-control-solid" name="cardName" value="{{ $card->name }}"
                    required />
            </div>

            <!-- Card Number -->
            <div class="d-flex flex-column mb-7 fv-row">
                <label class="required fs-6 fw-semibold form-label mb-2">Card Number</label>
                <input type="text" class="form-control form-control-solid" name="cardNumber"
                    value="{{ $card->number }}" maxlength="19" required />
            </div>

            <!-- Expiration Date -->
            <div class="row mb-5">
                <!-- Expiry Month and Year -->
                <div class="col-md-8 fv-row">
                    <label class="required fs-6 fw-semibold form-label mb-2">Expiration Date</label>
                    <div class="row">
                        <!-- Month -->
                        <div class="col-6">
                            <select name="card_expiry_month" class="form-select form-select-solid" required>
                                <option disabled value="">Month</option>
                                @foreach (range(1, 12) as $month)
                                    <option value="{{ $month }}"
                                        {{ $card->expiryMonth == $month ? 'selected' : '' }}>
                                        {{ sprintf('%02d', $month) }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <!-- Year -->
                        <div class="col-6">
                            <select name="card_expiry_year" class="form-select form-select-solid" required>
                                <option disabled value="">Year</option>
                                @foreach (range(date('Y'), date('Y') + 10) as $year)
                                    <option value="{{ $year }}"
                                        {{ $card->expiryYear == $year ? 'selected' : '' }}>
                                        {{ $year }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>

                <!-- CVV -->
                <div class="col-md-4 fv-row">
                    <label class="required">CVV</label>
                    <div class="position-relative">
                        <input type="text" class="form-control form-control-solid" name="cvv"
                            value="{{ $card->cvv }}" maxlength="4" required />
                        <div class="position-absolute translate-middle-y top-50 end-0 me-3">
                            <i class="fa fa-credit-card fs-2hx"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>


<script>
    function validateCardForm() {
        let valid = true;
        const errors = {};

        const cardName = $('#cardName').val();
        const cardNumber = $('#cardNumber').val();
        const expiryDate = $('#expiryDate').val();

        if (!cardName) {
            errors.cardName = 'Card holder name is required.';
            valid = false;
        }
        if (!/^\d{16}$/.test(cardNumber)) {
            errors.cardNumber = 'Card number must be 16 digits.';
            valid = false;
        }
        if (!/^\d{2}\/\d{4}$/.test(expiryDate)) {
            errors.expiryDate = 'Expiry date must be in MM/YYYY format.';
            valid = false;
        }

        $('.text-danger').remove(); // Clear old errors
        for (const field in errors) {
            const input = $(`#${field}`);
            input.closest('.form-group').append(
                `<small class="text-danger">${errors[field]}</small>`
            );
        }

        return valid;
    }

    if (!validateCardForm()) {
        return false; // Prevent API call if validation fails
    }
</script>
