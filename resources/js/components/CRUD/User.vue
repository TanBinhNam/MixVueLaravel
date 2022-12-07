<template>
     <div class="title ml-2">
        Users
    </div>
    
    <hr class='mt-0' style="height: 10px" color="#99d6ff">
    <div class="row ml-5">
      <div class="alert alert-danger alert-error col-md-6 ml-5 text-center" role="alert" style="width:40%;" hidden></div>
      <div class="alert alert-success alert-success col-md-6  ml-5 text-center" role="alert" style="width:40%;" hidden></div>
    </div>
    
    
 
        <div class="form-row">
            <div class="col-md-2 mb-3 ml-2 col-lg-2">
              <label for="name">Tên</label>
              <input type="text" class="form-control" name="name" id="name" placeholder="Nhập họ tên" v-model="inputName">
     
            </div>
            <div class="col-md-2 mb-3 ml-6 col-lg-2">
              <label for="email">Email</label>
              <input type="text" class="form-control" name="email" id="email" placeholder="Nhập email" v-model="inputEmail">
       
            </div>
            <div class="col-md-2 mb-3 ml-6 col-lg-2">
                <label for="group_role">Nhóm</label>
                <select class="custom-select" id="group_role" name="group_role" v-model="selectedGroupRole">
                  <option selected hidden value=''>Chọn nhóm</option>
      
                  <option v-for="groupRole in groupRole" v-bind:value="groupRole" :selected="selectedGroupRole === groupRole">{{ groupRole }}</option>

                </select>
            </div>
            <div class="col-md-2 mb-2 ml-6 col-lg-2">
                <label for="status">Trạng thái</label> 
                <select class="custom-select" id="status" name="status" v-model="selectedActive">
                  <option selected hidden value=''> Chọn trạng thái</option>
                  <option v-for="active in active" :value="active" :selected="(selectedActive === active)">
                    <template v-if="(active === 1)">
                      Đang hoạt động
                    </template>
                    <template v-else>
                      Tạm khóa
                    </template>
                  </option>
                    <!-- <option value="{{$each}}">@if($each === 1) {{'Đang hoạt động'}} @else {{'Tạm khóa'}} @endif</option> -->
       
                </select>
              </div>
          </div>

  

    <div class="form-row">
        <div class="col-md-6 ml-2 col-lg-7 float-left">
            <button class="btn btn-primary" @click="openModalAdd()"><i class="fa fa-user-plus mr-2" ></i>Thêm mới</button>
        </div>
        <div class='col-md-5 text-right col-lg-4'>
            <button class="btn btn-primary ml-auto mr-3 btn-search" @click="search()"><i class="fa fa-search  mr-2"></i>Tìm kiếm</button>
            <button class="btn btn-success ml-auto btn-reset-search" @click="clearInput()"><i class="fa fa-times  mr-2"></i>Xóa tìm</button>
        </div>
    </div>

    <div class='mt-3 row'>
          <div  class="col-md-12 pagination justify-content-center col-lg-9 ">
            <pagination v-model="page" :records="total" :per-page="numberUserPerPage" @paginate="getListUsers"/>
          </div>
            

      
          <p  class="numberOfTotal col-md-12 text-right  col-lg-3" >
            Hiển thị từ {{about}} trong tổng số {{total}} thành viên
          </p>
    
    </div>


	    <div class="container-fluid p-0">
    <table class="table table-striped">
        <thead style="background-color: orangered; color:white">
          <tr>
            <th scope="col">#</th>
            <th scope="col">Họ tên</th>
            <th scope="col">Email</th>
            <th scope="col">Nhóm</th>
            <th scope="col">Trạng thái</th>
            <th scope="col"></th>
          </tr>
        </thead>
        <tbody id="data-table">
          <span hidden>{{i = numberUserPerPage * (page - 1)}}</span>
            <tr v-if="(listUsers.length <= 0)">
                <td colspan="6" class="text-center">Không có thành viên</td>
            </tr>
            <tr v-else v-for="(user,index) in listUsers" :key="user.id">
                   <th scope="row">{{ ++i }}</th>
                   <td>{{ user.name }}</td>
                   <td>{{ user.email }}</td>
                   <td>{{ user.group_role }}</td>
                   <td style="color: green" v-if="user.is_active === 1">Đang hoạt động</td>
                   <td style="color: red" v-else>Tạm khóa</td>
                   <td>

<a class="icon-CRUD edit_user" style="color:#009999" id="edit" @click="openModalEdit(index)"><i class="fa fa-pencil" ></i></a>

<a class="icon-CRUD delete_user" style="color:red" id="delete" @click="openModalDelete(index)"><i class="fa  fa-trash"></i></a>

<a class="icon-CRUD change_status_user" style="color:black" id="lock_unlock" @click="openModalChangeStatus(index)"><i class="fa fa-user-times"></i></a>
                    
    </td>

    </tr>
        </tbody>
      </table>

    </div>

    <div class='mt-3 row'>
          <div  class="col-md-12 pagination justify-content-center col-lg-9 ">
            <pagination v-model="page" :records="total" :per-page="numberUserPerPage" @paginate="getListUsers"/>
          </div>
      </div>
       <!--Add Modal-->
       <div class="modal fade" id="addmodal">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header" style="background-color:#b3b3cc">
              <h5 class="modal-title" id="exampleModalLabel" >Thêm User</h5>
            </div>
             <form id="addform">
            <div class="modal-body">
              


              <div class="form-group row">
                <label for="add_name" class="col-sm-3 col-form-label">Tên</label>
                <div class="col-sm-9">
                  <input type="text" class="form-control" name="add_name" id="add_name" 
                  v-model="userCreate.addName" 
                  placeholder="Nhập họ tên" required="">
     
                  <div class="error_add_name" >
                  </div>

                </div>
              </div>
              <div class="form-group row">
                <label for="add_email" class="col-sm-3 col-form-label">Email</label>
                <div class="col-sm-9" required>
                  <input required type="text" class="form-control" name="add_email" id="add_email" 
                  v-model="userCreate.addEmail" 
                  placeholder="Nhập email" >
            
                  <div class="error_add_email" >            
                  </div>

                </div>
              </div>

              <div class="form-group row">
                <label for="add_password" class="col-sm-3 col-form-label">Mật khẩu</label>
                <div class="col-sm-9">
                  <input type="password" class="form-control" name="add_password" id="add_password"
                  v-model="userCreate.addPassword"  
                  placeholder="Mật khẩu" required>

                  <div class="error_add_password" >
                  </div>
                  
                 
                </div>
              </div>
              <div class="form-group row">
                <label for="confirmpassword" class="col-sm-3 col-form-label">Xác nhận</label>
                <div class="col-sm-9">
                  <input type="password" class="form-control" required name="add_password_confirmation" id="add_password_confirmation"
                  v-model="userCreate.addConirmPassword"
                  placeholder="Xác nhận mật khẩu" >
                </div>
              </div>

              <div class="form-group row">
                <label for="group_role"  class="col-sm-3 col-form-label">Nhóm</label>
                <div class="col-sm-9">
                  <select class="form-control" id="add_group_role" name="add_group_role" 
                  v-model="userCreate.addGroupRole" required>
                    <option selected hidden value=''>Chọn nhóm</option>
                    <option v-for="each in groupRole" :value="each" :selected="(userCreate.addGroupRole === each)">
                              {{each}}
                    </option>
                  </select>
                  <div class="error_add_group_role m-auto" >
                 
                  </div>
                </div>
                
   
              </div>
              <div class="form-group row">
                <div class="col-sm-3">Trạng thái</div>
                <div class="col-sm-9">
                  <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="add_status" id="add_status"
                    v-model="userCreate.addStatus" 
                    > 
              
                   
                  </div>
                </div>
              </div>

            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary mr-5 btn-lg " @click="closeModalAdd">Hủy</button>
              <button type="button" class="btn btn-primary btn-add-user btn-lg" style="background-color: orangered" @click="submitModalAdd()" >Lưu</button>
            </div>
                      </form>
          </div>
        </div>
      </div>
      <!---->

      <!--Edit Modal-->
 <div class="modal fade" id="editmodal">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header" style="background-color:#b3b3cc">
        <h5 class="modal-title" id="exampleModalLabel" >Chỉnh sửa User</h5>
      </div>
       <form id="editform">
      <div class="modal-body">
    
        <input type="hidden" name="edit_id" id="edit_user_id" 
        v-model="userEdit.editId"> 
        <div class="form-group row">
          <label for="add_name" class="col-sm-3 col-form-label">Tên</label>
          <div class="col-sm-9">
            <input type="text" class="form-control" name="edit_name" id="edit_name" 
            v-model="userEdit.editName" 
            placeholder="Nhập họ tên" required="">

            <div class="error_edit_name" >
            </div>

          </div>
        </div>
        <div class="form-group row">
          <label for="add_email" class="col-sm-3 col-form-label">Email</label>
          <div class="col-sm-9" required>
            <input required type="text" class="form-control" name="edit_email" id="edit_email" 
            v-model="userEdit.editEmail" 
            placeholder="Nhập email" >
      
            <div class="error_edit_email" >            
            </div>

          </div>
        </div>

        <div class="form-group row">
          <label for="add_password" class="col-sm-3 col-form-label">Mật khẩu</label>
          <div class="col-sm-9">
            <input type="password" class="form-control" name="edit_password" id="edit_password"
            v-model="userEdit.editPassword"  
            placeholder="Mật khẩu" required>

            <div class="error_edit_password" >
            </div>
                      
          </div>
        </div>

        <div class="form-group row">
          <label for="edit_password_confirmation" class="col-sm-3 col-form-label">Xác nhận</label>
          <div class="col-sm-9">
            <input type="password" class="form-control" required name="edit_password_confirmation" id="edit_password_confirmation" 
            v-model="userEdit.editConfirmPassword" 
            placeholder="Xác nhận mật khẩu" >
          </div>
        </div>


        <div class="form-group row">
          <label for="group_role"  class="col-sm-3 col-form-label mr-3">Nhóm</label>
          <select class="form-control col-sm-8" id="edit_group_role" name="edit_group_role" 
          v-model="userEdit.editGroupRole" 
          required>
            <option class="option-hidden" selected hidden value=''>Chọn nhóm</option>
            <option v-for="each in groupRole" :value="each" :selected="(userCreate.addGroupRole === each)">
                              {{each}}
            </option>
          </select>
          <div class="error_edit_group_role" >
         
          </div>

        </div>
        <div class="form-group row">
          <div class="col-sm-3">Trạng thái</div>
          <div class="col-sm-9">
            <div class="form-check">
              <input class="form-check-input" type="checkbox" name="edit_status" id="edit_status"
              v-model="userEdit.editStatus" >
             
            </div>
          </div>
        </div>

      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary btn-lg mr-5" @click="closeModalEdit">Close</button>
        <button type="button" class="btn btn-primary btn-edit-user btn-lg " @click="submitModalEdit" style="background-color: orangered" >Lưu</button>
      </div>
                </form>
    </div>
  </div>
</div>
<!---->

<!--Deleted Modal-->
      <div class="modal fade" id="deletedmodal">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="exampleModalLabel">Nhắc nhở</h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
              <form id="deleteUserForm">
               
                <input type="hidden" id="deleted_user_id" name="id"> 
              </form>
                
                <p class='modal-body-deleted'>Bạn có muốn xóa thành viên {{this.remind.name}} không?</p>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" @click="closeModalDelete">Close</button>
              <button type="button" class="btn btn-primary btn-deleted-user" @click="deleteUser">Xác nhận</button>
            </div>
          </div>
        </div>
      </div>

      <!--ChangeStatus Modal-->
      <div class="modal fade" id="changestatusmodal">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="exampleModalLabel">Nhắc nhở</h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">               
                <p class='modal-body-change-status' >Bạn có muốn <span :style="{color: statusColorStyle}">{{(this.remind.status === 1) ? 'Khóa' : 'Mở khóa'}}</span> thành viên {{this.remind.name}} không?</p>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" @click="closeModalChangeStatus">Close</button>
              <button type="button" class="btn btn-primary btn-change-status-user" @click="changStatus">Xác nhận</button>
            </div>
          </div>
        </div>
      </div>

   
</template>

<script>
	    export default {
        data() {
           return {
               userCreate: {
                addName: '',
                addEmail: '',
                addPassword: '',
                addGroupRole: '',
                addConirmPassword: '',
                addStatus: true,
               },
               userEdit: {
                editId: '',
                editName: '',
                editEmail: '',
                editPassword: '',
                editGroupRole: '',
                editConfirmPassword: '',
                editStatus: true,
               },

              total: 0,
              numberUserPerPage: 0,
              to: 0,
              from: 0,
              listUsers: [],
              groupRole: [],
              active: [],

              inputName: '',
              inputEmail: '',
              selectedGroupRole: '',
              selectedActive: '',   
              error: null,
              msg: null,
              page: 1,

              id: null,
              remind: {
                  name: '',
                  status: null,
              },

           }
       },
       created() {
           this.getListUsers(),
           this.getValue()
       },
       computed: {
          about() {
            return this.from + '~' + this.to
          },
          statusColorStyle() {
            return (this.remind.status === 1) ? 'red' : 'green'
          }
       },
       methods: {
           async getListUsers() {
               try {
                   const response = await axios.get(`http://127.0.0.1:8000/user-list?page=${this.page}&name=${this.inputName}&email=${this.inputEmail}&group_role=${this.selectedGroupRole}&status=${this.selectedActive}`);
                  
                   this.loadUser(response);
    
               } catch (error) {
                this.error = error.response.data
               }
              
          },
          async getValue() {
               try {
                   const response = await axios.get('http://127.0.0.1:8000/user-search')
                   this.groupRole = response.data.group_role
                   this.active = response.data.active
          
               } catch (error) {   
              
               }
              
          },
          async search() {
    
                const response = await axios.get(`http://127.0.0.1:8000/user-list?page=${this.page}&name=${this.inputName}&email=${this.inputEmail}&group_role=${this.selectedGroupRole}&status=${this.selectedActive}`)
                  
                this.loadUser(response);
          },
          async clearInput() {
              this.inputName = '';
              this.inputEmail = '';
              this.selectedGroupRole = '';
              this.selectedActive = ''; 
              this.page = 1;
              this.getListUsers();
          },
          async openModalAdd() {
            this.emptyErrorAlert('add');
            $('#addmodal').modal('show');
            
          },
          async closeModalAdd() {
            $('#addmodal').modal('hide');          
          },
          async submitModalAdd() {
            try {
                  const response = await axios.post('http://127.0.0.1:8000/user-add', {
                    add_name: this.userCreate.addName,
                    add_email: this.userCreate.addEmail,
                    add_password: this.userCreate.addPassword,
                    add_password_confirmation : this.userCreate.addConirmPassword,
                    add_group_role: this.userCreate.addGroupRole,
                    add_status: (this.userCreate.addStatus == true) ? 1 : 0,
                  })
    
                  $('.alert-success').removeAttr('hidden');
                  $('.alert-success').text(response.data.msg);
                  this.getListUsers();
                  $('#addmodal').modal('hide');
          
               } catch (error) {
                  $.each(error.response.data.errors, function(key, value) {
                    var name = key;
                    $.each(value,  function(key, value){

                      $(`#${name}`).addClass('input-error');
                      
                      $(`.error_${name}`).append(
                      `<div style="color:red">${value}</div>`
                      );

                    });                    
                  });
               }

          },
          async openModalEdit(index) {
                this.emptyErrorAlert('edit');
                this.userEdit.editId = this.listUsers[index].id,
                this.userEdit.editName = this.listUsers[index].name,
                this.userEdit.editEmail = this.listUsers[index].email,
  
                this.userEdit.editGroupRole = this.listUsers[index].group_role,
                this.userEdit.editStatus = this.listUsers[index].is_active === 1 ? true : false,
            $('#editmodal').modal('show');
            
          },
          async closeModalEdit() {
            $('#editmodal').modal('hide');          
          },
          async submitModalEdit() {
            try {
                  const response = await axios.post('http://127.0.0.1:8000/user-edit', {
                    edit_id: this.userEdit.editId,
                    edit_name: this.userEdit.editName,
                    edit_email: this.userEdit.editEmail,
                    edit_password: this.userEdit.editPassword,
                    edit_password_confirmation : this.userEdit.editConfirmPassword,
                    edit_group_role: this.userEdit.editGroupRole,
                    edit_status: (this.userEdit.editStatus == true) ? 1 : 0,
                  })
    
                  $('.alert-success').removeAttr('hidden');
                  $('.alert-success').text(response.data.msg);
                  this.getListUsers();
                  $('#editmodal').modal('hide');
          
               } catch (error) {
                  $.each(error.response.data.errors, function(key, value) {
                    var name = key;
                    $.each(value,  function(key, value){

                      $(`#${name}`).addClass('input-error');
                      
                      $(`.error_${name}`).append(
                      `<div style="color:red">${value}</div>`
                      );

                    });                    
                  });
               }

          },
          async openModalDelete(index) {

            this.id = this.listUsers[index].id;
            this.remind.name = this.listUsers[index].name;

            $('#deletedmodal').modal('show');
            
          },
          async closeModalDelete() {
            $('#deletedmodal').modal('hiden');
            this.id = null;
          },
          async deleteUser() {
            try{
                const response = await axios.post('http://127.0.0.1:8000/user-delete', {
                  id: this.id,
                  })
                this.getListUsers();
                this.hiddenAlert();
                this.showAlert('success', response.data.msg)
                $('#deletedmodal').modal('hide');
                this.id = null;
            }catch(error){
              this.hiddenAlert();
              this.showAlert('error', error.response.data.error)
              $('#deletedmodal').modal('hide');
            }
              
          },
          async openModalChangeStatus(index) {
            this.id = this.listUsers[index].id;
            this.remind.name = this.listUsers[index].name;
            this.remind.status = this.listUsers[index].is_active;

            $('#changestatusmodal').modal('show');
            
          },
          async closeModalChangeStatus() {
            $('#changestatusmodal').modal('hide');
            this.id = null;
          },
          async changStatus() {
            try{
                const response = await axios.post('http://127.0.0.1:8000/user-status', {
                    id: this.id,
                  })
                this.getListUsers();
                this.hiddenAlert();
                this.showAlert('success', response.data.msg)
                $('#changestatusmodal').modal('hide');
                this.id = null;
            }catch(error){
              this.hiddenAlert();
              this.showAlert('error', error.response.data.error);
              $('#changestatusmodal').modal('hide');
            }
          },
          
          
          async emptyErrorAlert(action){
            $(`.error_${action}_name`).empty().html();
            $(`.error_${action}_email`).empty().html();
            $(`.error_${action}_password`).empty().html();
            $(`.error_${action}_group_role`).empty().html();
          },
          async loadUser(response){
                   this.listUsers = response.data.data;
                   this.total = response.data.count;
                   this.numberUserPerPage = response.data.numberUserPerPage;
                   var length = Object.keys(response.data.data).length;

                   var offset = this.numberUserPerPage * (this.page -1 );
                   this.from = offset + 1;
                   if(length === 0){
                     this.from = offset ;
                   }                  
                   this.to = offset + length;
          },
          async showAlert(type, text){
              $(`.alert-${type}`).text(text);
              $(`.alert-${type}`).attr('hidden', false);
          },
          async hiddenAlert(){
            $(`.alert-error`).attr('hidden', true);
            $(`.alert-success`).attr('hidden', true);
          }
       }
    }
</script>

<style scoped>


    .title{
        font-weight: bold;
        
    }
    .icon{
        border: 1px solid white;
        
    }


</style>