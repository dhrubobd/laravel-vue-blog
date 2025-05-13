<script setup>
import { Link, usePage } from '@inertiajs/vue3';
</script>

<template>
  <header class="bg-black text-white p-4 flex justify-between items-center">
    <div>
      <h1 class="text-2xl font-bold">My Blog</h1>
    </div>
    <nav class="space-x-4">
      <Link href="/" class="hover:underline">Blog</Link>
      <Link v-if="usePage().props.auth.user != null" href="/bookmarks" class="hover:underline">Your Blogs</Link>
      <Link v-if="usePage().props.auth.user == null" href="/login" class="hover:underline">Login</Link>
      <Link v-if="usePage().props.auth.user != null" href="/profile" class="hover:underline">Profile</Link>
      <Link v-if="usePage().props.auth.user != null" href="/logout" class="hover:underline">Logout</Link>
    </nav>
  </header>
  <main class="p-4 max-w-4xl mx-auto">
    <slot />
  </main>
</template>

