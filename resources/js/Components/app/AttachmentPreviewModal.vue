<template>
  <teleport to="body">
    <TransitionRoot appear :show="show" as="template">
      <Dialog as="div" @close="closeModal" class="relative z-10">
        <TransitionChild
          as="template"
          enter="duration-300 ease-out"
          enter-from="opacity-0"
          enter-to="opacity-100"
          leave="duration-200 ease-in"
          leave-from="opacity-100"
          leave-to="opacity-0"
        >
          <div class="fixed inset-0 bg-black/25" />
        </TransitionChild>

        <div class="fixed inset-0 overflow-y-auto">
          <div
            class="flex h-screen w-screen items-center justify-center p-4 text-center"
          >
            <TransitionChild
              as="template"
              enter="duration-300 ease-out"
              enter-from="opacity-0 scale-95"
              enter-to="opacity-100 scale-100"
              leave="duration-200 ease-in"
              leave-from="opacity-100 scale-100"
              leave-to="opacity-0 scale-95"
            >
              <DialogPanel
                class="w-full max-w-screen-md transform overflow-hidden rounded-2xl bg-white p-6 text-left align-middle shadow-xl transition-all"
              >
                <DialogTitle
                  as="h3"
                  class="text-lg font-medium leading-6 text-gray-900 flex items-center justify-between"
                >
                  Preview Attachment
                  <button
                    class="w-8 h-8 rounded-full hover:bg-black/5 dark:hover:bg-black/30 transition flex items-center justify-center"
                    @click="closeModal"
                  >
                    <XMarkIcon class="w-5 h-5" />
                  </button>
                </DialogTitle>
                <div class="mt-2 relative group bg-slate-800">
                  <div
                    @click="prev"
                    class="absolute left-0 flex opacity-0 group-hover:opacity-100 text-white items-center bg-black/5 h-full cursor-pointer"
                  >
                    <ChevronLeftIcon class="w-16" />
                  </div>
                  <div
                    @click="next"
                    class="absolute right-0 flex opacity-0 group-hover:opacity-100 text-white items-center bg-black/5 h-full cursor-pointer"
                  >
                    <ChevronRightIcon class="w-16" />
                  </div>

                  <div
                    class="flex items-center justify-center w-full h-full p-3"
                  >
                    <img
                      v-if="isImage(attachment)"
                      :src="attachment.url"
                      class="max-w-full max-h-full"
                    />
                    <div
                      v-else-if="isVideo(attachment)"
                      class="flex items-center"
                    >
                      <video :src="attachment.url" controls autoplay class="max-h-[500px]"></video>
                    </div>
                    <div
                      v-else
                      class="p-32 flex flex-col justify-center items-center text-gray-100"
                    >
                      <PaperClipIcon class="w-10 h-10 mb-3" />

                      <small>{{ attachment.name }}</small>
                    </div>
                  </div>
                </div>
              </DialogPanel>
            </TransitionChild>
          </div>
        </div>
      </Dialog>
    </TransitionRoot>
  </teleport>
</template>
  
<script setup>
import { computed, ref, watch } from "vue";
import {
  TransitionRoot,
  TransitionChild,
  Dialog,
  DialogPanel,
  DialogTitle,
} from "@headlessui/vue";
import {
  XMarkIcon,
  PaperClipIcon,
  ChevronLeftIcon,
  ChevronRightIcon,
} from "@heroicons/vue/20/solid";
import { isImage, isVideo } from "@/helpers";

const props = defineProps({
  attachments: {
    type: Array,
    required: true,
  },
  index: Number,
  modelValue: Boolean,
});

const emit = defineEmits(["update:modelValue", "update:index", "hide"]);

const show = computed({
  get: () => props.modelValue,
  set: (value) => emit("update:modelValue", value),
});

const currentIndex = computed({
  get: () => props.index,
  set: (value) => emit("update:index", value),
});

const attachment = computed(() => {
  return props.attachments[currentIndex.value];
});

function prev() {
  if (currentIndex.value === 0) return;
  currentIndex.value--;
}

function next() {
  if (currentIndex.value === props.attachments.length - 1) return;
  currentIndex.value++;
}

function closeModal() {
  show.value = false;
  emit("hide");
}
</script>
  