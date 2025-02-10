@extends('app-user')
@section('content')
<style>
    .card {
        background-color: #ffffff;
        border-radius: 15px;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
    }

    .badge {
        border-radius: 50px;
        font-size: 1.2rem;
        display: flex;
        align-items: center;
    }

    .badge i {
        transform: rotate(45deg); /* Makes the arrow look like a square root */
    }

    h5 {
        font-size: 1.3rem;
    }

    h2 {
        font-size: 1.8rem;
    }

    .separator {
        border-color: #eaeaea;
    }
</style>

<div id="kt_app_content_container" class="app-container container-fluid">
    <div class="row mt-10">
        <div class="col-md-6 offset-md-3">
            <div class="card shadow-lg rounded-4 border-0" style="background: #f9f9f9; padding: 30px;">
                <!--begin::Body-->
                <div class="card-body">
                    <!--begin::Title-->
                    <div class="text-center mb-5">
                        <h2 class="fw-bold text-gray-800">Credit Metric</h2>
                        <p class="text-muted fs-6">Your credit factors at a glance</p>
                    </div>
                    <!--end::Title-->

                    <!--begin::Items-->
                    <div class="mt-3">
                        <!--begin::Item-->
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <div class="me-3">
                                <h5 class="fw-bold text-gray-800">Payment History</h5>
                            </div>
                            <span class="badge badge-light-success px-4 py-2 text-gray-800 fw-bold fs-5">
                                <i class="fa fa-arrow-up fs-4 text-success"></i>
                                &nbsp;35%
                            </span>
                        </div>
                        <!--end::Item-->

                        <div class="separator separator-dashed my-3"></div>

                        <!--begin::Item-->
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <div class="me-3">
                                <h5 class="fw-bold text-gray-800">Credit Utilization</h5>
                            </div>
                            <span class="badge badge-light-success px-4 py-2 text-gray-800 fw-bold fs-5">
                                <i class="fa fa-arrow-up fs-4 text-success"></i>
                                &nbsp;30%
                            </span>
                        </div>
                        <!--end::Item-->

                        <div class="separator separator-dashed my-3"></div>

                        <!--begin::Item-->
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <div class="me-3">
                                <h5 class="fw-bold text-gray-800">Length of Credit History</h5>
                            </div>
                            <span class="badge badge-light-warning px-4 py-2 text-gray-800 fw-bold fs-5">
                                <i class="fa fa-arrow-down fs-4 text-warning"></i>
                                &nbsp;15%
                            </span>
                        </div>
                        <!--end::Item-->

                        <div class="separator separator-dashed my-3"></div>

                        <!--begin::Item-->
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <div class="me-3">
                                <h5 class="fw-bold text-gray-800">New Credit Inquiries</h5>
                            </div>
                            <span class="badge badge-light-warning px-4 py-2 text-gray-800 fw-bold fs-5">
                                <i class="fa fa-arrow-down fs-4 text-warning"></i>
                                &nbsp;10%
                            </span>
                        </div>
                        <!--end::Item-->

                        <div class="separator separator-dashed my-3"></div>

                        <!--begin::Item-->
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <div class="me-3">
                                <h5 class="fw-bold text-gray-800">Credit Mix</h5>
                            </div>
                            <span class="badge badge-light-danger px-4 py-2 text-gray-800 fw-bold fs-5">
                                <i class="fa fa-arrow-down fs-4 text-danger"></i>
                                &nbsp;10%
                            </span>
                        </div>
                        <!--end::Item-->

                        <div class="separator separator-dashed my-3"></div>
                    </div>
                    <!--end::Items-->
                </div>
                <!--end::Body-->
            </div>
        </div>
    </div>
</div>

@endsection
