<template>
    <div class="flex-table-item is-order">
        <div class="flex-table-cell is-index" data-th="No.">
            <span class="light-text">#{{ index }}</span>
        </div>
        <div
            class="flex-table-cell cursor-pointer is-grow"
            @click="viewUser(base64UrlEncode(user.id.toString()))"
            data-th="Full Name"
        >
            <span>{{ user.first_name }} {{ user.last_name }}</span>
        </div>
        <div class="flex-table-cell is-grow" data-th="Email">
            <span class="light-text">{{ truncateText(user.email) }}</span>
        </div>
        <div class="flex-table-cell" data-th="Country">
            <span class="light-text">{{ user.address.country }}</span>
        </div>
        <div class="flex-table-cell" data-th="Created">
            <span class="light-text">{{ formattedCreatedAt }}</span>
        </div>
        <div class="flex-table-cell cell-end action">
            <button @click="editUser(base64UrlEncode(user.id.toString()))" title="Edit">
                <svg
                    xmlns="http://www.w3.org/2000/svg"
                    width="24"
                    height="24"
                    viewBox="0 0 24 24"
                    fill="none"
                    stroke="currentColor"
                    strokeWidth="2"
                    strokeLinecap="round"
                    strokeLinejoin="round"
                >
                    <path d="M12 20h9"></path>
                    <path
                        d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4L16.5 3.5z"
                    ></path>
                </svg>
            </button>
            <button
                data-action="delete"
                title="Delete"
                @click="handleDelete(base64UrlEncode(user.id.toString()))"
            >
                <svg
                    xmlns="http://www.w3.org/2000/svg"
                    width="24"
                    height="24"
                    viewBox="0 0 24 24"
                    fill="none"
                    stroke="currentColor"
                    strokeWidth="2"
                    strokeLinecap="round"
                    strokeLinejoin="round"
                >
                    <polyline points="3 6 5 6 21 6"></polyline>
                    <path
                        d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"
                    ></path>
                    <line x1="10" y1="11" x2="10" y2="17"></line>
                    <line x1="14" y1="11" x2="14" y2="17"></line>
                </svg>
            </button>
        </div>
    </div>
</template>

<script>
export default {
    props: {
        index: Number,
        viewUser: Function,
        editUser: Function,
        user: Object,
        handleDelete: Function,
    },
    computed: {
        formattedCreatedAt() {
            const date = new Date(this.user.created_at);

            const options = {
                year: "numeric",
                month: "short",
                day: "numeric",
                hour: "2-digit",
                minute: "2-digit",
                hour12: true,
            };

            return date.toLocaleString("en-US", options);
        },
        truncateText() {
            return (text) => {
                if (text.length > 20) {
                    return text.substring(0, 20) + "...";
                }
                return text;
            };
        },
        base64UrlEncode() {
            return (str) => {
                const base64 = btoa(str);
                return base64.replace(/\+/g, '-').replace(/\//g, '_').replace(/=+$/, '');
            };
        }
    },
    methods: {},
};
</script>
