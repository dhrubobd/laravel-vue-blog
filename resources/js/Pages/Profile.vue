<script setup>
import { reactive, ref, onMounted } from "vue";
import { usePage, router } from "@inertiajs/vue3";
import { useToast } from 'vue-toastification';
import AppLayout from "./Layouts/AppLayout.vue";

const toast = useToast();

const { user } = usePage().props;
//console.log(user);

const authorPhoto = ref(null);

const form = reactive({
    username: user.username,
    current_password: "",
    new_password: "",
    new_password_confirmation: ""
});

// Loading States
const loading = reactive({
    picture: false,
    username: false,
    password: false
});

// Handle Profile Picture Upload
const handleProfilePicUpload = (event) => {
    const file = event.target.files[0];
    if (file) {
        const formData = new FormData();
        formData.append("profile_pic", file);
        loading.picture = true;

        router.post("/profile/update-picture", formData, {
            headers: {
                "Content-Type": "multipart/form-data",
            },
            onFinish: () => (loading.picture = false),
            onSuccess: () => {
                toast.success("Profile picture updated successfully!");
                authorPhoto.value = URL.createObjectURL(file);
            }
        });
    }
};

// Update Username
const updateUsername = () => {
    if (!form.username.trim()) {
        showToast("You Name cannot be empty.", "error");
        return;
    }

    loading.username = true;
    router.post("/profile/update-username", { username: form.username }, {
        onFinish: () => (loading.username = false),
        onSuccess: () => {
            toast.success("Your Name updated successfully!");
        }
    });
};

// Update Password
const updatePassword = () => {
    if (!form.current_password || !form.new_password || !form.new_password_confirmation) {
        toast.error("Please fill out all password fields.");
        return;
    }

    if (form.new_password != form.new_password_confirmation) {
        toast.error("New Password is not Confirmed.");
        return;
    }

    loading.password = true;
    router.post("/profile/update-password", {
        current_password: form.current_password,
        new_password: form.new_password,
        new_password_confirmation: form.new_password_confirmation
    }, {
        onFinish: () => (loading.password = false),
        onSuccess: () => {
            toast.success("Password updated successfully!");
            form.current_password = "";
            form.new_password = "";
            form.new_password_confirmation = "";
        },
        onError: () => {
            toast.error("Password update Failed!");
        }
    });
};

onMounted(() => {
    if(user){
        //console.log(user);
        authorPhoto.value = `/${user.profile_pic}`;
    }

});
</script>

<template>
    <AppLayout>
        <div class="max-w-3xl mx-auto p-6 mt-10 bg-white rounded-lg shadow-md">
            <h2 class="text-2xl font-bold text-gray-800 mb-6">Your Profile</h2>

            <!-- Profile Picture Section -->
            <div class="flex items-center space-x-4 mb-6">
                <img :src="authorPhoto ? authorPhoto : 'https://placehold.co/100x100?text=Photo'"
                    alt="Profile Picture" class="w-24 h-24 rounded-full object-cover border">

                <div>
                    <label class="block text-gray-700 font-semibold mb-2">Change Profile Picture</label>
                    <input type="file" @change="handleProfilePicUpload" class="block text-sm text-gray-500 
                 file:mr-4 file:py-2 file:px-4 
                 file:rounded-full file:border-0 
                 file:text-sm file:font-semibold 
                 file:bg-blue-50 file:text-black
                 hover:file:bg-blue-100">
                    <p v-if="loading.picture" class="text-blue-500 mt-2">Updating profile picture...</p>
                </div>
            </div>

            <!-- Username Update Section -->
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Update Your Name</h3>
            <div class="mb-6">
                <input v-model="form.username" type="text" placeholder="Enter new username"
                    class="w-full px-4 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                <button @click="updateUsername" :disabled="loading.username"
                    class="mt-2 px-4 py-2 bg-black text-white rounded-md hover:bg-blue-300 transition cursor-pointer">
                    <span v-if="loading.username">Updating...</span>
                    <span v-else>Update Your Name</span>
                </button>
            </div>

            <!-- Password Update Section -->
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Update Password</h3>
            <div class="space-y-4 mb-6">
                <input v-model="form.current_password" type="password" placeholder="Current Password"
                    class="w-full px-4 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                <input v-model="form.new_password" type="password" placeholder="New Password"
                    class="w-full px-4 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                <input v-model="form.new_password_confirmation" type="password" placeholder="Confirm New Password"
                    class="w-full px-4 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                <button @click="updatePassword" :disabled="loading.password"
                    class="mt-2 px-4 py-2 bg-black text-white rounded-md hover:bg-blue-300 transition cursor-pointer">
                    <span v-if="loading.password">Updating...</span>
                    <span v-else>Update Password</span>
                </button>
            </div>
        </div>
    </AppLayout>

</template>
