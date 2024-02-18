<script setup>
import PostItem from "./PostItem.vue";

defineProps({
  posts: {
    type: Array
  }
})

</script>

<template>
  <div class="flex-1 overflow-auto">
    <PostItem v-for="post in posts" :key="post.id" :post="post" />
  </div>
</template>