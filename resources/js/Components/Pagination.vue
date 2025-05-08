<template>
    <div v-if="users.last_page > 1">
        <button
            @click="goToPage(users.current_page - 1)"
            :disabled="users.current_page === 1"
        >
            Previous
        </button>

        <!-- Page numbers -->
        <button
            v-for="page in pageNumbers"
            :key="page"
            @click="goToPage(page)"
            :class="{'active': users.current_page === page}"
        >
            {{ page }}
        </button>

        <button
            @click="goToPage(users.current_page + 1)"
            :disabled="users.current_page === users.last_page"
        >
            Next
        </button>
    </div>
</template>

<script>
export default {
    props: {
        users: Object,
    },
    computed: {
        pageNumbers() {
            const pages = [];
            const totalPages = this.users.last_page;
            const currentPage = this.users.current_page;

            let startPage = Math.max(currentPage - 2, 1);
            let endPage = Math.min(currentPage + 2, totalPages);

            if (currentPage > 3) {
                pages.push(1);
            }
            for (let i = startPage; i <= endPage; i++) {
                pages.push(i);
            }
            if (currentPage < totalPages - 2) {
                pages.push(totalPages);
            }

            return pages;
        },
    },
    methods: {
        goToPage(page) {
            if (page >= 1 && page <= this.users.last_page) {
                this.$inertia.get("/", { page: page });
            }
        },
    },
};
</script>

<style scoped>
button.active {
    font-weight: bold;
    color: white;
    background-color: #007bff;
}
</style>
