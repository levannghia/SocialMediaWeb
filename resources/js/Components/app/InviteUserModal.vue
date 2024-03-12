<script setup>
import { computed, ref } from 'vue'
import { XMarkIcon, BookmarkIcon } from '@heroicons/vue/24/solid'
import { useForm, usePage } from "@inertiajs/vue3";
import axiosClient from "@/axiosClient.js";
import BaseModal from "@/Components/app/BaseModal.vue";
import TextInput from '../TextInput.vue';

const props = defineProps({
    modelValue: Boolean
})

const page = usePage();

const formErrors = ref({});
const form = useForm({
    email: '',
})

const show = computed({
    get: () => props.modelValue,
    set: (value) => emit('update:modelValue', value)
})


const emit = defineEmits(['update:modelValue', 'hide', 'create'])


function closeModal() {
    show.value = false
    emit('hide')
    resetModal();
}

function resetModal() {
    form.reset()
    formErrors.value = {}
}

function submit() {
    form.post(route('group.inviteUsers', page.props.group.slug),{
        onSuccess(res){
            console.log(res);
            closeModal();
        },
        onError(res){
            console.log(res);
        }
    });
}

</script>

<template>
    <BaseModal title="Create new Group" v-model="show" @hide="closeModal()">
        <div class="p-4 dark:text-gray-100">
            <div class="mb-3 dark:text-gray-100">
                <label>Username or Email</label>
                <TextInput type="text" class="mt-1 block w-full" v-model="form.email" required autofocus />
            </div>
        </div>

        <div class="flex justify-end gap-2 py-3 px-4">
            <button @click="show = false"
                class="text-gray-800 flex gap-1 items-center justify-center bg-gray-100 rounded-md hover:bg-gray-200 py-2 px-4">
                <XMarkIcon class="w-5 h-5" />
                Cancel
            </button>
            <button type="button"
                class="flex items-center justify-center rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600"
                @click="submit">
                <BookmarkIcon class="w-4 h-4 mr-2" />
                Submit
            </button>
        </div>
    </BaseModal>
</template>

