<script setup>
import { usePage, Link, router } from "@inertiajs/vue3";
import AppLayout from "../Layouts/AppLayout.vue";
import { computed } from "vue";

const { posts } = usePage().props;

// Calculate Pagination Pages (Dynamic)
const pages = computed(() => {
  const total = posts.last_page;
  const current = posts.current_page;
  const pageNumbers = [];

  // Show 5 pages max (2 before and 2 after the current page)
  for (let i = Math.max(1, current - 2); i <= Math.min(total, current + 2); i++) {
    pageNumbers.push(i);
  }

  return pageNumbers;
});

// Pagination Handler
const goToPage = (url) => {
  if (url) {
    router.get(url);
  }
  //console.log(posts);
};

// Generate URL for Page Number
const getPageUrl = (page) => {
  const baseUrl = posts.path;
  return `${baseUrl}?page=${page}`;
};


</script>
<template>
    <AppLayout>
        <div class="max-w-4xl mx-auto mt-8">
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-3xl font-bold">Recent Posts</h2>
                <div v-if="usePage().props.auth.user != null">
                    <Link href="/posts/create" class="bg-black text-white px-4 py-2 rounded cursor-pointer">Create Post</Link>
                </div>
                <div v-else>
                    <button class="bg-black text-white px-4 py-2 rounded cursor-pointer">Login to create a
                        post</button>
                </div>
            </div>

            <div v-if="posts.data.length">
                <div v-for="post in posts.data" :key="post.id" class="mb-4 p-4 border rounded-lg">
                    <h3 class="text-xl font-semibold">{{ post.title }}</h3>
                    <p class="text-gray-600">by {{ post.user.username }}</p>
                    <p class="mt-2 text-gray-800">{{ post.content.substring(0, 150) }}...</p>
                    <Link :href="`/posts/${post.id}`" class="bg-black text-white py-2 px-5 m-2 float-end block hover:underline rounded">Read more</Link>
                    <div class="clear-both"></div>
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

            <div v-else>
                <p class="text-gray-500">No posts available.</p>
            </div>
        </div>
    </AppLayout>
</template>
