<script setup>
import { Inertia } from "@inertiajs/inertia";
import AppLayout from "@/Layouts/AppLayout.vue";
import { Head } from "@inertiajs/inertia-vue3";

const props = defineProps({
    user: Object,
});

function goBack() {
    Inertia.visit("/");
}
</script>

<template>
    <Head title="User Details" />
    <AppLayout>
        <h1>User Details</h1>
        <div v-if="user">
            <h2>{{ user.first_name }} {{ user.last_name }}</h2>
            <p><strong>Email:</strong> {{ user.email }}</p>
            <p><strong>Country:</strong> {{ user.address?.country }}</p>
            <p>
                <strong>Address:</strong> {{ user.address?.street }},
                {{ user.address?.city }}, {{ user.address?.country }}
            </p>
            <p><strong>Postcode:</strong> {{ user.address?.postcode }}</p>
            <button @click="goBack">Back to Dashboard</button>
        </div>
        <div v-else>
            <p>Loading user details...</p>
        </div>
    </AppLayout>
</template>
