<template>
<div class='row'>
          <div class='col-md-8' style="float:left">
            <p class="title ml-2 mb-0">Chi tiết sản phẩm</p>
          </div>
          <div class='col-md-3 text-right'>
           <router-link to="/product">Sản phẩm</router-link> > {{$title ?? ''}}
          </div>
        </div>
        
        
     
        <hr class='mt-0' style="height: 10px" color="#99d6ff">
    
        <div class='container-fluid pl-5 pr-5'>
            <!-- <form method="POST" enctype="multipart/form-data" class="form-horizontal" id="product_form"
            > -->
            <input type="hidden" name="_token" id="_token" />
              <input hidden name="product_id" id="product_id">
                <div class='col-md-6 col-lg-7' style="float:left">
                    <div class="form-group row">
                        <label for="product_name" class="col-md-4 col-form-label col-lg-2">Tên sản phẩm</label>
                        <div class="col-md-8 col-lg-9">
                          <input type="text" class="form-control" name="product_name" id="product_name" 
                          v-model="product.productName"
                          placeholder="Nhập tên sản phẩm" required="">
              
       
                          
                            <div style="color:red" hidden id="error_product_name"></div>
      
                          
              
                        </div>
                      </div>
    
                      <div class="form-group row">
                        <label for="product_price" class="col-md-4 col-form-label  col-lg-2">Giá bán</label>
                        <div class="col-md-8  col-lg-9">
                          <input type="number" class="form-control" min="0" name="product_price" id="product_price" 
                          v-model="product.productPrice"
                          placeholder="Nhập giá bán" required="">
                      

                          
                            <div style="color:red" hidden id="error_product_price"></div>
                          
                  
              
                        </div>
                      </div>

                      <div class="form-group row">
                        <label for="description" class="col-md-4 col-form-label col-lg-2">Mô tả</label>
                        <div class="col-md-8 col-lg-9">
                          <textarea class="form-control pt-5" name="description" id="editor" 
                          v-model="product.productDescription"
                          placeholder="Mô tả sản phẩm" style="text-align:left;" rows="5" cols="50" ></textarea>
              
                          <div style="color:red" hidden id="error_description"></div>
              
                        </div>
                      </div>

                      <div class="form-group row">
                        <label for="status" class="col-sm-4 col-form-label col-lg-2">Trạng thái</label> 
                        <div class="col-sm-8 col-lg-9">
                        <select class="form-control" id="is_sales" name="is_sales"
                        v-model="product.status"
                        required="">
                            <option selected hidden value='' ></option>            
                            <option value="0" 
                            >Dừng bán</option>
                            <option value="1"
                            >Đang bán</option>
                            <option value="2" 
                            >Hết hàng</option>
                        </select>
                        
                        <div style="color:red" hidden id="error_is_sales"></div>

                        </div>
                      </div>

                </div>
    
                <div class='col-md-6 col-lg-5' style="float:left">
                  <div class="form-group row">
                    <div class="form-group">
                      <label for="product_image" >Hình ảnh</label>
                      <div>
                        <!-- <img class="ml-5" src="{{ asset($data->product_image ?? 'upload/product/default-thumbnail.jpg') }}" id="f"/> -->
                        <img class="ml-5" 
                        :src="(product.productImage === '') ? '../upload/product/default-thumbnail.jpg' : product.productImage" 
                        id="f"/>
                      </div>
                      </div>
                      <div class="col-md-12 pl-0  col-lg-12">
                          <a class='btn btn-primary btn-upload p-1' @click="uploadImg">Upload</a>
                          <a class='btn btn-danger btn-delete-file p-1 btn-file' @click="deleteUpload">Xóa file</a>
                          <input type="text" id="file_name" placeholder="tên file upload" class="btn-file"
                          v-model="fileName" readonly>
                      </div>

                      <div style="color:red" hidden id="error_product_image"></div>

                      <input type="file" class="form-control-file" id="product_image" name="product_image"
                      oninput="f.src=window.URL.createObjectURL(this.files[0])" hidden @change="getFileData">
                    </div>
                </div>
                <input hidden type="text" id="scr-img" :value="(this.product.productImage === '') ? '../upload/product/default-thumbnail.jpg' : this.product.productImage">
                <div class="col-md-12 mt-5 text-right col-lg-10" style="float:left">
                  <router-link to="/product" type="button" class="btn btn-secondary mb-2 mr-5 btn-lg btn-cancel">Hủy</router-link>
                  <button class="btn mb-2 ml-4 btn-lg btn-send-form" @click="submitForm">Lưu</button>
                </div>                
            <!-- </form>
        -->
        </div>

</template>


<script>

export default {
    data() {
           return {
              productId: '',
              product: {
                productName: '',
                productPrice: '',
                productDescription: '',
                status: '',
                productImage: '',
              },
              error: {
                product_name_msg: '',
                product_name_hidden: true,

              },
              fileName: ''
           }
       },
       created() {
          this.getProductId(),
          this.loadProduct()
       },
       methods: {
            async loadProduct() {
              try{
                if(this.productId !== ''){
                      const response = await axios.get(`http://127.0.0.1:8000/product-edit/${this.productId}`);
                      let data = response.data.data
                      this.product.productName = data.product_name;
                      this.product.productPrice = data.product_price;
                      this.product.productDescription = data.description;
                      this.product.status = data.is_sales;
                      this.product.productImage = '/../'+data.product_image;
                }
              } catch(error){

              }
                
            },
            async submitForm() {
                $('#_token').val($('meta[name="csrf-token"]').attr('content'));
                this.hiddenAlert()
                let formData = new FormData();
                formData.append("product_id", this.productId); 
                formData.append("_token", $('meta[name="csrf-token"]').attr('content')); 
                formData.append("product_name", this.product.productName);
                formData.append("product_price", this.product.productPrice);
                formData.append("description", this.product.productDescription);
                formData.append("is_sales", this.product.status);   
                if($('#product_image')[0].files[0] !== undefined){
                  formData.append("product_image", $('#product_image')[0].files[0]);
                }
                if(this.productId === ''){
                  try {
                    const response = await axios.post(`http://127.0.0.1:8000/product-add`,
                    formData);
                    this.alertAndRender(response.data.msg)   
                  } catch (error) {
                    $.each(error.response.data.errors, function(key, value) {
                      var name = key;
                        $.each(value,  function(key, value){
                          $(`#error_${name}`).text(value);
                          $(`#error_${name}`).attr('hidden', false);
                    }); 
                    });
                  
                  }
                  return
                }  

                try {
                    const response = await axios.post(`http://127.0.0.1:8000/product-edit`,
                    formData); 
                    this.alertAndRender(response.data.msg)   
                  } catch (error) {
                    $.each(error.response.data.errors, function(key, value) {
                      var name = key;
                    $.each(value,  function(key, value){
                        $(`#error_${name}`).text(value);
                        $(`#error_${name}`).attr('hidden', false);
                    }); 
                    });
                  
                  }                           
            },

            getFileData(){
                var file = $('#product_image')[0].files[0];  
                var filename = file.name;
                $('#file_name').val(filename);
            },
            uploadImg() {
                $('#product_image').click()
            },
            deleteUpload() {
              $('#product_image').val('');
              $('#file_name').val('');
              var src = $('#scr-img').val();
              $('#f').attr('src' , src); 
            },
            getProductId() {
                this.productId = this.$route.params.productId;
            },
            alertAndRender(msg) {
                  Swal.fire({
                        position: 'center',
                        icon: 'success',
                        title: msg,
                        showConfirmButton: false,
                        timer: 1500
                      })
                      setTimeout(() => {
                        this.$router.push('/product');
                      },1550);     
            },
            hiddenAlert(){
              $(`#error_product_name`).attr('hidden', true);
              $(`#error_description`).attr('hidden', true);
              $(`#error_is_sales`).attr('hidden', true);
              $(`#error_product_image`).attr('hidden', true);
              $(`#error_product_price`).attr('hidden', true);
            }
       }
}




</script>



<style scoped>
    .title{
        font-weight: bold;
        
    }

    img {
    height: 300px;
    width: 300px;

  }
  .btn{
    color:white !important;
    font:bold;
    padding-left:30px !important;
    padding-right:30px !important;
  }
  .btn-send-form{
    background-color: orangered;
    color:white;
   
  }
  .btn-file{
    margin-left:-5px;
  } 
</style>