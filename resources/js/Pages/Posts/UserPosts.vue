<script setup>
import { ref } from "vue";
import { usePage,Link } from "@inertiajs/vue3";
import AppLayout from "../Layouts/AppLayout.vue";

const posts = ref(usePage().props.posts);

// Format date to a more readable format
const formatDate = (date) => {
    return new Date(date).toLocaleDateString("en-US", {
        year: "numeric",
        month: "long",
        day: "numeric",
    });
};
</script>
<template>
    <AppLayout>
        <div class="max-w-4xl mx-auto mt-8">
            <h1 class="text-3xl font-bold text-center mb-6">My Posts</h1>

            <div v-if="posts.data.length > 0" class="space-y-4">
                <div v-for="post in posts.data" :key="post.id"
                    class="p-4 bg-white rounded-lg shadow-md hover:shadow-lg transition duration-200">
                    <div class="flex items-start justify-between">
                        <div>
                            <h2 class="text-xl font-semibold text-black hover:underline">
                                <Link :href="`/posts/${post.id}`">{{ post.title }}</Link>
                            </h2>
                            <p class="text-sm text-gray-500">
                                {{ formatDate(post.created_at) }}
                            </p>
                        </div>
                        <div class="flex space-x-2">
                            <inertia-link :href="`/posts/${post.id}/edit`"
                                class="px-3 py-1 text-white bg-black rounded hover:bg-blue-500 transition cursor-pointer">
                                Edit
                            </inertia-link>
                            <button @click="deletePost(post.id)"
                                class="px-3 py-1 text-white bg-red-500 rounded hover:bg-red-600 transition cursor-pointer">
                                Delete
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <div v-else class="text-center mt-12 text-gray-500">
                <p>You have not created any posts yet.</p>
            </div>

            <!-- Enhanced Pagination Links -->
            <div class="mt-6 flex justify-center items-center space-x-2">
                <button @click="goToPage(posts.prev_page_url)" :disabled="!posts.prev_page_url"
                    class="px-4 py-2 bg-gray-200 rounded hover:bg-gray-300 disabled:opacity-50 cursor-pointer">
                    Previous
                </button>

                <button v-for="page in pages" :key="page" @click="goToPage(getPageUrl(page))"
                    :class="['px-4 py-2 rounded cursor-pointer', page === posts.current_page ? 'bg-black text-white' : 'bg-gray-200 text-black hover:bg-gray-300']">
                    {{ page }}
                </button>

                <button @click="goToPage(posts.next_page_url)" :disabled="!posts.next_page_url"
                    class="px-4 py-2 bg-gray-200 rounded hover:bg-gray-300 disabled:opacity-50 cursor-pointer">
                    Next
                </button>
            </div>
        </div>
    </AppLayout>
</template>
