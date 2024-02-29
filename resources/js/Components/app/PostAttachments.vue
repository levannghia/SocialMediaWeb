<script setup>
import { isImage } from "@/helpers";
import UrlPreview from "@/Components/app/UrlPreview.vue";
import { ArrowDownTrayIcon } from '@heroicons/vue/24/outline'
import {
  PaperClipIcon
} from "@heroicons/vue/20/solid";
const props = defineProps({
    attachments: Array
})

const emit = defineEmits(['attachmentClick'])
</script>

<template>
    <template v-for="(attachment, index) of attachments.slice(0, 4)" :key="attachment.id">
        <div class="bg-blue-100 aspect-square flex flex-col items-center justify-center relative group cursor-pointer"
            @click="emit('attachmentClick', index)">
            <div v-if="index === 3 && attachments.length > 4"
                class="absolute top-0 left-0 right-0 bottom-0 z-10 bg-black/30 justify-center flex items-center text-2xl text-white">
                + {{ attachments.length - 4 }} more
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
</template>