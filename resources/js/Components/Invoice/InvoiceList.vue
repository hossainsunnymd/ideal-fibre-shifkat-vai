<script setup>
import { ref } from "vue";
import InvoiceDetails from "./InvoiceDetails.vue";
import { Link, usePage, useForm, router } from "@inertiajs/vue3";
import { createToaster } from "@meforma/vue-toaster";

const toaster = createToaster({});

const show = ref(false);
const customer = ref();
const page = usePage();

const searchValue = ref();
const searchField = ref(["customer.name", "id"]);
const headers = [
  { text: "Invoice no", value: "id" },
  { text: "Party Name", value: "customer.name" },
  { text: "Moblie", value: "customer.mobile" },
  { text: "Total Order", value: "total_work_order" },
  { text: "Amount", value: "total" },
  { text: "Created By", value: "created_by" },
  { text: "Created Date", value: "created_at" },
  { text: "Action", value: "action" },
];

const items = ref(page.props.list);

const deleteInvoice = (id) => {
  if (confirm("Are you sure you want to delete this Invoice?")) {
    router.get(`/delete-invoice?id=${id}`);
  }
};

if (page.props.flash.status === true) {
  toaster.success(page.props.flash.message);
}

const showDetails = (id) => {
  show.value = !show.value;
  customer.value = items.value.find((item) => item.id === id);
};

const searchForm = ref({
  fromDate: "",
  toDate: "",
  customerId: "",
});

const search = () => {
  router.get(
    `/invoice-page?fromDate=${searchForm.value.fromDate}&toDate=${searchForm.value.toDate}&customerId=${searchForm.value.customerId}`
  );
};

const formatDate = (date) => {
  const d = new Date(date).toISOString().split("T")[0];
  return d;
};
</script>

<template>
  <div class="p-4 bg-[#f8f8f8] overflow-auto">
    <h1 class="text-2xl font-bold mb-4">Work Order List / Invoices</h1>
    <InvoiceDetails v-model:show="show" :customer="customer" />

    <div class="flex flex-col md:flex-row justify-between items-center gap-4 mb-5">
      <input
        v-model="searchValue"
        type="text"
        class="px-2 py-1 w-full md:w-[200px] border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition-all"
        placeholder="Search...."
      />

      <select v-model="searchForm.customerId" class="bg-white px-2 py-1 w-full md:w-[200px] border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition-all">
        <option value="" disabled selected>Select Customer</option>
        <option v-for="customer in page.props.customerList" :key="customer.id" :value="customer.id">{{ customer.name }}</option>
      </select>

      <div class="flex flex-col md:flex-row gap-2 w-full md:w-auto ">
        <label class="flex flex-col text-sm">
          Search From:
          <input v-model="searchForm.fromDate" type="date" class="px-2 py-1 w-full md:w-[200px] border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition-all" />
        </label>
        <label class="flex flex-col text-sm">
          To:
          <input v-model="searchForm.toDate" type="date" class="px-2 py-1 w-full md:w-[200px] border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition-all" />
        </label>
        <div>
            <button @click="search" class="bg-blue-500 text-white px-4 py-2 mt-3 rounded-md hover:bg-blue-600">Search</button>
        </div>
      </div>
    </div>

    <div class="overflow-x-auto">
      <EasyDataTable
        buttons-pagination
        alternating
        :headers="headers"
        :items="items"
        :search-value="searchValue"
        :search-field="searchField"
        :rows-per-page="50"
      >
        <template #item-action="{ id }">
          <button class="btn btn-outline-dark text-sm px-3 py-1 btn-sm m-1" @click="showDetails(id)">
            <i class="fa text-sm fa-eye"></i>
          </button>
          <button class="bg-red-500 text-white px-3 py-1 rounded-md hover:bg-red-600 mx-2 mb-1" @click="deleteInvoice(id)">Delete</button>
        </template>

        <template #item-created_at="{ created_at }">
          {{ formatDate(created_at) }}
        </template>
      </EasyDataTable>
    </div>
  </div>
</template>
