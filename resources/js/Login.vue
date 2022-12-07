<template>
  <div class="container">
 <div class="row justify-content-center mt-5" >
      <div class="col-md-6">

        <div class="alert alert-danger" role="alert" hidden id="error_error">
            {{errorMsg}}
        </div>
 
   
                <div class="form-group mt-3  input-wrapper">
                
                <input type="email" class="form-control" id="email" name="email" 
                v-model="userLogin.email"
                placeholder="Email" required>
                <label for="email" class="fa fa-user input-icon fa-2x"></label>

        
                <div style="color: red" id="error_email" hidden>
                    {{ errorEmail }}
                </div>

                </div>
                <div class="form-group input-wrapper">

                <input type="password" class="form-control" name="password" id="password"
                v-model="userLogin.password"
                placeholder="Mật khẩu" required>
                <label for="password" class="fa fa-lock  input-icon fa-2x"></label>

                <div style="color: red" id="error_password" hidden>
   
                </div>

                </div>

                <div class="form-inline">

                <div class="form-check mr-auto">
                <input type="checkbox" id="remember" v-model="userLogin.remember">
                <label class="form-check-label" for="remember">Remember</label>
                </div>

                <button  class="btn btn-primary" @click="login">Đăng nhập</button>
                </div>
    

    
       


        </div>
  
        <br>
      </div>
    </div>
</template>

<script>
export default {
   data() {
          return {
                // hiddenEmail: false,
                // errorEmail: null,   
                // hiddenPassword: false,
                // errorPassword: String,
                // hidden: false,
                // errorMsg: String,

                userLogin: {
                    email: '',
                    password: '',
                    remember: false,
                }
          }
      },
    methods: {
            async login(){
                try{
                    const response = await axios.post('http://127.0.0.1:8000/login', {
                        email: this.userLogin.email,
                        password: this.userLogin.password,
                        remember: this.userLogin.remember,
                  })
                  this.$router.push('/user')

                }catch(error){
                    // this.hiddenEmail = true;
                    // this.hiddenPassword = true;
                    // this.hidden = true;
                    $(`#error_email`).attr('hidden', true);
                    $(`#error_password`).attr('hidden', true);
                    $(`#error_error`).attr('hidden', true);

                    $.each(error.response.data.errors, function(key, value) {
                        $(`#error_${key}`).removeAttr('hidden');                    
                        $(`#error_${key}`).text(value);
                    });
                  
                }
            }
      }
}
</script>

<style>
.container{
        margin-top:300px;
    }
.input-icon{
      size:20px;
  position: absolute  ;
  left: 7px  ;
  top: 5px;
}
input{
  padding-left: 35px  ;
}
.input-wrapper{
  position: relative;
}
</style>