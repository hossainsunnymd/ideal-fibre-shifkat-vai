<script setup>
import { ref } from "vue";

const props = defineProps({
    show: Boolean,
    items: Array,
});

const emit = defineEmits(["update:show"]);

const printInvoice = () => {
const printContent = document.getElementById("print-invoice").innerHTML;
document.body.innerHTML = (`
    <html>
        <head>
            <title>Invoice Print</title>
            <style>
                @media print {
                    /* বাটনগুলো লুকাবে */
                    .print-hide {
                        display: none !important;
                    }

                    /* পৃষ্ঠা বিভাজন শুধুমাত্র যেখানে প্রয়োজন */
                    .print-page-break {
                        page-break-after: auto;
                    }

                    /* টেবিল ব্রেক না হয় */
                    table {
                        page-break-inside: auto;
                    }

                    /* প্রতিটি টেবিল row ব্রেক না হয় */
                    tr {
                        page-break-inside: avoid;
                        page-break-after: auto;
                    }

                    /* টেবিলের স্টাইল */
                    table,th, td ,tr{
                        border: 1px solid black;
                        border-collapse: collapse;
                    }

                }

                body{
                background-color: #ffffff;
                }
                table {
                    width: 100%;
                    border-collapse: collapse;
                }

                th, td,tr {
                    padding: 8px;
                    text-align: left;
                    border: 2px solid black;
                }

                .title {
                    font-size: 20px;
                    font-weight: bold;
                    text-align: center;
                    margin-bottom: 20px;
                }
            </style>
        </head>
        <body>
            <div class="title">Invoice</div>
            ${printContent}
        </body>
    </html>
`);

window.print();
window.location.reload();

};
</script>

<template>
    <div v-if="show" class="fixed inset-0 flex justify-center items-center bg-gray-500 bg-opacity-50 z-50">
        <div class="bg-white border rounded-lg shadow-lg p-6 w-[800px]">
            <!-- Scrollable Invoice Content -->
            <div id="print-invoice" class="max-h-[400px] overflow-y-auto">
                <div v-for="(item, i) in props.items" :key="i" class="print-page-break">
                    <table class="w-full border-collapse table-fixed">
                        <thead v-if="i === 0">
                            <tr class="bg-gray-200">
                                <th class="border px-2 py-1">Item</th>
                                <th class="border px-2 py-1">Party's Name</th>
                                <th class="border px-2 py-1">Description</th>
                                <th class="border px-2 py-1">Weight</th>
                                <th class="border px-2 py-1">Size</th>
                                <th class="border px-2 py-1">Rate</th>
                                <th class="border px-2 py-1">Qty</th>
                                <th class="border px-2 py-1">Order Price</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="(product, index) in item.invoice_products" :key="index" class="border-b">
                                <td v-if="index === 0" :rowspan="item.invoice_products.length" class="border px-2 py-1">
                                    {{ i + 1 }}
                                </td>
                                <td v-if="index === 0" :rowspan="item.invoice_products.length" class="border px-2 py-1">
                                    {{ item.customer.name }}
                                </td>
                                <td class="border px-2 py-1">{{ product.product.decription }}</td>
                                <td class="border px-2 py-1">{{ product.product.weight }}</td>
                                <td class="border px-2 py-1">{{ product.product.size }}</td>
                                <td class="border px-2 py-1">{{ product.rate }}</td>
                                <td class="border px-2 py-1">{{ product.qty }}</td>
                                <td class="border px-2 py-1">{{ product.order_price }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Action Buttons (Hidden in Print) -->
            <div class="print-hide flex justify-between mt-6">
                <button @click="$emit('update:show', false)" class="bg-red-500 text-white py-2 px-4 rounded-md hover:bg-red-600">
                    Close
                </button>
                <button @click="printInvoice()" class="bg-blue-500 text-white py-2 px-4 rounded-md hover:bg-blue-600">
                    Print
                </button>
            </div>
        </div>
    </div>
</template>

<style scoped>

.fixed {
    position: fixed;
}
.bg-opacity-50 {
    background-color: rgba(0, 0, 0, 0.5);
}
.z-50 {
    z-index: 50;
}
.bg-white {
    background-color: white;
}
.border {
    border: 1px solid #ddd;
}
.rounded-lg {
    border-radius: 8px;
}
.shadow-lg {
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
}
.p-6 {
    padding: 1.5rem;
}
.mt-6 {
    margin-top: 1.5rem;
}
.font-bold {
    font-weight: bold;
}
.text-center {
    text-align: center;
}
.flex {
    display: flex;
}
.justify-between {
    justify-content: space-between;
}
.hover\:bg-red-600:hover {
    background-color: #e53e3e;
}
.hover\:bg-blue-600:hover {
    background-color: #3182ce;
}
table {
    width: 100%;
    border-collapse: collapse;
}
th, td {
    padding: 8px;
    border: 1px solid #ddd;
    text-align: left;
}
</style>
