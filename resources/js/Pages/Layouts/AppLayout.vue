<script setup>
import { Link, usePage } from '@inertiajs/vue3';
</script>

<template>
  <header class="bg-black text-white p-4 flex justify-between items-center">
    <div>
      <h1 class="text-2xl font-bold"><Link href="/">Amar Blog</Link></h1>
    </div>
    <nav class="space-x-4">
      <Link v-if="usePage().props.auth.user == null" href="/login" class="hover:underline text-green-300">Login</Link>
      <Link v-if="usePage().props.auth.user == null" href="/register" class="hover:underline text-blue-300">Register</Link>
      <Link v-if="usePage().props.auth.user != null" href="/blogs" class="hover:underline text-green-300">Your Blogs</Link>
      <Link v-if="usePage().props.auth.user != null" href="/bookmarks" class="hover:underline">Bookmarked</Link>
      <Link v-if="usePage().props.auth.user != null" href="/profile" class="hover:underline">Profile</Link>
      <Link v-if="usePage().props.auth.user != null" href="/logout" class="hover:underline">Logout</Link>
    </nav>
  </header>
  <main class="p-4 max-w-4xl mx-auto">
    <slot />
  </main>
</template>

