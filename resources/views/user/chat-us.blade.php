@extends('app-user')
@section('content')
    <div id="kt_app_content_container" class="app-container container-fluid">
        <div class="d-flex flex-column flex-lg-row">
            <div class="flex-lg-row-fluid ms-lg-7 ms-xl-10">
                <div id="app">
                    <chat-component></chat-component>
                </div>
            </div>
        </div>
    </div>
    <script>
    window.Laravel = {
        user: @json($userData),
    };
    </script>
    
    
    <script src="https://cdn.jsdelivr.net/npm/vue@3.2.45/dist/vue.global.js"></script>
    @vite('resources/js/app.js') <!-- Vite directive to include app.js -->

@endsection
