@extends('admin.app-admin')

@section('styles')
<link href="https://cdn.jsdelivr.net/npm/vuetify@2.x/dist/vuetify.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/@mdi/font@5.x/css/materialdesignicons.min.css" rel="stylesheet">
<style>
    .profile-card {
        border-radius: 12px;
        overflow: hidden;
        transition: all 0.3s;
    }
    .profile-card:hover {
        box-shadow: 0 8px 16px rgba(0, 0, 0, 0.1);
        transform: translateY(-5px);
    }
    .avatar-container {
        width: 120px;
        height: 120px;
        margin: 0 auto;
        position: relative;
    }
    .v-application--wrap {
        min-height: unset !important;
    }
    .fade-enter-active, .fade-leave-active {
        transition: opacity .5s;
    }
    .fade-enter, .fade-leave-to {
        opacity: 0;
    }
    .card {
        border: none;
        border-radius: 12px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    }
    .dashboard-title {
        font-size: 24px;
        font-weight: 600;
        color: #333;
    }
    /* Custom green color style */
    .custom-green {
        background-color: #4caf50 !important;
        border-color: #4caf50 !important;
    }
    .image-preview-enter-active, .image-preview-leave-active {
        transition: opacity 0.3s;
    }
    .image-preview-enter, .image-preview-leave-to {
        opacity: 0;
    }
    .profile-avatar-wrapper {
        border: 3px solid #4caf50;
        box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        overflow: hidden;
    }
</style>
@endsection

@section('content')
<div class="d-flex flex-column flex-column-fluid">
    <!-- Card content -->
    <div class="app-container">
        <div class="card">
            <div class="card-body p-4">
                <div class="welcome-section p-4 bg-light rounded mb-5">
                    <h2 class="fw-bold mb-0">My Profile</h2>
                </div>
                
                <div id="profile-app">
                    <v-app>
                        <v-container class="pa-0">
                            <v-row>
                                <!-- Profile Update Card -->
                                <v-col cols="12" md="6">
                                    <v-card class="profile-card elevation-3" height="100%">
                                        <v-card-title class="custom-green white--text">
                                            <v-icon class="mr-2 white--text">mdi-account-edit</v-icon>
                                            Update Profile
                                        </v-card-title>
                                        
                                        <v-card-text class="pt-5">
                                            <div class="avatar-container mb-6">
                                                <v-avatar size="120" color="grey lighten-4" class="profile-avatar-wrapper">
                                                    <template v-if="profileImage">
                                                        <v-img :src="profileImage" alt="Profile Picture"></v-img>
                                                    </template>
                                                    <template v-else>
                                                        <v-img :src="originalProfileImage" alt="Profile Picture"></v-img>
                                                    </template>
                                                </v-avatar>
                                                <v-btn
                                                    color="custom-green"
                                                    fab
                                                    x-small
                                                    class="elevation-2"
                                                    style="position: absolute; bottom: 5px; right: 15px;"
                                                    @click="$refs.profileImageInput.click()"
                                                >
                                                    <v-icon>mdi-camera</v-icon>
                                                </v-btn>
                                                <input
                                                    ref="profileImageInput"
                                                    type="file"
                                                    accept="image/*"
                                                    style="display: none"
                                                    @change="onProfileImageChange"
                                                />
                                            </div>
                                            
                                            <v-form ref="profileForm" v-model="profileFormValid" lazy-validation @submit.prevent="updateProfile">
                                                <v-row>
                                                    <v-col cols="12" sm="6">
                                                        <v-text-field
                                                            v-model="profile.first_name"
                                                            label="First Name"
                                                            prepend-icon="mdi-account"
                                                            :rules="nameRules"
                                                            required
                                                        ></v-text-field>
                                                    </v-col>
                                                    
                                                    <v-col cols="12" sm="6">
                                                        <v-text-field
                                                            v-model="profile.last_name"
                                                            label="Last Name"
                                                            prepend-icon="mdi-account"
                                                            :rules="nameRules"
                                                            required
                                                        ></v-text-field>
                                                    </v-col>
                                                </v-row>
                                                
                                                <v-text-field
                                                    v-model="profile.email"
                                                    label="Email"
                                                    prepend-icon="mdi-email"
                                                    :rules="emailRules"
                                                    required
                                                    disabled
                                                ></v-text-field>
                                                
                                                <v-text-field
                                                    v-model="profile.phone"
                                                    label="Phone Number"
                                                    prepend-icon="mdi-phone"
                                                    :rules="phoneRules"
                                                ></v-text-field>
                                                
                                                <v-btn
                                                    color="custom-green"
                                                    class="mt-4"
                                                    block
                                                    :loading="profileLoading"
                                                    :disabled="!profileFormValid"
                                                    type="submit"
                                                >
                                                    Update Profile
                                                </v-btn>
                                            </v-form>
                                        </v-card-text>
                                    </v-card>
                                </v-col>
                                
                                <!-- Change Password Card -->
                                <v-col cols="12" md="6">
                                    <v-card class="profile-card elevation-3" height="100%">
                                        <v-card-title class="purple white--text">
                                            <v-icon class="mr-2 white--text">mdi-lock-reset</v-icon>
                                            Change Password
                                        </v-card-title>
                                        
                                        <v-card-text class="pt-5">
                                            <v-form ref="passwordForm" v-model="passwordFormValid" lazy-validation @submit.prevent="updatePassword">
                                                <v-text-field
                                                    v-model="password.current"
                                                    label="Current Password"
                                                    prepend-icon="mdi-lock"
                                                    :append-icon="showCurrentPassword ? 'mdi-eye' : 'mdi-eye-off'"
                                                    :type="showCurrentPassword ? 'text' : 'password'"
                                                    @click:append="showCurrentPassword = !showCurrentPassword"
                                                    :rules="[v => !!v || 'Current password is required']"
                                                    required
                                                ></v-text-field>
                                                
                                                <v-text-field
                                                    v-model="password.new"
                                                    label="New Password"
                                                    prepend-icon="mdi-lock-outline"
                                                    :append-icon="showNewPassword ? 'mdi-eye' : 'mdi-eye-off'"
                                                    :type="showNewPassword ? 'text' : 'password'"
                                                    @click:append="showNewPassword = !showNewPassword"
                                                    :rules="passwordRules"
                                                    counter
                                                    required
                                                ></v-text-field>
                                                
                                                <v-text-field
                                                    v-model="password.confirm"
                                                    label="Confirm New Password"
                                                    prepend-icon="mdi-lock-check"
                                                    :append-icon="showConfirmPassword ? 'mdi-eye' : 'mdi-eye-off'"
                                                    :type="showConfirmPassword ? 'text' : 'password'"
                                                    @click:append="showConfirmPassword = !showConfirmPassword"
                                                    :rules="[
                                                        v => !!v || 'Please confirm your password',
                                                        v => v === password.new || 'Passwords do not match'
                                                    ]"
                                                    required
                                                ></v-text-field>
                                                
                                                <v-divider class="my-5"></v-divider>
                                                
                                                <div class="password-requirements mb-4">
                                                    <div class="text-subtitle-2 mb-2">Password Requirements:</div>
                                                    <v-row>
                                                        <v-col cols="12" sm="6">
                                                            <div class="d-flex align-center">
                                                                <v-icon :color="password.new && password.new.length >= 8 ? 'success' : 'grey'">
                                                                    mdi-check-circle
                                                                </v-icon>
                                                                <span class="ml-2">Minimum 8 characters</span>
                                                            </div>
                                                        </v-col>
                                                        <v-col cols="12" sm="6">
                                                            <div class="d-flex align-center">
                                                                <v-icon :color="password.new && /[A-Z]/.test(password.new) ? 'success' : 'grey'">
                                                                    mdi-check-circle
                                                                </v-icon>
                                                                <span class="ml-2">At least 1 uppercase letter</span>
                                                            </div>
                                                        </v-col>
                                                        <v-col cols="12" sm="6">
                                                            <div class="d-flex align-center">
                                                                <v-icon :color="password.new && /[a-z]/.test(password.new) ? 'success' : 'grey'">
                                                                    mdi-check-circle
                                                                </v-icon>
                                                                <span class="ml-2">At least 1 lowercase letter</span>
                                                            </div>
                                                        </v-col>
                                                        <v-col cols="12" sm="6">
                                                            <div class="d-flex align-center">
                                                                <v-icon :color="password.new && /[0-9]/.test(password.new) ? 'success' : 'grey'">
                                                                    mdi-check-circle
                                                                </v-icon>
                                                                <span class="ml-2">At least 1 number</span>
                                                            </div>
                                                        </v-col>
                                                    </v-row>
                                                </div>
                                                
                                                <v-btn
                                                    color="purple"
                                                    class="mt-4"
                                                    block
                                                    :loading="passwordLoading"
                                                    :disabled="!passwordFormValid"
                                                    type="submit"
                                                >
                                                    Change Password
                                                </v-btn>
                                            </v-form>
                                        </v-card-text>
                                    </v-card>
                                </v-col>
                            </v-row>
                        </v-container>
                    </v-app>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/vue@2.x/dist/vue.js"></script>
<script src="https://cdn.jsdelivr.net/npm/vuetify@2.x/dist/vuetify.js"></script>
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>

<script>
    new Vue({
        el: '#profile-app',
        vuetify: new Vuetify({
            theme: {
                themes: {
                    light: {
                        'custom-green': '#4caf50'
                    }
                }
            }
        }),
        data() {
            return {
                // Profile form data
                profile: {
                    first_name: "{{ Auth::guard('admin')->user()->first_name }}",
                    last_name: "{{ Auth::guard('admin')->user()->last_name }}",
                    email: "{{ Auth::guard('admin')->user()->email }}",
                    phone: "{{ Auth::guard('admin')->user()->phone }}",
                },
                originalProfileImage: "{{ Auth::guard('admin')->user()->profile_picture ? Storage::url(Auth::guard('admin')->user()->profile_picture) : asset('assets/images/default-avatar.png') }}",
                profileFormValid: true,
                profileLoading: false,
                profileImage: null,
                profileImageFile: null,
                
                // Password form data
                password: {
                    current: '',
                    new: '',
                    confirm: ''
                },
                showCurrentPassword: false,
                showNewPassword: false,
                showConfirmPassword: false,
                passwordFormValid: true,
                passwordLoading: false,
                
                // Validation rules
                nameRules: [
                    v => !!v || 'Name is required',
                    v => (v && v.length <= 50) || 'Name must be less than 50 characters',
                    v => /^[a-zA-Z\s]*$/.test(v) || 'Name must contain only letters'
                ],
                emailRules: [
                    v => !!v || 'Email is required',
                    v => /.+@.+\..+/.test(v) || 'Email must be valid'
                ],
                phoneRules: [
                    v => !v || /^[0-9+\-\s()]*$/.test(v) || 'Phone number must be valid',
                    v => !v || (v.length >= 10 && v.length <= 15) || 'Phone number must be between 10 and 15 characters'
                ],
                passwordRules: [
                    v => !!v || 'Password is required',
                    v => (v && v.length >= 8) || 'Password must be at least 8 characters',
                    v => /[A-Z]/.test(v) || 'Password must contain at least 1 uppercase letter',
                    v => /[a-z]/.test(v) || 'Password must contain at least 1 lowercase letter',
                    v => /[0-9]/.test(v) || 'Password must contain at least 1 number'
                ]
            }
        },
        methods: {
            onProfileImageChange(e) {
                const file = e.target.files[0];
                if (!file) return;
                
                // Validate file type
                const validTypes = ['image/jpeg', 'image/png', 'image/jpg', 'image/gif'];
                if (!validTypes.includes(file.type)) {
                    window.showToast('error', 'Please upload a valid image file (JPEG, PNG, GIF)');
                    return;
                }
                
                // Validate file size (max 2MB)
                if (file.size > 2 * 1024 * 1024) {
                    window.showToast('error', 'Image size should not exceed 2MB');
                    return;
                }
                
                this.profileImageFile = file;
                
                // Create preview
                const reader = new FileReader();
                reader.onload = e => {
                    this.profileImage = e.target.result;
                };
                reader.readAsDataURL(file);
            },
            
            async updateProfile() {
                if (!this.$refs.profileForm.validate()) return;
                
                this.profileLoading = true;
                
                try {
                    const formData = new FormData();
                    formData.append('first_name', this.profile.first_name);
                    formData.append('last_name', this.profile.last_name);
                    formData.append('phone', this.profile.phone);
                    
                    if (this.profileImageFile) {
                        formData.append('profile_picture', this.profileImageFile);
                    }
                    
                    formData.append('_method', 'PUT');
                    
                    // Send update request to the server
                    const response = await axios.post('{{ route("admin.profile.update") }}', formData, {
                        headers: {
                            'Content-Type': 'multipart/form-data',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        }
                    });
                    
                    if (response.data.success) {
                        window.showToast('success', response.data.message || 'Profile updated successfully');
                        
                        // Update the original profile image if a new one was uploaded
                        if (response.data.data && response.data.data.user && response.data.data.user.profile_picture) {
                            this.originalProfileImage = response.data.data.user.profile_picture;
                            
                            // Update profile image in header and sidebar if present
                            const headerProfileImg = document.querySelector('.header-profile-img');
                            const sidebarProfileImg = document.querySelector('.sidebar-profile-img');
                            
                            if (headerProfileImg) {
                                headerProfileImg.src = this.originalProfileImage;
                            }
                            
                            if (sidebarProfileImg) {
                                sidebarProfileImg.src = this.originalProfileImage;
                            }
                        }
                        
                        // Reset the temp profile image
                        this.profileImage = null;
                        this.profileImageFile = null;
                        
                        // Update user name in header if present
                        const headerUserName = document.querySelector('.header-user-name');
                        if (headerUserName) {
                            headerUserName.textContent = `${this.profile.first_name} ${this.profile.last_name}`;
                        }
                    } else {
                        throw new Error(response.data.message || 'Failed to update profile');
                    }
                } catch (error) {
                    window.showToast('error', error.response?.data?.message || error.message || 'An error occurred');
                    console.error('Profile update error:', error);
                } finally {
                    this.profileLoading = false;
                }
            },
            
            async updatePassword() {
                if (!this.$refs.passwordForm.validate()) return;
                
                this.passwordLoading = true;
                
                try {
                    // Send password update request to the server
                    const response = await axios.post('{{ route("admin.password.update") }}', {
                        current_password: this.password.current,
                        password: this.password.new,
                        password_confirmation: this.password.confirm,
                        _method: 'PUT'
                    }, {
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        }
                    });
                    
                    if (response.data.success) {
                        window.showToast('success', response.data.message || 'Password changed successfully');
                        
                        // Reset form
                        this.password.current = '';
                        this.password.new = '';
                        this.password.confirm = '';
                        this.$refs.passwordForm.resetValidation();
                    } else {
                        throw new Error(response.data.message || 'Failed to change password');
                    }
                } catch (error) {
                    window.showToast('error', error.response?.data?.message || error.message || 'An error occurred');
                    console.error('Password update error:', error);
                } finally {
                    this.passwordLoading = false;
                }
            }
        }
    });
</script>
@endsection