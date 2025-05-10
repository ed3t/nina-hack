<template>
    <div class="placeload-wrapper">
        <div v-for="n in size" :key="n" class="list-view-item mb-4">
            <div class="placeload-wrap is-flex">
                <div class="content-shape-group mx-2">
                    <div class="content-shape is-grow loads"></div>
                </div>
                <div class="content-shape mx-2"></div>
                <div class="content-shape mx-2 loads h-hidden-tablet-p"></div>
                <div class="content-shape mx-2 loads h-hidden-tablet-p"></div>
                <div class="content-shape mx-2 loads h-hidden-tablet-p"></div>
                <div class="content-shape cell-end mx-2 loads"></div>
            </div>
        </div>
    </div>
</template>

<script setup>
const props = defineProps({
  size: Number
});
</script>