<script setup>
import PostItem from "./PostItem.vue";
import PostModal from "@/Components/app/PostModal.vue";
import { ref, onMounted, watch } from "vue";
import { usePage } from "@inertiajs/vue3";
import AttachmentPreviewModal from "./AttachmentPreviewModal.vue";
import axiosClient from "@/axiosClient";

const page = usePage();

const props = defineProps({
  posts: {
    type: Array,
  },
});

const allPosts = ref({
  data: [],
  next: [],
});

const authUser = usePage().props.auth.user;

const editPost = ref({});
const showEditModal = ref(false);
const showAttachmentModal = ref(false);
const previewAttachmentPost = ref({});
const loadMoreIntersect = ref(null);

// watch(
//   () => page.props.posts.data,
//   () => {
//     console.log("update");
//     allPosts.value = {
//       data: page.props.posts.data,
//       next: page.props.posts.links?.next,
//     };
//   },
//   { deep: true }
// );

watch(() => page.props.posts, () => {
    if (page.props.posts) {
        allPosts.value = {
            data: page.props.posts.data,
            next: page.props.posts.links?.next
        }
    }
}, {deep: true, immediate: true})

function openEditModal(post) {
  editPost.value = post;
  showEditModal.value = true;
}

function openAttachmentModalPreview(post, index) {
  previewAttachmentPost.value = {
    post,
    index,
  };
  showAttachmentModal.value = true;
}

function onModalHide() {
  editPost.value = {
    id: null,
    body: "",
    user: authUser,
  };
}

function loadMore() {
  // console.log("load more");
  if (!allPosts.value.next) {
    return;
  }

  axiosClient.get(allPosts.value.next).then(({ data }) => {
    // console.log(data);
    // debugger;
    allPosts.value.data = [...allPosts.value.data, ...data.data];
    allPosts.value.next = data.links.next;
  });
}

onMounted(() => {
  const observer = new IntersectionObserver(
    (entries) => entries.forEach((entry) => entry.isIntersecting && loadMore()),
    {
      rootMargin: "-250px 0px 0px 0px",
    }
  );

  observer.observe(loadMoreIntersect.value);
});

// console.log(allPosts.value.data);
</script>

<template>
  <div class="overflow-auto">
    <PostItem
      v-for="post in allPosts.data"
      :key="post.id"
      :post="post"
      @editClick="openEditModal"
      @attachmentClick="openAttachmentModalPreview"
    />
    <div ref="loadMoreIntersect"></div>
    <PostModal :post="editPost" v-model="showEditModal" @hide="onModalHide" :group="editPost.group"/>
    <AttachmentPreviewModal
      :attachments="previewAttachmentPost.post?.attachments || []"
      v-model:index="previewAttachmentPost.index"
      v-model="showAttachmentModal"
    />
  </div>
</template>