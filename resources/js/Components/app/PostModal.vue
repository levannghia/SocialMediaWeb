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
                  <!-- <InputTextarea v-model="form.body" class="mb-3 mt-3 w-full" /> -->
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

function onAttachmentChoose(event) {
  console.log(event.target.files);
}
</script>
