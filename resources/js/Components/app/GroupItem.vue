<script setup>
import { Link } from "@inertiajs/vue3";


</script>

<template>
  <div class="cursor-pointer hover:bg-gray-100 dark:hover:bg-slate-800">
    <Link href="" class="flex items-start gap-1 py-2 px-2">
      <img src="https://picsum.photos/100" class="w-[32px] rounded-full" />
      <div class="flex-1">
        <div class="flex items-center justify-between">
          <h3 class="font-black">abc</h3>
        </div>
        <!--                <div class="text-xs text-gray-500" v-html="group.description"></div>-->
        <span class="text-xs">
          <!-- {{
                            group.status === "approved"
                              ? group.role === "admin"
                                ? group.role
                                : ""
                              : "not approved"
                          }} -->
        </span>
      </div>
    </Link>
  </div>
</template>
