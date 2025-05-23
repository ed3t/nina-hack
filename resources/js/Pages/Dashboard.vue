<script setup>
import { ref, computed, watch } from "vue";
import { Inertia } from "@inertiajs/inertia";
import { Head, useForm } from "@inertiajs/inertia-vue3";
import { useToast } from "vue-toastification";
import { debounce } from "lodash";
import axios from "axios";

import AppLayout from "@/Layouts/AppLayout.vue";
import DataTable from "@/Components/DataTable.vue";

const form = useForm({});
const toast = useToast();

const props = defineProps({
    users: Object,
    pageSize: Number,
    sortColumn: String,
    sortDirection: String,
    search: String,
});

const users = ref(props.users);
const sortColumn = ref(props.sortColumn);
const sortDirection = ref(props.sortDirection);
const pageSize = ref(props.pageSize);
const currentPage = ref(props.users.current_page);
const searchQuery = ref(props.search || "");
const loading = ref(false);

const debouncedSearch = debounce(async () => {
    if (searchQuery.value.length === 0) {
        users.value = props.users;
        return;
    }

    loading.value = true;

    try {
        const response = await axios.get(route("users.search"), {
            params: { search: searchQuery.value },
        });
        users.value = response.data.users;
    } catch (error) {
        console.error("Error fetching search results:", error);
    } finally {
        loading.value = false;
    }
}, 300);

const viewUser = (userId) =>
    Inertia.get(route("users.show", { encryptedId: userId }));
const editUser = (userId) =>
    Inertia.get(route("users.edit", { encryptedId: userId }));

const handleSort = (column) => {
    if (column === sortColumn.value) {
        sortDirection.value = sortDirection.value === "asc" ? "desc" : "asc";
    } else {
        sortColumn.value = column;
        sortDirection.value = "asc";
    }

    loading.value = true;

    Inertia.get(
        "/",
        {
            page: currentPage.value,
            size: pageSize.value,
            column: sortColumn.value,
            direction: sortDirection.value,
            search: searchQuery.value,
        },
        {
            onFinish: () => {
                loading.value = false;
            },
        },
    );
};

const handleDelete = (id) => {
    if (!confirm("Are you sure you want to delete this user?")) return;
    loading.value = true;
    form.delete(route("users.destroy", { id }), {
        preserveScroll: true,
        onSuccess: () => {
            toast.success("Deleted successfully!");
        },
        onError: () => {
            loading.value = false;
            toast.error("Failed to delete user.");
        },
        onFinish: () => {
            Inertia.get(route("users.index"), {
                page: currentPage.value,
                size: pageSize.value,
                column: sortColumn.value,
                direction: sortDirection.value,
                search: searchQuery.value,
            });
            loading.value = false;
        },
    });
};

const handlePageSizeChange = (newPageSize) => {
    pageSize.value = newPageSize;
    currentPage.value = 1;

    loading.value = true;

    Inertia.get(
        route("users.index"),
        {
            page: currentPage.value,
            size: pageSize.value,
            column: sortColumn.value,
            direction: sortDirection.value,
            search: searchQuery.value,
        },
        {
            onFinish: () => {
                loading.value = false;
            },
        },
    );
};
</script>

<template>
    <Head title="Dashboard" />
    <AppLayout :isEdit="false">
        <DataTable
            :viewUser="viewUser"
            :editUser="editUser"
            :loading="loading"
            :users="users"
            :debouncedSearch="debouncedSearch"
            :pageSize="pageSize"
            :handleSort="handleSort"
            :sortColumn="sortColumn"
            :sortDirection="sortDirection"
            :handleDelete="handleDelete"
            @update:pageSize="handlePageSizeChange"
            v-model:searchQuery="searchQuery"
        />
    </AppLayout>
</template>
