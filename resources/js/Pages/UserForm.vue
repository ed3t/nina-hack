<script setup>
import { ref, computed } from "vue";
import { Inertia } from "@inertiajs/inertia";
import { useToast } from "vue-toastification";
import { useForm, Head } from "@inertiajs/inertia-vue3";
import AppLayout from "@/Layouts/AppLayout.vue";

const submitLoading = ref(false);

const toast = useToast();

const props = defineProps({
    user: Object,
});

const form = useForm({
    first_name: props.user?.first_name || "",
    last_name: props.user?.last_name || "",
    email: props.user?.email || "",
    country: props.user?.address?.country || "",
    city: props.user?.address?.city || "",
    postcode: props.user?.address?.postcode || "",
    street: props.user?.address?.street || "",
});

const formDisabled = computed(() => {
    return !form.first_name || !form.last_name || !form.email || !form.city;
});

// Computed property to check if we are editing an existing user
const isEdit = computed(() => !!props.user && !!props.user.id);

function submitForm() {
    submitLoading.value = true;
    if (isEdit.value) {
        form.put(`/users/update/${props.user.id}`, {
            onSuccess: () => {
                toast.success("User updated successfully!");
                submitLoading.value = false;
            },
            onError: (errors) => {
                toast.error("Failed to update user!");
                submitLoading.value = false;
                console.log(errors);
            },
        });
    } else {
        form.post("/users/store", {
            onSuccess: () => {
                toast.success("User created successfully!");
                submitLoading.value = false;
            },
            onError: (errors) => {
                toast.error("Failed to create user!");
                submitLoading.value = false;
                console.log(errors);
            },
        });
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
    <AppLayout :isEdit="true" :userId="props.user.id">
        <div>
            <form class="max-w-lg mx-auto" @submit.prevent="submitForm">
                <div class="bg-white shadow p-4 rounded-2xl">
                    <div class="border rounded-2xl mt-4">
                        <div class="flex">
                            <div class="py-3 px-4" style="width: 50%">
                                <label>First Name</label>
                                <input
                                    class="focus:ring-orange-500 disabled:bg-slate-50 disabled:text-slate-500 disabled:border-slate-200 disabled:shadow-none invalid:border-pink-500 invalid:text-pink-600 focus:invalid:border-pink-500 focus:invalid:ring-pink-500 focus:outline-0"
                                    v-model="form.first_name"
                                    type="text"
                                    required
                                />
                                <span
                                    v-if="form.errors.first_name"
                                    class="text-red-500"
                                    >{{ form.errors.first_name }}</span
                                >
                            </div>
                            <div class="py-3 px-4" style="width: 50%">
                                <label>Last Name</label>
                                <input
                                    class="focus:ring-orange-500 disabled:bg-slate-50 disabled:text-slate-500 disabled:border-slate-200 disabled:shadow-none invalid:border-pink-500 invalid:text-pink-600 focus:invalid:border-pink-500 focus:invalid:ring-pink-500 focus:outline-0"
                                    v-model="form.last_name"
                                    type="text"
                                    required
                                />
                                <span
                                    v-if="form.errors.last_name"
                                    class="text-red-500"
                                    >{{ form.errors.last_name }}</span
                                >
                            </div>
                        </div>
                        <div class="flex">
                            <div class="py-3 px-4" style="width: 100%">
                                <label>Email:</label>
                                <input
                                    class="focus:ring-orange-500 disabled:bg-slate-50 disabled:text-slate-500 disabled:border-slate-200 disabled:shadow-none invalid:border-pink-500 invalid:text-pink-600 focus:invalid:border-pink-500 focus:invalid:ring-pink-500 focus:outline-0"
                                    v-model="form.email"
                                    type="email"
                                    :disabled="props.user.id"
                                    required
                                />
                                <span
                                    v-if="form.errors.email"
                                    class="text-red-500"
                                    >{{ form.errors.email }}</span
                                >
                            </div>
                            <div class="py-3 px-4" style="width: 100%">
                                <label>Country</label>
                                <input
                                    class="focus:ring-orange-500 disabled:bg-slate-50 disabled:text-slate-500 disabled:border-slate-200 disabled:shadow-none invalid:border-pink-500 invalid:text-pink-600 focus:invalid:border-pink-500 focus:invalid:ring-pink-500 focus:outline-0"
                                    v-model="form.country"
                                    type="text"
                                />
                                <small
                                    v-if="form.errors.country"
                                    class="text-pink-500"
                                    >{{ form.errors.country }}</small
                                >
                            </div>
                        </div>
                        <div class="flex">
                            <div class="py-3 px-4" style="width: 50%">
                                <label>City</label>
                                <input
                                    class="focus:ring-orange-500 disabled:bg-slate-50 disabled:text-slate-500 disabled:border-slate-200 disabled:shadow-none invalid:border-pink-500 invalid:text-pink-600 focus:invalid:border-pink-500 focus:invalid:ring-pink-500 focus:outline-0"
                                    v-model="form.city"
                                    type="text"
                                    required
                                />
                                <span
                                    v-if="form.errors.city"
                                    class="text-red-500"
                                    >{{ form.errors.city }}</span
                                >
                            </div>
                            <div class="py-3 px-4" style="width: 50%">
                                <label>Post Code</label>
                                <input
                                    class="focus:ring-orange-500 disabled:bg-slate-50 disabled:text-slate-500 disabled:border-slate-200 disabled:shadow-none invalid:border-pink-500 invalid:text-pink-600 focus:invalid:border-pink-500 focus:invalid:ring-pink-500 focus:outline-0"
                                    v-model="form.postcode"
                                    type="text"
                                />
                                <span
                                    v-if="form.errors.postcode"
                                    class="text-red-500"
                                    >{{ form.errors.postcode }}</span
                                >
                            </div>
                        </div>
                        <div class="flex">
                            <div class="py-3 px-4" style="width: 100%">
                                <label>Street</label>
                                <input
                                    class="focus:ring-orange-500 disabled:bg-slate-50 disabled:text-slate-500 disabled:border-slate-200 disabled:shadow-none invalid:border-pink-500 invalid:text-pink-600 focus:invalid:border-pink-500 focus:invalid:ring-pink-500 focus:outline-0"
                                    v-model="form.street"
                                    type="text"
                                />
                                <span
                                    v-if="form.errors.street"
                                    class="text-red-500"
                                    >{{ form.errors.street }}</span
                                >
                            </div>
                        </div>
                    </div>
                    <div class="control">
                        <button
                            :class="[
                                'primary mt-4',
                                submitLoading ? 'is-loading' : '',
                            ]"
                            type="submit"
                            :disabled="formDisabled || submitLoading"
                        >
                            {{ isEdit ? "Update User" : "Create User" }}
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </AppLayout>
</template>
