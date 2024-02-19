<script setup>
import PostItem from "./PostItem.vue";
import PostModal from "@/Components/app/PostModal.vue";
import { ref } from "vue";

defineProps({
  posts: {
    type: Array
  }
})

const editPost = ref({});
const showEditModal = ref(false);

function openEditModal(post) {
    editPost.value = post;
    showEditModal.value = true;
}

</script>

<template>
  <div class="flex-1 overflow-auto">
    <PostItem v-for="post in posts" :key="post.id" :post="post" @editClick="openEditModal"/>
    <PostModal :post="editPost" v-model="showEditModal" />
  </div>
</template>