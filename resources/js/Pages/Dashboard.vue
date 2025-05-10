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
});

const users = ref(props.users);
const sortColumn = ref(props.sortColumn);
const sortDirection = ref(props.sortDirection);
const pageSize = ref(props.pageSize);
const currentPage = ref(props.users.current_page);
const searchQuery = ref("");
const loading = ref(false);

const defaultOptions = {
    page: currentPage.value,
    pageSize: pageSize.value,
    sortColumn: sortColumn.value,
    sortDirection: sortDirection.value,
};

const debouncedSearch = debounce(searchUsers, 300);

async function searchUsers() {
    if (searchQuery.value.length === 0) {
        users.value = props.users;
        return;
    }

    loading.value = true;

    try {
        const response = await axios.get("/search", {
            params: { search: searchQuery.value },
        });
        users.value = response.data.users;
    } catch (error) {
        console.error("Error fetching search results:", error);
    } finally {
        loading.value = false;
    }
}

const viewUser = (userId) => Inertia.get(route("users.show", { id: userId }));
const editUser = (userId) => Inertia.get(route("users.edit", { id: userId }));

const handleSort = (column) => {
    if (column === sortColumn.value) {
        sortDirection.value = sortDirection.value === "asc" ? "desc" : "asc";
    } else {
        sortColumn.value = column;
        sortDirection.value = "asc";
    }

    loading.value = true;

    Inertia.get("/", {
        page: currentPage.value,
            pageSize: pageSize.value,
            sortColumn: sortColumn.value,
            sortDirection: sortDirection.value,
    }, {
        onFinish: () => {
            loading.value = false;
        },
    });
};
const handleDelete = (id) => {
    if (!confirm("Are you sure you want to delete this user?")) return;
    loading.value = true;
    form.delete(route("users.destroy", { id }), {
        preserveScroll: true,
        onSuccess: () => {
            toast.success("Deleted successfully!");
            Inertia.get("/", defaultOptions);
            loading.value = false;
        },
        onError: () => {
            loading.value = false;
            toast.error("Failed to delete user.");
        },
    });
};
const handlePageSizeChange = (newPageSize) => {
    pageSize.value = newPageSize;
    currentPage.value = 1;

    loading.value = true;

    Inertia.get(
        "/",
        {
            page: currentPage.value,
            pageSize: pageSize.value,
            sortColumn: sortColumn.value,
            sortDirection: sortDirection.value,
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
