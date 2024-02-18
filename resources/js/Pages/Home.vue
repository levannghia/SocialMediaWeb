<script setup>
import { Head, Link } from "@inertiajs/vue3";
import { ref } from "vue";
import TextInput from "@/Components/TextInput.vue";
import CreatePost from "@/Components/app/CreatePost.vue";
import PostList from "@/Components/app/PostList.vue";
import GroupList from "@/Components/app/GroupList.vue";
import FollowingList from "@/Components/app/FollowingList.vue";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";

defineProps({
  following: Array,
  posts: {
    type: Object,
  }
});
</script>

<template>
  <Head title="Social Media Website" />
  <AuthenticatedLayout>
    <div class="grid lg:grid-cols-12 gap-3 p-4 h-full">
      <div class="lg:col-span-3 lg:order-1 h-full overflow-hidden">
        <GroupList />
      </div>
      <div class="lg:col-span-3 lg:order-3 h-full overflow-hidden">
        <FollowingList :users="following" />
      </div>
      <div
        class="lg:col-span-6 lg:order-2 h-full overflow-hidden flex flex-col"
      >
        <CreatePost />
        <PostList :posts="posts.data"/>
      </div>
    </div>
  </AuthenticatedLayout>
</template>

<style>
</style>
