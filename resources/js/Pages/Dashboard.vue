<script setup>
import { ref } from "vue";
import { Inertia } from "@inertiajs/inertia";
import AppLayout from "@/Layouts/AppLayout.vue";
import { Head } from "@inertiajs/inertia-vue3";
import Pagination from "@/Components/Pagination.vue";
import { debounce } from "lodash";
import Loader from "@/Components/ListLoader.vue";
import axios from "axios";

const props = defineProps({
    users: Object,
    userDetails: Object,
});

const users = ref(props.users);
const searchQuery = ref("");
const loading = ref(false);
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
        users.value = response.data;
    } catch (error) {
        console.error("Error fetching search results:", error);
    } finally {
        loading.value = false;
    }
}

function viewUser(userId) {
    Inertia.get(`/users/${userId}`);
}
</script>

<template>
    <Head title="Dashboard" />
    <AppLayout>
        <h1>User Dashboard</h1>
        <input
            v-model="searchQuery"
            placeholder="Search Users..."
            @input="debouncedSearch"
        />

        <div v-if="loading">
            <Loader />
        </div>

        <div v-if="users.data && users.data.length && !loading">
            <div v-for="user in users.data" :key="user.id">
                <p @click="viewUser(user.id)">
                    {{ user.first_name }} {{ user.last_name }}
                </p>
            </div>
        </div>

        <Pagination :users="users" />
    </AppLayout>
</template>
