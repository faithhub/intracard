<template>
    <div class="d-flex flex-column flex-root app-root" id="kt_app_root">
      <Header />
      <div class="app-wrapper flex-column flex-row-fluid" id="kt_app_wrapper">
        <Sidebar />
        <div class="app-main flex-column flex-row-fluid" id="kt_app_main">
          <div class="app-content flex-column-fluid">
            <div class="app-container container-fluid">
              <router-view />
            </div>
          </div>
          <Footer />
        </div>
      </div>
    </div>
  </template>

<script>
import Header from "@/components/user/layouts/Header.vue";
import TopNav from "@/components/user/layouts/TopNav.vue";
import Sidebar from "@/components/user/layouts/Sidebar.vue";
import Footer from "@/components/user/layouts/Footer.vue";
import SetupBillModal from "@/components/user/modals/SetupBillModal.vue";
import WalletModal from "@/components/user/modals/WalletModal.vue";

export default {
    components: {
        Header,
        TopNav,
        Sidebar,
        Footer,
        SetupBillModal,
        WalletModal,
    },
};
</script>

<style scoped>
.dashboard-layout {
    display: flex;
    flex-direction: column;
    min-height: 100vh;
    overflow-x: hidden;
}

#toastContainer {
    position: fixed;
    z-index: 1060;
}
</style>
