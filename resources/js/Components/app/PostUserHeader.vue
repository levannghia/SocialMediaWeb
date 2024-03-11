<script setup>
import { Link } from '@inertiajs/vue3';

defineProps({
    post: {
        type: Object
    },
    showTime: {
        type: Boolean,
        required: true
    }
})

</script>

<template>
  <div class="flex items-center gap-2 mb-3">
    <Link :href="route('profile', post.user.username)">
      <img
        :src="post.user.avatar_url"
        alt=""
        class="w-[42px] h-[42px] rounded-full border-2 hover:border-blue-500 transition-all"
      />
    </Link>
    <div>
      <h4 class="font-bold">
        <Link :href="route('profile', post.user.username)" class="hover:underline">{{
          post.user.name
        }}</Link>
        <template v-if="post.group">
          >
          <a href="javascript:void(0)" class="hover:underline">{{
            post.group.name
          }}</a>
        </template>
      </h4>
      <small v-if="showTime" class="text-gray-400">{{ post.updated_at }}</small>
    </div>
  </div>
</template>