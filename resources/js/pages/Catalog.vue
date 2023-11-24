<template>
    <div class="container">
    <header>
      <a class="about" href="/">О нас</a>
      <a class="catalogue underline" href="/catalog">Каталог</a>
      <a class="where" href="/where">Где нас найти?</a>
      <div v-if="is_logged" class="admin_div">
          <a v-if="is_admin" href="/admin">Админ панель</a>
          <a v-else href="/cart" class="cart">Корзина</a>
          <a @click="logout" class="logout">Выйти</a>
      </div>
      <div v-else class="guest_nav">
          <a href="/login" class="auth">Авторизация</a>
          <a href="/registration" class="register_nav">Регистрация</a>
      </div>
  </header>
  <main class="catalogue_main">
      <div class="sort_div">
          <div>
                <p class="sort_title">Упорядочить:</p>
              <div class="sort_input_div1">
              <div>
              <input class="year_input" type="radio" @click="order_by" value="year" name="orderby" id="ob_1">
              <label class="year_input_name" for="ob_1">по году производства</label>
              </div>
              <div>
              <input class="name_input" type="radio" @click="order_by" value="name" name="orderby" id="ob_2">
              <label class="name_input_name" for="ob_2">по наименованию</label>
              </div>
              <div>
              <input class="price_input" type="radio" @click="order_by" value="price" name="orderby" id="ob_3">
              <label class="price_input_name" for="ob_3">по цене</label>
              </div>
              </div>
          </div>
          <div>
                <p class="sort_title">Категории:</p>
              <div class="sort_input_div2">
              <div v-for="i in index">
                  <input type="radio" @click="filter" :value="categories[i-1].id" name="filter" :id="'filter_'+i">
                  <label :for="'filter_'+i">{{ categories[i-1].name }}</label> 
              </div>
              </div>
          </div>
      </div>
      <div class="product_div">
          <div v-if="!filter_is_on" v-for="product in products">
            <div class="product_item_div">
            
            <a :href="$router.resolve({name: 'ProductPage', params: { id: product.id}}).href">
            <img :src="'/storage/printer_imgs/'+product.img" alt="">
            </a>
            <p class="product_item_name">{{ product.name }}</p>
             <p class="product_item_price">{{ product.price }} ₽</p>
            <button v-if="is_logged" @click="add_to_card($event, product.id)" class="cart_add_btn">Добавить в корзину</button>
            </div>
          </div>
          <div v-else v-for="product in products" class="product_item_div">
              <div v-if="filter_word == product.categories_id">
                <a :href="$router.resolve({name: 'ProductPage', params: { id: product.id}}).href">
                <img :src="'/storage/printer_imgs/'+product.img" alt="">
                </a>
                  <p class="product_item_name">{{ product.name }}</p>
                  <p class="product_item_price">{{ product.price }} ₽</p>
                  <button v-if="is_logged" @click="add_to_card($event, product.id)" class="cart_add_btn">Добавить в корзину</button>
              </div>
            </div>
      </div>
   </main>
   </div>
  </template>
  
<style>

.catalogue_main {
    display: flex;
}

.sort_div {
    width: 491px;
    height: 698px;
    background-color: white;
    margin-left: 5%;
    position: fixed;
}

.sort_div label {
    position: relative;
    font-size: 24px;
    margin-left: 5%;
    bottom: 2vh;
}

.sort_title {
    font-size: 40px;
    margin-top: 10%;
    margin-left: 10%;
}

.sort_div p {
    text-align: start;
}

.sort_div input {
    background-color: #D9D9D9;
    width: 50px;
    height: 50px;
    border-radius: 1px;
    position: relative;
}

.sort_input_div1 {
    display: inline;
    position: relative;
    top: 2vh;
}

.year_input {
    position: relative;
    right: 12%;
}

.year_input_name {
    position: relative;
    right: 10%;
}

.name_input {
    position: relative;
    right: 15%;
}

.name_input_name {
    position: relative;
    right: 13%;
}

.price_input {
    position: relative;
    right: 25.5%;
}

.price_input_name {
    position: relative;
    right: 23%;
}

.sort_input_div2 {
    display: flex;
    flex-direction: column;
    position: relative;
    text-align: left;
    top: 2vh;
    margin-left: 10%;
}

.product_div {
    display: grid;
    grid-template-columns: 1fr 1fr;
    margin-left: 33%;
    grid-column-gap: 15%;
    grid-row-gap: 5%;
    margin-top: 5%;
}

.product_item_div {
    background-color: #E5F0FF;
    width: 500px;
    height: 541px;
}

.product_item_div img {
    margin-left: auto;
    margin-right: auto;
    margin-top: 5%;
}

.product_item_div p {
    font-size: 40px;
}

.product_item_name, .product_item_price {
    margin-top: 4%;
}

.cart_add_btn {
    width: 250px;
    height: 62px;
    background-color: white;
    border-radius: 10px;
    border: none;
    font-size: 24px;
    font-family: "font";
    margin-top: 8%;
    cursor: pointer;

}

</style>
  
  
  <script>
  export default {
    name: "Home",
    data() {
      return {
          is_logged: false,
          is_admin: false,
          products: [],
          categories: [],
          index: 0,
          filter_is_on: false,
          filter_word: ''
      };
    },
    created() {
      if(localStorage.token){
              this.is_logged = true
              this.$axios
              .get("http://127.0.0.1:8000/api-samohod/getowninfo",{
                  headers: { Authorization: 'Bearer '+ localStorage.token }
              })
              .then((response) => {
                  if(response.data.role == 'admin'){
                      this.is_admin = true
                  }
              });
          }
          this.$axios
              .get("http://127.0.0.1:8000/api-samohod/products")
              .then((response) => {
                  console.log(response.data.content)
                  this.products = response.data.content;
              });
              this.$axios
              .get("http://127.0.0.1:8000/api-samohod/getcategories")
              .then((response) => {
                  this.categories = response.data;
                  this.index = this.categories.length
                  console.log(this.index);
              });
    },
    methods: {
      clear_filter(e){
          e.preventDefault()
          this.filter_word = ''
          this.filter_is_on = false
          var ele = document.getElementsByName("orderby");
          for(var i=0;i<ele.length;i++)
              ele[i].checked = false;
              var ele = document.getElementsByName("filter");
          for(var i=0;i<ele.length;i++)
              ele[i].checked = false;
          this.$axios
              .get("http://127.0.0.1:8000/api-samohod/products")
              .then((response) => {
                  console.log(response.data.content)
                  this.products = response.data.content;
              });
      },
      add_to_card(e, id){
              this.$axios
              .request({
                  url: "http://127.0.0.1:8000/api-samohod/cart/"+id,
                  method: "POST",
                  headers: {
                      Authorization: "Bearer " + localStorage.token,
                  }
              })
              .then((response) => {
                  console.log(response.data)
              });
      },
      order_by(e){
                  console.log(e.target.value)
          this.$axios
              .request({
                  url: "http://127.0.0.1:8000/api-samohod/productsreorder",
                  method: "POST",
                  data: {
                      orderby: e.target.value
                  }
              })
              .then((response) => {
                  this.products = response.data.content
              });
      },
      filter(e){
          console.log(e.target.value)
          this.filter_is_on = true;
          this.filter_word = e.target.value;
      },
      logout() {
              console.log(localStorage.token);
              this.$axios
              .request({
                  url: "http://127.0.0.1:8000/api-samohod/logout",
                  method: "POST",
                  headers: {
                      Authorization: "Bearer " + localStorage.token,
                  }
              })
              .then((response) => {
                  localStorage.removeItem('token');
                  window.location.href = "/";
              });
          }
    },
  };
  </script>