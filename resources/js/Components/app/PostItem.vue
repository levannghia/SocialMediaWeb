<script setup>
import { Disclosure, DisclosureButton, DisclosurePanel } from "@headlessui/vue";
import { ChatBubbleLeftRightIcon, HandThumbUpIcon, ArrowDownTrayIcon } from '@heroicons/vue/24/outline'
import { Menu, MenuButton, MenuItems, MenuItem } from "@headlessui/vue";
import {
  ChevronDownIcon,
  PencilIcon,
  TrashIcon,
  EllipsisVerticalIcon,
  PaperClipIcon
} from "@heroicons/vue/20/solid";
import PostUserHeader from "@/Components/app/PostUserHeader.vue"
import { ref } from "vue";
import { router } from "@inertiajs/vue3";
import { isImage } from "@/helpers";
import UrlPreview from "@/Components/app/UrlPreview.vue";

const props = defineProps({
  post: Object,
});

const emit = defineEmits(['editClick']);
function openEditModal() {
  emit('editClick', props.post);
}

function openAttachment(attachment) {

}

function deletePost() {
  if (window.confirm('Bạn có chắc chắn xóa bài viết này?')) {
    router.delete(route('post.destroy', props.post), {
      preserveScroll: true,
    });
  }
}
</script>

<template>
  <div class="bg-white border rounded p-4 mb-3">
    <div class="flex items-center justify-between">
      <PostUserHeader :post="post" :showTime="true" />

      <Menu as="div" class="relative inline-block text-left z-10">
        <div>
          <MenuButton class="w-8 h-8 rounded-full hover:bg-black/10 transition flex items-center justify-center">
            <EllipsisVerticalIcon class="w-5 h-5" />
          </MenuButton>
        </div>

        <transition enter-active-class="transition duration-100 ease-out" enter-from-class="transform scale-95 opacity-0"
          enter-to-class="transform scale-100 opacity-100" leave-active-class="transition duration-75 ease-in"
          leave-from-class="transform scale-100 opacity-100" leave-to-class="transform scale-95 opacity-0">
          <MenuItems
            class="absolute right-0 mt-2 w-32 origin-top-right divide-y divide-gray-100 rounded-md bg-white shadow-lg ring-1 ring-black/5 focus:outline-none">
            <div class="px-1 py-1">
              <MenuItem v-slot="{ active }">
              <button @click="openEditModal" :class="[
                active ? 'bg-violet-500 text-white' : 'text-gray-900',
                'group flex w-full items-center rounded-md px-2 py-2 text-sm',
              ]">
                <PencilIcon class="mr-2 h-5 w-5 text-violet-400" aria-hidden="true" />
                Edit
              </button>
              </MenuItem>
              <MenuItem v-slot="{ active }">
              <button @click="deletePost" :class="[
                active ? 'bg-violet-500 text-white' : 'text-gray-900',
                'group flex w-full items-center rounded-md px-2 py-2 text-sm',
              ]">
                <TrashIcon class="mr-2 h-5 w-5 text-violet-400" aria-hidden="true" />
                Delete
              </button>
              </MenuItem>
            </div>
          </MenuItems>
        </transition>
      </Menu>
    </div>
    <div>
      <Disclosure v-slot="{ open }">
        <div v-if="!open" class="ck-content-output" v-html="post.body.substring(0, 200)" />
        <template v-if="post.body && post.body.length > 200">
          <DisclosurePanel>
            <div class="ck-content-output" v-html="post.body" />
          </DisclosurePanel>
          <div class="flex justify-end">
            <DisclosureButton class="text-blue-500 hover:underline">
              {{ open ? "Read Less" : "Read More" }}
            </DisclosureButton>
          </div>
        </template>
      </Disclosure>
    </div>

    <div class="grid gap-3 mb-3" :class="post.attachments.length === 1 ? 'grid-cols-1' : 'grid-cols-2'">
      <template v-for="(attachment, index) of post.attachments.slice(0,4)" :key="attachment.id">
        <div class="bg-blue-100 aspect-square flex flex-col items-center justify-center relative group cursor-pointer" @click="openAttachment(attachment)">
          <div v-if="index === 3 && post.attachments.length > 4 "
            class="absolute top-0 left-0 right-0 bottom-0 z-10 bg-black/30 justify-center flex items-center text-2xl text-white">
            + {{ post.attachments.length - 4 }} more
          </div>
          <a :href="route('post.download', attachment)"
            class="opacity-0 group-hover:opacity-100 w-8 h-8 absolute top-2 right-2 flex items-center justify-center rounded text-gray-100 bg-gray-700 transition-all hover:bg-gray-800">
            <ArrowDownTrayIcon class="w-4 h-4"/>
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
    <div class="flex gap-2">
      <button class="flex flex-1 text-gray-800 items-center py-2 px-4 gap-1 justify-center bg-gray-100 hover:bg-gray-200">
        <HandThumbUpIcon class="w-5 h-5" />
        Like
      </button>
      <button class="flex flex-1 text-gray-800 items-center py-2 px-4 gap-1 justify-center bg-gray-100 hover:bg-gray-200">
        <ChatBubbleLeftRightIcon class="w-5 h-5" />
        Comment
      </button>
    </div>
  </div>
</template>
