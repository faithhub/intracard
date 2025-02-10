@extends('app-user')
@section('content')
<style>
   
   .img-cc{
            max-width: 70px !important;
        }
</style>
    <div id="kt_app_content_container" class="app-container container-fluid">
        <div class="card mb-5 mb-xl-10">
            <!--begin::Card header-->
            <div class="card-header card-header-stretch pb-0">
                <!--begin::Title-->
                <div class="card-title">
                    <h3 class="m-0">Payment Methods</h3>
                </div>
                <!--end::Title-->
                <!--begin::Toolbar-->
                <div class="card-toolbar m-0">
                    <!--begin::Tab nav-->
                    <ul class="nav nav-stretch nav-line-tabs border-transparent" role="tablist">
                        <!--begin::Tab item-->
                        <li class="nav-item" role="presentation">
                            <a id="kt_billing_creditcard_tab" class="nav-link fs-5 fw-bold me-5 active" data-bs-toggle="tab"
                                role="tab" href="#kt_billing_creditcard" aria-selected="true">
                                Credit
                            </a>
                        </li>
                        <li class="nav-item" role="presentation">
                            <a id="kt_billing_creditcardDebit_tab" class="nav-link fs-5 fw-bold me-5" data-bs-toggle="tab"
                                role="tab" href="#kt_billing_creditcardDebit" aria-selected="true">
                                Debit Card
                            </a>
                        </li>
                    </ul>
                    <!--end::Tab nav-->
                </div>
                <!--end::Toolbar-->
            </div>
            <!--end::Card header-->
            <!--begin::Tab content-->
            <div id="kt_billing_payment_tab_content" class="card-body tab-content">
                <div id="kt_billing_creditcard" class="tab-pane fade show active" role="tabpanel" "="" aria-labelledby="kt_billing_creditcard_tab">
                 <!--begin::Title-->
                 <h3 class="mb-5">My Credit Cards</h3>
                 <!--end::Title-->
                 <!--begin::Row-->
                 <div class="row gx-9 gy-6">
                    <!--begin::Col-->
                    <div class="col-xl-6" data-kt-billing-element="card">
                       <!--begin::Card-->
                       <div class="card card-dashed h-xl-100 flex-row flex-stack flex-wrap p-6">
                          <!--begin::Info-->
                          <div class="d-flex flex-column py-2">
                             <!--begin::Owner-->
                             <div class="d-flex align-items-center fs-4 fw-bold mb-5">
                                Marcus Morris
                                <span class="badge badge-light-success fs-7 ms-2">Primary</span>
                             </div>
                             <!--end::Owner-->
                             <!--begin::Wrapper-->
                             <div class="d-flex align-items-center">
                                <!--begin::Icon-->
                                <img src="{{ asset('assets/cards/visa.webp') }}" alt="" class="me-4 img-cc">
                                <!--end::Icon-->
                                <!--begin::Details-->
                                <div>
                                   <div class="fs-4 fw-bold">Visa **** 1679</div>
                                   <div class="fs-6 fw-semibold text-gray-500">Card expires at 09/24</div>
                                </div>
                                <!--end::Details-->
                             </div>
                             <!--end::Wrapper-->
                          </div>
                          <!--end::Info-->
                          <!--begin::Actions-->
                          <div class="d-flex align-items-center py-2">
                             <button class="btn btn-sm btn-light btn-active-light-primary me-3" data-kt-billing-action="card-delete">
                                <!--begin::Indicator label-->
                                <span class="indicator-label">
                                Delete</span>
                                <!--end::Indicator label-->
                                <!--begin::Indicator progress-->
                                <span class="indicator-progress">
                                Please wait...    <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
                                </span>
                                <!--end::Indicator progress-->
                             </button>
                             <button class="btn btn-sm btn-light btn-active-light-primary" data-bs-toggle="modal" data-bs-target="#kt_modal_new_card">Edit</button>
                          </div>
                          <!--end::Actions-->
                       </div>
                       <!--end::Card-->
                    </div>
                    <div class="col-xl-6">
                       <!--begin::Notice-->
                       <div class="notice d-flex bg-light-primary rounded border-primary border border-dashed h-lg-100 p-6" style="background-color: #a000f900 !important">
                          <!--begin::Wrapper-->
                          <div class="d-flex flex-stack flex-grow-1 flex-wrap flex-md-nowrap">
                             <!--begin::Content-->
                             <div class="mb-3 mb-md-0 fw-semibold">
                                <h4 class="text-gray-900 fw-bold">Important Note!</h4>
                                <div class="fs-6 text-gray-700 pe-7">Please carefully read <a href="#" class="fw-bold me-1">Product Terms</a> adding <br> your new payment card</div>
                             </div>
                             <!--end::Content-->
                             <!--begin::Action-->
                             <a href="#" class="btn btn-primary px-6 align-self-center text-nowrap" data-bs-toggle="modal" data-bs-target="#kt_modal_new_card">
                             Add Card            </a>
                             <!--end::Action-->
                          </div>
                          <!--end::Wrapper-->
                       </div>
                       <!--end::Notice-->
                    </div>
                    <!--end::Col-->
                 </div>
                 <!--end::Row-->
              </div>
                <div id="kt_billing_creditcardDebit" class="tab-pane fade show" role="tabpanel" "="" aria-labelledby="kt_billing_creditcardDebit_tab">
                 <!--begin::Title-->
                 <h3 class="mb-5">My Debit Cards</h3>
                 <!--end::Title-->
                 <!--begin::Row-->
                 <div class="row gx-9 gy-6">
                    <!--begin::Col-->
                    <div class="col-xl-6" data-kt-billing-element="card">
                       <!--begin::Card-->
                       <div class="card card-dashed h-xl-100 flex-row flex-stack flex-wrap p-6">
                          <!--begin::Info-->
                          <div class="d-flex flex-column py-2">
                             <!--begin::Owner-->
                             <div class="d-flex align-items-center fs-4 fw-bold mb-5">
                                Jacob Holder
                             </div>
                             <div class="d-flex align-items-center">
                                <!--begin::Icon-->
                                <img src="{{ asset('assets/cards/mastercard.png') }}" alt="" class="me-4 img-cc">
                                <div>
                                   <div class="fs-4 fw-bold">Mastercard **** 2040</div>
                                   <div class="fs-6 fw-semibold text-gray-500">Card expires at 10/22</div>
                                </div>
                                <!--end::Details-->
                             </div>
                             <!--end::Wrapper-->
                          </div>
                          <!--end::Info-->
                          <!--begin::Actions-->
                          <div class="d-flex align-items-center py-2">
                             <button class="btn btn-sm btn-light btn-active-light-primary me-3" data-kt-billing-action="card-delete">
                                <!--begin::Indicator label-->
                                <span class="indicator-label">
                                Delete</span>
                                <!--end::Indicator label-->
                                <!--begin::Indicator progress-->
                                <span class="indicator-progress">
                                Please wait...    <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
                                </span>
                                <!--end::Indicator progress-->
                             </button>
                             <button class="btn btn-sm btn-light btn-active-light-primary" data-bs-toggle="modal" data-bs-target="#kt_modal_new_card">Edit</button>
                          </div>
                          <!--end::Actions-->
                       </div>
                       <!--end::Card-->
                    </div>
                    <!--end::Col-->
                    <!--begin::Col-->
                    <div class="col-xl-6">
                       <!--begin::Notice-->
                       <div class="notice d-flex bg-light-primary rounded border-primary border border-dashed h-lg-100 p-6" style="background-color: #a000f900 !important">
                          <!--begin::Wrapper-->
                          <div class="d-flex flex-stack flex-grow-1 flex-wrap flex-md-nowrap">
                             <!--begin::Content-->
                             <div class="mb-3 mb-md-0 fw-semibold">
                                <h4 class="text-gray-900 fw-bold">Important Note!</h4>
                                <div class="fs-6 text-gray-700 pe-7">Please carefully read <a href="#" class="fw-bold me-1">Product Terms</a> adding <br> your new payment card</div>
                             </div>
                             <!--end::Content-->
                             <!--begin::Action-->
                             <a href="#" class="btn btn-primary px-6 align-self-center text-nowrap" data-bs-toggle="modal" data-bs-target="#kt_modal_new_card">
                             Add Card            </a>
                             <!--end::Action-->
                          </div>
                          <!--end::Wrapper-->
                       </div>
                       <!--end::Notice-->
                    </div>
                    <!--end::Col-->
                 </div>
                 <!--end::Row-->
              </div>
           </div>
           <!--end::Tab content-->
        </div>

        <div class="card mb-5 mb-xl-10">
            <!--begin::Card header-->
            <div class="card-header card-header-stretch pb-0">
               <!--begin::Title-->
               <div class="card-title">
                  <h3 class="m-0">Transactions</h3>
               </div>
            </div>
            
            <div class="card-body pt-0">
               <div id="kt_customers_table_wrapper" class="dt-container dt-bootstrap5 dt-empty-footer">
                  <div id="" class="table-responsive">
                     <table class="table align-middle table-row-dashed fs-6 gy-5 dataTable" id="kt_customers_table" style="width: 100%;">
                           <colgroup>
                           <col data-dt-column="0" style="width: 36.3906px;">
                           <col data-dt-column="1" style="width: 132.766px;">
                           <col data-dt-column="2" style="width: 166.844px;">
                           <col data-dt-column="3" style="width: 191.25px;">
                           <col data-dt-column="4" style="width: 170.078px;">
                           <col data-dt-column="5" style="width: 177.438px;">
                           <col data-dt-column="6" style="width: 111.734px;">
                           </colgroup>
                           <thead>
                           <tr class="text-start text-gray-500 fw-bold fs-7 text-uppercase gs-0" role="row">
                              <th class="w-10px pe-2 dt-orderable-none" data-dt-column="0" rowspan="1" colspan="1" aria-label="">
                                 <span class="dt-column-title">
                                       <div class="form-check form-check-sm form-check-custom form-check-solid me-3">
                                       <input class="form-check-input" type="checkbox" data-kt-check="true" data-kt-check-target="#kt_customers_table .form-check-input" value="1">
                                       </div>
                                 </span>
                                 <span class="dt-column-order"></span>
                              </th>
                              <th class="min-w-125px dt-orderable-asc dt-orderable-desc" data-dt-column="1" rowspan="1" colspan="1" aria-label="Customer Name: Activate to sort" tabindex="0"><span class="dt-column-title" role="button">Transaction ID</span><span class="dt-column-order"></span></th>
                              <th class="min-w-125px dt-orderable-asc dt-orderable-desc" data-dt-column="2" rowspan="1" colspan="1" aria-label="Email: Activate to sort" tabindex="0"><span class="dt-column-title" role="button">Amount</span><span class="dt-column-order"></span></th>
                              <th class="min-w-125px dt-orderable-asc dt-orderable-desc" data-dt-column="4" rowspan="1" colspan="1" aria-label="Payment Method: Activate to sort" tabindex="0"><span class="dt-column-title" role="button" id="tableCardType">Credit Card</span><span class="dt-column-order"></span></th>
                              <th class="min-w-125px dt-orderable-asc dt-orderable-desc" data-dt-column="5" rowspan="1" colspan="1" aria-label="Created Date: Activate to sort" tabindex="0"><span class="dt-column-title" role="button">Date</span><span class="dt-column-order"></span></th>
                              <th class="text-end min-w-70px dt-orderable-none" data-dt-column="6" rowspan="1" colspan="1" aria-label="Actions"><span class="dt-column-title">Actions</span><span class="dt-column-order"></span></th>
                           </tr>
                           </thead>
                           <tbody class="fw-semibold text-gray-600">
                           <tr>
                              <td>
                                 <div class="form-check form-check-sm form-check-custom form-check-solid">
                                       <input class="form-check-input" type="checkbox" value="1">
                                 </div>
                              </td>
                              <td>
                                 #DFSGDFHGGFDJHGF
                              </td>
                              <td>
                                 ${{ number_format(500,2) }}
                              </td>
                              <td data-filter="visa">
                                 <img src="{{ asset('assets/cards/mastercard.png') }}" class="w-35px me-3" alt="">
                                 **** 3215
                              </td>
                              <td data-order="2020-08-18T15:34:00+01:00">
                                 18 Aug 2020, 3:34 pm
                              </td>
                              <td class="text-end">
                                 <a href="#" class="menu-link px-3">View</a>
                              </td>
                           </tr>
                           </tbody>
                     </table>
                  </div>
               </div>
            </div>
        </div>
     </div>
     <!--end::Modal - Create Project--><!--begin::Modal - New Card-->
     <div class="modal fade" id="kt_modal_new_card" tabindex="-1" aria-hidden="true">
        <!--begin::Modal dialog-->
        <div class="modal-dialog modal-dialog-centered mw-650px">
           <!--begin::Modal content-->
           <div class="modal-content">
              <!--begin::Modal header-->
              <div class="modal-header">
                 <!--begin::Modal title-->
                 <h2>Add New Card</h2>
                 <!--end::Modal title-->
                 <!--begin::Close-->
                 <div class="btn btn-sm btn-icon btn-active-color-primary" data-bs-dismiss="modal">
                    <i class="fa fa-xmark fs-1"><span class="path1"></span><span class="path2"></span></i>
                 </div>
                 <!--end::Close-->
              </div>
              <!--end::Modal header-->
              <!--begin::Modal body-->
              <div class="modal-body scroll-y mx-5 mx-xl-15 my-7">
                 <!--begin::Form-->
                 <form id="kt_modal_new_card_form" class="form" action="#">
                    <!--begin::Input group-->
                    <div class="d-flex flex-column mb-7 fv-row">
                       <!--begin::Label-->
                       <label class="d-flex align-items-center fs-6 fw-semibold form-label mb-2">
                       <span class="required">Name On Card</span>
                       <span class="ms-1"  data-bs-toggle="tooltip" title="Specify a card holder's name" >
                       <i class="fa fa-exclamation text-gray-500"><span class="path1"></span><span class="path2"></span><span class="path3"></span></i></span>    </label>
                       <!--end::Label-->
                       <input type="text" class="form-control form-control-solid" placeholder="" name="card_name" value="Max Doe"/>
                    </div>
                    <!--end::Input group-->
                    <!--begin::Input group-->
                    <div class="d-flex flex-column mb-7 fv-row">
                       <!--begin::Label-->
                       <label class="required fs-6 fw-semibold form-label mb-2">Card Number</label>
                       <!--end::Label-->
                       <!--begin::Input wrapper-->
                       <div class="position-relative">
                          <!--begin::Input-->
                          <input type="text" class="form-control form-control-solid" placeholder="Enter card number" name="card_number" value="4111 1111 1111 1111"/>
                          <!--end::Input-->
                          <!--begin::Card logos-->
                          <div class="position-absolute translate-middle-y top-50 end-0 me-5">
                             <img src="{{ asset('assets/cards/visa.webp') }}" alt="" class="h-25px"/>
                             <img src="{{ asset('assets/cards/mastercard.png') }}" alt="" class="h-25px"/>
                             {{-- <img src="/good/assets/media/svg/card-logos/american-express.svg" alt="" class="h-25px"/> --}}
                          </div>
                          <!--end::Card logos-->
                       </div>
                       <!--end::Input wrapper-->
                    </div>
                    <!--end::Input group-->
                    <!--begin::Input group-->
                    <div class="row mb-10">
                       <!--begin::Col-->
                       <div class="col-md-8 fv-row">
                          <!--begin::Label-->
                          <label class="required fs-6 fw-semibold form-label mb-2">Expiration Date</label>
                          <!--end::Label-->
                          <!--begin::Row-->
                          <div class="row fv-row">
                             <!--begin::Col-->
                             <div class="col-6">
                                <select name="card_expiry_month" class="form-select form-select-solid" data-control="select2" data-hide-search="true" data-placeholder="Month">
                                   <option></option>
                                   <option value="1">1</option>
                                   <option value="2">2</option>
                                   <option value="3">3</option>
                                   <option value="4">4</option>
                                   <option value="5">5</option>
                                   <option value="6">6</option>
                                   <option value="7">7</option>
                                   <option value="8">8</option>
                                   <option value="9">9</option>
                                   <option value="10">10</option>
                                   <option value="11">11</option>
                                   <option value="12">12</option>
                                </select>
                             </div>
                             <div class="col-6">
                                <select name="card_expiry_year" class="form-select form-select-solid" data-control="select2" data-hide-search="true" data-placeholder="Year">
                                   <option></option>
                                   <option value="2024">2024</option>
                                   <option value="2025">2025</option>
                                   <option value="2026">2026</option>
                                   <option value="2027">2027</option>
                                   <option value="2028">2028</option>
                                   <option value="2029">2029</option>
                                   <option value="2030">2030</option>
                                   <option value="2031">2031</option>
                                   <option value="2032">2032</option>
                                   <option value="2033">2033</option>
                                   <option value="2034">2034</option>
                                </select>
                             </div>
                          </div>
                       </div>
                       <div class="col-md-4 fv-row">
                          <label class="d-flex align-items-center fs-6 fw-semibold form-label mb-2">
                          <span class="required">CVV</span>
                          <span class="ms-1"  data-bs-toggle="tooltip" title="Enter a card CVV code">
                          <i class="fa fa-exclamation text-gray-500">
                          <span class="path1"></span>
                          <span class="path2"></span>
                          <span class="path3"></span>
                          </i>
                          </span>
                          </label>
                          <div class="position-relative">
                             <input type="text" class="form-control form-control-solid" minlength="3" maxlength="4" placeholder="CVV" name="card_cvv"/>
                             <div class="position-absolute translate-middle-y top-50 end-0 me-3">
                                <i class="fa fa-credit-card fs-2hx"><span class="path1"></span><span class="path2"></span></i>
                             </div>
                          </div>
                       </div>
                    </div>
                    <div class="text-center pt-15">
                       <button type="submit" id="kt_modal_new_card_submit" class="btn btn-primary">
                       <span class="indicator-label">
                       Submit
                       </span>
                       <span class="indicator-progress">
                       Please wait... <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
                       </span>
                       </button>
                    </div>
                 </form>
                 <!--end::Form-->
              </div>
              <!--end::Modal body-->
           </div>
           <!--end::Modal content-->
        </div>
        <!--end::Modal dialog-->
     </div>

     <script>
        document.addEventListener("DOMContentLoaded", function () {
            // Get references to the tabs and the text div
            const creditTab = document.getElementById("kt_billing_creditcard_tab");
            const debitTab = document.getElementById("kt_billing_creditcardDebit_tab");
            const dynamicText = document.getElementById("topNavText");
            const dynamicText2 = document.getElementById("tableCardType");
    
            // Function to update the text
            function updateText(selectedText) {
                dynamicText.textContent = `${selectedText}`;
                dynamicText2.textContent = `${selectedText}`;
            }
    
            // Add event listeners to the tabs
            creditTab.addEventListener("click", function () {
                updateText("Credit Card");
            });
    
            debitTab.addEventListener("click", function () {
                updateText("Debit Card");
            });
        });
    </script>
    
@endsection
