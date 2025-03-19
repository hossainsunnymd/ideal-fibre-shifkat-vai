<script setup>
import { usePage } from '@inertiajs/vue3';

const page = usePage();
</script>

<template>
<!-- component -->
<div class="flex flex-wrap mb-2">
    <div class="w-full md:w-1/2 xl:w-1/3 pt-3 px-3 md:pr-2 xl:pl-2 xl:pr-3">
        <div class="bg-blue-600 border rounded shadow p-2">
            <div class="flex flex-row items-center">
                <div class="flex-shrink pl-1 pr-4"><i class="fas fa-tasks fa-2x fa-fw fa-inverse"></i></div>
                <div class="flex-1 text-right">
                    <h5 class="text-white">Today's Work Order</h5>
                    <h3 class="text-white text-3xl">{{ page.props.data['invoice'] }} </h3>
                </div>
            </div>
        </div>
    </div>
    <div class="w-full md:w-1/2 xl:w-1/3 pt-3 px-3 md:pr-2 xl:pl-2 xl:pr-3">
        <div class="bg-blue-600 border rounded shadow p-2">
            <div class="flex flex-row items-center">
                <div class="flex-shrink pl-1 pr-4"><i class="fas fa-tasks fa-2x fa-fw fa-inverse"></i></div>
                <div class="flex-1 text-right">
                    <h5 class="text-white">Today's Total Order Quantity</h5>
                    <h3 class="text-white text-3xl">{{ page.props.data['invoice'] }} </h3>
                </div>
            </div>
        </div>
    </div>
    <div class="w-full md:w-1/2 xl:w-1/3 pt-3 px-3 md:pl-2 xl:pl-1">
        <div class="bg-blue-600 border rounded shadow p-2">
            <div class="flex flex-row items-center">
                <div class="flex-shrink pl-1 pr-4"><i class="fas fa-inbox fa-2x fa-fw fa-inverse"></i></div>
                <div class="flex-1 text-right">
                    <h5 class="text-white">Today's Total Order Amount</h5>
                    <h3 class="text-white text-3xl"> {{ page.props.data['products'] }} </h3>
                </div>
            </div>
        </div>
    </div>
    <div class="w-full md:w-1/2 xl:w-1/3 pt-3 px-3 md:pl-2 xl:pl-1">
        <div class="bg-blue-600 border rounded shadow p-2">
            <div class="flex flex-row items-center">
                <div class="flex-shrink pl-1 pr-4"><i class="fas fa-inbox fa-2x fa-fw fa-inverse"></i></div>
                <div class="flex-1 text-right">
                    <h5 class="text-white">Today's Total Delivery</h5>
                    <h3 class="text-white text-3xl"> {{ page.props.data['products'] }} </h3>
                </div>
            </div>
        </div>
    </div>
</div>
</template>

<style scoped>

</style>
