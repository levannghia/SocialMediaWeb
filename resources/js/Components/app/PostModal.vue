<template>
  <teleport to="body">
    <TransitionRoot appear :show="show" as="template">
      <Dialog as="div" @close="closeModal" class="relative z-10">
        <TransitionChild as="template" enter="duration-300 ease-out" enter-from="opacity-0" enter-to="opacity-100"
          leave="duration-200 ease-in" leave-from="opacity-100" leave-to="opacity-0">
          <div class="fixed inset-0 bg-black/25" />
        </TransitionChild>

        <div class="fixed inset-0 overflow-y-auto">
          <div class="flex min-h-full items-center justify-center p-4 text-center">
            <TransitionChild as="template" enter="duration-300 ease-out" enter-from="opacity-0 scale-95"
              enter-to="opacity-100 scale-100" leave="duration-200 ease-in" leave-from="opacity-100 scale-100"
              leave-to="opacity-0 scale-95">
              <DialogPanel
                class="w-full max-w-md transform overflow-hidden rounded-2xl bg-white p-6 text-left align-middle shadow-xl transition-all">
                <DialogTitle as="h3"
                  class="text-lg font-medium leading-6 text-gray-900 flex items-center justify-between">
                  {{ form.id ? 'Update Post' : 'Create Post' }}

                  <button
                    class="w-8 h-8 rounded-full hover:bg-black/5 dark:hover:bg-black/30 transition flex items-center justify-center"
                    @click="closeModal">
                    <XMarkIcon class="w-5 h-5" />
                  </button>
                </DialogTitle>
                <div class="mt-2">
                  <PostUserHeader :post="post" :showTime="false" />
                  <ckeditor :editor="editor" v-model="form.body" :config="editorConfig"></ckeditor>
                  <div class="grid grid-cols-2 lg:grid-cols-3 gap-3 mb-3">
                    <template v-for="attachment of post.attachments" :key="attachment.id">
                      <div class="bg-blue-100 aspect-square flex flex-col items-center justify-center relative group">
                        <button
                          class="opacity-0 group-hover:opacity-100 w-8 h-8 absolute top-2 right-2 flex items-center justify-center rounded text-gray-100 bg-gray-700 transition-all hover:bg-gray-800">
                          <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-4 h-4">
                            <path fill-rule="evenodd"
                              d="M12 2.25a.75.75 0 0 1 .75.75v11.69l3.22-3.22a.75.75 0 1 1 1.06 1.06l-4.5 4.5a.75.75 0 0 1-1.06 0l-4.5-4.5a.75.75 0 1 1 1.06-1.06l3.22 3.22V3a.75.75 0 0 1 .75-.75Zm-9 13.5a.75.75 0 0 1 .75.75v2.25a1.5 1.5 0 0 0 1.5 1.5h13.5a1.5 1.5 0 0 0 1.5-1.5V16.5a.75.75 0 0 1 1.5 0v2.25a3 3 0 0 1-3 3H5.25a3 3 0 0 1-3-3V16.5a.75.75 0 0 1 .75-.75Z"
                              clip-rule="evenodd" />
                          </svg>
                        </button>

                        <img v-if="false" :src="attachment.url" alt=""
                          class="object-cover aspect-square max-h-full max-w-full" />

                        <template v-else>
                          <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"
                            class="w-16 h-16 text-gray-500">
                            <path
                              d="M5.625 1.5c-1.036 0-1.875.84-1.875 1.875v17.25c0 1.035.84 1.875 1.875 1.875h12.75c1.035 0 1.875-.84 1.875-1.875V12.75A3.75 3.75 0 0 0 16.5 9h-1.875a1.875 1.875 0 0 1-1.875-1.875V5.25A3.75 3.75 0 0 0 9 1.5H5.625Z" />
                            <path
                              d="M12.971 1.816A5.23 5.23 0 0 1 14.25 5.25v1.875c0 .207.168.375.375.375H16.5a5.23 5.23 0 0 1 3.434 1.279 9.768 9.768 0 0 0-6.963-6.963Z" />
                          </svg>

                          {{ attachment.name }}
                        </template>
                      </div>
                    </template>
                  </div>
                </div>

                <div class="flex gap-3 py-3">
                  <button type="button"
                    class="flex items-center justify-center rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600 w-full relative">
                    <PaperClipIcon class="w-4 h-4 mr-2" />
                    Attach Files
                    <input @click.stop @change="onAttachmentChoose" type="file" multiple
                      class="absolute left-0 top-0 right-0 bottom-0 opacity-0">
                  </button>
                  <button type="button"
                    class="flex items-center justify-center rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600 w-full"
                    @click="submit">
                    <BookmarkIcon class="w-4 h-4 mr-2" />
                    Submit
                  </button>
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
import InputTextarea from "@/Components/InputTextarea.vue";
import PostUserHeader from "@/Components/app/PostUserHeader.vue"
import {
  XMarkIcon,
  PaperClipIcon,
  BookmarkIcon
} from "@heroicons/vue/20/solid";
import { useForm } from "@inertiajs/vue3";
import ClassicEditor from "@ckeditor/ckeditor5-build-classic";
import { isImage } from "@/helpers";

const editor = ClassicEditor;
const editorConfig = {
  mediaEmbed: {
    removeProviders: ['dailymotion', 'spotify', 'youtube', 'vimeo', 'instagram', 'twitter', 'googleMaps', 'flickr', 'facebook']
  },
  toolbar: ['bold', 'italic', '|', 'bulletedList', 'numberedList', '|', 'heading', '|', 'outdent', 'indent', '|', 'link', '|', 'blockQuote'],
}

const props = defineProps({
  post: {
    type: Object,
    required: true,
  },
  modelValue: Boolean,
});


const form = useForm({
  id: null,
  body: '',
});
const attachmentFiles = ref([]);

const emit = defineEmits(["update:modelValue"]);

const show = computed({
  get: () => props.modelValue,
  set: (value) => emit("update:modelValue", value),
});

function closeModal() {
  show.value = false;
}

watch(() => props.post, () => {
  // console.log("change", props.post);
  form.id = props.post.id;
  form.body = props.post.body;
})

function submit() {
  if (form.id) {
    form.put(route('post.update', props.post), {
      preserveScroll: true,
      onSuccess: () => {
        show.value = false;
      }
    });
  } else {
    form.post(route('post.store'), {
      preserveScroll: true,
      onSuccess: () => {
        show.value = false;
        form.id = null;
        form.body = '';
      }
    })
  }
}

async function onAttachmentChoose(event) {
  for (const file of event.target.files) {
    const myFile = {
      file,
      src: await readFile(file)
    }
    attachmentFiles.value.push(myFile)
  }
}

async function readFile(file) {
  return new Promise((res, rej) => {
    if (isImage(file)) {
      const reader = new FileReader();
      reader.onload = () => {
        res(reader.result)
      }
      reader.onerror = rej
      reader.readAsDataURL(file)
    } else {
      res(null)
    }
  })
}
</script>
