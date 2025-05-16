<script setup>
import { ref, reactive, onMounted } from "vue";
import { usePage, router } from "@inertiajs/vue3";
import axios from "axios";

const { post } = usePage().props;
const user = usePage().props.auth.user;

const comments = ref([]);
const newComment = ref("");
const parentId = ref(null);

onMounted(() => {

    comments.value = post.comments;

    /*
    window.Echo.channel(`post.${post.id}`).listen(".comment.posted", (event) => {
        comments.value.push(event.comment);
    });
    */
    Echo.channel(`post.${post.id}.comments`)
        .listen("CommentCreated", (event) => {
            if (event.comment.parent_id) {
                const parent = comments.value.find(c => c.id === event.comment.parent_id);
                if (parent) parent.replies.push(event.comment);
            } else {
                comments.value.push(event.comment);
            }
        })
        .listen("CommentDeleted", (event) => {
            // Remove the comment from the list
            comments.value = comments.value.filter(c => c.id !== event.commentId);
            comments.value.forEach(comment => {
                if (comment.replies) {
                    comment.replies = comment.replies.filter(reply => reply.id !== event.commentId);
                }
            });
        });
});

const submitComment = async () => {
    await axios.post(`/posts/${post.id}/comments`, {
        content: newComment.value,
        parent_id: parentId.value
    });

    newComment.value = "";
    parentId.value = null;
    location.reload();
};

// Check if user can delete the comment (post owner or comment owner)
const canDeleteComment = (comment) => {
    return user && (comment.user_id === user.id || post.user_id === user.id);
};

// Delete a comment
const deleteComment = async (commentId) => {
    await axios.delete(`/posts/${post.id}/comments/${commentId}`);
    location.reload();
};

const reply = (id) => {
    parentId.value = id;
};
</script>

<template>
    <div class="mt-4">
        <h3 class="text-2xl font-semibold mb-2">Comments</h3>
        <div v-for="comment in comments" :key="comment.id" class="mb-2">
            <div class="bg-gray-100 p-2 rounded">
                <p><strong>{{ comment.user.username }}</strong>: {{ comment.content }}</p>
                <div class="flex gap-2">
                    <!--
                        <button class="text-primary text-sm cursor-pointer" @click="reply(comment.id)">Reply</button>
                    -->
                    <button v-if="canDeleteComment(comment)" @click="deleteComment(comment.id)"
                        class="text-red-500 text-sm hover:underline cursor-pointer">
                        Delete
                    </button>
                </div>


            </div>
            <div v-if="comment.replies" class="ml-4 mt-2">
                <div v-for="reply in comment.replies" :key="reply.id" class="bg-gray-50 p-2 rounded">
                    <p><strong>{{ reply.user.username }}</strong>: {{ reply.content }}</p>
                </div>
            </div>
        </div>

        <textarea v-model="newComment" class="w-full mt-2 p-2 border rounded" placeholder="Add a comment..."></textarea>
        <button @click="submitComment" class="bg-black text-white px-4 py-2 rounded mt-2 cursor-pointer">Post
            Comment</button>
    </div>
</template>
