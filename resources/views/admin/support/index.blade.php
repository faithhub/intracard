@extends('admin.app-admin')

@section('styles')
    @vite(['resources/css/app.css'])
    <link href="https://cdn.jsdelivr.net/npm/@mdi/font@latest/css/materialdesignicons.min.css" rel="stylesheet">
    <style>
        /* Make the Vue app take full height without overlapping header/footer */
        #admin-support-app {
            height: calc(100vh - 240px);
            /* min-height: 500px; */
        }

        /* Prevent Vuetify's app bar from sticking to the top of the viewport */
        #admin-support-app .v-app-bar {
            position: relative !important;
        }

        /* Override z-index to keep components within the card */
        #admin-support-app .v-overlay__content {
            z-index: 1000 !important;
        }

        /* Make the Vue app take full width */
        #admin-support-app,
        #admin-support-app .v-application,
        #admin-support-app .v-application__wrap,
        #admin-support-app .v-container,
        #admin-support-app .v-row,
        #admin-support-app .v-col,
        #admin-support-app .v-card {
            width: 100% !important;
            max-width: 100% !important;
        }

        /* Force card to expand */
        .card,
        .card-body {
            padding: 0 !important;
            width: 100% !important;
            max-width: 100% !important;
        }

        /* Remove any margins that might be causing spacing issues */
        #kt_app_content_container .container-fluid {
            padding: 0 !important;
        }
    </style>
@endsection

@section('content')
    <div id="kt_app_content_container" class="app-container container-fluid">
        <div class="card">
            <div class="card-header border-0">
                <div class="card-title">
                    Help & Support
                </div>
            </div>
            <div class="card-body p-3">
                <div id="admin-support-app">
                    <admin-support-chat></admin-support-chat>
                </div>
            </div>
        </div>
        <meta name="user-id" content="{{ Auth::guard('admin')->id() }}">
    </div>
@endsection

@section('scripts')
    @vite(['resources/js/admin-support.js'])
@endsection
