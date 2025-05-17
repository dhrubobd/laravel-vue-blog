<script setup>
import { ref, computed } from "vue";
import { usePage, router, Link } from "@inertiajs/vue3";
import AppLayout from "./Layouts/AppLayout.vue";
import { useToast } from 'vue-toastification';
const toast = useToast();

const flash = computed(() => usePage().props.flash);

//const bookmarks = usePage().props.bookmarks;
const props = defineProps({
  bookmarks : Array
});

// Format date to a more readable format
const formatDate = (date) => {
  return new Date(date).toLocaleDateString("en-US", {
    year: "numeric",
    month: "long",
    day: "numeric",
  });
};

// Remove a bookmark
const removeBookmark = (bookmarkId) => {
  router.delete(`/bookmarks/${bookmarkId}`,
    {
      onSuccess: () => {
        if (flash.value.success != null) {
          toast.success(flash.value.success);
        }
        if (flash.value.error != null) {
          toast.error(flash.value.error);
        }
      }
    }
  );
};

// Change page
const changePage = (url) => {
  if (url) {
    router.get(url);
  }
};
</script>

<template>
  <AppLayout>
    <div class="max-w-4xl mx-auto mt-8">
      <h1 class="text-3xl font-bold text-center mb-6">Bookmarked Blog Posts</h1>

      <div v-if="bookmarks.data.length > 0" class="space-y-4">
        <div v-for="bookmark in bookmarks.data" :key="bookmark.id"
          class="p-4 bg-white rounded-lg shadow-md hover:shadow-lg transition duration-200">
          <div class="flex items-start justify-between">
            <div class="flex items-center space-x-4">
              <img v-if="bookmark.post.image" :src="`/${bookmark.post.image}`" alt="Post Image"
                class="w-16 h-16 rounded-md object-cover">
              <div>
                <h2 class="text-xl font-semibold text-black hover:underline">
                  <Link :href="`/posts/${bookmark.post.id}/show`">{{ bookmark.post.title }}</Link>
                </h2>
                <p class="text-sm text-gray-500">
                  By <span class="font-medium">{{ bookmark.post.user.username }}</span> - {{
                    formatDate(bookmark.post.created_at) }}
                </p>
                <div class="flex items-center mt-2 space-x-2">
                  <span v-for="tag in bookmark.post.tags" :key="tag.id"
                    class="px-2 py-1 text-xs bg-blue-100 text-blue-600 rounded-full">
                    {{ tag.name }}
                  </span>
                </div>
              </div>
            </div>
            <button @click="removeBookmark(bookmark.id)"
              class="text-red-500 hover:text-red-700 transition duration-200 cursor-pointer">
              Remove
            </button>
          </div>
        </div>
        <!-- Pagination Controls -->
        <div class="mt-6 flex justify-center space-x-2">
          <button @click="changePage(bookmarks.prev_page_url)" v-show="bookmarks.prev_page_url"
            class="px-4 py-2 bg-gray-200 hover:bg-gray-300 rounded-md cursor-pointer">
            Previous
          </button>

          <button @click="changePage(bookmarks.next_page_url)" v-show="bookmarks.next_page_url"
            class="px-4 py-2 bg-gray-200 hover:bg-gray-300 rounded-md cursor-pointer">
            Next
          </button>
        </div>
      </div>

      <div v-else class="text-center mt-12 text-gray-500">
        <p>You have no bookmarked blog post yet.</p>
      </div>
    </div>
  </AppLayout>
</template>