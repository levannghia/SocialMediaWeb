<script setup>
import { isImage, isVideo } from "@/helpers";
import { ArrowDownTrayIcon } from "@heroicons/vue/24/outline";
import { PaperClipIcon } from "@heroicons/vue/20/solid";
const props = defineProps({
  attachments: Array,
});

const emit = defineEmits(["attachmentClick"]);
</script>

<template>
  <template
    v-for="(attachment, index) of attachments.slice(0, 4)"
    :key="attachment.id"
  >
    <div
      class="bg-blue-100 aspect-square flex flex-col items-center justify-center relative group cursor-pointer"
      @click="emit('attachmentClick', index)"
    >
      <div
        v-if="index === 3 && attachments.length > 4"
        class="absolute top-0 left-0 right-0 bottom-0 z-10 bg-black/30 justify-center flex items-center text-2xl text-white"
      >
        + {{ attachments.length - 4 }} more
      </div>
      <a
        @click.stop
        :href="route('post.download', attachment)"
        class="opacity-0 group-hover:opacity-100 w-8 h-8 absolute top-2 right-2 flex items-center justify-center rounded text-gray-100 bg-gray-700 transition-all hover:bg-gray-800"
      >
        <ArrowDownTrayIcon class="w-4 h-4" />
      </a>

      <img
        v-if="isImage(attachment)"
        :src="attachment.url"
        alt=""
        class="object-contain aspect-square max-h-full max-w-full"
      />
      <div
        v-else-if="isVideo(attachment)"
        class="relative flex justify-center items-center"
      >
        <svg
          xmlns="http://www.w3.org/2000/svg"
          fill="none"
          viewBox="0 0 24 24"
          stroke-width="1.5"
          stroke="currentColor"
          class="z-9 absolute w-16 h-16 text-gray-800 text-white opacity-70"
        >
          <path
            stroke-linecap="round"
            stroke-linejoin="round"
            d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"
          />
          <path
            stroke-linecap="round"
            stroke-linejoin="round"
            d="M15.91 11.672a.375.375 0 010 .656l-5.603 3.113a.375.375 0 01-.557-.328V8.887c0-.286.307-.466.557-.327l5.603 3.112z"
          />
        </svg>

        <div class="absolute left-0 top-0 w-full h-full bg-black/50 z-9"></div>
        <video :src="attachment.url"></video>
      </div>
      <template v-else>
        <PaperClipIcon class="w-10 h-10 mb-3" />
        {{ attachment.name }}
      </template>
    </div>
  </template>
</template>