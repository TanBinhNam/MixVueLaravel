<template>

<li class="nav-item" >        
                    <router-link class="nav-link"  to="/product">Sản phẩm</router-link>
          </li>
          <li class="nav-item " :class="[{active: $route.name === 'customer'}]">
              <router-link class="nav-link"  to="/customer">Khách hàng</router-link>
          </li>
     
          <li class="nav-item " :class="[{active: $route.name === 'user'}]">
              <router-link class="nav-link"  to="/user">Users</router-link>
          </li>
</template>

<script>
export default {
    data() {
           return {
            title: 'How To Install Vue 3 in Laravel 8 From Scratch'
           }
       },
}
</script>

<style>
li .router-link-active{
    background-color: orangered;

    border-right:0px;
}

</style>