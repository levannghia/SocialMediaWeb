<script setup>
import PostItem from "./PostItem.vue";
import PostModal from "@/Components/app/PostModal.vue";
import { ref } from "vue";
import { usePage } from "@inertiajs/vue3";

defineProps({
  posts: {
    type: Array
  }
})

const authUser = usePage().props.auth.user;

const editPost = ref({});
const showEditModal = ref(false);

function openEditModal(post) {
    editPost.value = post;
    showEditModal.value = true;
}

function onModalHide() {
    editPost.value = {
        id: null,
        body: '',
        user: authUser
    }
}

</script>

<template>
  <div class="flex-1 overflow-auto">
    <PostItem v-for="post in posts" :key="post.id" :post="post" @editClick="openEditModal"/>
    <PostModal :post="editPost" v-model="showEditModal" @hide="onModalHide"/>
  </div>
</template>