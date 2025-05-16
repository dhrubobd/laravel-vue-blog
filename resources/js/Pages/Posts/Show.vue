<script setup>
import { usePage, router, Link } from "@inertiajs/vue3";
const { post, isBookmarked, isLiked } = usePage().props;
import { ref, computed, onMounted } from "vue";
import { useToast } from 'vue-toastification';
import AppLayout from "../Layouts/AppLayout.vue";
import Comments from "@/Components/Comments.vue";
import axios from "axios";

const flash = computed(() => usePage().props.flash);

const toast = useToast();

// Bookmark and Like State
const toggleBookmark = async () => {
    if (usePage().props.auth.user == null) {
        toast.error("You need to login to bookmark.");
        return;
    }

    await axios.post(`/posts/${post.id}/bookmark`);
    location.reload();
};

const toggleLike = async () => {
    if (usePage().props.auth.user == null) {
        toast.error("You need to login to like.");
        return;
    }

    await axios.post(`/posts/${post.id}/like`);
    location.reload();
};
</script>

<template>
    <AppLayout>
        <div class="max-w-4xl mx-auto p-6 bg-white rounded-lg shadow-md mt-10">
            <div class="mb-6">
                <h1 class="text-3xl font-bold mb-2">{{ post.title }}</h1>
                <div class="flex items-center text-gray-500 mb-2">
                    <img :src="post.user.profile_pic ? `/${post.user.profile_pic}` : 'https://placehold.co/100x100?text=Author'"
                        class="w-10 h-10 rounded-full mr-2" />
                    <span class="font-semibold">{{ post.user.username }}</span>
                    <span class="mx-2">•</span>
                    <span>{{ new Date(post.created_at).toLocaleDateString() }}</span>
                </div>
                <div v-if="post.image">
                    <img :src="`/${post.image}`" class="w-full h-auto rounded mb-4" />
                </div>
                <div v-else>
                    <img :src="`https://placehold.co/400x400?text=Image`" alt="">
                </div>
                <p class="text-lg text-gray-800 mb-4">{{ post.content }}</p>

                <!-- Tags -->
                <div class="flex flex-wrap mb-4">
                    <span v-for="tag in post.tags" :key="tag.id"
                        class="px-2 py-1 text-sm bg-gray-200 rounded mr-2 mb-2">
                        #{{ tag.name }}
                    </span>
                </div>

                <!-- Bookmark and Like Buttons -->
                <div class="flex items-center space-x-4 mb-6">
                    <button @click="toggleBookmark"
                        class="flex items-center text-primary hover:underline cursor-pointer">
                        <span v-if="isBookmarked" class="text-green-400">🔖 Bookmarked</span>
                        <span v-else>🔖 Bookmark</span>
                    </button>

                    <button @click="toggleLike" class="flex items-center text-primary hover:underline cursor-pointer">
                        <span v-if="isLiked">❤️ Liked</span>
                        <span v-else>🤍 Like</span>
                        <span class="ml-1">({{ post.likes.length }})</span>
                    </button>
                </div>
            </div>
            <Comments :post="post" />
        </div>
    </AppLayout>
</template>