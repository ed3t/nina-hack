<script setup>
import { Inertia } from "@inertiajs/inertia";
import AppLayout from "@/Layouts/AppLayout.vue";
import PageLoader from "@/Components/PageLoader.vue";
import { formatDatetime, base64UrlEncode } from "@/Utils/Helper";
import { Head } from "@inertiajs/inertia-vue3";

const props = defineProps({
    user: Object,
});

const editUser = (userId) => Inertia.get(route("users.edit", { encryptedId: userId }));
const goBack = () => Inertia.visit(route("users.index"));
</script>

<template>
    <Head title="User Details" />
    <AppLayout>
        <div>
            <div
                class="bg-white shadow rounded-2xl p-12 sm:p-7 border border-solid border-gray-100"
                v-if="user"
            >
                <div class="flex justify-between">
                    <div class="px-4 sm:px-0">
                        <h3 class="text-lg/9 font-semibold text-gray-900">
                            User Information
                        </h3>
                        <p class="mt-1 max-w-2xl text-sm/6 text-gray-500">
                            <span class="text-gray-700">Created at: </span
                            >{{ formatDatetime(user.created_at) }}
                        </p>
                    </div>
                    <div class="mt-5 flex lg:ml-4 lg:mt-0">
                        <span class="hidden sm:block">
                            <button
                                type="button"
                                @click="editUser(base64UrlEncode(user.id.toString()))"
                                class="inline-flex items-center rounded-full bg-white px-3 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50"
                            >
                                Edit User
                            </button>
                        </span>

                        <span class="sm:ml-3">
                            <button
                                type="button"
                                @click="goBack"
                                class="inline-flex items-center rounded-full bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600"
                            >
                                <span class="-ml-0.5 mr-1.5 size-5">
                                    <svg
                                        xmlns="http://www.w3.org/2000/svg"
                                        viewBox="0 0 20 20"
                                        fill="currentColor"
                                        aria-hidden="true"
                                        data-slot="icon"
                                        class="iq on bbs"
                                    >
                                        <path
                                            fill-rule="evenodd"
                                            d="M18 10a.75.75 0 0 1-.75.75H4.66l2.1 1.95a.75.75 0 1 1-1.02 1.1l-3.5-3.25a.75.75 0 0 1 0-1.1l3.5-3.25a.75.75 0 1 1 1.02 1.1l-2.1 1.95h12.59A.75.75 0 0 1 18 10Z"
                                            clip-rule="evenodd"
                                        ></path>
                                    </svg>
                                </span>
                                Go Back
                            </button>
                        </span>
                    </div>
                </div>
                <div class="mt-6 border-t border-gray-100">
                    <dl class="divide-y divide-gray-100">
                        <div
                            class="px-4 py-6 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0"
                        >
                            <dt class="text-sm/6 font-medium text-gray-900">
                                Full name
                            </dt>
                            <dd
                                class="mt-1 text-sm/6 text-gray-700 sm:col-span-2 sm:mt-0"
                            >
                                {{ user.first_name }} {{ user.last_name }}
                            </dd>
                        </div>
                        <div
                            class="px-4 py-6 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0"
                        >
                            <dt class="text-sm/6 font-medium text-gray-900">
                                Email address
                            </dt>
                            <dd
                                class="mt-1 text-sm/6 text-gray-700 sm:col-span-2 sm:mt-0"
                            >
                                {{ user.email }}
                            </dd>
                        </div>
                        <div
                            class="px-4 py-6 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0"
                        >
                            <dt class="text-sm/6 font-medium text-gray-900">
                                Country
                            </dt>
                            <dd
                                class="mt-1 text-sm/6 text-gray-700 sm:col-span-2 sm:mt-0"
                            >
                                {{ user.address?.country }}
                            </dd>
                        </div>
                        <div
                            class="px-4 py-6 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0"
                        >
                            <dt class="text-sm/6 font-medium text-gray-900">
                                Post code
                            </dt>
                            <dd
                                class="mt-1 text-sm/6 text-gray-700 sm:col-span-2 sm:mt-0"
                            >
                                {{ user.address?.postcode }}
                            </dd>
                        </div>
                        <div
                            class="px-4 py-6 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0"
                        >
                            <dt class="text-sm/6 font-medium text-gray-900">
                                Address
                            </dt>
                            <dd
                                class="mt-1 text-sm/6 text-gray-700 sm:col-span-2 sm:mt-0"
                            >
                                {{ user.address?.street }},
                                {{ user.address?.city }},
                                {{ user.address?.country }}
                            </dd>
                        </div>
                    </dl>
                </div>
            </div>
            <div v-else>
                <PageLoader />
            </div>
        </div>
    </AppLayout>
</template>
