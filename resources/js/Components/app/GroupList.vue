<script setup>
import { Disclosure, DisclosureButton, DisclosurePanel } from "@headlessui/vue";
import { ref } from "vue";
import TextInput from "@/Components/TextInput.vue";
import GroupItem from "@/Components/app/GroupItem.vue";
import GroupListItems from "./GroupListItems.vue";

defineProps({
  groups: Array,
});

const searchKeyword = ref("");
const showNewGroupModal = ref(false)
</script>

<template>
  <div
    class="px-3 bg-white dark:bg-slate-950 rounded border dark:border-slate-900 dark:text-gray-100 h-full py-3 overflow-hidden">
    <div class="block lg:hidden">
      <Disclosure v-slot="{ open }">
        <DisclosureButton class="w-full">
          <div class="flex justify-between items-center">
            <h2 class="text-xl font-bold">My Groups</h2>
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
              stroke="currentColor" class="w-6 h-6 transition-all" :class="open ? 'rotate-90 transform' : ''">
              <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5" />
            </svg>
          </div>
        </DisclosureButton>
        <DisclosurePanel>
          <div class="flex gap-2 mt-4">
            <TextInput :model-value="searchKeyword" placeholder="Type to search" class="w-full" />
            <button class="text-sm bg-indigo-500 hover:bg-indigo-600 text-white rounded py-1 px-2 w-[120px]">
              new group
            </button>
          </div>
          <div class="mt-3 h-[200px] lg:flex-1 overflow-auto">
            <div v-if="false" class="text-gray-400 text-center p-3">
              You are not joined to any groups
            </div>
            <div v-else>
              <GroupItem />
              <GroupItem />
              <GroupItem />
              <GroupItem />
            </div>
          </div>
        </DisclosurePanel>
      </Disclosure>
    </div>
    <div class="h-full overflow-hidden flex-col hidden lg:flex">
      <div class="flex justify-between">
        <h2 class="text-xl font-bold">My Groups</h2>
      </div>
      <GroupListItems :groups="groups" />
    </div>
  </div>
</template>