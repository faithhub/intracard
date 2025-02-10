<!-- components/PhoneInput.vue -->
<template>
    <div class="mb-4">
      <label for="phone" class="form-label">Phone Number</label>
      <div class="input-group">
        <span class="input-group-text" id="basic-addon1">
          <img 
            src="https://upload.wikimedia.org/wikipedia/en/c/cf/Flag_of_Canada.svg"
            alt="Canada" 
            style="width: 20px; height: 14px;" 
          />
          +1
        </span>
        <input 
          :value="modelValue"
          type="text"
          class="form-control"
          placeholder="XXX XXX-XXXX"
          @input="handleInput"
          maxlength="12"
        />
      </div>
      <span v-if="error" class="text-danger mt-1">{{ error }}</span>
    </div>
  </template>
  
  <script>
  export default {
    name: 'PhoneInput',
    props: {
      modelValue: String,
      error: String
    },
    emits: ['update:modelValue'],
    methods: {
      handleInput(e) {
        let value = e.target.value.replace(/\D/g, '');
        if (value.length > 10) value = value.slice(0, 10);
        
        // Format: XXX XXX-XXXX
        let formatted = '';
        if (value.length > 0) {
          formatted = value.slice(0, 3);
          if (value.length > 3) {
            formatted += ' ' + value.slice(3, 6);
            if (value.length > 6) {
              formatted += '-' + value.slice(6, 10);
            }
          }
        }
        
        e.target.value = formatted;
        this.$emit('update:modelValue', value);
      }
    }
  }
  </script>