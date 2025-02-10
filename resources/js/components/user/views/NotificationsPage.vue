<!-- views/NotificationsPage.vue -->
<template>
    <div class="notifications-page">
      <v-card>
        <v-card-title class="d-flex justify-space-between align-center">
          <span>All Notifications</span>
          <div>
            <v-btn @click="markAllAsRead">Mark All as Read</v-btn>
            <v-btn @click="clearAll">Clear All</v-btn>
          </div>
        </v-card-title>
  
        <v-tabs v-model="activeTab">
          <v-tab value="all">All</v-tab>
          <v-tab value="unread">Unread</v-tab>
          <v-tab v-for="category in categories" 
                 :key="category" 
                 :value="category">
            {{ category }}
          </v-tab>
        </v-tabs>
  
        <v-list>
          <!-- Notifications with infinite scroll -->
          <notification-item
            v-for="notification in filteredNotifications"
            :key="notification.id"
            :notification="notification"
            show-actions
          />
          
          <v-infinite-scroll
            @load="loadMore"
            :loading="loading"
            :has-more="hasMore"
          />
        </v-list>
      </v-card>
    </div>
  </template>