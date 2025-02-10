<!-- <template>
    <div class="loader-overlay">
      <div class="spinner"></div>
    </div>
  </template>
  
  <script>
  export default {
    setup() {
      console.log("Loader.vue is rendered");
    },
  };
  </script>
  
  <style scoped>
  .loader-overlay {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(6, 3, 3, 0.5); /* Semi-transparent black */
    display: flex;
    align-items: center;
    justify-content: center;
    z-index: 9999; /* Ensure it appears on top */
  }
  
  .spinner {
    width: 70px;
    height: 70px;
    border: 5px solid #f3f3f3;
    border-top: 5px solid #210035; /* Blue spinner */
    border-radius: 50%;
    animation: spin 1s linear infinite;
  }
  
  @keyframes spin {
    0% {
      transform: rotate(0deg);
    }
    100% {
      transform: rotate(360deg);
    }
  }
  </style>
   -->

   <template>
    <div :class="[
        'loader-wrapper',
        `loader-${type}`,
        type === 'background' ? 'loader-background' : 'loader-overlay'
    ]">
        <div :class="['spinner', `spinner-${type}`]"></div>
    </div>
</template>
  
<script>
export default {
    props: {
        type: {
            type: String,
            default: 'page',
            validator: (value) => ['page', 'background', 'overlay'].includes(value)
        }
    },

    setup(props) {
        console.log(`Loader rendered with type: ${props.type}`);
    }
};
</script>
  
<style scoped>
/* Base loader styles */
.loader-wrapper {
    display: flex;
    align-items: center;
    justify-content: center;
}

/* Full screen overlay loader */
.loader-overlay {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(6, 3, 3, 0.5);
    z-index: 9999;
}

/* Background loader styles */
.loader-background {
    position: fixed;
    top: 1rem;
    right: 1rem;
    z-index: 9998;
    background: none;
}

/* Spinner base styles */
.spinner {
    border-radius: 50%;
    animation: spin 1s linear infinite;
}

/* Page spinner */
.spinner-page {
    width: 70px;
    height: 70px;
    border: 5px solid #f3f3f3;
    border-top: 5px solid #210035;
}

/* Background spinner */
.spinner-background {
    width: 30px;
    height: 30px;
    border: 3px solid #f3f3f3;
    border-top: 3px solid #210035;
}

/* Overlay spinner */
.spinner-overlay {
    width: 50px;
    height: 50px;
    border: 4px solid #f3f3f3;
    border-top: 4px solid #210035;
}

@keyframes spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
}
</style>