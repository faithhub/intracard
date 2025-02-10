<!-- Modal Body -->
<div>
    <form action="" method="post" id="billForm">
        <div class="modal-body">
            <!-- Select Dropdown -->
            <select name="bill_type" class="form-select form-select-solid mb-5 mt-3" id="billTypeSelect"
                onchange="updateBillDetails()">
                <option value="">Select Bill</option>
                <option value="carBill">Car Bill</option>
                <option value="utilityBill" selected>Utility Bill</option>
                <option value="phoneBill">Phone Bill</option>
                <option value="internetBill">Internet Bill</option>
            </select>

            <!-- Shared Fields -->
            <div id="sharedBillDetails" class="bill-details d-block">
                <div class="row">
                    <!-- Amount Input -->
                    <div class="col-md-6 mb-4">
                        <div class="border border-gray-300 border-dashed rounded py-3 px-4">
                            <label class="fw-semibold mb-2" for="amount">Amount</label>
                            <input type="number" name="amount" class="form-control form-control-solid" id="amount"
                                placeholder="Enter amount" />
                        </div>
                    </div>

                    <!-- Due Date Picker -->
                    <div class="col-md-6 mb-4">
                        <div class="border border-gray-300 border-dashed rounded py-3 px-4">
                            <label class="fw-semibold mb-2" for="dueDate">Due Date</label>
                            <input type="date" name="due_date" class="form-control form-control-solid"
                                id="dueDate" />
                        </div>
                    </div>
                </div>

                <div class="row">
                    <!-- Provider Dropdown -->
                    <div class="col-md-6 mb-4">
                        <div class="border border-gray-300 border-dashed rounded py-3 px-4">
                            <label class="fw-semibold mb-2" for="provider">Provider</label>
                            <select name="provider" id="provider" class="form-select form-select-solid">
                                <option value="" selected>Select Provider</option>
                                <option value="ABC Power Company">ABC Power Company</option>
                                <option value="XYZ Energy Solutions">XYZ Energy Solutions</option>
                                <option value="National Grid">National Grid</option>
                                <option value="XYZ Mobile">XYZ Mobile</option>
                                <option value="ABC Telecom">ABC Telecom</option>
                                <option value="Global Mobile">Global Mobile</option>
                                <option value="NetFast Internet">NetFast Internet</option>
                                <option value="SpeedNet">SpeedNet</option>
                                <option value="Global Internet">Global Internet</option>
                            </select>
                        </div>
                    </div>

                    <!-- Account Number Input -->
                    <div class="col-md-6 mb-4">
                        <div class="border border-gray-300 border-dashed rounded py-3 px-4">
                            <label class="fw-semibold mb-2" for="accountNumber">Account Number</label>
                            <input type="text" name="account_number" class="form-control form-control-solid"
                                id="accountNumber" placeholder="Enter account number" />
                        </div>
                    </div>
                </div>
            </div>

            <!-- Car-Specific Fields -->
            <div id="carBillDetails" class="bill-details d-none">
                <div class="row">
                    <!-- Amount Input -->
                    <div class="col-md-6 mb-4">
                        <div class="border border-gray-300 border-dashed rounded py-3 px-4">
                            <label class="fw-semibold mb-2" for="amount">Amount</label>
                            <input type="number" name="car_amount" class="form-control form-control-solid" id="carAmount"
                                placeholder="Enter amount" />
                        </div>
                    </div>

                    <!-- Frequency Dropdown -->
                    <div class="col-md-6 mb-4">
                        <div class="border border-gray-300 border-dashed rounded py-3 px-4">
                            <label class="fw-semibold mb-2" for="carFrequency">Frequency</label>
                            <select name="frequency" id="carFrequency" class="form-select form-select-solid">
                                <option value="">Select</option>
                                <option value="Bi-weekly">Bi-weekly</option>
                                <option value="Monthly">Monthly</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <!-- Due Date Picker -->
                    <div class="col-md-6 mb-4">
                        <div class="border border-gray-300 border-dashed rounded py-3 px-4">
                            <label class="fw-semibold mb-2" for="dueDate">Due Date</label>
                            <input type="date" name="car_due_date" class="form-control form-control-solid"
                                id="carDueDate" />
                        </div>
                    </div>

                    <!-- Car Model Input -->
                    <div class="col-md-6 mb-4">
                        <div class="border border-gray-300 border-dashed rounded py-3 px-4">
                            <label class="fw-semibold mb-2" for="carModel">Car Model</label>
                            <input type="text" name="car_model" class="form-control form-control-solid"
                                id="carModel" placeholder="Enter car model" />
                        </div>
                    </div>
                </div>

                <div class="row">
                    <!-- Car Year Input -->
                    <div class="col-md-6 mb-4">
                        <div class="border border-gray-300 border-dashed rounded py-3 px-4">
                            <label class="fw-semibold mb-2" for="carYear">Car Year</label>
                            <input type="number" name="car_year" class="form-control form-control-solid"
                                id="carYear" placeholder="Enter car year" />
                        </div>
                    </div>

                    <!-- Car VIN Input -->
                    <div class="col-md-6 mb-4">
                        <div class="border border-gray-300 border-dashed rounded py-3 px-4">
                            <label class="fw-semibold mb-2" for="carVIN">Car VIN</label>
                            <input type="text" name="car_vin" class="form-control form-control-solid"
                                id="carVIN" placeholder="Enter car VIN" />
                        </div>
                    </div>
                </div>
            </div>

            <!-- Card Selection Section -->
            <div class="mb-0 mt-5 fv-row">
                <!-- Label -->
                <label class="d-flex align-items-center form-label mb-5">
                    Select Payment Card
                    <span class="ms-1" data-bs-toggle="tooltip" aria-label="Select a saved card or add a new one"
                        data-bs-original-title="Select a saved card or add a new one">
                        <i class="ki-duotone ki-information-5 text-gray-500 fs-6"></i>
                    </span>
                </label>

                <!-- Options -->
                <div class="mb-0">
                    <!-- Visa Card Option -->
                    <label class="d-flex flex-stack mb-5 cursor-pointer">
                        <span class="d-flex align-items-center me-2">
                            <span class="symbol symbol-50px me-6">
                                <span class="symbol-label">
                                    <img src="{{ asset('assets/cards/visa.webp') }}" alt="Visa" class="h-40px">
                                </span>
                            </span>
                            <span class="d-flex flex-column">
                                <span class="fw-bold cc-last-4 text-gray-800 text-hover-primary fs-5">Visa ****
                                    1234</span>
                                <span class="fs-6 fw-semibold text-muted">Expires 09/25</span>
                                <span class="fs-6 fw-semibold text-muted"> Card Limit: <span
                                        class="fw-bold text-gray-800">$2,000.00</span></span>
                            </span>
                        </span>
                        <span class="form-check form-check-custom form-check-solid">
                            <input class="form-check-input" type="radio" name="payment_card" value="visa">
                        </span>
                    </label>

                    <!-- Mastercard Option -->
                    <label class="d-flex flex-stack mb-5 cursor-pointer">
                        <span class="d-flex align-items-center me-2">
                            <span class="symbol symbol-50px me-6">
                                <span class="symbol-label">
                                    <img src="{{ asset('assets/cards/mastercard.png') }}" alt="Mastercard"
                                        class="h-40px">
                                </span>
                            </span>
                            <span class="d-flex flex-column">
                                <span class="fw-bold cc-last-4 text-gray-800 text-hover-primary fs-5">Mastercard ****
                                    5678</span>
                                <span class="fs-6 fw-semibold text-muted">Expires 11/26 &nbsp;</span>
                                <span class="fs-6 fw-semibold text-muted"> Card Limit: <span
                                        class="fw-bold text-gray-800">$1,000.00</span></span>
                            </span>
                        </span>
                        <span class="form-check form-check-custom form-check-solid">
                            <input class="form-check-input" type="radio" name="payment_card" value="mastercard">
                        </span>
                    </label>

                    @isset($cards)
                        {{-- @foreach ($cards as $card)
                                <label class="d-flex flex-stack mb-5 cursor-pointer">
                                    <span class="d-flex align-items-center me-2">
                                        <span class="symbol symbol-50px me-6">
                                            <span class="symbol-label">
                                                <img src="{{ asset('assets/cards/' . $card['type'] . '.png') }}"
                                                    alt="{{ $card['type'] }}" class="h-40px">
                                            </span>
                                        </span>
                                        <span class="d-flex flex-column">
                                            <span class="fw-bold cc-last-4 text-gray-800 text-hover-primary fs-5">{{ $card['type'] }}
                                                **** {{ $card['last_four'] }}</span>
                                            <span class="fs-6 fw-semibold text-muted">Expires
                                                {{ $card['expiry'] }}</span>
                                        </span>
                                    </span>
                                    <span class="form-check form-check-custom form-check-solid">
                                        <input class="form-check-input" type="radio" name="payment_card"
                                            value="{{ $card['id'] }}">
                                    </span>
                                </label>
                                <div class="mb-5 ps-14">
                                    <span class="text-muted fs-7">Card Limit: </span>
                                    <span class="fw-bold text-gray-800">${{ number_format($card['limit'], 2) }}</span>
                                </div>
                            @endforeach --}}
                    @endisset
                </div>
            </div>

        </div>
    </form>
</div>

<script>
    function updateBillDetails() {
        const selectedValue = document.getElementById('billTypeSelect').value;

        const carFields = document.getElementById('carBillDetails');
        const sharedFields = document.getElementById('sharedBillDetails');
        // const providerAndAccountDetails = document.getElementById('providerAndAccountDetails');

        if (selectedValue === "carBill") {
            carFields.classList.remove('d-none'); // Show car-specific fields
            sharedFields.classList.add('d-none'); // Hide provider and account fields
        } else {
            carFields.classList.add('d-none'); // Hide car-specific fields
            sharedFields.classList.remove('d-none'); // Show provider and account fields
        }
    }
</script>
