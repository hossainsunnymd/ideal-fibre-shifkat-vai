<template>
    <!-- Main modal -->
    <div v-if="showModal" class="modal fade show" tabindex="-1" style="display: block;">
        <div class="modal-dialog">
            <!-- Modal content -->
            <div class="modal-content">
                <!-- Modal header -->
                <div class="modal-header">
                    <h5 class="modal-title">Add Qty</h5>
                    <button type="button" class="btn-close" @click="closeModal" aria-label="Close"></button>
                </div>
                <!-- Modal body -->
                <div class="modal-body">
                    <div>
                        <label for="qty" class="form-label">Add Quantity</label>
                        <input v-model="productQty" type="text" name="text" id="qty" class="form-control" />
                    </div>
                </div>
                <!-- Modal footer -->
                <div class="modal-footer">
                    <button @click="addProduct()" type="button" class="btn btn-primary">Add</button>
                </div>
            </div>
        </div>
    </div>

    <div class="container">
        <h1 class="text-2xl font-bold mb-4 text-center">Create Work Order</h1>

        <div class="row">
            <!-- Customer Column -->
            <div class="col-md-6">

                <div class="border p-3 rounded">
                    <p><strong>Select Party</strong></p>

                    <input v-model="searchCustomer" type="text" class="form-control mb-3" placeholder="Search Customers..." />
                    <EasyDataTable
                        buttons-paginations
                        alternating
                        :items="customers"
                        :headers="customersHeaders"
                        :rows-per-page="50"
                        :search-value="searchCustomer"
                        :seach-field="customerSearchField"
                    >
                        <template #item-action="{name,mobile,id}">
                            <button type="button" class="btn btn-success btn-sm" @click="addCustomer(name,mobile,id)">Select</button>
                        </template>
                    </EasyDataTable>
                </div>
            </div>

            <!-- Product Column -->
            <div class="col-md-6">
                <div class="border p-3 rounded">
                    <p><strong>Select Product</strong></p>
                    <input v-model="searchProduct" type="text" class="form-control mb-3" placeholder="Search Products..." />
                    <EasyDataTable
                        buttons-paginations
                        alternating
                        :items="products"
                        :headers="productsHeaders"
                        :rows-per-page="50"
                        :search-value="searchProduct"
                        :seach-field="productSearchField"
                    >
                        <template #item-action="{id,decription,rate,size,weight}">
                            <button class="btn btn-success btn-sm" @click="addQty(id,decription,rate,size,weight)">Select</button>
                        </template>
                    </EasyDataTable>
                </div>
            </div>
        </div>

        <!-- Invoice Section -->
        <div class="mt-5 border p-3 rounded">
            <h5 class="text-end">Invoice</h5>
            <h6 class="text-end">{{ new Date().toISOString().slice(0, 10) }}</h6>

            <div>
                <h6>Bill To:</h6>
                <p>Name: {{ customer.name }}</p>
                <p>Mobile: {{ customer.mobile }}</p>
            </div>

            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Description</th>
                        <th>Qty</th>
                        <th>Weight</th>
                        <th>Size</th>
                        <th>Rate</th>
                        <th>Total</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-if="productList.length > 0" v-for="(product,index) in productList" :key="index">
                        <td>{{ index + 1 }}</td>
                        <td>{{ product['description'] }}</td>
                        <td>{{ product['qty'] }}</td>
                        <td>{{ product['weight'] }}</td>
                        <td>{{ product['size'] }}</td>
                        <td>{{ product['rate'] }}</td>
                        <td>{{ product['order_price'] }}</td>
                        <td><button @click="removeProduct(index)" class="btn btn-danger">Remove</button></td>
                    </tr>
                </tbody>
            </table>

            <div class="mt-4">
                <p>Total: {{ calculate.total }}</p>
                <p>Payable: {{ calculate.payable }}</p>
            </div>

            <button @click="createInvoice()" class="btn btn-success">Confirm</button>
        </div>
    </div>
</template>

<script setup>
import { ref ,reactive} from "vue";
import { usePage, useForm, router } from "@inertiajs/vue3";
const page = usePage();
import { createToaster } from "@meforma/vue-toaster";

const showModal=ref(false)
const toaster = createToaster({ });

const productsHeaders = [
    { text: "No", value: "id" },
    { text: "Description", value: "decription" },
    { text: "Size", value: "size" },
    { text: "Weight", value: "weight" },
    { text: "Rate", value: "rate", sortable: true },
    { text: "Action", value: "action" },
];
const searchCustomer = ref();
const customerSearchField = ref(["description"]);
const searchProduct = ref();
const productSearchField = ref(["name"]);

const customers = ref(page.props.customers);
const products = ref(page.props.products);

const customersHeaders = [
    { text: "No", value: "id" },
    { text: "Name", value: "name", sortable: true },
    { text: "Action", value: "action" },
];

const productList=ref([]);
const productQty=ref('');


const product=reactive({
    id:'',
    description:'',
    rate:'',
    size:'',
    weight:'',
    qty:''

});
function addQty(id,decription,rate,size,weight){

    showModal.value=!showModal.value;
    product.id=id;
    product.description=decription;
    product.rate=rate;
    product.size=size;
    product.weight=weight;
    product.qty=productQty.value;

};


const addProduct = () => {
    if(productQty.value==''){
        toaster.error('Product Quantity is Required');
        return ;
    }

   const selectedProduct={
       id:product.id,
       description:product.description,
       rate:product.rate,
       size:product.size,
       weight:product.weight,
       qty:productQty.value,
       order_price: parseFloat(productQty.value)*parseFloat(product.rate)

   };
   productList.value.push(selectedProduct);
   calculateTotal();
   showModal.value=!showModal.value;
};

function closeModal() {
    showModal.value = false;
}


const removeProduct = (index)=>{
    productList.value.splice(index,1);
    calculateTotal();
}


const customer=reactive({
    name:'',
    mobile:'',
    id:''
})

const addCustomer=(name,mobile,id)=>{
    customer.name=name;
    customer.mobile=mobile;
    customer.id=id;
}

const calculate=reactive({
    total:0,
    payable:0,
})

const calculateTotal=()=>{
    calculate.total=0;
    productList.value.forEach((product)=>{
    calculate.total+=parseFloat(product.order_price);

    });
    calculate.payable=calculate.total;
}

const totalOrder=ref(0);
function totalWorkOrder(){
    productList.value.forEach((product)=>{
        totalOrder.value+=parseFloat(product.qty);
    })
}

const form=useForm({
    cus_id:'',
    products:'',
    total:'',
    payable:'',
    total_work_order:'',
    created_by:page.props.user.name
})


const createInvoice=()=>{
    if(customer.name==''){
        toaster.error('Customer is required');

    }else if(productList.value.length==0){
        toaster.error('Product is required');
    }else{
        totalWorkOrder();
    form.cus_id=customer.id;
    form.products=productList.value;
    form.total=calculate.total;
    form.payable=calculate.payable;
    form.total_work_order=totalOrder.value;

    form.post('create-invoice',{
        onSuccess: () => {

            if(page.props.flash.status===true){
                form.reset();
                productList.value=[];
                calculate.total=0;
                calculate.payable=0;
                toaster.success(page.props.flash.message);
                setTimeout(() => {
                    router.get("/invoice-page");
                },500);
            }
            else {
                toaster.error(page.props.flash.message)

            }
        }
    })
}
}

</script>
