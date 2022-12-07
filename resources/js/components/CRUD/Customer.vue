<template>
    <div class="container-fluid p-0">

        <div class='row'>
      <div class='col-md-9' style="float:left">
        <p class="title ml-2 mb-0">Danh sách khách hàng </p>
      </div>
      <div class='col-md-2 text-right'>
        <router-link to="/customer">Khách hàng</router-link>
      </div>
    </div>
    
    
 
    <hr class='mt-0' style="height: 10px" color="#99d6ff">
    <div class="row ml-5">
        <div class="row ml-5">
            <div class="alert alert-success alert-success col-md-12  ml-5 text-center" role="alert" style="width:40%;" hidden></div>

        </div>
        <div class="row ml-5">
            <div class="alert alert-danger col-md-12 ml-5 text-center import-alert" role="alert" style="width:40%;" hidden>
                Các dòng bị lỗi khi import
            </div>

</div>
         

      <div class="alert alert-danger alert-error col-md-6 ml-5 text-center" role="alert" style="width:40%;" hidden></div>

    </div>
    
    

        <div class="form-row">
            <div class="col-md-2 mb-3 ml-2 col-lg-2">
              <label for="name">Tên</label>
              <input type="text" class="form-control" name="name" id="name" placeholder="Nhập họ tên"
              v-model="inputName">
            </div>

            <div class="col-md-2 mb-3 ml-6 col-lg-2">
              <label for="email">Email</label>
              <input type="text" class="form-control" name="email" id="email" placeholder="Nhập email"
              v-model="inputEmail">     
            </div>

            <div class="col-md-2 mb-3 ml-6 col-lg-2">
              <label for="status">Trạng thái</label> 
              <select class="custom-select" id="status" name="status" v-model="selectedStatus">
                <option selected hidden value=''> Chọn trạng thái</option>
                  <option value="1">Đang hoạt động</option>
                  <option value="0">Tạm khóa</option>
              </select>
            </div>

            <div class="col-md-2 mb-2 ml-6 col-lg-2">
              <label for="name">Địa chỉ</label>
              <input type="text" class="form-control" name="address" id="address" placeholder="Nhập địa chỉ"
              v-model="inputAddress">
            </div>
        </div>

    <div class="form-row">
     
        <form  id="import" action="/customer-import" method="POST" enctype="multipart/form-data" class="form-horizontal">
 
          <input id="import_excel" name="import_excel" type='file' @change="importFile" style="display: none;"/>
          <input type="submit" hidden>
          <input type="hidden" name="_token" id="_token1" />
       </form>
        <div class="col-md-7 ml-2 col-lg-7 float-left">
            <!-- <button class="btn btn-primary btn-open-add-modal" data-toggle="modal" data-target="#addmodal"><i class="fa fa-user-plus mr-2"></i>Thêm mới</button> -->
            <button class="btn btn-success ml-3 btn-import" for="#import_excel" @click="importExcel"><i class="fa fa-download mr-2"></i>Import CSV</button>
            
            <button  type="submit" class="btn btn-success ml-3 btn-export" @click="exportExcel"><i class="fa fa-upload mr-2"></i>Export CSV</button>
        </div>

 
        <div class='col-md-12 text-right col-lg-4'>
            <button class="btn btn-primary ml-auto mr-5 btn-search" @click="search"><i class="fa fa-search  mr-2"></i>Tìm kiếm</button>
            <button class="btn btn-success ml-auto btn-reset-search" @click="clearInput"><i class="fa fa-times  mr-2"></i>Xóa tìm</button>
        </div>
    </div>

    <form id="export" action="/customer-export" method="post">
              <input type="text" class="form-control" name="name" 
              :value="inputName" hidden>       
              <input type="text" class="form-control" name="email" 
              :value="inputEmail" hidden>               
              <input type="text" class="form-control" name="status" 
              :value="selectedStatus" hidden>                  
              <input type="text" class="form-control" name="address" 
              :value="inputAddress" hidden>
              <input type="hidden" name="_token" id="_token2" />
    </form>

    <div class='mt-3 row'>
      <div class="col-md-12 pagination justify-content-center col-lg-9" id="pagination">
        <pagination v-model="page" :records="total" :per-page="numberCustomerPerPage" @paginate="getListCustomers"/>
      </div>
        <p  class="numberOfTotal col-md-12 text-right  col-lg-3" >
            Hiển thị từ {{about}} trong tổng số {{total}} khách hàng
        </p>

    </div>



<table class="table table-striped">
    <thead style="background-color: orangered; color:white">
      <tr>
        <th scope="col">#</th>
            <th scope="col">Họ tên</th>
            <th scope="col">Email</th>
            <th scope="col">Địa chỉ</th>
            <th scope="col">Điện thoại</th>
            <!-- <th scope="col"></th> -->
      </tr>
    </thead>
    <tbody id="data-table">
      <span hidden>{{i = numberCustomerPerPage * (page - 1)}}</span>
        <tr v-if="(listCustomers.length <= 0)">
                <td colspan="6" class="text-center">Không có khách hàng</td>
        </tr>
     
        <tr v-else v-for="(customer,index) in listCustomers" :key="customer.id">
               <th scope="row">{{ ++i }}</th>
               <td>{{ customer.customer_name }}</td>
               <td>{{ customer.email }}</td>
               <td>{{ customer.address }}</td>
               <td>{{ customer.tel_num }}</td>
               <!-- <td>

                
<button class="btn btn-success">
  Edit
</button>
</td> -->

</tr>
    </tbody>
  </table>

    <div class='mt-3 row'>
      <div class="col-md-12 pagination justify-content-center col-lg-9" id="pagination">
        <pagination v-model="page" :records="total" :per-page="numberCustomerPerPage" @paginate="getListCustomers"/>
      </div>
    </div>
</div>



</template>

<script>
    export default {
    data() {
       return {
            customer: {
               customerName: '',
               email: '',
               address: '',
               status: '',
            },
            i: 0,
            total: 0,
            numberCustomerPerPage: 0,
            to: 0,
            from: 0,
            page: 1,
            inputName: '',
            inputEmail: '',
            selectedStatus: '',
            inputAddress: '',  

            listCustomers: [],
            error: null,
            msg: null,
            file: '',
       }
   },
   created() {
       this.getListCustomers()
   },
   computed: {
        about() {
            return this.from + '~' + this.to
          }
    },
   methods: {

       async getListCustomers() {
           try {
               const response = await axios.get(`http://127.0.0.1:8000/customer-list?page=${this.page}&name=${this.inputName}&email=${this.inputEmail}&address=${this.inputAddress}&status=${this.selectedStatus}`)

               this.loadCustomer(response);
           } catch (error) {
               this.error = error.response.data
           }         
        },
        async search() {
     
                const response = await axios.get(`http://127.0.0.1:8000/customer-list?page=${this.page}&name=${this.inputName}&email=${this.inputEmail}&address=${this.inputAddress}&status=${this.selectedStatus}`)
                  
                this.loadCustomer(response);
                     
        },
        async clearInput() {
            this.inputName = '';
            this.inputEmail = '';
            this.inputAddress = '';
            this.selectedStatus = ''; 
            this.page = 1;
            this.getListCustomers();
            this.hiddenAlert();
        },

        async exportExcel() {
            $('#_token2').val($('meta[name="csrf-token"]').attr('content'));
            document.getElementById("export").submit();
        },
        async importExcel() {
            $('#import_excel').click();

        },
        async importFile(){
            try{
                this.hiddenAlert();
                let formData = new FormData();
                formData.append("import_excel", $('#import_excel')[0].files[0]);  
                formData.append("_token", $('meta[name="csrf-token"]').attr('content'));    

                const response = await axios.post('http://127.0.0.1:8000/customer-import', 
                        formData            
                    );
                this.showAlert('success', 'Import thành công');
                
                var error = Object.keys(response.data.error_row).length;
                
                if(error > 0){
                    $.each(response.data.error_row, function(key, value) {
                        $(`.import-alert`).attr('hidden', false);         
                        $(`.import-alert`).append(
                        ` <li>Dòng ${key}:  ${value}</li>`
                        );                                     
                    });
                }
                this.page = 1;
                this.getListCustomers();
            }catch(error){
                $.each(error.reponse.data.errors, function(key, value) {
            
                    showAlert('error', value);
                          
                });
            }

        },
        async loadCustomer(response){
                   this.listCustomers = response.data.data;
                   this.total = response.data.count;
                   this.numberCustomerPerPage = response.data.numberCustomerPerPage;
                   var length = Object.keys(response.data.data).length;

                   var offset = this.numberCustomerPerPage * (this.page -1 );
                   this.from = offset + 1;
                   if(length === 0){
                     this.from = offset ;
                   }                  
                   this.to = offset + length;
        },
        showAlert(type, text){
                $(`.alert-${type}`).text(text);
                $(`.alert-${type}`).attr('hidden', false);
        },
        hiddenAlert()
        {
            $(`.alert-error`).text('');
            $(`.alert-error`).attr('hidden', true);
            $(`.alert-success`).text('');
            $(`.alert-success`).attr('hidden', true);
        }

   }
}
</script>

<style scoped>

</style>