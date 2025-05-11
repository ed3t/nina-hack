<template>
    <ul :class="users.last_page > 1 ? '' : 'is-disabled'">
        <li :class="{ 'is-disabled': users.current_page === 1 }">
            <a
                data-page="previous"
                @click="goToPage(users.current_page - 1)"
                :disabled="users.current_page === 1"
            >
                <svg
                    viewBox="0 0 24 24"
                    width="24"
                    height="24"
                    stroke="currentColor"
                    strokeWidth="2"
                    fill="none"
                    strokeLinecap="round"
                    strokeLinejoin="round"
                >
                    <polyline points="15 18 9 12 15 6"></polyline>
                </svg>
            </a>
        </li>

        <!-- Page numbers -->
        <li
            v-for="page in pageNumbers"
            :key="page"
            :class="['inner', users.current_page === page ? 'active' : '']"
        >
            <a
                :data-page="page"
                @click="goToPage(page)"
                :disabled="users.current_page === page"
            >
                {{ page }}
            </a>
        </li>

        <li
            :class="users.current_page === users.last_page ? 'is-disabled' : ''"
        >
            <a
                data-page="next"
                @click="goToPage(users.current_page + 1)"
                :disabled="users.current_page === users.last_page"
            >
                <svg
                    viewBox="0 0 24 24"
                    width="24"
                    height="24"
                    stroke="currentColor"
                    strokeWidth="2"
                    fill="none"
                    strokeLinecap="round"
                    strokeLinejoin="round"
                >
                    <polyline points="9 18 15 12 9 6"></polyline>
                </svg>
            </a>
        </li>
    </ul>
</template>

<script>
export default {
    props: {
        users: Object,
        sortColumn: String,
        sortDirection: String,
        pageSize: Number,
        searchQuery: String,
    },
    computed: {
        pageNumbers() {
            const { last_page: totalPages, current_page: currentPage } =
                this.users;

            const startPage = Math.max(currentPage - 2, 1);
            const endPage = Math.min(currentPage + 2, totalPages);

            const pages = [
                ...(startPage > 2 ? ["..."] : []),
                ...Array.from(
                    { length: endPage - startPage + 1 },
                    (_, i) => startPage + i,
                ),
                ...(endPage < totalPages - 1 ? ["..."] : []),
            ];

            return pages;
        },
    },
    methods: {
        goToPage(page) {
            if (page >= 1 && page <= this.users.last_page) {
                this.$inertia.get(route("users.index"), {
                    page: page,
                    search: this.searchQuery,
                    column: this.sortColumn,
                    direction: this.sortDirection,
                    size: this.pageSize,
                });
            }
        },
    },
};
</script>
