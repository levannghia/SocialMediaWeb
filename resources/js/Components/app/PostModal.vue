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
                  <div class="grid gap-3 mt-3" :class="attachmentFiles.length === 1 ? 'grid-cols-1' : 'grid-cols-2'">
                    <template v-for="(attachment, index) of attachmentFiles.slice(0, 4)" :key="attachment.id">
                      <div class="bg-blue-100 aspect-square flex flex-col items-center justify-center relative group">
                        <div v-if="index >= 3"
                          class="absolute top-0 left-0 right-0 bottom-0 z-10 bg-black/30 justify-center flex items-center text-2xl text-white">
                          + {{ attachmentFiles.length - 4 }} more
                        </div>
                        <button @click="removeFile(attachment)"
                          class="absolute z-20 right-2 top-2 w-7 h-7 flex items-center justify-center bg-black/30 text-white rounded-full hover:bg-black/40">
                          <XMarkIcon class="w-5 h-5" />
                        </button>
                        <img v-if="isImage(attachment.file)" :src="attachment.url" alt=""
                          class="object-cover aspect-square max-h-full max-w-full" />

                        <template v-else>
                          <small class="flex flex-col justify-center items-center">
                            <PaperClipIcon class="w-10 h-10" />

                            {{ attachment.file.name }}
                          </small>
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
  BookmarkIcon,
  PaperClipIcon
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
  form.reset();
  attachmentFiles.value = [];
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
      url: await readFile(file)
    }
    attachmentFiles.value.push(myFile)
  }

  event.target.value = null
  console.log(attachmentFiles.value);
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

function removeFile(file) {
  attachmentFiles.value = attachmentFiles.value.filter(f => f !== file);
}
</script>
