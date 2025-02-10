export default [
    {
        path: "/otp-verify",
        name: "OTPVerify",
        component: () => import("@/components/auth/OTPVerify.vue"),
        meta: { requiresAuth: true }, // Protected route, user must be authenticated
    },
    {
        path: "/dashboard",
        component: () => import("@/components/user/layouts/full/FullLayout.vue"),
        meta: { requiresAuth: true, requiresOtp: true },
        // path: "/",
        // // component: () => import("@/components/user/UserDashboardLayout.vue"),
        // component: () => import("@/components/user/layouts/full/FullLayout.vue"),
        // meta: { requiresAuth: true, requiresOtp: true },
        // redirect: "/dashboard", // Redirect base route to the "dashboard" child route
        children: [
            {
                path: "/dashboard",
                name: "Dashboard",
                component: () => import("@/components/user/views/Dashboard.vue"),
            },
            {
                path: "/profile",
                name: "Profile",
                component: () => import("@/components/user/views/Profile.vue"),
            },
            {
                path: "/team",
                name: "Team",
                component: () => import("@/components/user/views/Team.vue"),
                meta: { requiresTeam: true }, // Mark route as requiring is_team
            },
            {
                path: "/support",
                name: "Support",
                component: () => import("@/components/user/views/Support.vue"),
            },
            {
                path: "/cards",
                name: "My Cards",
                component: () => import("@/components/user/views/Cards.vue"),
            },
            {
                path: '/edit-address',
                name: 'edit-address',
                component: () => import('@/components/EditAddressModal.vue')
            },
            {
                path: "/wallet",
                name: "Wallet",
                component: () => import("@/components/user/views/Wallet.vue"),
            },
            {
                path: "/account",
                name: "Account",
                component: () => import("@/components/user/views/Account.vue"),
            },
            {
                path: "/credit-advisory",
                name: "Credit Advisory",
                component: () => import("@/components/user/views/CreditAdvisory.vue"),
            },
        ],
    }, {
        path: "/",
        redirect: "/dashboard", // Redirect base route to "/dashboard"
    }
];
