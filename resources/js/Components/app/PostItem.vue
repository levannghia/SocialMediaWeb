<script setup>
import { Disclosure, DisclosureButton, DisclosurePanel, Menu, MenuButton, MenuItems, MenuItem } from "@headlessui/vue";
import { ChatBubbleLeftRightIcon, HandThumbUpIcon, ArrowDownTrayIcon, ChatBubbleLeftEllipsisIcon } from '@heroicons/vue/24/outline'
import {
  PaperClipIcon
} from "@heroicons/vue/20/solid";
import PostUserHeader from "@/Components/app/PostUserHeader.vue"
import { ref } from "vue";
import { router, usePage } from "@inertiajs/vue3";
import { isImage } from "@/helpers";
import UrlPreview from "@/Components/app/UrlPreview.vue";
import axiosClient from "@/axiosClient";
import InputTextarea from "../InputTextarea.vue";
import IndigoButton from "./IndigoButton.vue";
import ReadMoreReadLess from "@/Components/app/ReadMoreReadLess.vue"
import EditDeleteDropdown from "./EditDeleteDropdown.vue";

const props = defineProps({
  post: Object,
});

const newCommentText = ref('');
const editingComment = ref(null);

const authUser = usePage().props.auth.user;

const emit = defineEmits(['editClick', 'attachmentClick']);
function openEditModal() {
  emit('editClick', props.post);
}

function openAttachment(index) {
  emit('attachmentClick', props.post, index);
}

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

function createComment() {
  axiosClient.post(route('post.comment.create', props.post), {
    comment: newCommentText.value,
  })
    .then(({ data }) => {
      newCommentText.value = '';
      props.post.comments.unshift(data);
      props.post.num_of_comment++;
    })
}

function startCommentEdit(comment) {
  // editingComment.value = comment;
  editingComment.value = {
    id: comment.id,
    comment: comment.comment.replace(/<br\s*\/?>/gi, '\n') // <br />, <br > <br> <br/>, <br    />
  }
}

function deleteComment(comment) {
  if (!window.confirm('Bạn có chắc muốn xóa bình luận này?')) {
    return false;
  }

  axiosClient.delete(route('comment.delete', comment.id)).then(({ data }) => {
    props.post.comments = props.post.comments.filter(c => c.id != comment.id);
    props.post.num_of_comment--;
  });

}

function updateComment() {
  axiosClient.put(route('comment.update', editingComment.value.id), {
    comment: editingComment.value.comment,
  }).then(({ data }) => {
    editingComment.value = null;
    props.post.comments = props.post.comments.map((c) => {
      if (c.id == data.id) {
        return data;
      }
      return c;
    });
  })
}

function sendCommetReaction(comment) {
  axiosClient.post(route('comment.reaction', comment.id), {
    reaction: 'like',
  })
    .then(({ data }) => {
      comment.num_of_reaction = data.num_of_reaction;
      comment.current_user_has_reaction = data.current_user_has_reaction;
    })
}
</script>

<template>
  <div class="bg-white border rounded p-4 mb-3">
    <div class="flex items-center justify-between">
      <PostUserHeader :post="post" :showTime="true" />

      <EditDeleteDropdown :user="post.user" @edit="openEditModal" @delete="deletePost" />
    </div>
    <div>
      <ReadMoreReadLess :content="post.body" />
    </div>

    <div class="grid gap-3 mb-3" :class="post.attachments.length === 1 ? 'grid-cols-1' : 'grid-cols-2'">
      <template v-for="(attachment, index) of post.attachments.slice(0, 4)" :key="attachment.id">
        <div class="bg-blue-100 aspect-square flex flex-col items-center justify-center relative group cursor-pointer"
          @click="openAttachment(index)">
          <div v-if="index === 3 && post.attachments.length > 4"
            class="absolute top-0 left-0 right-0 bottom-0 z-10 bg-black/30 justify-center flex items-center text-2xl text-white">
            + {{ post.attachments.length - 4 }} more
          </div>
          <a @click.stop :href="route('post.download', attachment)"
            class="opacity-0 group-hover:opacity-100 w-8 h-8 absolute top-2 right-2 flex items-center justify-center rounded text-gray-100 bg-gray-700 transition-all hover:bg-gray-800">
            <ArrowDownTrayIcon class="w-4 h-4" />
          </a>

          <img v-if="isImage(attachment)" :src="attachment.url" alt=""
            class="object-contain aspect-square max-h-full max-w-full" />

          <template v-else>
            <PaperClipIcon class="w-10 h-10 mb-3" />
            {{ attachment.name }}
          </template>
        </div>
      </template>
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
      <DisclosurePanel class="mt-3">
        <div>
          <div class="flex items-center gap-2 mb-3">
            <a href="javascript:void(0)">
              <img :src="authUser.avatar_url" alt=""
                class="w-[42px] h-[42px] rounded-full border-2 hover:border-blue-500 transition-all" />
            </a>
            <div class="flex flex-1">
              <InputTextarea v-model="newCommentText" rows="1" class="w-full max-h-[160px] resize-none rounded-r-none"
                placeholder="Enter your comment here" />
              <IndigoButton @click="createComment" class="rounded-l-none w-[100px]">Submit</IndigoButton>
            </div>
          </div>
        </div>
        <div>
          <div v-for="comment in props.post.comments" :key="comment.id" class="mb-3">
            <div class="flex justify-between gap-2">
              <div class="flex items-center gap-2">
                <a href="javascript:void(0)">
                  <img :src="comment.user.avatar_url" alt=""
                    class="w-[42px] h-[42px] rounded-full border-2 hover:border-blue-500 transition-all" />
                </a>
                <div>
                  <h4 class="font-bold">
                    <a href="javascript:void(0)" class="hover:underline">{{ comment.user.name }}</a>
                  </h4>
                  <small class="text-gray-400 text-xs">{{ post.updated_at }}</small>
                </div>
              </div>
              <EditDeleteDropdown :user="comment.user" @edit="startCommentEdit(comment)"
                @delete="deleteComment(comment)" />
            </div>
            <div class="pl-12">
              <div v-if="editingComment && editingComment.id == comment.id">
                <InputTextarea v-model="editingComment.comment" rows="1" class="w-full max-h-[160px] resize-none"
                  placeholder="Enter your comment here" />
                <div class="flex gap-2 justify-end">
                  <button @click="editingComment = null" class="rounded-r-none text-indigo-500">cancel
                  </button>
                  <IndigoButton @click="updateComment" class="w-[100px]">update
                  </IndigoButton>
                </div>
              </div>
              <ReadMoreReadLess v-else :content="comment.comment" contentClass="flex flex-1 text-sm" />
              <div class="mt-1 flex gap-2">
                <button @click="sendCommetReaction(comment)"
                  class="flex items-center text-xs text-indigo-500 py-0.5 px-1  rounded-lg"
                  :class="[
            comment.current_user_has_reaction ?
              'bg-indigo-50 hover:bg-indigo-100' :
              'hover:bg-indigo-50'
          ]"
                  >
                  <HandThumbUpIcon class="w-3 h-3 mr-2" />
                  {{ comment.num_of_reaction }}
                  {{ comment.current_user_has_reaction ? 'Unlike' : 'Like' }}
                </button>
                <button class="flex items-center text-xs text-indigo-500 py-0.5 px-1 hover:bg-indigo-100 rounded-lg">
                  <ChatBubbleLeftEllipsisIcon class="w-3 h-3 mr-2" />
                  Reply
                </button>
              </div>
            </div>
          </div>
        </div>
      </DisclosurePanel>
    </Disclosure>

  </div>
</template>
