// resources/js/components/BillingTabs.vue
<template>
  <div>
    <ul class="nav nav-stretch nav-line-tabs border-transparent" role="tablist">
      <li class="nav-item" role="presentation">
        <a
          class="nav-link fs-5 fw-bold me-5"
          :class="{ active: selectedTab === 'credit' }"
          @click="selectTab('credit')"
        >
          Credit
        </a>
      </li>
      <li class="nav-item" role="presentation">
        <a
          class="nav-link fs-5 fw-bold me-5"
          :class="{ active: selectedTab === 'debit' }"
          @click="selectTab('debit')"
        >
          Debit Card
        </a>
      </li>
    </ul>
    <div v-if="selectedTab === 'credit'">
      <h3>My Credit Cards</h3>
      <!-- Credit Card Content -->
      <slot name="credit"></slot>
    </div>
    <div v-if="selectedTab === 'debit'">
      <h3>My Debit Cards</h3>
      <!-- Debit Card Content -->
      <slot name="debit"></slot>
    </div>
  </div>
</template>

<script>
export default {
  data() {
    return {
      selectedTab: "credit", // Default tab
    };
  },
  methods: {
    selectTab(tab) {
      this.selectedTab = tab;
    },
  },
  mounted() {
  // window.Echo.channel("billing-updates").listen("BillingUpdated", (event) => {
  //   console.log("Billing updated:", event);
  //   // Handle real-time updates (e.g., refresh card list)
  // });
}

};
</script>

<style scoped>
.nav-link.active {
  color: #007bff;
  font-weight: bold;
}
</style>
