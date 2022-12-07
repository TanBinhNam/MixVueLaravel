<template>
<div class=' row'>
      <div class='col-md-9' style="float:left">
        <p class="title ml-2 mb-0">Danh sách sản phẩm </p>
      </div>
      <div class='col-md-2 text-right'>
        <router-link class=" ml-2 mb-0" to="/product">Sản phẩm </router-link>
      </div>
    </div>
    
    
 
    <hr class='mt-0' style="height: 10px" color="#99d6ff">
    <div class="row ml-5">
      <!-- @if (session('msg'))         
                <div class="alert alert-success col-md-6  ml-5 text-center" role="alert" style="width:40%;">          
                  {{ session('msg') }}    
                </div>                 
      @endif -->
      <div class="alert alert-danger alert-error col-md-6 ml-5 text-center" role="alert" style="width:40%;" hidden></div>
    </div>
    
    
    <form id="search" method="post">
        <div class="form-row">
          
            <div class="col-md-2 mb-3 ml-2 col-lg-2">
              <label for="product_name">Tên sản phẩm</label>
              <input type="text" class="form-control" name="product_name" id="product_name" 
              placeholder="Nhập tên sản phẩm" 
              v-model="inputName">
            </div>

            <div class="col-md-2 mb-3 ml-3 col-lg-2">
              <label for="status">Trạng thái</label> 
              <select class="form-control" id="status" name="status" 
              v-model="selectedStatus">
                <option selected hidden value=''> Chọn trạng thái</option>
                <option v-for="(each,index) in status" 
                :value="index" 
                :selected="(selectedStatus === each)">{{ each }}</option>
              </select>
            </div>

            <div class="col-md-6 mb-2 ml-5 col-lg-6">

            <div class="col-md-4 mb-2 input_price col-lg-3" style="float: left">
              <label for="price_from">Giá bán từ</label>
              <input type="text" class="form-control" size="4" name="price_from" id="price_from"
              v-model="inputPriceFrom">
            
             
            </div>
     
            <div class="col-md-2 text-center col-lg-1" style="float: left;margin-top:32px;padding-left:15px">
              ~ 
            </div>

            <div class="col-md-4 mb-2 input_price col-lg-3" style="float: left">
              <label for="price_to">Giá bán đến</label>
              <input type="text" class="form-control" size="4" name="price_to" id="price_to"
               v-model="inputPriceTo">
             
            </div>

            </div>
            
            
          </div>

        </form>

    <div class="form-row">

        <div  class="col-md-6 ml-2 col-lg-7 float-left">
            <router-link class="btn btn-primary" to="/product/product-form" style="color:white"> 
                <i class="fa fa-user-plus mr-2"></i>Thêm mới
            </router-link>       
        </div>

 
        <div class='col-md-5 text-right col-lg-4'>
            <button class="btn btn-primary ml-auto mr-5 btn-search" @click="search"><i class="fa fa-search  mr-2"></i>Tìm kiếm</button>
            <button class="btn btn-success ml-auto btn-reset-search" @click="clearInput"><i class="fa fa-times  mr-2"></i>Xóa tìm</button>
        </div>
    </div>

    <div class='mt-3 row'>
      <div class="col-md-6 pagination justify-content-center col-lg-9" id="pagination">
      
      </div>
      <p   class="numberOfTotal col-md-6 text-right float-right col-lg-3" ></p>

    </div>
    
    <table class="table table-striped">
        <thead style="background-color: orangered; color:white">
          <tr>
            <th scope="col">#</th>
            <th scope="col">Tên sản phẩm</th>
            <th scope="col">Mô tả</th>
            <th scope="col">Giá</th>
            <th scope="col">Tình trạng</th>
            <th scope="col"></th>
          </tr>
        </thead>
        <tbody id="data-table">
            <tr v-if="(listProducts.length <= 0)">
                <td colspan="6" class="text-center">Không có sản phẩm</td>
            </tr>
            <tr v-else v-for="(product,index) in listProducts" :key="product.product_id">
                   <th scope="row">{{ product.product_id }}</th>
                   <td>{{ product.product_name }}
                    <img :src="product.product_image" />
                  </td>
                  
                   <td ><span v-html="product.description"></span></td>
                   <td>{{ product.product_price }}</td>
                   <td v-if="(product.is_sales === 0)" style="color: red">Ngừng bán</td>
                   <td v-else-if="(product.is_sales === 1)" style="color: green">Đang bán</td>
                   <td v-else style="color: green">Hết hàng</td>
                   <td>

<a class="icon-CRUD edit_user" style="color:#009999" id="edit" @click="editProduct(product.product_id)"><i class="fa fa-pencil" ></i></a>

                    
    </td>

    </tr>
        </tbody>
      </table>

      <div class='mt-3 row'>
        <div class="col-md-9 pagination justify-content-center">
           
          </div>
                  
    </div>
</template>

<script>
    export default {
    data() {
       return {

            total: 0,
            numberCustomerPerPage: 0,
            to: 0,
            from: 0,
            page: 1,
            inputName: '',
            inputEmail: '',
            selectedStatus: '',
            inputAddress: '',  

            listProducts: [],
            status: [],
            inputName: '',
            inputPriceFrom: 0,
            inputPriceTo: 0,
            selectedStatus: '',   
            error: null,
            msg: null,
            page: 1,

       }
   },
   created() {
       this.getListProducts(),
       this.getStatus()
   },
   computed: {
        about() {
            return this.from + '~' + this.to
          }
    },
   methods: {

       async getListProducts() {
           try {
               const response = await axios.get(`http://127.0.0.1:8000/product-list?page=${this.page}&product_name=${this.inputName}&status=${this.selectedStatus}&price_from=${this.inputPriceFrom}&price_to=${this.inputPriceTo}`)

               this.loadProduct(response);
           } catch (error) {
               this.error = error.response.data
           }         
        },
        async search() {
                
                const response = await axios.get(`http://127.0.0.1:8000/product-list?page=${this.page}&name=${this.inputName}&status=${this.selectedStatus}&price_from=${this.inputPriceFrom}&price_to=${this.inputPriceTo}`)
                  
                this.getListProducts(response);
                     
        },
        async getStatus() {
               try {
                   const response = await axios.get('http://127.0.0.1:8000/product-status')
                   this.status = response.data.status          
               } catch (error) {   
              
               }
              
        },
        async clearInput() {
            this.inputName = '';
            this.inputPriceFrom = '';
            this.inputPriceTo = '';
            this.selectedStatus = ''; 
            this.page = 1;
            this.getListProducts();
        },

        async editProduct(productId) {
            this.$router.push('/product/product-form/'+productId);
        },

  
        async loadProduct(response){
                   this.listProducts = response.data.data;
                   this.total = response.data.count;
                   this.numberProductPerPage = response.data.numberProductPerPage;
                   var length = Object.keys(response.data.data).length;

                   var offset = this.numberProductPerPage * (this.page -1 );
                   this.from = offset + 1;
                   if(length === 0){
                     this.from = offset ;
                   }                  
                   this.to = offset + length;
        },


   }
}
</script>

<style scoped>
.VuePagination__count {
       font-size: 0;
    }
    .title{
        font-weight: bold;        
    }

    img {
    height: auto;
    width: 300px;
    display: none
  }
  td:hover img {
    display: block;
  position: absolute;
    z-index: 2
  }
  .input_price{
    margin-right: -25px;
    margin-left: -25px;
  }
</style>