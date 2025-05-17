<script setup>
import { ref, computed } from "vue";
import { usePage,Link, router } from "@inertiajs/vue3";
import AppLayout from "../Layouts/AppLayout.vue";
import { useToast } from 'vue-toastification';
const toast = useToast();

const flash = computed(() => usePage().props.flash);

const props = defineProps({
    posts: Object
});
//const {posts} = usePage().props;

//console.log(posts);
// Format date to a more readable format
const formatDate = (date) => {
    return new Date(date).toLocaleDateString("en-US", {
        year: "numeric",
        month: "long",
        day: "numeric",
    });
};

// Pagination Handler
const goToPage = (url) => {
    if (url) {
        router.get(url);
    }
};

// Generate URL for Page Number
const getPageUrl = (page) => {
    const baseUrl = posts.path;
    return `${baseUrl}?page=${page}`;
};

// Delete a post
const deletePost = (postId) => {
  if (confirm("Are you sure you want to delete this post?")) {
    router.delete(`/posts/${postId}`, {
      onSuccess: () => {
        if (flash.value.success != null) {
            toast.success(flash.value.success);
        }
        if (flash.value.error != null) {
            toast.error(flash.value.error);
        }
      }
    });
  }
};
</script>
<template>
    <AppLayout>
        <div class="max-w-4xl mx-auto mt-8">
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-3xl font-bold">My Posts</h2>
                <div>
                    <Link href="/posts/create" class="bg-black text-white px-4 py-2 rounded cursor-pointer">Create Post
                    </Link>
                </div>
            </div>

            <div v-if="posts.data.length > 0" class="space-y-4">
                <div v-for="post in posts.data" :key="post.id"
                    class="p-4 bg-white rounded-lg shadow-md hover:shadow-lg transition duration-200">
                    <div class="flex items-start justify-between">
                        <div>
                            <h2 class="text-xl font-semibold text-black hover:underline">
                                <Link :href="`/posts/${post.id}/show`">{{ post.title }}</Link>
                            </h2>
                            <p class="text-sm text-gray-500">
                                {{ formatDate(post.created_at) }}
                            </p>
                        </div>
                        <div class="flex space-x-2">
                            <Link :href="`/posts/${post.id}/edit`"
                                class="px-3 py-1 text-white bg-black rounded hover:bg-blue-500 transition cursor-pointer">
                                Edit
                            </Link>
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

                <button @click="goToPage(posts.next_page_url)" :disabled="!posts.next_page_url"
                    class="px-4 py-2 bg-gray-200 rounded hover:bg-gray-300 disabled:opacity-50 cursor-pointer">
                    Next
                </button>
            </div>
        </div>
    </AppLayout>
</template>
