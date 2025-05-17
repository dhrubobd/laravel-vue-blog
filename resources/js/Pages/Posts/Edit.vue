<script setup>
import { ref } from "vue";
import { usePage, router, Link } from "@inertiajs/vue3";

const { post, tags } = usePage().props;
const form = ref({
  title: post.title,
  content: post.content,
  tags: post.tags.map(tag => tag.id),
  visibility: post.visibility,
  image: post.image
});

const imagePreview = ref(form.value.image ? `/${form.value.image}` : null);

// Handle image selection
const handleImageUpload = (e) => {
  const file = e.target.files[0];
  if (file) {
    imagePreview.value = URL.createObjectURL(file);
    form.value.image = file;
  }
};

// Update post function
const updatePost = () => {
  const formData = new FormData();
  formData.append("title", form.value.title);
  formData.append("content", form.value.content);
  formData.append("visibility", form.value.visibility);
  form.value.tags.forEach(tag => formData.append("tags[]", tag));
  if (form.value.image instanceof File) {
    formData.append("image", form.value.image);
  }

  router.post(`/posts/${post.id}/update`, formData, {
    onBefore: () => console.log("Updating Post..."),
    onSuccess: () => console.log("Post Updated"),
    onError: (errors) => console.error(errors)
  });
};
</script>

<template>
  <div class="max-w-2xl mx-auto mt-8 p-6 bg-white rounded-lg shadow-md">
    <h1 class="text-2xl font-bold mb-6">Edit Post</h1>

    <form @submit.prevent="updatePost">
      <div class="mb-4">
        <label class="block font-semibold mb-2">Title</label>
        <input 
          v-model="form.title" 
          type="text" 
          class="w-full p-2 border rounded focus:outline-none focus:ring-2 focus:ring-blue-500" 
          placeholder="Enter your post title">
      </div>

      <div class="mb-4">
        <label class="block font-semibold mb-2">Content</label>
        <textarea 
          v-model="form.content" 
          rows="6" 
          class="w-full p-2 border rounded focus:outline-none focus:ring-2 focus:ring-blue-500" 
          placeholder="Write your post content"></textarea>
      </div>

      <div class="mb-4">
        <label class="block font-semibold mb-2">Tags</label>
        <div class="flex flex-wrap gap-2">
          <div v-for="tag in tags" :key="tag.id">
            <label class="flex items-center space-x-2">
              <input 
                type="checkbox" 
                :value="tag.id" 
                v-model="form.tags">
              <span>{{ tag.name }}</span>
            </label>
          </div>
        </div>
      </div>

      <div class="mb-4">
        <label class="block font-semibold mb-2">Visibility</label>
        <select 
          v-model="form.visibility" 
          class="w-full p-2 border rounded focus:outline-none focus:ring-2 focus:ring-blue-500">
          <option value="public">Public</option>
          <option value="private">Private</option>
        </select>
      </div>

      <div class="mb-4">
        <label class="block font-semibold mb-2">Post Image</label>
        <input type="file" @change="handleImageUpload" class="block w-full text-sm text-gray-500 
                 file:mr-4 file:py-2 file:px-4 
                 file:rounded-full file:border-0 
                 file:text-sm file:font-semibold 
                 file:bg-blue-50 file:text-blue-700 
                 hover:file:bg-blue-100">
        <div v-if="imagePreview" class="mt-2">
          <img :src="imagePreview" alt="Image Preview" class="w-32 h-32 rounded mt-2 object-cover">
        </div>
        <p v-if="form.image" class="text-sm text-gray-500 mt-2">Current Image: {{ form.image }}</p>
      </div>

      <div class="flex justify-end gap-4">
        <Link 
          href="/posts" 
          class="px-4 py-2 bg-gray-500 text-white rounded hover:bg-gray-600">
          Cancel
        </Link>
        <button 
          type="submit" 
          class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600">
          Update Post
        </button>
      </div>
    </form>
  </div>
</template>

