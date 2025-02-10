@extends('admin.app-admin')
@section('content')

<style>
    textarea.form-control {
        resize: none;
        border-radius: 0.5rem;
    }

    .card-footer {
        border-top: 1px solid #eee;
    }

    .file-input-wrapper {
        position: relative;
        display: inline-block;
    }

    .file-input-wrapper input[type="file"] {
        position: absolute;
        top: 0;
        left: 0;
        opacity: 0;
        cursor: pointer;
        width: 100%;
        height: 100%;
    }

    .form-control.form-control-flush {
        border: 0;
        background-color: #eee4e48f !important;
        outline: 0 !important;
        box-shadow: none;
        border-radius: 5px;
    }

    #file-preview-container img {
        max-width: 50px !important;
        max-height: 50px !important;
        object-fit: cover;
        border-radius: 4px;
        margin-right: 5px;
    }

    #file-preview-container span {
        font-size: 12px;
        /* Smaller font size for details */
        color: #6c757d;
        margin-top: 2px;
    }
</style>
    <div id="kt_app_content_container" class="app-container container-fluid">

        <div class="card">
            <!--begin::Card header-->
            <div class="card-header border-0">
                <!--begin::Card title-->
                <div class="card-title">
                    <!--begin::Search-->
                    Help & Support
                    <!--end::Search-->
                </div>
                <!--begin::Card title-->

                <!--begin::Card toolbar-->
                <div class="card-toolbar">
                </div>
                <!--end::Card toolbar-->
            </div>

            <div class="d-flex flex-column flex-lg-row">
                <!--begin::Sidebar-->
                <div class="flex-column flex-lg-row-auto w-100 w-lg-300px w-xl-400px mb-10 mb-lg-0" style="margin-left: 2rem;">
                    <!--begin::Contacts-->
                    <div class="card card-flush">
                        <!--begin::Card header-->
                        <div class="card-header pt-7" id="kt_chat_contacts_header">
                            <!--begin::Form-->
                            <form class="w-100 position-relative" autocomplete="off" data-gtm-form-interact-id="0">
                                <!--begin::Icon-->
                                <i
                                    class="fa fa-search fs-3 text-gray-500 position-absolute top-50 ms-5 translate-middle-y"><span
                                        class="path1"></span><span class="path2"></span></i> <!--end::Icon-->
    
                                <!--begin::Input-->
                                <input type="text" class="form-control form-control-solid px-13" name="search"
                                    value="" placeholder="Search by username or email..."
                                    data-gtm-form-interact-field-id="0">
                                <!--end::Input-->
                            </form>
                            <!--end::Form-->
                        </div>
                        <!--end::Card header-->
    
                        <!--begin::Card body-->
                        <div class="card-body pt-5" id="kt_chat_contacts_body">
                            <!--begin::List-->
                            <div class="scroll-y me-n5 pe-5 h-200px h-lg-auto" data-kt-scroll="true"
                                data-kt-scroll-activate="{default: false, lg: true}" data-kt-scroll-max-height="auto"
                                data-kt-scroll-dependencies="#kt_header, #kt_app_header, #kt_toolbar, #kt_app_toolbar, #kt_footer, #kt_app_footer, #kt_chat_contacts_header"
                                data-kt-scroll-wrappers="#kt_content, #kt_app_content, #kt_chat_contacts_body"
                                data-kt-scroll-offset="5px" style="max-height: 684px;">
    
                                <div class="d-flex flex-stack py-4">
                                    <!--begin::Details-->
                                    <div class="d-flex align-items-center">
                                        <!--begin::Avatar-->
                                        <div class="symbol symbol-45px symbol-circle">
                                            <span class="symbol-label  bg-light-danger text-danger fs-6 fw-bolder">M</span>
                                        </div>
                                        <div class="ms-5">
                                            <a href="#" class="fs-5 fw-bold text-gray-900 text-hover-primary mb-2">Melody  Macy</a>
                                            <div class="fw-semibold text-muted">melody@altbox.com</div>
                                        </div>
                                        <!--end::Details-->
                                    </div>
                                    <!--end::Details-->
    
                                    <!--begin::Lat seen-->
                                    <div class="d-flex flex-column align-items-end ms-2">
                                        <span class="text-muted fs-7 mb-1">1 day</span>
                                    </div>
                                    <!--end::Lat seen-->
                                </div>
                                <div class="separator separator-dashed d-none"></div>
                                <div class="d-flex flex-stack py-4">
                                    <!--begin::Details-->
                                    <div class="d-flex align-items-center">
                                        <!--begin::Avatar-->
                                        <div class="symbol symbol-45px symbol-circle">
                                            <span class="symbol-label  bg-light-warning text-warning fs-6 fw-bolder">F</span>
                                        </div>
                                        <!--begin::Details-->
                                        <div class="ms-5">
                                            <a href="#" class="fs-5 fw-bold text-gray-900 text-hover-primary mb-2">Francis
                                                Mitcham</a>
                                            <div class="fw-semibold text-muted">f.mit@kpmg.com</div>
                                        </div>
                                        <!--end::Details-->
                                    </div>
                                    <!--end::Details-->
    
                                    <!--begin::Lat seen-->
                                    <div class="d-flex flex-column align-items-end ms-2">
                                        <span class="text-muted fs-7 mb-1">2 weeks</span>
    
                                        <span class="badge badge-sm badge-circle badge-light-warning">9</span>
                                    </div>
                                    <!--end::Lat seen-->
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
    
                <!--begin::Content-->
                <div class="flex-lg-row-fluid ms-lg-7 ms-xl-10">
                   
                <div class="card" id="kt_chat_messenger">
                    <div class="card-header" id="kt_chat_messenger_header">
                        <div class="card-title">
                            <div class="d-flex justify-content-center flex-column me-3">
                                <a href="#"
                                    class="fs-4 fw-bold text-gray-900 text-hover-primary me-1 mb-2 lh-1">{{ Auth::guard('admin')->user()->first_name ?? '--' }}
                                    {{ Auth::guard('admin')->user()->last_name ?? '--' }}</a>
                                <div class="mb-0 lh-1">
                                    <span class="badge badge-success badge-circle w-10px h-10px me-1"></span>
                                    <span class="fs-7 fw-semibold text-muted">Active</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!--end::Card header-->
    
                    <!--begin::Card body-->
                    <div class="card-body" id="kt_chat_messenger_body">
                        <!--begin::Messages-->
                        <div class="scroll-y me-n5 pe-5 h-300px h-lg-auto" data-kt-element="messages" data-kt-scroll="true"
                            data-kt-scroll-activate="{default: false, lg: true}" data-kt-scroll-max-height="auto"
                            data-kt-scroll-dependencies="#kt_header, #kt_app_header, #kt_app_toolbar, #kt_toolbar, #kt_footer, #kt_app_footer, #kt_chat_messenger_header, #kt_chat_messenger_footer"
                            data-kt-scroll-wrappers="#kt_content, #kt_app_content, #kt_chat_messenger_body"
                            data-kt-scroll-offset="5px" style="max-height: 539px;">
                            <!-- Chat Messages (in and out as provided) -->
                        </div>
                        <!--end::Messages-->
    
    
                        <!--begin::Message(in)-->
                        <div class="d-flex justify-content-start mb-10 ">
                            <!--begin::Wrapper-->
                            <div class="d-flex flex-column align-items-start">
                                <!--begin::User-->
                                <div class="d-flex align-items-center mb-2">
                                    <!--begin::Avatar-->
                                    <div class="symbol  symbol-35px symbol-circle "><img alt="Pic"
                                            src="https://img.freepik.com/premium-vector/avatar-icon0002_750950-43.jpg?semt=ais_hybrid">
                                    </div><!--end::Avatar-->
                                    <!--begin::Details-->
                                    <div class="ms-3">
                                        <a href="#" class="fs-5 fw-bold text-gray-900 text-hover-primary me-1">Admin</a>
                                        <span class="text-muted fs-7 mb-1">2 mins</span>
                                    </div>
                                    <!--end::Details-->
    
                                </div>
                                <!--end::User-->
    
                                <!--begin::Text-->
                                <div class="p-5 rounded bg-light-info text-gray-900 fw-semibold mw-lg-400px text-start"
                                    data-kt-element="message-text">Hi, how are you doing?</div>
                                <!--end::Text-->
                            </div>
                            <!--end::Wrapper-->
                        </div>
                        <!--end::Message(in)-->
    
                        <!--begin::Message(out)-->
                        <div class="d-flex justify-content-end mb-10 ">
                            <!--begin::Wrapper-->
                            <div class="d-flex flex-column align-items-end">
                                <!--begin::User-->
                                <div class="d-flex align-items-center mb-2">
                                    <!--begin::Details-->
                                    <div class="me-3">
                                        <span class="text-muted fs-7 mb-1">5 mins</span>
                                        <a href="#" class="fs-5 fw-bold text-gray-900 text-hover-primary ms-1">You</a>
                                    </div>
                                    <!--end::Details-->
    
                                    <!--begin::Avatar-->
                                    <div class="symbol  symbol-35px symbol-circle "><img alt="Pic"
                                            src="https://img.freepik.com/premium-vector/avatar-icon0002_750950-43.jpg?semt=ais_hybrid">
                                    </div><!--end::Avatar-->
                                </div>
                                <!--end::User-->
    
                                <!--begin::Text-->
                                <div class="p-5 rounded bg-light-info text-gray-900 fw-semibold mw-lg-400px text-end"
                                    data-kt-element="message-text">I'm doing good, thanks</div>
                                <!--end::Text-->
                            </div>
                            <!--end::Wrapper-->
                        </div>
                        <!--end::Message(out)-->
                    </div>
                    <!--end::Card body-->
    
                    <!--begin::Card footer-->
                    <!--begin::Card footer-->
                    <div class="card-footer pt-4 position-sticky bottom-0 bg-white" id="kt_chat_messenger_footer">
    
                        <!--begin::File Preview-->
                        <div id="file-preview-container" class="mb-3"></div>
                        <!--end::File Preview-->
                        <!--begin::Input-->
                        <textarea class="form-control form-control-flush mb-3" rows="2" id="chat-input" placeholder="Type a message"></textarea>
                        <!--end::Input-->
    
                        <!--begin:Toolbar-->
                        <div class="d-flex flex-stack">
                            <!--begin::Actions-->
                            <div class="d-flex align-items-center me-2">
                                <button class="btn btn-light me-2 position-relative" type="button">
                                    <i class="fa fa-paperclip fs-3"></i>
                                    <input type="file"
                                        class="position-absolute top-0 start-0 opacity-0 w-100 h-100 cursor-pointer"
                                        id="file-input" />
                                </button>
                            </div>
                            <!--end::Actions-->
    
                            <!--begin::Send-->
                            <button class="btn btn-primary" type="button" id="send-button">Send</button>
                            <!--end::Send-->
                        </div>
                        <!--end::Toolbar-->
                    </div>
                    <!--end::Card footer-->
                    <!--end::Card footer-->
                </div>
                </div>
                <!--end::Content-->
            </div>
        </div>
    </div>
@endsection
