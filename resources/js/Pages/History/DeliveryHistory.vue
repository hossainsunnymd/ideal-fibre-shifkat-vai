<script setup>
import SideNavLayout from '../../Layout/SideNavLayout.vue';
import { ref } from "vue";
import { usePage,Link,router } from "@inertiajs/vue3";

const page = usePage();
const searchValue = ref();
const searchField = ref(["invoice_product.invoice.customer.name", "invoice_product.invoice.id"]);
const headers = [
    { text: "No", value: "id" },
    { text: "Party Name", value: "invoice_product.invoice.customer.name", sortable: true },
    { text: "Description", value: "product.decription", sortable: true },
    { text: "Invoice no", value: "invoice_product.invoice.id", sortable: true },
    { text: "Work Order", value: "work_order", sortable: true },
    { text: "Delivered", value: "delivered_work_order", sortable: true },
    { text: "Pending", value: "pending_work_order", sortable: true },
    { text: "Delivered By", value: "delivered_by" },
    { text: "Delivered Date", value: "delivery_date", sortable: true },
    { text: "Action", value: "action"},
];

const items = ref(page.props.list);
console.log(items);

const formatDate = (date) => {
    return new Date(date).toISOString().split("T")[0];
};

const deleteDeliveryHistory = (id) => {
    if (confirm("Are you sure you want to delete this delivery history?")) {
       router.get(`/delete-delivery-history?id=${id}`) ;
    }
}
</script>

<template>
 <SideNavLayout>
    <div class="p-4 bg-[#f8f8f8]">
        <h1 class="text-2xl font-bold mb-4">Delivery History</h1>
        <input
            v-model="searchValue"
            type="text"
            class="mb-2 px-2 py-1 w-[300px] border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition-all"
            placeholder="Search...."
        />
        <EasyDataTable
            buttons-pagination
            alternating
            :headers="headers"
            :items="items"
            :search-value="searchValue"
            :search-field="searchField"
            :rows-per-page="50"
        >
            <template #item-delivery_date="{ created_at }">
                {{ formatDate(created_at) }}
            </template>

               <template #item-action="{ id }">
                <Link
                    :href="`/order-save-page?id=${id}`"
                    class="bg-green-500 ml-1 text-white font-bold py-2 px-4 rounded"
                >
                    Edit
                </Link>
                <button @click="deleteDeliveryHistory(id)" class="bg-red-500 ml-1 text-white font-bold py-2 px-4 rounded">
                    Delete
                </button>
            </template>
        </EasyDataTable>
    </div>
 </SideNavLayout>
</template>
