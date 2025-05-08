<script setup>
import { ref, computed, watchEffect } from "vue";
import { Inertia } from "@inertiajs/inertia";
import AppLayout from "@/Layouts/AppLayout.vue";
import { Head } from "@inertiajs/inertia-vue3";

const props = defineProps({
    user: Object,
});

// Reactive form object
const form = ref({
    first_name: props.user?.first_name || "",
    last_name: props.user?.last_name || "",
    email: props.user?.email || "",
    country: props.user?.address?.country || "",
    city: props.user?.address?.city || "",
    postcode: props.user?.address?.postcode || "",
    street: props.user?.address?.street || "",
});

// Computed property to check if we are editing an existing user
const isEdit = computed(() => !!props.user && !!props.user.id);

// Update form data when user prop changes
watchEffect(() => {
    if (props.user) {
        form.value.first_name = props.user.first_name || "";
        form.value.last_name = props.user.last_name || "";
        form.value.email = props.user.email || "";
        form.value.country = props.user.address?.country || "";
        form.value.city = props.user.address?.city || "";
        form.value.postcode = props.user.address?.postcode || "";
        form.value.street = props.user.address?.street || "";
    }
});

function submitForm() {
    if (isEdit.value) {
        Inertia.put(`/users/update/${props.user.id}`, form.value);
    } else {
        Inertia.post("/users", form.value);
    }
}
</script>

<template>
    <Head
        :title="
            isEdit
                ? `Edit User - ${form.first_name} ${form.last_name}`
                : 'Add New User'
        "
    />
    <AppLayout>
        <h1>{{ isEdit ? "Edit User" : "Add New User" }}</h1>
        <form @submit.prevent="submitForm">
            <div>
                <label>First Name:</label>
                <input v-model="form.first_name" type="text" required />
            </div>
            <div>
                <label>Last Name:</label>
                <input v-model="form.last_name" type="text" required />
            </div>
            <div>
                <label>Email:</label>
                <input v-model="form.email" type="email" required />
            </div>
            <div>
                <label>Country:</label>
                <input v-model="form.country" type="text" required />
            </div>
            <div>
                <label>City:</label>
                <input v-model="form.city" type="text" required />
            </div>
            <div>
                <label>Post Code:</label>
                <input v-model="form.postcode" type="text" required />
            </div>
            <div>
                <label>Street:</label>
                <input v-model="form.street" type="text" required />
            </div>
            <div>
                <button type="submit">
                    {{ isEdit ? "Update User" : "Create User" }}
                </button>
            </div>
        </form>
    </AppLayout>
</template>
