<script setup>
import { ref, computed } from "vue";
import { Inertia } from "@inertiajs/inertia";
import { useToast } from "vue-toastification";
import { useForm, Head } from "@inertiajs/inertia-vue3";
import AppLayout from "@/Layouts/AppLayout.vue";
import countries from "@/Data/countries.json";
import { base64UrlEncode } from "@/Utils/Helper";

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
        form.put(route("users.update", { encryptedId: base64UrlEncode(props.user.id.toString()) }), {
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
        form.post(route("users.store"), {
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
            <form
                class="bg-white shadow rounded-2xl p-12 sm:p-7 border border-solid border-gray-100"
                @submit.prevent="submitForm"
            >
                <h2 class="text-lg/9 font-semibold text-gray-900">
                    Personal Information
                </h2>
                <p class="mt-1 text-sm/6 text-gray-600">
                    Use a permanent address where you can receive mail.
                </p>

                <div
                    class="mt-10 grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6"
                >
                    <div class="sm:col-span-3">
                        <label
                            for="first_name"
                            class="block text-sm/6 font-medium text-gray-900"
                            >First name</label
                        >
                        <div class="mt-2">
                            <input
                                type="text"
                                name="first_name"
                                id="first_name"
                                autocomplete="given-name"
                                v-model="form.first_name"
                                class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline focus:outline-1 focus:-outline-offset-1 focus:outline-indigo-600 sm:text-sm/6"
                                required
                            />
                            <span
                                v-if="form.errors.first_name"
                                class="text-red-500"
                                >{{ form.errors.first_name }}</span
                            >
                        </div>
                    </div>

                    <div class="sm:col-span-3">
                        <label
                            for="last_name"
                            class="block text-sm/6 font-medium text-gray-900"
                            >Last name</label
                        >
                        <div class="mt-2">
                            <input
                                type="text"
                                name="last_name"
                                id="last_name"
                                v-model="form.last_name"
                                autocomplete="family-name"
                                class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline focus:outline-1 focus:-outline-offset-1 focus:outline-indigo-600 sm:text-sm/6"
                                required
                            />
                            <span
                                v-if="form.errors.last_name"
                                class="text-red-500"
                                >{{ form.errors.last_name }}</span
                            >
                        </div>
                    </div>

                    <div class="sm:col-span-3">
                        <label
                            for="email"
                            class="block text-sm/6 font-medium text-gray-900"
                            >Email address</label
                        >
                        <div class="mt-2">
                            <input
                                id="email"
                                name="email"
                                type="email"
                                autocomplete="email"
                                v-model="form.email"
                                class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline focus:outline-1 focus:-outline-offset-1 focus:outline-indigo-600 sm:text-sm/6"
                                required
                                :disabled="props.user.id"
                            />
                            <span
                                v-if="form.errors.email"
                                class="text-red-500"
                                >{{ form.errors.email }}</span
                            >
                        </div>
                    </div>

                    <div class="sm:col-span-3">
                        <label
                            for="country"
                            class="block text-sm/6 font-medium text-gray-900"
                            >Country</label
                        >
                        <div class="mt-2 grid grid-cols-1">
                            <select
                                id="country"
                                name="country"
                                autocomplete="country-name"
                                v-model="form.country"
                                class="col-start-1 row-start-1 w-full appearance-none rounded-xl bg-white py-1.5 pl-3 pr-8 text-base text-gray-900 outline outline-1 -outline-offset-1 outline-gray-300 focus:outline focus:outline-1 focus:-outline-offset-1 focus:outline-indigo-600 sm:text-sm/6"
                            >
                                <option value="">Select a country</option>
                                <option
                                    v-for="(country, index) in countries"
                                    :key="index"
                                    :value="country"
                                >
                                    {{ country }}
                                </option>
                            </select>
                            <small
                                v-if="form.errors.country"
                                class="text-pink-500"
                                >{{ form.errors.country }}</small
                            >
                        </div>
                    </div>

                    <div class="col-span-full">
                        <label
                            for="street-address"
                            class="block text-sm/6 font-medium text-gray-900"
                            >Street address</label
                        >
                        <div class="mt-2">
                            <input
                                type="text"
                                name="street"
                                id="street"
                                v-model="form.street"
                                autocomplete="street-address"
                                class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline focus:outline-1 focus:-outline-offset-1 focus:outline-indigo-600 sm:text-sm/6"
                            />
                            <span
                                v-if="form.errors.street"
                                class="text-red-500"
                                >{{ form.errors.street }}</span
                            >
                        </div>
                    </div>

                    <div class="sm:col-span-3">
                        <label
                            for="city"
                            class="block text-sm/6 font-medium text-gray-900"
                            >City</label
                        >
                        <div class="mt-2">
                            <input
                                type="text"
                                name="city"
                                id="city"
                                v-model="form.city"
                                autocomplete="address-level2"
                                class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline focus:outline-1 focus:-outline-offset-1 focus:outline-indigo-600 sm:text-sm/6"
                            />
                            <span
                                v-if="form.errors.city"
                                class="text-red-500"
                                >{{ form.errors.city }}</span
                            >
                        </div>
                    </div>

                    <div class="sm:col-span-3">
                        <label
                            for="postal-code"
                            class="block text-sm/6 font-medium text-gray-900"
                            >ZIP / Postal code</label
                        >
                        <div class="mt-2">
                            <input
                                type="text"
                                name="postcode"
                                id="postal-code"
                                v-model="form.postcode"
                                autocomplete="postal-code"
                                class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline focus:outline-1 focus:-outline-offset-1 focus:outline-indigo-600 sm:text-sm/6"
                            />
                            <span
                                v-if="form.errors.postcode"
                                class="text-red-500"
                                >{{ form.errors.postcode }}</span
                            >
                        </div>
                    </div>
                </div>
                <div class="mt-6 flex justify-end gap-x-6">
                    <button
                        :disabled="formDisabled || submitLoading"
                        :class="[
                            'rounded-full bg-indigo-600 px-3 py-2 text-md font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600',
                            submitLoading ? 'is-loading relative' : '',
                        ]"
                        type="submit"
                    >
                        {{ isEdit ? "Update User" : "Create User" }}
                    </button>
                </div>
            </form>
        </div>
    </AppLayout>
</template>
