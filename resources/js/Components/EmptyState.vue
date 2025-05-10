<script>
import emptyIcon from '@/Components/icons/empty-forms.svg';

export default {
  data() {
    return {
      emptyIcon,
    };
  },
};
</script>

<template>
    <div class="mt-4 grow flex items-center justify-center">
        <div class="mt-14 text-center">
            <img :src="emptyIcon" class="empty-icon" alt="Empty Icon" />
            <p class="my-4">No data to show!</p>
        </div>
    </div>
</template>
