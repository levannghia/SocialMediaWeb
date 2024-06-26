<script setup>
import { Disclosure, DisclosureButton, DisclosurePanel } from "@headlessui/vue";
import {
  ChatBubbleLeftRightIcon,
  HandThumbUpIcon,
  MapPinIcon,
} from "@heroicons/vue/24/outline";
import PostUserHeader from "@/Components/app/PostUserHeader.vue";
import { ref, computed } from "vue";
import { router, useForm, usePage } from "@inertiajs/vue3";
import axiosClient from "@/axiosClient";
import ReadMoreReadLess from "@/Components/app/ReadMoreReadLess.vue";
import EditDeleteDropdown from "./EditDeleteDropdown.vue";
import PostAttachments from "./PostAttachments.vue";
import CommentList from "./CommentList.vue";
import UrlPreview from "./UrlPreview.vue";

const props = defineProps({
  post: Object,
});

const group = usePage().props.group;
const authUser = usePage().props.auth.user;
const emit = defineEmits(["editClick", "attachmentClick"]);
function openEditModal() {
  emit("editClick", props.post);
}

function openAttachment(index) {
  emit("attachmentClick", props.post, index);
}

const postBody = computed(() => {
  let content = props.post.body.replace(
    /(?:(\s+)|<p>)((#\w+)(?![^<]*<\/a>))/g,
    (match, group1, group2) => {
      const encodedGroup = encodeURIComponent(group2);
      return `${
        group1 || ""
      }<a href="/search/${encodedGroup}" class="hashtag">${group2}</a>`;
    }
  );

  return content;
});

function deletePost() {
  if (window.confirm("Bạn có chắc chắn xóa bài viết này?")) {
    router.delete(route("post.destroy", props.post), {
      preserveScroll: true,
    });
  }
}

function sendReaction() {
  axiosClient
    .post(route("post.reaction", props.post), {
      reaction: "like",
    })
    .then(({ data }) => {
      props.post.num_of_reaction = data.num_of_reaction;
      props.post.current_user_has_reaction = data.current_user_has_reaction;
    });
}

const isPinned = computed(() => {
  if (group?.id) {
    return group?.pinned_post_id === props.post.id;
  }

  return authUser?.pinned_post_id === props.post.id;
});

function pinUppinPost() {
  const form = useForm({
    forGroup: group?.id,
  });

  let isPinned = false;
  if (group?.id) {
    isPinned = group?.pinned_post_id === props.post.id;
  } else {
    isPinned = authUser?.pinned_post_id === props.post.id;
  }

  form.post(route("post.pinUppin", props.post.id), {
    preserveScroll: true,
    onSuccess: () => {
      if (group?.id) {
        group.pinned_post_id = isPinned ? null : props.post.id;
        console.log(group);
      } else {
        authUser.pinned_post_id = isPinned ? null : props.post.id;
      }
    },
  });
}
</script>

<template>
  <div class="bg-white border rounded p-4 mb-3 dark:bg-slate-950 dark:border-slate-900 dark:text-gray-100">
    <div class="flex items-center justify-between">
      <PostUserHeader :post="post" :showTime="true" />
      <div class="flex items-center gap-2">
        <div v-if="isPinned" class="flex items-center text-xs">
          <MapPinIcon class="h-3 w-3" aria-hidden="true" />
          pinned
        </div>
        <EditDeleteDropdown
          :post="post"
          @edit="openEditModal"
          @delete="deletePost"
          @pin="pinUppinPost"
        />
      </div>
    </div>
    <div class="mb-3">
      <ReadMoreReadLess :content="postBody" />
      <UrlPreview :preview="post.preview" :url="post.preview_url" />
    </div>

    <div
      class="grid gap-3 mb-3"
      :class="post.attachments.length === 1 ? 'grid-cols-1' : 'grid-cols-2'"
    >
      <PostAttachments
        :attachments="post.attachments"
        @attachmentClick="openAttachment"
      />
    </div>
    <Disclosure v-slot="{ open }">
      <div class="flex gap-2">
        <button
          @click="sendReaction"
          class="flex flex-1 text-gray-800 dark:text-gray-100 items-center py-2 px-4 gap-1 justify-center"
          :class="[
            post.current_user_has_reaction
              ? 'bg-sky-100 dark:bg-sky-900 hover:bg-sky-200 dark:hover:bg-sky-950'
              : 'bg-gray-100 dark:bg-slate-900 dark:hover:bg-slate-800',
          ]"
        >
          <HandThumbUpIcon class="w-5 h-5" />
          <span class="mr-2">{{ post.num_of_reaction }}</span>
          {{ post.current_user_has_reaction ? "Unlike" : "Like" }}
        </button>
        <DisclosureButton
          class="flex flex-1 dark:text-gray-100 text-gray-800 dark:bg-slate-900 dark:hover:bg-slate-800 items-center py-2 px-4 gap-1 justify-center bg-gray-100 hover:bg-gray-200"
        >
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
