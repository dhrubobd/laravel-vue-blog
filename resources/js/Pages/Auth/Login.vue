<script setup>
import { useForm, usePage, Link} from "@inertiajs/vue3";
import AppLayout from "../Layouts/AppLayout.vue";
import { computed } from 'vue';
import { useToast } from 'vue-toastification';
const toast = useToast();
const flash = computed(()=>usePage().props.flash);

const form = useForm({
    email: '',
    password: ''
});

const submit = () => {
    form.post('/login',{
        onSuccess: () => {
            flash.value.success && toast.success(flash.value.success);
            flash.value.error && toast.error(flash.value.error);
        }
    });
};
</script>
<template>
    <AppLayout>
        <div class="min-h-screen flex items-center justify-center bg-gray-50">
            <div class="bg-white p-8 rounded shadow-md w-full max-w-md">
                <h2 class="text-2xl font-bold mb-4 text-center">Login to Your Account</h2>

                <form @submit.prevent="submit">
                    <div class="mb-4">
                        <label class="block text-sm font-medium mb-1">Email</label>
                        <input v-model="form.email" type="email"
                            class="w-full p-2 border rounded focus:outline-none focus:ring-primary focus:ring-2"
                            required />
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium mb-1">Password</label>
                        <input v-model="form.password" type="password"
                            class="w-full p-2 border rounded focus:outline-none focus:ring-primary focus:ring-2"
                            required />
                    </div>

                    <button type="submit"
                        class="w-full bg-black text-white py-2 rounded hover:bg-primary/90 transition cursor-pointer">
                        Login
                    </button>
                </form>

                <p class="text-center mt-4 text-sm text-gray-500">
                    Don't have an account?
                    <Link href="/register" class="text-primary hover:underline">Register here</Link>.
                </p>
            </div>
        </div>
    </AppLayout>

</template>
