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


//const comments = ref([]);
//const newComment = ref("");

// Bookmark and Like State
const toggleBookmark = () => {
    if (usePage().props.auth.user == null) {
        toast.error("You need to login to bookmark.");
        return;
    }

    router.post(`/posts/${post.id}/bookmark`);
};

const toggleLike = async () => {
    if (usePage().props.auth.user == null) {
        toast.error("You need to login to like.");
        return;
    }

    await axios.post(`/posts/${post.id}/like`);
    location.reload();
};

/*
// Comment Submission

const submitComment = () => {
    if (!newComment.value.trim()) {
        toast.error("Comment cannot be empty.");
        return;
    }

    router.post(`/posts/${post.id}/comments`, { content: newComment.value, parent_id: null }, {
        onSuccess: () => {
            setTimeout(() => {
                console.log("Success");
                newComment.value = "";
                comments.value = post.comments;
                toast.success(flash.value.success);
            }, 1000);
        },
        onError: () => {
            //toast.error(flash.value.error);
            console.log("Error");
        }
    });
};


onMounted(() => {
    comments.value = post.comments;

    window.Echo.channel(`post.${post.id}`).listen(".comment.posted", (event) => {
        comments.value.push(event.comment);
    });
});
*/
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
                        <span v-if="isBookmarked">🔖 Bookmarked</span>
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
            <!-- Comments Section
            <div class="mt-8">
                <h2 class="text-2xl font-bold mb-4">Comments</h2>

                <div v-if="usePage().props.auth.user != null">
                    <textarea v-model="newComment" class="w-full p-2 border rounded mb-2"
                        placeholder="Add a comment..."></textarea>
                    <button @click="submitComment"
                        class="bg-black text-white px-4 py-2 rounded cursor-pointer rounded">Post
                        Comment</button>
                </div>
                <div v-else class="flex items-center text-sm text-gray-500 mb-2">
                    <p class="text-gray-500">Login to comment on this post.</p>
                    <Link href="/login" class="text-white bg-black hover:underline px-5 py-2 mx-2 clear-both rounded">
                    Login
                    here</Link>
                </div>

                <div class="mt-6 space-y-4">
                    <div v-for="comment in comments" :key="comment.id" class="p-4 border rounded">
                        <div class="flex items-center text-sm text-gray-500 mb-2">
                            <span class="font-semibold">{{ comment.user.username }}</span>
                            <span class="mx-2">•</span>
                            <span>{{ new Date(comment.created_at).toLocaleDateString() }}</span>
                        </div>
                        <p class="text-gray-800">{{ comment.content }}</p>
                    </div>
                </div>
            </div>
            -->
        </div>
    </AppLayout>
</template>