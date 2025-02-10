<div>
    <form action="" method="post" id="walletForm">
        <div class="modal-body">
                <!-- Amount Input -->
                <div class="mb-5">
                    <label for="amount" class="form-label fw-bold">Enter Amount</label>
                    <input
                        type="number"
                        name="amount"
                        class="form-control form-control-lg"
                        id="amount"
                        placeholder="Enter amount"
                        min="1"
                    />
                </div>
                <div class="mb-5">
                    <label for="service" class="form-label fw-bold">Services</label>
                    <select
                        name="service"
                        class="form-select form-select-solid"
                        id="service"
                    >
                        <option value="">Select Service</option>
                        @if (Auth::user()->account_type == 'mortgage')
                            <option value="Mortgage">Mortgage</option>
                        @endif
                        @if (Auth::user()->account_type == 'rent')
                            <option value="Rent">Rent</option>
                        @endif
                        <option value="carBill">Car Bill</option>
                        <option value="utilityBill">Utility Bill</option>
                        <option value="phoneBill">Phone Bill</option>
                        <option value="internetBill">Internet Bill</option>
                    </select>
                    <label id="service-error" class="error text-danger mt-1" for="service"></label>
                </div>
                <!-- Card Selection Section -->
                <div class="mb-0 fv-row mt-3">
                    <!-- Label -->
                    <label class="d-flex align-items-center form-label mb-5">
                        Select Payment Card
                        <span class="ms-1" data-bs-toggle="tooltip"
                            aria-label="Select a saved card or add a new one"
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
                                        <img src="{{ asset('assets/cards/visa.webp') }}" alt="Visa"
                                            class="h-40px">
                                    </span>
                                </span>
                                <span class="d-flex flex-column">
                                    <span class="fw-bold cc-last-4 text-gray-800 text-hover-primary fs-5">Visa **** 1234</span>
                                    <span class="fs-6 fw-semibold text-muted">Expires 09/25</span>
                                    <span class="fs-6 fw-semibold text-muted"> Card Limit: <span
                                            class="fw-bold text-gray-800">$2,000.00</span></span>
                                </span>
                            </span>
                            <span class="form-check form-check-custom form-check-solid">
                                <input class="form-check-input" type="radio" name="payment_card"
                                    value="visa">
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
                                    <span class="fs-6 fw-semibold text-muted"> Card Limit:
                                    <span class="fw-bold text-gray-800">$1,000.00</span></span>
                                </span>
                            </span>
                            <span class="form-check form-check-custom form-check-solid">
                                <input class="form-check-input" type="radio" name="payment_card"
                                    value="mastercard">
                            </span>
                        </label>

                        @isset($cards)
                            @foreach ($cards as $card)
                                <label class="d-flex flex-stack mb-5 cursor-pointer">
                                    <span class="d-flex align-items-center me-2">
                                        <span class="symbol symbol-50px me-6">
                                            <span class="symbol-label">
                                                <img src="{{ asset('assets/cards/' . $card['type'] . '.png') }}"
                                                    alt="{{ $card['type'] }}" class="h-40px">
                                            </span>
                                        </span>
                                        <span class="d-flex flex-column">
                                            <span
                                                class="fw-bold cc-last-4 text-gray-800 text-hover-primary fs-5">{{ $card['type'] }}
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
                            @endforeach
                        @endisset
                    </div>
                </div>
                {{-- <div class="modal-footer">
                   <div class="m-3">
                     <!-- Submit Button -->
                     <button type="submit" class="btn btn-primary m-2">Proceed</button>
                     <!-- Close Button -->
                     <button type="button" class="btn btn-secondary m-2" id="closeWalletModalButton">Close</button>
                   </div>
                </div> --}}
        </div>
    </form>
</div>