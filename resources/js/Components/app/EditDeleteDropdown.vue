<script setup>
import { Menu, MenuButton, MenuItems, MenuItem } from "@headlessui/vue";
import {
  PencilIcon,
  TrashIcon,
  EllipsisVerticalIcon,
} from "@heroicons/vue/20/solid";
const emit = defineEmits(['edit', 'delete']);

</script>

<template>
    <Menu as="div" class="relative inline-block text-left z-10">
        <div>
            <MenuButton class="w-8 h-8 rounded-full hover:bg-black/10 transition flex items-center justify-center">
                <EllipsisVerticalIcon class="w-5 h-5" />
            </MenuButton>
        </div>

        <transition enter-active-class="transition duration-100 ease-out" enter-from-class="transform scale-95 opacity-0"
            enter-to-class="transform scale-100 opacity-100" leave-active-class="transition duration-75 ease-in"
            leave-from-class="transform scale-100 opacity-100" leave-to-class="transform scale-95 opacity-0">
            <MenuItems
                class="absolute right-0 mt-2 w-32 origin-top-right divide-y divide-gray-100 rounded-md bg-white shadow-lg ring-1 ring-black/5 focus:outline-none">
                <div class="px-1 py-1">
                    <MenuItem v-slot="{ active }">
                    <button @click="emit('edit')" :class="[
                        active ? 'bg-violet-500 text-white' : 'text-gray-900',
                        'group flex w-full items-center rounded-md px-2 py-2 text-sm',
                    ]">
                        <PencilIcon class="mr-2 h-5 w-5 text-violet-400" aria-hidden="true" />
                        Edit
                    </button>
                    </MenuItem>
                    <MenuItem v-slot="{ active }">
                    <button @click="emit('delete')" :class="[
                        active ? 'bg-violet-500 text-white' : 'text-gray-900',
                        'group flex w-full items-center rounded-md px-2 py-2 text-sm',
                    ]">
                        <TrashIcon class="mr-2 h-5 w-5 text-violet-400" aria-hidden="true" />
                        Delete
                    </button>
                    </MenuItem>
                </div>
            </MenuItems>
        </transition>
    </Menu>
</template>