import { createApp } from "vue";
import ChatComponent from './components/ChatComponent.vue'; // Adjust the path based on your file structure

const app = createApp({});
app.component("chat-component", ChatComponent);
app.mount("#app");