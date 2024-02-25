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

                  <div v-if="showExtensionsText"
                    class="border-l-4 border-amber-500 py-2 px-3 bg-amber-100 mt-3 text-gray-800">
                    Files must be one of the following extensions <br>
                    <small>{{ attachmentExtensions.join(', ') }}</small>
                  </div>

                  <div v-if="formErrors.attachments"
                    class="border-l-4 border-red-500 py-2 px-3 bg-red-100 mt-3 text-gray-800">
                    {{ formErrors.attachments }}
                  </div>

                  <div class="grid gap-3 mt-3" :class="computedAttachments.length === 1 ? 'grid-cols-1' : 'grid-cols-2'">
                    <template v-for="(attachment, index) of computedAttachments" :key="attachment.id">
                      <div
                        class="group aspect-square bg-blue-100 flex flex-col items-center justify-center text-gray-500 relative border-2"
                        :class="attachmentErrors[index] ? 'border-red-500' : ''">
                        <div v-if="attachment.deleted"
                          class="absolute z-10 left-0 bottom-0 right-0 py-2 px-3 text-sm bg-black text-white flex justify-between items-center">
                          To be deleted
                          <ArrowUturnLeftIcon class="w-4 h-4 cursor-pointer" @click="undoDelete(attachment)" />
                        </div>
                        <button @click="removeFile(attachment)"
                          class="absolute z-20 right-2 top-2 w-7 h-7 flex items-center justify-center bg-black/30 text-white rounded-full hover:bg-black/40">
                          <XMarkIcon class="w-5 h-5" />
                        </button>
                        <img v-if="isImage(attachment.file || attachment)" :src="attachment.url" alt=""
                          class="object-contain aspect-square max-h-full max-w-full"
                          :class="attachment.deleted ? 'opacity-50' : ''" />

                        <div v-else class="flex flex-col justify-center items-center px-3"
                          :class="attachment.deleted ? 'opacity-50' : ''">
                          <PaperClipIcon class="w-10 h-10" />
                          <small class="">
                            {{ (attachment.file || attachment).name }}
                          </small>
                        </div>
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
  PaperClipIcon,
  ArrowUturnLeftIcon
} from "@heroicons/vue/20/solid";
import { useForm, usePage } from "@inertiajs/vue3";
import ClassicEditor from "@ckeditor/ckeditor5-build-classic";
import { isImage } from "@/helpers";

const attachmentExtensions = usePage().props.attachmentExtensions;
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
  attachments: [],
  deleted_file_ids: [],
  _method: 'POST'
});

const attachmentFiles = ref([]);
const attachmentErrors = ref([]);
const formErrors = ref({});

const emit = defineEmits(["update:modelValue", "hide"]);

const computedAttachments = computed(() => {
  return [...attachmentFiles.value, ...(props.post.attachments || [])]
})

const show = computed({
  get: () => props.modelValue,
  set: (value) => emit("update:modelValue", value),
});

function closeModal() {
  show.value = false;
  emit('hide');
  resetModal();
}

function resetModal() {
  form.reset();
  formErrors.value = {}
  attachmentFiles.value = [];
  if (props.post.attachments) {
    props.post.attachments.forEach(file => file.deleted = false);
  }
}

function undoDelete(myFile) {
  myFile.deleted = false;
  form.deleted_file_ids = form.deleted_file_ids.filter(id => myFile.id !== id)
}

const showExtensionsText = computed(() => {
  for (const myFile of attachmentFiles.value) {
    const file = myFile.file;
    const parts = file.name.split('.')
    const ext = parts.pop().toLowerCase();
    if (!attachmentExtensions.includes(ext)) {
      return true
    }
  }

  return false
})

watch(() => props.post, () => {
  // console.log("change", props.post);
  form.id = props.post.id;
  form.body = props.post.body || '';
})

function submit() {
  form.attachments = attachmentFiles.value.map(myFile => {
    return myFile.file;
  })

  if (form.id) {
    form._method = 'PUT'
    form.post(route('post.update', props.post), {
      preserveScroll: true,
      onSuccess: () => {
        show.value = false;
        closeModal();
      },
      onError: (errors) => {
        process(errors)
      }
    });
  } else {
    form.post(route('post.store'), {
      preserveScroll: true,
      onSuccess: () => {
        show.value = false;
        closeModal();
      },
      onError: (errors) => {
        process(errors)
      }
    })
  }
}

function process(errors) {
  formErrors.value = errors
  console.log(formErrors.value);
  for (const key in errors) {
    if (key.includes('.')) {
      const [, index] = key.split('.');
      // console.log(key.split('.'));
      attachmentErrors.value[index] = errors[key]
    }
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
  // console.log(attachmentFiles.value);
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

function removeFile(myFile) {
  if (myFile.file) {
    attachmentFiles.value = attachmentFiles.value.filter(f => f !== myFile);
  } else {
    form.deleted_file_ids.push(myFile.id);
    myFile.deleted = true;
  }
}
</script>
