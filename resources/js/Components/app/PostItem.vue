<script setup>
import { Disclosure, DisclosureButton, DisclosurePanel } from "@headlessui/vue";
import { ChatBubbleLeftRightIcon, HandThumbUpIcon, MapPinIcon } from '@heroicons/vue/24/outline'
import PostUserHeader from "@/Components/app/PostUserHeader.vue"
import { ref, computed } from "vue";
import { router, usePage } from "@inertiajs/vue3";
import axiosClient from "@/axiosClient";
import ReadMoreReadLess from "@/Components/app/ReadMoreReadLess.vue"
import EditDeleteDropdown from "./EditDeleteDropdown.vue";
import PostAttachments from "./PostAttachments.vue";
import CommentList from "./CommentList.vue";
import UrlPreview from "./UrlPreview.vue";

const props = defineProps({
  post: Object,
});
let isPinned = false;
// const authUser = usePage().props.auth.user;
const emit = defineEmits(['editClick', 'attachmentClick']);
function openEditModal() {
  emit('editClick', props.post);
}

function openAttachment(index) {
  emit('attachmentClick', props.post, index);
}

const postBody = computed(() => {
  let content = props.post.body.replace(
    /(?:(\s+)|<p>)((#\w+)(?![^<]*<\/a>))/g,
    (match, group1, group2) => {
      const encodedGroup = encodeURIComponent(group2);
      return `${group1 || ''}<a href="/search/${encodedGroup}" class="hashtag">${group2}</a>`;
    }
  )

  return content;
})

function deletePost() {
  if (window.confirm('Bạn có chắc chắn xóa bài viết này?')) {
    router.delete(route('post.destroy', props.post), {
      preserveScroll: true,
    });
  }
}

function sendReaction() {
  axiosClient.post(route('post.reaction', props.post), {
    reaction: 'like',
  })
    .then(({ data }) => {
      props.post.num_of_reaction = data.num_of_reaction;
      props.post.current_user_has_reaction = data.current_user_has_reaction;
    })
}

function pinUppinPost() {

}
</script>

<template>
  <div class="bg-white border rounded p-4 mb-3">
    <div class="flex items-center justify-between">
      <PostUserHeader :post="post" :showTime="true" />
      <div class="flex items-center gap-2">
        <div v-if="isPinned" class="flex items-center text-xs">
          <MapPinIcon class="h-3 w-3" aria-hidden="true" />
          pinned
        </div>
        <EditDeleteDropdown :post="post" @edit="openEditModal" @delete="deletePost" @pin="pinUppinPost" />
      </div>
    </div>
    <div class="mb-3">
      <ReadMoreReadLess :content="postBody" />
      <UrlPreview :preview="post.preview" :url="post.preview_url" />
    </div>

    <div class="grid gap-3 mb-3" :class="post.attachments.length === 1 ? 'grid-cols-1' : 'grid-cols-2'">
      <PostAttachments :attachments="post.attachments" @attachmentClick="openAttachment" />
    </div>
    <Disclosure v-slot="{ open }">
      <div class="flex gap-2">
        <button @click="sendReaction" class="flex flex-1 text-gray-800 items-center py-2 px-4 gap-1 justify-center"
          :class="[
        post.current_user_has_reaction ?
          'bg-sky-100 hover:bg-sky-200' :
          'bg-gray-100 hover:bg-gray-200'
      ]">
          <HandThumbUpIcon class="w-5 h-5" />
          <span class="mr-2">{{ post.num_of_reaction }}</span>
          {{ post.current_user_has_reaction ? 'Unlike' : 'Like' }}
        </button>
        <DisclosureButton
          class="flex flex-1 text-gray-800 items-center py-2 px-4 gap-1 justify-center bg-gray-100 hover:bg-gray-200">
          <ChatBubbleLeftRightIcon class="w-5 h-5" />
          <span class="mr-2">{{ post.num_of_comment }}</span>
          Comment
        </DisclosureButton>
      </div>
      <DisclosurePanel class="comment-list mt-3 max-h-[400px] overflow-auto">
        <CommentList :post="post" :data="{ comments: post.comments }" />
      </DisclosurePanel>
    </Disclosure>

  </div>
</template>
