<style>
    /* Hide by default */
    .mobile-only {
        display: none;
    }

    .desktop-only {
        display: block;
    }

    /* Show profile picture only on smaller screens */
    @media (max-width: 768px) {
        .remove-pp-margin {
            margin-right: 0rem !important;
        }

        .mobile-only {
            display: block;
            /* Show profile picture */
        }

        .desktop-only {
            display: none;
            /* Hide username and description */
        }
    }

    /* Show username and description only on larger screens */
    @media (min-width: 769px) {
        .mobile-only {
            display: none;
            /* Hide profile picture */
        }

        .desktop-only {
            display: block;
            /* Show username and description */
        }
    }

    .notification-badge {
        background-color: #dc3545;
        /* Red background */
        color: white;
        /* White text color */
        font-size: 0.75rem;
        /* Smaller font size */
        font-weight: bold;
        /* Bold text */
        padding: 0.25rem 0.5rem;
        /* Padding for size */
        border-radius: 50%;
        /* Circular badge */
        position: absolute;
        top: 0;
        /* Align to the top */
        right: 0;
        /* Align to the right */
        transform: translate(50%, -50%);
        /* Offset to place it outside the icon */
        min-width: 1.5rem;
        /* Minimum width for proper display */
        text-align: center;
        /* Center the text */
    }
</style>
<div id="kt_app_header" class="app-header ">
    <!--begin::Header container-->
    <div class="app-container  container-fluid d-flex align-items-stretch justify-content-between "
        id="kt_app_header_container">
        <!--begin::Mobile menu toggle-->
        <div class="d-flex align-items-center d-lg-none ms-n2 me-2" title="Show sidebar menu">
            <div class="btn btn-icon btn-active-color-primary w-35px h-35px" id="kt_app_sidebar_mobile_toggle">
                <i class="fa fa-bars fs-1" style="font-size: 2.5rem !important;"></i>
                {{-- <i class="fa fa-user fs-1"></i> --}}
            </div>
        </div>
        <!--end::Mobile menu toggle-->
        <!--begin::Mobile logo-->
        <div class="d-flex align-items-center flex-grow-1 flex-lg-grow-0">
            <a href="" class="d-lg-none">
                <img alt="Logo" src="{{ asset('assets/logos/intracard.png') }}" class="h-45px" />
            </a>
        </div>
        <!--end::Mobile logo-->
        <!--begin::Header wrapper-->
        <div class="d-flex align-items-stretch justify-content-between flex-lg-grow-1" id="kt_app_header_wrapper">
            <!--begin::Page title-->
            <div data-kt-swapper="true" data-kt-swapper-mode="{default: 'prepend', lg: 'prepend'}"
                data-kt-swapper-parent="{default: '#kt_app_content_container', lg: '#kt_app_header_wrapper'}"
                class="page-title d-flex flex-column justify-content-center flex-wrap me-3 mb-5 mb-lg-0">
                <!--begin::Title-->
                <h1 class="page-heading d-flex text-gray-900 fw-bold fs-3 flex-column justify-content-center my-0">
                    Dashboard
                </h1>
                <!--end::Title-->
                <!--begin::Breadcrumb-->
                <ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 my-0 pt-1">
                    <!--begin::Item-->
                    <li class="breadcrumb-item text-muted">
                        <a href="" class="breadcrumb-link text-muted text-hover-primary">
                            Home
                        </a>
                    </li>
                    <!--end::Item-->
                    <!--begin::Item-->
                    <li class="breadcrumb-item">
                        <span class="bullet bg-gray-300 w-5px h-2px"></span>
                    </li>
                    <!--end::Item-->
                    <!--begin::Item-->
                    <li class="breadcrumb-item text-muted" id="topNavText">
                        {{ isset($dashboard_title) && $dashboard_title ? $dashboard_title : ' My Account' }}
                    </li>
                    <!--end::Item-->
                </ul>
                <!--end::Breadcrumb-->
            </div>

            <div class="app-navbar align-items-center flex-shrink-0">
                <div class="app-sidebar-user d-flex flex-stack">
                    <div class="d-flex">
                        <div class="app-navbar-item ms-2 ms-lg-4" style="margin-right: 1rem;">
                            <a href="#" class="btn btn-icon btn-secondary mr-2 fw-bold position-relative"
                                data-kt-menu-trigger="click" data-kt-menu-attach="parent"
                                data-kt-menu-placement="bottom-end" data-kt-menu-flip="bottom"
                                style="height: calc(1.3em + 1.3rem + 2px); width: calc(1.3em + 1.25rem + 2px);">
                                <i class="fa fa-bell fs-2"></i>
                                <span class="notification-badge position-absolute top-0 start-100 translate-middle"
                                    style="display: none;">0</span>
                            </a>
                            <div class="menu menu-sub menu-sub-dropdown menu-column w-300px w-lg-375px"
                                data-kt-menu="true" id="kt_menu_notifications" style="">

                                <div class="d-flex flex-column bgi-no-repeat rounded-top"
                                    style="background-image:url('')">

                                    <h3 class="text-black fw-semibold px-9 mt-10 mb-6">
                                        Notifications <span class="fs-8 opacity-75 ps-3">0 reports</span>
                                    </h3>

                                    <ul class="nav nav-line-tabs nav-line-tabs-2x nav-stretch fw-semibold px-9"
                                        role="tablist">
                                        <li class="nav-item" role="presentation">
                                            <a class="nav-link text-black opacity-75 opacity-state-100 pb-4 active"
                                                data-bs-toggle="tab" href="#kt_topbar_notifications_1"
                                                aria-selected="false" tabindex="-1" role="tab">
                                                General (0)
                                            </a>
                                        </li>
                                        <li class="nav-item" role="presentation">
                                            <a class="nav-link text-black opacity-75 opacity-state-100 pb-4"
                                                data-bs-toggle="tab" href="#kt_topbar_notifications_2"
                                                aria-selected="true" role="tab">
                                                Payment (0)
                                            </a>
                                        </li>
                                        <li class="nav-item" role="presentation">
                                            <a class="nav-link text-black opacity-75 opacity-state-100 pb-4"
                                                data-bs-toggle="tab" href="#kt_topbar_notifications_3"
                                                aria-selected="false" tabindex="-1" role="tab">
                                                Reminder (0)
                                            </a>
                                        </li>
                                    </ul>

                                </div>
                                <div class="tab-content">
                                    <div class="tab-pane fade show active" id="kt_topbar_notifications_1"
                                        role="tabpanel">
                                        <div class="scroll-y mh-325px my-5 px-8">
                                            <div class="d-flex flex-stack py-4">
                                                <div class="d-flex align-items-center">
                                                    <div class="symbol symbol-35px me-4">
                                                        <span class="symbol-label bg-light-primary">
                                                            <i class="ki-duotone ki-abstract-28 fs-2 text-primary"><span
                                                                    class="path1"></span><span
                                                                    class="path2"></span></i>
                                                        </span>
                                                    </div>
                                                    <div class="mb-0 me-2">
                                                        <a href="#"
                                                            class="fs-6 text-gray-800 text-hover-primary fw-bold">Project
                                                            Alice</a>
                                                        <div class="text-gray-500 fs-7">Phase 1 development</div>
                                                    </div>
                                                </div>
                                                <span class="badge badge-light fs-8">1 hr</span>
                                            </div>
                                            <div class="d-flex flex-stack py-4">
                                                <div class="d-flex align-items-center">
                                                    <div class="symbol symbol-35px me-4">
                                                        <span class="symbol-label bg-light-danger">
                                                            <i class="ki-duotone ki-information fs-2 text-danger"><span
                                                                    class="path1"></span><span
                                                                    class="path2"></span><span
                                                                    class="path3"></span></i>
                                                        </span>
                                                    </div>
                                                    <div class="mb-0 me-2">
                                                        <a href="#"
                                                            class="fs-6 text-gray-800 text-hover-primary fw-bold">HR
                                                            Confidential</a>
                                                        <div class="text-gray-500 fs-7">Confidential staff documents
                                                        </div>
                                                    </div>
                                                </div>
                                                <span class="badge badge-light fs-8">2 hrs</span>
                                            </div>
                                        </div>
                                        <div class="py-3 text-center border-top">
                                            <div class="pagination-container"></div>
                                            <a href="" class="btn btn-color-black-600 btn-active-color-dark">
                                                View All
                                                <i class="fc-icon fc-icon-chevron-right fs-5"><span
                                                        class="path1"></span><span class="path2"></span></i>
                                            </a>
                                        </div>
                                    </div>

                                    <div class="tab-pane fade" id="kt_topbar_notifications_2" role="tabpanel">
                                        <div class="scroll-y mh-325px my-5 px-8">
                                            <div class="d-flex flex-stack py-4">
                                                <div class="d-flex align-items-center">
                                                    <div class="symbol symbol-35px me-4">
                                                        <span class="symbol-label bg-light-primary">
                                                            <i class="ki-duotone ki-abstract-28 fs-2 text-primary"><span
                                                                    class="path1"></span><span
                                                                    class="path2"></span></i>
                                                        </span>
                                                    </div>
                                                    <div class="mb-0 me-2">
                                                        <a href="#"
                                                            class="fs-6 text-gray-800 text-hover-primary fw-bold">Project
                                                            Alice</a>
                                                        <div class="text-gray-500 fs-7">Phase 1 development</div>
                                                    </div>
                                                </div>
                                                <span class="badge badge-light fs-8">1 hr</span>
                                            </div>
                                            <div class="d-flex flex-stack py-4">
                                                <div class="d-flex align-items-center">
                                                    <div class="symbol symbol-35px me-4">
                                                        <span class="symbol-label bg-light-danger">
                                                            <i class="ki-duotone ki-information fs-2 text-danger"><span
                                                                    class="path1"></span><span
                                                                    class="path2"></span><span
                                                                    class="path3"></span></i>
                                                        </span>
                                                    </div>
                                                    <div class="mb-0 me-2">
                                                        <a href="#"
                                                            class="fs-6 text-gray-800 text-hover-primary fw-bold">HR
                                                            Confidential</a>
                                                        <div class="text-gray-500 fs-7">Confidential staff documents
                                                        </div>
                                                    </div>
                                                </div>
                                                <span class="badge badge-light fs-8">2 hrs</span>
                                            </div>
                                        </div>
                                        <div class="py-3 text-center border-top">
                                            <a href="" class="btn btn-color-black-600 btn-active-color-dark">
                                                View All
                                                <i class="fc-icon fc-icon-chevron-right fs-5"><span
                                                        class="path1"></span><span class="path2"></span></i>
                                            </a>
                                        </div>
                                    </div>


                                    <div class="tab-pane fade" id="kt_topbar_notifications_3" role="tabpanel">
                                        <div class="scroll-y mh-325px my-5 px-8">
                                            <div class="d-flex flex-stack py-4">
                                                <div class="d-flex align-items-center">
                                                    <div class="symbol symbol-35px me-4">
                                                        <span class="symbol-label bg-light-primary">
                                                            <i class="ki-duotone ki-abstract-28 fs-2 text-primary"><span
                                                                    class="path1"></span><span
                                                                    class="path2"></span></i>
                                                        </span>
                                                    </div>
                                                    <div class="mb-0 me-2">
                                                        <a href="#"
                                                            class="fs-6 text-gray-800 text-hover-primary fw-bold">Project
                                                            Alice</a>
                                                        <div class="text-gray-500 fs-7">Phase 1 development</div>
                                                    </div>
                                                </div>
                                                <span class="badge badge-light fs-8">1 hr</span>
                                            </div>
                                            <div class="d-flex flex-stack py-4">
                                                <div class="d-flex align-items-center">
                                                    <div class="symbol symbol-35px me-4">
                                                        <span class="symbol-label bg-light-danger">
                                                            <i class="ki-duotone ki-information fs-2 text-danger"><span
                                                                    class="path1"></span><span
                                                                    class="path2"></span><span
                                                                    class="path3"></span></i>
                                                        </span>
                                                    </div>
                                                    <div class="mb-0 me-2">
                                                        <a href="#"
                                                            class="fs-6 text-gray-800 text-hover-primary fw-bold">HR
                                                            Confidential</a>
                                                        <div class="text-gray-500 fs-7">Confidential staff documents
                                                        </div>
                                                    </div>
                                                </div>
                                                <span class="badge badge-light fs-8">2 hrs</span>
                                            </div>
                                        </div>
                                        <div class="py-3 text-center border-top">
                                            <a href="" class="btn btn-color-black-600 btn-active-color-dark">
                                                View All
                                                <i class="fc-icon fc-icon-chevron-right fs-5"><span
                                                        class="path1"></span><span class="path2"></span></i>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="me-5 remove-pp-margin">
                            <div class="symbol symbol-40px cursor-pointer"
                                data-kt-menu-trigger="{default: 'click', lg: 'hover'}"
                                data-kt-menu-placement="bottom-start" data-kt-menu-overflow="true">
                                <img src="https://img.freepik.com/premium-vector/avatar-icon0002_750950-43.jpg?semt=ais_hybrid"
                                    alt="" />
                            </div>
                            <div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-800 menu-state-bg menu-state-color fw-semibold py-4 fs-6 w-275px"
                                data-kt-menu="true">
                                <div class="menu-item px-3">
                                    <div class="menu-content d-flex align-items-center px-3">
                                        <div class="symbol symbol-50px me-5">
                                            <img alt="Logo"
                                                src="https://img.freepik.com/premium-vector/avatar-icon0002_750950-43.jpg?semt=ais_hybrid" />
                                        </div>
                                        <div class="d-flex flex-column">
                                            <div class="fw-bold d-flex align-items-center fs-5">
                                                {{ Auth::user()->first_name ?? '--' }}
                                                {{ Auth::user()->last_name ?? '--' }}
                                                <span class="badge badge-light-success fw-bold fs-8 px-2 py-1 ms-2">
                                                    {{-- Pro --}}
                                                </span>
                                            </div>
                                            <a href="#"
                                                class="fw-semibold text-muted text-hover-primary fs-7">{{ Auth::user()->email ?? '--' }}
                                            </a>
                                        </div>
                                    </div>
                                </div>
                                <div class="separator my-2"></div>
                                <div class="menu-item px-5">
                                    <a href="{{ route('profile') }}" class="menu-link px-5">
                                        My Profile
                                    </a>
                                </div>
                                <div class="menu-item px-5">
                                    <a href="{{ route('password') }}" class="menu-link px-5">
                                        <span class="menu-text">Update Password</span>
                                        <span class="menu-badge">
                                            <!-- <span class="badge badge-light-danger badge-circle fw-bold fs-7">3</span> -->
                                        </span>
                                    </a>
                                </div>
                                <div class="menu-item px-5">
                                    <button type="button" class="menu-link px-5 userLogoutBtn">
                                        Sign Out
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="me-2 desktop-only">
                            <!--begin::Username-->
                            <a href="#"
                                class="app-sidebar-username text-gray-800 text-hover-primary fs-6 fw-semibold lh-0">{{ Auth::user()->first_name ?? '--' }}
                                {{ Auth::user()->last_name ?? '--' }}</a>
                            <!--end::Username-->

                            <!--begin::Description-->
                            <span class="app-sidebar-deckription text-gray-500 fw-semibold d-block fs-8">My
                                Account</span>
                            <!--end::Description-->
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!--end::Navbar-->
    </div>
    <!--end::Header wrapper-->
</div>
<script>
    const fetchNotificationsRoute = "{{ route('notifications.fetch') }}";
    const markAsReadRoute = "{{ route('notifications.mark-read') }}";
    const markAllAsReadRoute = "{{ route('notifications.mark-all-read') }}";
    const archiveRoute = "{{ route('notifications.archive') }}";
    const searchRoute = "{{ route('notifications.search') }}";


    // Fetch notifications from the backend
    async function fetchNotifications() {
        try {
            const response = await fetch(fetchNotificationsRoute);
            const result = await response.json();

            // Extract notifications, unread count, and category counts
            const notifications = result.notifications.data;
            const unreadCount = result.unread_count;
            const categoryCounts = result.category_counts;

            // Update the total unread count in the header and badge
            updateUnreadCount(unreadCount);

            // Update category counts
            updateCategoryCounts(categoryCounts);

            // Clear existing notifications
            document.querySelectorAll(".tab-content .tab-pane").forEach(tab => {
                tab.querySelector(".scroll-y").innerHTML = "";
            });

            // Populate notifications in each category
            notifications.forEach(notification => {
                const container = document.querySelector(
                    `#kt_topbar_notifications_${mapCategoryToTab(notification.category)}`);
                if (container) {
                    const notificationHTML = createNotificationHTML(notification);
                    container.querySelector(".scroll-y").insertAdjacentHTML("beforeend", notificationHTML);
                }
            });

        } catch (error) {
            console.error("Error fetching notifications:", error);
        }
    }

    function updateCategoryCounts(categoryCounts) {
        const generalTab = document.querySelector('[href="#kt_topbar_notifications_1"]');
        const paymentTab = document.querySelector('[href="#kt_topbar_notifications_2"]');
        const reminderTab = document.querySelector('[href="#kt_topbar_notifications_3"]');

        if (generalTab) generalTab.innerHTML = `General (${categoryCounts.general})`;
        if (paymentTab) paymentTab.innerHTML = `Payment (${categoryCounts.payment})`;
        if (reminderTab) reminderTab.innerHTML = `Reminder (${categoryCounts.reminder})`;
    }




    function updateUnreadCount(unreadCount) {
        // Update the header
        const headerCount = document.querySelector('h3 span.fs-8');
        if (headerCount) {
            headerCount.textContent = `${unreadCount} reports`;
        }

        // Update the notification badge
        const badge = document.querySelector('.notification-badge');
        if (badge) {
            badge.textContent = unreadCount;
            badge.style.display = unreadCount > 0 ? 'inline-block' : 'none'; // Hide if no unread notifications
        }
    }


    function updatePagination(paginationData) {
        const paginationContainer = document.querySelector(
            ".pagination-container"); // Ensure you have a pagination container in your HTML
        if (!paginationContainer) return;

        // Clear previous pagination
        paginationContainer.innerHTML = "";

        // Add pagination links
        if (paginationData.prev_page_url) {
            paginationContainer.innerHTML +=
                `<a href="#" onclick="fetchPaginatedNotifications('${paginationData.prev_page_url}')">Previous</a>`;
        }
        if (paginationData.next_page_url) {
            paginationContainer.innerHTML +=
                `<a href="#" onclick="fetchPaginatedNotifications('${paginationData.next_page_url}')">Next</a>`;
        }
    }

    async function fetchPaginatedNotifications(url) {
        try {
            const response = await fetch(url);
            const result = await response.json();

            // Extract and render notifications as before
            const notifications = result.data;
            notifications.forEach(notification => {
                const container = document.querySelector(
                    `#kt_topbar_notifications_${mapCategoryToTab(notification.category)}`);
                if (container) {
                    const notificationHTML = createNotificationHTML(notification);
                    container.querySelector(".scroll-y").insertAdjacentHTML("beforeend", notificationHTML);
                }
            });

            // Update pagination
            updatePagination(result);

        } catch (error) {
            console.error("Error fetching paginated notifications:", error);
        }
    }

    function formatRelativeTime(timestamp) {
        const now = new Date();
        const then = new Date(timestamp);
        const diffInSeconds = Math.floor((now - then) / 1000);

        if (diffInSeconds < 60) return `${diffInSeconds} sec ago`;
        const diffInMinutes = Math.floor(diffInSeconds / 60);
        if (diffInMinutes < 60) return `${diffInMinutes} min ago`;
        const diffInHours = Math.floor(diffInMinutes / 60);
        if (diffInHours < 24) return `${diffInHours} hrs ago`;
        const diffInDays = Math.floor(diffInHours / 24);
        return `${diffInDays} days ago`;
    }


    // Map categories to tab IDs
    function mapCategoryToTab(category) {
        const mapping = {
            general: "1",
            payment: "2",
            reminder: "3",
        };
        return mapping[category] || "1"; // Default to 'General' tab
    }

    // Create HTML for a single notification
    function createNotificationHTML(notification) {
        return `
        <div class="d-flex flex-stack py-4">
            <div class="d-flex align-items-center">
                <div class="symbol symbol-35px me-4">
                    <span class="symbol-label bg-light-primary">
                        <i class="fa fa-bell fs-2 text-primary"></i>
                    </span>
                </div>
                <div class="mb-0 me-2">
                    <a href="#" class="fs-6 text-gray-800 text-hover-primary fw-bold">${notification.title}</a>
                    <div class="text-gray-500 fs-7">${notification.message}</div>
                </div>
            </div>
            <span class="badge badge-light fs-8">${formatRelativeTime(notification.created_at)}</span>
        </div>
    `;
    }

    // Format timestamp to a human-readable string
    function formatTimestamp(timestamp) {
        return new Date(timestamp).toLocaleTimeString([], {
            hour: '2-digit',
            minute: '2-digit'
        });
    }

    // Mark a single notification as read
    async function markAsRead(notificationId) {
        try {
            const response = await fetch(markAsReadRoute, {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute(
                        "content")
                },
                body: JSON.stringify({
                    id: notificationId
                })
            });

            if (response.ok) {
                fetchNotifications(); // Refresh notifications
            }
        } catch (error) {
            console.error("Error marking notification as read:", error);
        }
    }

    // Mark all notifications as read
    async function markAllAsRead() {
        try {
            const response = await fetch(markAllAsReadRoute, {
                method: "POST",
                headers: {
                    "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute(
                        "content")
                }
            });

            if (response.ok) {
                fetchNotifications(); // Refresh notifications
            }
        } catch (error) {
            console.error("Error marking all notifications as read:", error);
        }
    }

    // Archive a notification
    async function archiveNotification(notificationId) {
        try {
            const response = await fetch(archiveRoute, {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute(
                        "content")
                },
                body: JSON.stringify({
                    id: notificationId
                })
            });

            if (response.ok) {
                fetchNotifications(); // Refresh notifications
            }
        } catch (error) {
            console.error("Error archiving notification:", error);
        }
    }

    // Initial fetch
    fetchNotifications();

    window.Echo.private('notifications')
        .listen('NotificationEvent', (event) => {
            fetchNotifications(); // Refresh notifications and update unread count
        });
</script>
