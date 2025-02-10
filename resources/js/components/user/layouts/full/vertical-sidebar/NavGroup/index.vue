<script setup>
const props = defineProps({ item: Object });
</script>

<template>
    <v-list-subheader  class="smallCap text-capitalize text-subtitle-1 mt-5 d-flex align-items-center">
        <span class="mini-icon">
            <!-- <DotsIcon size="16" stroke-width="1.5" class="iconClass" /> -->
        </span>
       <span class="mini-text font-weight-semibold pl-2 text-medium-emphasis text-uppercase ">{{ props.item.header}}</span> 
        <template v-slot:append>
          <div class="pa-2">
            <v-btn block>
              Logout
            </v-btn>
          </div>
        </template>
    </v-list-subheader>
    <v-layout>
      <v-navigation-drawer
        class="bg-deep-purple"
        theme="dark"
        permanent
      >
        <v-list color="transparent">
          <v-list-item prepend-icon="mdi-view-dashboard" title="Dashboard"></v-list-item>
          <v-list-item prepend-icon="mdi-account-box" title="Account"></v-list-item>
          <v-list-item prepend-icon="mdi-gavel" title="Admin"></v-list-item>
        </v-list>

        <template v-slot:append>
          <div class="pa-2">
            <v-btn block>
              Logout
            </v-btn>
          </div>
        </template>
      </v-navigation-drawer>
    </v-layout>
</template>
