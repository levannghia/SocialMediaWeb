<template>

  <Head :title="group.name" />
  <AuthenticatedLayout>
    <div class="max-w-[768px] mx-auto h-full overflow-auto">
      <div v-show="showNotification && success" class="font-medium text-sm text-white bg-emerald-500 my-2 py-2 px-3">
        {{ success }}
      </div>
      <div v-if="errors.cover" class="font-medium text-sm text-white bg-red-400 my-2 py-2 px-3">
        {{ errors.cover }}
      </div>
      <div class="relative bg-white group">
        <img :src="coverImageSrc || group.cover_url || '/images/defaut_cover_photo.jpg'
    " class="w-full h-[200px] object-cover" />
        <div class="absolute top-2 right-2">
          <button v-if="isCurrentUserAdmin && !coverImageSrc"
            class="opacity-0 group-hover:opacity-100 transition-all flex items-center bg-gray-50 hover:bg-gray-100 text-gray-800 py-1 px-2 text-sm">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
              stroke="currentColor" class="w-4 h-4 mr-2">
              <path stroke-linecap="round" stroke-linejoin="round"
                d="M6.827 6.175A2.31 2.31 0 0 1 5.186 7.23c-.38.054-.757.112-1.134.175C2.999 7.58 2.25 8.507 2.25 9.574V18a2.25 2.25 0 0 0 2.25 2.25h15A2.25 2.25 0 0 0 21.75 18V9.574c0-1.067-.75-1.994-1.802-2.169a47.865 47.865 0 0 0-1.134-.175 2.31 2.31 0 0 1-1.64-1.055l-.822-1.316a2.192 2.192 0 0 0-1.736-1.039 48.774 48.774 0 0 0-5.232 0 2.192 2.192 0 0 0-1.736 1.039l-.821 1.316Z" />
              <path stroke-linecap="round" stroke-linejoin="round"
                d="M16.5 12.75a4.5 4.5 0 1 1-9 0 4.5 4.5 0 0 1 9 0ZM18.75 10.5h.008v.008h-.008V10.5Z" />
            </svg>
            Upload
            <input type="file" class="absolute top-0 left-0 right-0 bottom-0 opacity-0 cursor-pointer"
              @change="onCoverChange" />
          </button>
          <div v-else-if="isCurrentUserAdmin" class="flex whitespace-nowrap gap-2 opacity-0 group-hover:opacity-100">
            <button @click="cancelCoverImage"
              class="transition-all flex items-center bg-gray-50 hover:bg-gray-100 text-gray-800 py-1 px-2 text-sm">
              <XMarkIcon class="w-4 h-4" />
              Cancel
            </button>
            <button @click="submitCoverImage"
              class="transition-all flex items-center bg-gray-800 hover:bg-gray-900 text-gray-100 py-1 px-2 text-sm">
              <CheckIcon class="w-4 h-4" />
              Submit
            </button>
          </div>
        </div>
        <div class="flex">
          <div
            class="flex items-center justify-center relative group/avatar -mt-[64px] ml-[48px] w-[128px] h-[128px] rounded-full">
            <img :src="thumbnailImageSrc ||
    group.thumbnail_url ||
    '/images/user_default.png'
    " class="w-full h-full object-cover rounded-full" />
            <button v-if="isCurrentUserAdmin && !thumbnailImageSrc"
              class="absolute left-0 top-0 right-0 bottom-0 bg-black/50 text-gray-200 rounded-full opacity-0 flex items-center justify-center group-hover/avatar:opacity-100">
              <CameraIcon class="w-8 h-8" />

              <input type="file" class="absolute left-0 top-0 bottom-0 right-0 opacity-0" @change="onThumbnailChange" />
            </button>
            <div v-else-if="isCurrentUserAdmin" class="absolute top-1 right-0 flex flex-col gap-2">
              <button @click="resetThumbnailImage"
                class="w-7 h-7 flex items-center justify-center bg-red-500/80 text-white rounded-full">
                <XMarkIcon class="h-5 w-5" />
              </button>
              <button @click="submitThumbnailImage"
                class="w-7 h-7 flex items-center justify-center bg-emerald-500/80 text-white rounded-full">
                <CheckCircleIcon class="h-5 w-5" />
              </button>
            </div>
          </div>
          <div class="flex-1 p-3 flex justify-between items-center">
            <h2 class="font-semibold text-lg">{{ group.name }}</h2>
            <PrimaryButton v-if="!authUser" :href="route('login')">Login to join to this group</PrimaryButton>
            <PrimaryButton v-if="authUser && isCurrentUserAdmin" @click="showInviteUserModal = true">Invite Users
            </PrimaryButton>
            <PrimaryButton v-if="authUser && !group.role && group.auto_approval" @click="joinToGroup">Join To Group
            </PrimaryButton>
            <PrimaryButton v-if="authUser && !group.role && !group.auto_approval" @click="joinToGroup">Request To Join
            </PrimaryButton>
          </div>
        </div>
      </div>
      <div>
        <TabGroup>
          <TabList class="flex bg-white">
            <Tab v-slot="{ selected }" as="template">
              <TabItem text="Posts" :selected="selected" />
            </Tab>
            <Tab v-if="isJoinedGroup" v-slot="{ selected }" as="template">
              <TabItem text="Users" :selected="selected" />
            </Tab>
            <Tab v-if="isCurrentUserAdmin" v-slot="{ selected }" as="template">
              <TabItem text="Pending Requests" :selected="selected" />
            </Tab>
            <Tab v-slot="{ selected }" as="template">
              <TabItem text="Photos" :selected="selected" />
            </Tab>
            <Tab v-slot="{ selected }" as="template">
              <TabItem text="About" :selected="selected" />
            </Tab>
            <!-- <Tab v-if="isCurrentUserAdmin" v-slot="{ selected }" as="template">
              <TabItem text="My Profile" :selected="selected" />
            </Tab> -->
          </TabList>

          <TabPanels class="mt-2">
            <TabPanel class="bg-white p-3 shadow"> Posts </TabPanel>
            <TabPanel v-if="isJoinedGroup" class="bg-white p-3 shadow">
              <div class="mb-3">
                <TextInput :model-value="searchKeyword" placeholder="Type to search" class="w-full" />
              </div>
              <div class="grid grid-cols-2 gap-3">
                <UserListItem v-for="user of users" :user="user" :key="user.id" :show-role-dropdown="isCurrentUserAdmin"
                  :disable-role-dropdown="group.user_id === user.id" class="shadow rounded-lg"
                  @role-change="onRoleChange" @delete="deleteUser" />
              </div>
            </TabPanel>
            <TabPanel v-if="isCurrentUserAdmin" class="bg-white p-3 shadow">
              <div v-if="requests.length" class="grid grid-cols-2 gap-3">
                <UserListItem v-for="user of requests" :user="user" :key="user.id" :forApprove="true"
                  @approve="approveUser" @reject="rejectUser" class="shadow rounded-lg" />
              </div>
              <div v-else class="py-8 text-center dark:text-gray-100">
                There are no pending requests.
              </div>
            </TabPanel>
            <TabPanel class="bg-white p-3 shadow"> Photos </TabPanel>
            <TabPanel class="bg-white p-3 shadow">
              <template v-if="isCurrentUserAdmin">
                <GroupForm :form="groupForm" />
                <PrimaryButton @click="updateGroup">
                  Submit
                </PrimaryButton>
              </template>
              <div v-else class="ck-content-output dark:text-gray-100" v-html="group.about"></div>
            </TabPanel>
            <!-- <TabPanel class="bg-white p-3 shadow">
              <Edit :mustVerifyEmail="mustVerifyEmail" :status="status" />
            </TabPanel> -->
          </TabPanels>
        </TabGroup>
      </div>
    </div>
  </AuthenticatedLayout>
  <InviteUserModal v-model="showInviteUserModal" />
</template>

<script setup>
import { ref, computed } from "vue";
import { TabGroup, TabList, Tab, TabPanels, TabPanel } from "@headlessui/vue";
import { usePage, Head, useForm } from "@inertiajs/vue3";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import TabItem from "@/Pages/Profile/Partials/TabItem.vue";
// import Edit from "@/Pages/Profile/Edit.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import TextInput from "@/Components/TextInput.vue";
import {
  XMarkIcon,
  CheckIcon,
  CameraIcon,
  CheckCircleIcon,
} from "@heroicons/vue/24/solid";
import InviteUserModal from "@/Components/app/InviteUserModal.vue";
import UserListItem from "@/Components/app/UserListItem.vue";
import GroupForm from "@/Components/app/GroupForm.vue";

const authUser = usePage().props.auth.user;

const props = defineProps({
  errors: {
    type: Object,
  },

  status: {
    type: String,
  },

  group: {
    type: Object,
  },

  requests: {
    type: Array,
  },

  users: {
    type: Array,
  },

  success: {
    type: String,
  },
});

const groupForm = useForm({
  name: props.group?.name,
  about: props.group?.about,
  auto_approval: !!parseInt(props.group?.auto_approval)
})

const imageForm = useForm({
  thumbnail: null,
  cover: null,
});

const searchKeyword = ref('');
const showInviteUserModal = ref(false);
const showNotification = ref(true);
const coverImageSrc = ref("");
const thumbnailImageSrc = ref("");
// const isCurrentUserAdmin = ref(false);

// watch(() => props.group.role, (newVal, oldVal) => {
//   // Thực hiện hành động dựa trên thay đổi của role
//   if (newVal === 'admin') {
//     isCurrentUserAdmin.value = true;
//   } else {
//     isCurrentUserAdmin.value = false;
//   }
// }, { immediate: true }); // Tùy chọn: Chạy hiệu ứng ngay lập tức

const isCurrentUserAdmin = computed(() => props.group.role === 'admin');
const isJoinedGroup = computed(() => {
  return props.group.role && props.group.status === "approved";
});

const onThumbnailChange = (event) => {
  imageForm.thumbnail = event.target.files[0];
  if (imageForm.thumbnail) {
    const reader = new FileReader();
    reader.onload = () => {
      thumbnailImageSrc.value = reader.result;
    };

    reader.readAsDataURL(imageForm.thumbnail);
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

function resetThumbnailImage() {
  imageForm.thumbnail = null;
  thumbnailImageSrc.value = null;
}

function submitCoverImage() {
  imageForm.post(route("group.updateImage", props.group.slug), {
    onSuccess: () => {
      showNotification.value = true;
      cancelCoverImage();
      setTimeout(() => {
        showNotification.value = false;
      }, 5000);
    },
  });
}

function submitThumbnailImage() {
  imageForm.post(route("group.updateImage", props.group.slug), {
    onSuccess: () => {
      showNotification.value = true;
      resetThumbnailImage();
      setTimeout(() => {
        showNotification.value = false;
      }, 5000);
    },
  });
}

function joinToGroup() {
  const form = useForm({});
  form.post(route("group.join", props.group.slug), {
    onSuccess(res) {
      console.log(res);
    },
    onError(res) {
      console.log(res);
    },
    preserveScroll: true,
  });
}

function approveUser(user) {
  const form = useForm({
    user_id: user.id,
    action: "approve",
  });
  form.post(route("group.approveRequest", props.group.slug), {
    preserveScroll: true,
  });
}

function rejectUser(user) {
  const form = useForm({
    user_id: user.id,
    action: "reject",
  });
  form.post(route("group.approveRequest", props.group.slug), {
    preserveScroll: true,
  });
}

function deleteUser(user) {
  console.log("111")
  if (!window.confirm(`Are you sure you want to remove user "${user.name}" from this group?`)) {
    return false;
  }

  const form = useForm({
    user_id: user.id,
  })
  form.delete(route('group.removeUser', props.group.slug), {
    preserveScroll: true
  })
}

function onRoleChange(user, role) {
  console.log(user, role)
  const form = useForm({
    user_id: user.id,
    role
  })
  form.post(route('group.changeRole', props.group.slug), {
    preserveScroll: true
  })
}

function updateGroup(){
  groupForm.put(route('group.update', props.group.slug),{
    preserveScroll: true,
  })
}
</script>