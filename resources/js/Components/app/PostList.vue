<script setup>
import PostItem from "./PostItem.vue";
import PostModal from "@/Components/app/PostModal.vue";
import { ref } from "vue";
import { usePage } from "@inertiajs/vue3";
import AttachmentPreviewModal from "./AttachmentPreviewModal.vue";

defineProps({
  posts: {
    type: Array
  }
})

const authUser = usePage().props.auth.user;

const editPost = ref({});
const showEditModal = ref(false);
const showAttachmentModal = ref(false);
const previewAttachmentPost = ref({});

function openEditModal(post) {
    editPost.value = post;
    showEditModal.value = true;
}

function openAttachmentModalPreview(post, index) {
  previewAttachmentPost.value = {
    post,
    index
  };
  showAttachmentModal.value = true;
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
    <PostItem v-for="post in posts" :key="post.id" :post="post" @editClick="openEditModal" @attachmentClick="openAttachmentModalPreview"/>
    <PostModal :post="editPost" v-model="showEditModal" @hide="onModalHide"/>
    <AttachmentPreviewModal :attachments="previewAttachmentPost.post?.attachments || []" v-model:index="previewAttachmentPost.index" v-model="showAttachmentModal"/>
  </div>
</template>