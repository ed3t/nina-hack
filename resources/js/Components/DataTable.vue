<script setup>
import { ref, defineEmits, watch } from "vue";
import EmptyState from "./EmptyState.vue";
import Pagination from "./Pagination.vue";
import ListItem from "./ListItem.vue";
import ListLoader from "./ListLoader.vue";

const emit = defineEmits();

const props = defineProps({
    users: Object,
    viewUser: Function,
    editUser: Function,
    loading: Boolean,
    searchQuery: String,
    debouncedSearch: Function,
    pageSize: Number,
    handleSort: Function,
    sortColumn: String,
    sortDirection: String,
    handleDelete: Function,
    handlePageSizeChange: Function,
});

const currentPage = ref(1);

watch(() => props.searchQuery, props.debouncedSearch);

const handleSearch = (e) => {
    props.debouncedSearch();
    emit("update:searchQuery", e.target.value);
};
const handlePageSizeChange = (e) => {
    const newPageSize = e.target.value;
    emit('update:pageSize', newPageSize);
};
</script>

<template>
    <div>
        <div
            :class="['flex justify-between mb-4', loading ? 'is-disabled' : '']"
        >
            <div class="control has-icon">
                <input
                    :value="searchQuery"
                    placeholder="Type to search..."
                    class="datatable-filter datatable-input-text focus:outline-0 input"
                    type="text"
                    @input="handleSearch"
                />

                <div class="form-icon">
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
                        <circle cx="11" cy="11" r="8"></circle>
                        <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                    </svg>
                </div>
            </div>
        </div>

        <div class="flex-table">
            <div :class="['flex-table-header', loading ? 'is-disabled' : '']">
                <span class="is-index cursor-pointer" @click="handleSort('id')">
                    No.
                    <figure
                        class="ml-2 text-primary"
                        v-if="sortColumn === 'id'"
                    >
                        {{ sortDirection === "asc" ? "↑" : "↓" }}
                    </figure>
                </span>
                <span
                    class="cursor-pointer is-grow"
                    @click="handleSort('first_name')"
                >
                    Full Name
                    <figure
                        class="ml-2 text-primary"
                        v-if="sortColumn === 'first_name'"
                    >
                        {{ sortDirection === "asc" ? "↑" : "↓" }}
                    </figure>
                </span>
                <span
                    class="cursor-pointer is-grow"
                    @click="handleSort('email')"
                    >Email
                    <figure
                        class="ml-2 text-primary"
                        v-if="sortColumn === 'email'"
                    >
                        {{ sortDirection === "asc" ? "↑" : "↓" }}
                    </figure>
                </span>
                <span>Country</span>
                <span class="cursor-pointer" @click="handleSort('created_at')"
                    >Created
                    <figure
                        class="ml-2 text-primary"
                        v-if="sortColumn === 'created_at'"
                    >
                        {{ sortDirection === "asc" ? "↑" : "↓" }}
                    </figure></span
                >
                <span class="cell-end">Actions</span>
            </div>

            <div class="flex-orders-inner">
                <div v-if="loading">
                    <ListLoader :size="4" />
                </div>
                <div v-else-if="!users.data || users.data.length === 0">
                    <EmptyState />
                </div>
                <div v-else>
                    <div v-for="(user, index) in users.data" :key="user.id">
                        <ListItem
                            :index="
                                (users.current_page - 1) * pageSize + index + 1
                            "
                            :user="user"
                            :viewUser="viewUser"
                            :editUser="editUser"
                            :handleDelete="handleDelete"
                        />
                    </div>
                </div>
            </div>
        </div>

        <div
            :class="[
                'pagination datatable-pagination pagination-datatables text-center',
                loading ? 'is-disabled' : '',
            ]"
        >
            <div class="datatable-info flex">
                <div class="datatable-info flex">
                    <label>Page Size: </label>
                    <div class="control">
                        <div class="select">
                            <select
                                :value="pageSize"
                                class="datatable-filter datatable-select form-control"
                                @change="handlePageSizeChange"
                            >
                                <option value="5">5</option>
                                <option value="10">10</option>
                                <option value="20">20</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            <Pagination
                :users="users"
                :sortColumn="sortColumn"
                :sortDirection="sortDirection"
            />
        </div>
    </div>
</template>
