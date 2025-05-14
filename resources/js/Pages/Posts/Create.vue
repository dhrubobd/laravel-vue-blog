<script setup>
import { useForm, router } from "@inertiajs/vue3";
import { useToast } from 'vue-toastification';
import AppLayout from "../Layouts/AppLayout.vue";


const toast = useToast();

const form = useForm({
    title: '',
    content: '',
    image: null,
    visibility: 'public'
});

// Handle Image Upload
const handleImageUpload = (event) => {
    form.image = event.target.files[0];
};

// Submit Post
const submitPost = () => {
    const formData = new FormData();
    formData.append("title", form.title);
    formData.append("content", form.content);
    formData.append("visibility", form.visibility);
    if (form.image) {
        formData.append("image", form.image);
    }

    router.post("/posts/create", formData, {
        headers: {
            "Content-Type": "multipart/form-data",
        },
        onSuccess: () => {
            toast.success("Post created successfully!");
            form.title = "";
            form.content = "";
            form.image = null;
            form.visibility = "public";
        },
    });
};
</script>

<template>
    <AppLayout>
        <div class="max-w-4xl mx-auto p-6 bg-white rounded-lg shadow-md mt-10">
            <h2 class="text-2xl font-bold mb-6 text-gray-800">Create a New Post</h2>

            <form @submit.prevent="submitPost">
                <!-- Title Input -->
                <div class="mb-4">
                    <label for="title" class="block text-gray-700 font-semibold mb-2">Title</label>
                    <input v-model="form.title" type="text" id="title"
                        class="w-full px-4 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                        placeholder="Enter post title" required>
                </div>

                <!-- Content Input -->
                <div class="mb-4">
                    <label for="content" class="block text-gray-700 font-semibold mb-2">Content</label>
                    <textarea v-model="form.content" id="content"
                        class="w-full px-4 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                        rows="5" placeholder="Write your post here..." required>
        </textarea>
                </div>

                <!-- Image Upload -->
                <div class="mb-4">
                    <label for="image" class="block text-gray-700 font-semibold mb-2">Image (optional)</label>
                    <input type="file" @change="handleImageUpload" class="block w-full text-sm text-gray-500 
                 file:mr-4 file:py-2 file:px-4 
                 file:rounded-full file:border-0 
                 file:text-sm file:font-semibold 
                 file:bg-blue-50 file:text-blue-700 
                 hover:file:bg-blue-100">
                </div>

                <!-- Visibility Options -->
                <div class="mb-4">
                    <label class="block text-gray-700 font-semibold mb-2">Visibility</label>
                    <div class="flex items-center space-x-4">
                        <label class="flex items-center">
                            <input type="radio" value="public" v-model="form.visibility"
                                class="text-blue-500 focus:ring-0" required>
                            <span class="ml-2 text-gray-700">Public</span>
                        </label>
                        <label class="flex items-center">
                            <input type="radio" value="private" v-model="form.visibility"
                                class="text-blue-500 focus:ring-0">
                            <span class="ml-2 text-gray-700">Private</span>
                        </label>
                    </div>
                </div>

                <!-- Submit Button -->
                <div class="flex justify-end mt-6">
                    <button type="submit"
                        class="px-6 py-2 bg-blue-500 text-white font-semibold rounded-md hover:bg-blue-600 transition duration-200 cursor-pointer">
                        Create Post
                    </button>
                </div>
            </form>
        </div>
    </AppLayout>

</template>
