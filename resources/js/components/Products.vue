<template>  
    <div>
        <h2>Products</h2>

        <div class="row">
            <div class="col-md-6">
                <form @submit.prevent="addProduct" class="mb-3">
                    <div class="form-group">
                        <input type="text" class="form-control" placeholder="Name" v-model="product.name">
                    </div>
                    <div class="form-group">
                        <input type="text" class="form-control" placeholder="Price" v-model="product.price">
                    </div>
                    <button type="submit" class="btn btn-light btn-block">Save</button>
                </form>
            </div>
            <div class="col-md-6">
                <form @submit.prevent="fetchProducts(null,search.product_name)" class="mb-3">
                    <div class="form-group">
                        <input type="text" class="form-control" placeholder="Name" v-model="search.product_name">
                    </div>
                    <button type="submit" class="btn btn-light btn-block">Search</button>
                </form>
            </div>
        </div>
        
        <nav aria-label="Page navigation example">
            <ul class="pagination">
                <li v-bind:class="[{disabled: !pagination.prev_page_url}]" class="page-item"><a class="page-link" href="#" @click="fetchProducts(pagination.prev_page_url)">Previous</a></li>

                 <li class="page-item disabled"><a class="page-link text-dark" href="#">Page {{pagination.current_page}} of {{pagination.last_page}}</a></li>

                <li v-bind:class="[{disabled: !pagination.next_page_url}]" class="page-item"><a class="page-link" href="#" @click="fetchProducts(pagination.next_page_url)">Next</a></li>
            </ul>
        </nav>
        <div class="card card-body mb-2" v-for="product in products" v-bind:key="product.id">
            <h3>{{product.name}}</h3>
            <p>{{product.price}}</p>
            <hr>
            <button @click="editProduct(product)" class="btn btn-warning mb-2">Edit</button>
            <button @click="deleteProduct(product.id)" class="btn btn-danger">Delete</button>
        </div>
    </div>
</template>

<script>
 export default {
     data() {
         return {
             products : [],
             product : {
                 id: '',
                 name: '',
                 price: ''
             }, 
             search :
             {
                 product_name: ''
             },
             product_id: '',
             pagination: {},
             edit: false
         }
     },

     created(){
         this.fetchProducts();
     },

     methods: {
         fetchProducts(page_url, name){
             let vm = this;

             page_url = page_url || '/api/products';
           

             if(name != null && name != ''){
                 page_url = 'api/products/search/'+name;
             }
            
             fetch(page_url)
                .then(res => res.json())
                .then(res => {
                    this.products = res.data;
                    vm.makePagination(res.meta, res.links);
                })
                .catch(err => console.log(err));
         },
         
         makePagination(meta, links){
             let pagination = {
                 current_page: meta.current_page,
                 last_page: meta.last_page,
                 next_page_url: links.next,
                 prev_page_url: links.prev
             }

             this.pagination = pagination;
         },
         deleteProduct(id) {
            if (confirm('Are You Sure?')) {
                fetch(`api/product/${id}`, {
                method: 'delete'
                })
                .then(res => res.json())
                .then(data => {
                    alert('Product Removed');
                    this.fetchProducts();
                })
                .catch(err => console.log(err));
            }
        },
        addProduct() {
            if (this.edit === false) {
                // Add
                fetch('api/product', {
                method: 'post',
                body: JSON.stringify(this.product),
                headers: {
                    'content-type': 'application/json'
                }
                })
                .then(res => res.json())
                .then(data => {
                    this.clearForm();
                    alert('Product Added');
                    this.fetchProducts();
                })
                .catch(err => console.log(err));
            } else {
                // Update
                fetch('api/product', {
                method: 'put',
                body: JSON.stringify(this.product),
                headers: {
                    'content-type': 'application/json'
                }
                })
                .then(res => res.json())
                .then(data => {
                    this.clearForm();
                    alert('Product Updated');
                    this.fetchProducts();
                })
                .catch(err => console.log(err));
            }
        },
        editProduct(product) {
            this.edit = true;
            this.product.id = product.id;
            this.product.product_id = product.id;
            this.product.name = product.name;
            this.product.price = product.price;
        },
        clearForm() {
            this.edit = false;
            this.product.id = null;
            this.product.product_id = null;
            this.product.name = '';
            this.product.price = '';
        }
     }
 };
</script>
