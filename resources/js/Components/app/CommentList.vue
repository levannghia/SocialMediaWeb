<script setup>
import { HandThumbUpIcon, ChatBubbleLeftEllipsisIcon } from '@heroicons/vue/24/outline'
import { Disclosure, DisclosureButton, DisclosurePanel } from "@headlessui/vue";
import { ref } from "vue";
import { usePage } from "@inertiajs/vue3";
import InputTextarea from "../InputTextarea.vue";
import IndigoButton from "./IndigoButton.vue";
import axiosClient from "@/axiosClient";
import ReadMoreReadLess from "@/Components/app/ReadMoreReadLess.vue";
import EditDeleteDropdown from "./EditDeleteDropdown.vue";

const props = defineProps({
    post: Object,
    data: Object,
    parentId: {
        type: String,
        default: ''
    }
})

const newCommentText = ref('');
const editingComment = ref(null);

const authUser = usePage().props.auth.user;

function createComment() {
    axiosClient.post(route('post.comment.create', props.post), {
        comment: newCommentText.value,
        parent_id: props.parentId || null,
    })
        .then(({ data }) => {
            newCommentText.value = '';
            props.data.comments.unshift(data);
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
        props.comments = props.comments.filter(c => c.id != comment.id);
        props.post.num_of_comment--;
    });

}

function updateComment() {
    axiosClient.put(route('comment.update', editingComment.value.id), {
        comment: editingComment.value.comment,
    }).then(({ data }) => {
        editingComment.value = null;
        props.data.comments = props.data.comments.map((c) => {
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
        <div v-for="comment in props.data.comments" :key="comment.id" class="mb-3">
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
            <Disclosure>
                <div class="pl-12">
                    <div v-if="editingComment && editingComment.id == comment.id">
                        <InputTextarea v-model="editingComment.comment" rows="1" class="w-full max-h-[160px] resize-none"
                            placeholder="Enter your comment here" />
                        <div class="flex gap-2 justify-end">
                            <button @click="editingComment = null" class="rounded-r-none text-indigo-500">cancel
                            </button>
                            <IndigoButton @click="updateComment" class="w-[100px]">update</IndigoButton>
                        </div>
                    </div>
                    <ReadMoreReadLess v-else :content="comment.comment" contentClass="flex flex-1 text-sm" />
                    <div class="mt-1 flex gap-2">
                        <button @click="sendCommetReaction(comment)"
                            class="flex items-center text-xs text-indigo-500 py-0.5 px-1  rounded-lg" :class="[
                                comment.current_user_has_reaction ?
                                    'bg-indigo-50 hover:bg-indigo-100' :
                                    'hover:bg-indigo-50'
                            ]">
                            <HandThumbUpIcon class="w-3 h-3 mr-2" />
                            {{ comment.num_of_reaction }}
                            {{ comment.current_user_has_reaction ? 'Unlike' : 'Like' }}
                        </button>
                        <DisclosureButton
                            class="flex items-center text-xs text-indigo-500 py-0.5 px-1 hover:bg-indigo-100 rounded-lg">
                            <ChatBubbleLeftEllipsisIcon class="w-3 h-3 mr-2" />
                            Reply
                        </DisclosureButton>
                    </div>
                    <DisclosurePanel>
                        <CommentList :post="post" :data="{ comments: post.comments }" :parentId="comment.id" />
                    </DisclosurePanel>
                </div>
            </Disclosure>
        </div>
    </div>
</template>