<template>
  <Head :title="user.name" />
  <AuthenticatedLayout>
    <div class="max-w-[768px] mx-auto h-full overflow-auto">
      <div
        v-show="showNotification && success"
        class="font-medium text-sm text-white bg-emerald-500 my-2 py-2 px-3"
      >
        {{ success }}
      </div>
      <div
        v-if="errors.cover"
        class="font-medium text-sm text-white bg-red-400 my-2 py-2 px-3"
      >
        {{ errors.cover }}
      </div>
      <div class="relative bg-white group dark:bg-slate-950 dark:text-gray-100">
        <img
          loading="lazy"
          :src="
            coverImageSrc || user.cover_url || '/images/defaut_cover_photo.jpg'
          "
          alt=""
          class="w-full h-[200px] object-cover"
        />
        <div class="absolute top-2 right-2">
          <button
            v-if="!coverImageSrc"
            class="opacity-0 group-hover:opacity-100 transition-all flex items-center bg-gray-50 hover:bg-gray-100 text-gray-800 py-1 px-2 text-sm"
          >
            <CameraIcon class="w-4 h-4 mr-2"/>
            Upload
            <input
              type="file"
              class="absolute top-0 left-0 right-0 bottom-0 opacity-0 cursor-pointer"
              @change="onCoverChange"
            />
          </button>
          <div
            v-else
            class="flex whitespace-nowrap gap-2 opacity-0 group-hover:opacity-100"
          >
            <button
              @click="cancelCoverImage"
              class="transition-all flex items-center bg-gray-50 hover:bg-gray-100 text-gray-800 py-1 px-2 text-sm"
            >
              <XMarkIcon class="w-4 h-4" />
              Cancel
            </button>
            <button
              @click="submitCoverImage"
              class="transition-all flex items-center bg-gray-800 hover:bg-gray-900 text-gray-100 py-1 px-2 text-sm"
            >
              <CheckIcon class="w-4 h-4" />
              Submit
            </button>
          </div>
        </div>
        <div class="flex">
          <div
            class="flex items-center justify-center relative group/avatar -mt-[64px] ml-[48px] w-[128px] h-[128px] rounded-full"
          >
            <img
              loading="lazy"
              :src="
                avatarImageSrc || user.avatar_url || '/images/user_default.png'
              "
              class="w-full h-full object-cover rounded-full"
            />
            <button
              v-if="!avatarImageSrc"
              class="absolute left-0 top-0 right-0 bottom-0 bg-black/50 text-gray-200 rounded-full opacity-0 flex items-center justify-center group-hover/avatar:opacity-100"
            >
              <CameraIcon class="w-8 h-8" />

              <input
                type="file"
                class="absolute left-0 top-0 bottom-0 right-0 opacity-0"
                @change="onAvatarChange"
              />
            </button>
            <div v-else class="absolute top-1 right-0 flex flex-col gap-2">
              <button
                @click="resetAvatarImage"
                class="w-7 h-7 flex items-center justify-center bg-red-500/80 text-white rounded-full"
              >
                <XMarkIcon class="h-5 w-5" />
              </button>
              <button
                @click="submitAvatarImage"
                class="w-7 h-7 flex items-center justify-center bg-emerald-500/80 text-white rounded-full"
              >
                <CheckCircleIcon class="h-5 w-5" />
              </button>
            </div>
          </div>
          <div class="flex-1 p-4 flex justify-between items-center">
            <div>
              <h2 class="font-bold text-lg">{{ user.name }}</h2>
              <p class="text-xs text-gray-500">
                {{ followerCount }} follower(s)
              </p>
            </div>

            <div v-if="!isMyProfile">
              <PrimaryButton v-if="!isCurrentUserFollower" @click="followUser">
                Follow User
              </PrimaryButton>
              <DangerButton v-else @click="followUser">
                Unfollow User
              </DangerButton>
            </div>
          </div>
        </div>
      </div>
      <div>
        <TabGroup>
          <TabList class="flex bg-white dark:bg-slate-950 dark:text-white">
            <Tab v-slot="{ selected }" as="template">
              <TabItem text="Posts" :selected="selected" />
            </Tab>
            <Tab v-slot="{ selected }" as="template">
              <TabItem text="Followers" :selected="selected" />
            </Tab>
            <Tab v-slot="{ selected }" as="template">
              <TabItem text="Followings" :selected="selected" />
            </Tab>
            <Tab v-slot="{ selected }" as="template">
              <TabItem text="Photos" :selected="selected" />
            </Tab>
            <Tab v-if="isMyProfile" v-slot="{ selected }" as="template">
              <TabItem text="My Profile" :selected="selected" />
            </Tab>
          </TabList>

          <TabPanels class="mt-2">
            <TabPanel>
              <template v-if="posts">
                <CreatePost />
                <PostList :posts="posts.data" class="flex-1" />
              </template>
              <div v-else class="py-8 text-center dark:text-gray-100">
                You don't have permission to view these posts.
              </div>
            </TabPanel>
            <TabPanel>
              <div class="mb-3">
                <TextInput
                  :model-value="searchFollowersKeyword"
                  placeholder="Type to search"
                  class="w-full"
                />
              </div>
              <div v-if="followers.length" class="grid grid-cols-2 gap-3">
                <UserListItem
                  v-for="user of followers"
                  :user="user"
                  :key="user.id"
                  class="shadow rounded-lg"
                />
              </div>
              <div v-else class="text-center py-8">
                User does not have followers.
              </div>
            </TabPanel>
            <TabPanel>
              <div class="mb-3">
                <TextInput
                  :model-value="searchFollowingsKeyword"
                  placeholder="Type to search"
                  class="w-full"
                />
              </div>
              <div v-if="followings.length" class="grid grid-cols-2 gap-3">
                <UserListItem
                  v-for="user of followings"
                  :user="user"
                  :key="user.id"
                  class="shadow rounded-lg"
                />
              </div>
              <div v-else class="text-center py-8">
                The user is not following to anybody
              </div>
            </TabPanel>
            <TabPanel class="p-3">
              <TabPhotos :photos="photos"/>
            </TabPanel>
            <TabPanel>
              <Edit :mustVerifyEmail="mustVerifyEmail" :status="status" />
            </TabPanel>
          </TabPanels>
        </TabGroup>
      </div>
    </div>
  </AuthenticatedLayout>
</template>

<script setup>
import { ref, computed } from "vue";
import { TabGroup, TabList, Tab, TabPanels, TabPanel } from "@headlessui/vue";
import { usePage, Head, useForm } from "@inertiajs/vue3";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import TabItem from "@/Pages/Profile/Partials/TabItem.vue";
import Edit from "@/Pages/Profile/Edit.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import DangerButton from "@/Components/DangerButton.vue";
import PostList from "@/Components/app/PostList.vue";
import {
  XMarkIcon,
  CheckIcon,
  CameraIcon,
  CheckCircleIcon,
  PencilIcon,
} from "@heroicons/vue/24/solid";
// import { CameraIcon } from "@heroicons/vue/24/outline";
import TextInput from "@/Components/TextInput.vue";
import UserListItem from "@/Components/app/UserListItem.vue";
import TabPhotos from "./TabPhotos.vue";

const authUser = usePage().props.auth.user;
const props = defineProps({
  errors: {
    type: Object,
  },
  mustVerifyEmail: {
    type: Boolean,
  },
  status: {
    type: String,
  },
  isCurrentUserFollower: Boolean,
  followerCount: Number,
  posts: Object,
  followers: Array,
  followings: Array,
  photos: Array,
  user: {
    type: Object,
  },
  success: {
    type: String,
  },
});

const searchFollowingsKeyword = ref('');
const searchFollowersKeyword = ref('');

const imageForm = useForm({
  avatar: null,
  cover: null,
});

const showNotification = ref(true);
const coverImageSrc = ref("");
const avatarImageSrc = ref("");

const isMyProfile = computed(() => authUser && authUser.id === props.user.id);

const onAvatarChange = (event) => {
  imageForm.avatar = event.target.files[0];
  if (imageForm.avatar) {
    const reader = new FileReader();
    reader.onload = () => {
      avatarImageSrc.value = reader.result;
    };

    reader.readAsDataURL(imageForm.avatar);
  }
};

const onCoverChange = (event) => {
  imageForm.cover = event.target.files[0];
  if (imageForm.cover) {
    const reader = new FileReader();
    reader.onload = () => {
      coverImageSrc.value = reader.result;
    };

    reader.readAsDataURL(imageForm.cover);
  }
};

function cancelCoverImage() {
  imageForm.cover = null;
  coverImageSrc.value = null;
}

function resetAvatarImage() {
  imageForm.avatar = null;
  avatarImageSrc.value = null;
}

function submitCoverImage() {
  imageForm.post(route("profile.updateImage"), {
    onSuccess: () => {
      showNotification.value = true;
      cancelCoverImage();
      setTimeout(() => {
        showNotification.value = false;
      }, 5000);
    },
  });
}

function submitAvatarImage() {
  imageForm.post(route("profile.updateImage"), {
    onSuccess: () => {
      showNotification.value = true;
      resetAvatarImage();
      setTimeout(() => {
        showNotification.value = false;
      }, 5000);
    },
  });
}

function followUser() {
  const form = useForm({
    follow: !props.isCurrentUserFollower,
  });

  form.post(route("user.follow", props.user.id), {
    preserveScroll: true,
  });
}
</script>
