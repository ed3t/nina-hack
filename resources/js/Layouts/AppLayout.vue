<script setup>
import TopNav from "@/Components/TopNav.vue";

const props = defineProps({
  isEdit: Boolean,
  userId: Number,
});
</script>

<template>
    <div class="py-4 px-8 flex flex-col min-h-screen max-w-5xl mx-auto">
        <TopNav :isEdit="isEdit" :userId="userId" />
        <slot />
    </div>
</template>
