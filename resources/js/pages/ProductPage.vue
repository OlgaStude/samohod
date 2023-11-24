<template>
    <div class="container">
    <header>
      <a class="about" href="/">О нас</a>
      <a class="catalogue" href="/catalog">Каталог</a>
      <a class="where" href="/where">Где нас найти?</a>
      <div v-if="is_logged" class="admin_div">
          <a v-if="is_admin" href="/admin" class="admin">Админ панель</a>
          <a v-else href="/cart" class="cart">Корзина</a>
          <a @click="logout" class="logout">Выйти</a>
      </div>
      <div v-else>
          <a class="auth" href="/login">Авторизация</a>
          <a class="register" href="/registration">Регистрация</a>
      </div>
  </header>
  
    <main>
      <div class="product_page_div">
      <div class="product_page_div1">
      <img :src="'/storage/printer_imgs/'+product.img" alt="">
      <br>
      <button v-if="is_logged" @click="add_to_card($event, product.id)" class="page_add_cart_btn">Добавить в корзину</button>
      <p>{{ add_to_cart_success }}</p>
      </div>
      <div class="product_page_div2">
        <a class="product_page_name" :href="$router.resolve({name: 'ProductPage', params: { id: product.id }}).href">{{ product.name }} </a>
        <p class="product_page_price">{{ product.price }} ₽</p>
        <p class="main_charaсters">Основные характеристики</p>
        <div class="characters">
        <p>Производитель - </p>
        <p class="product_character">{{ product.manufacturer }}</p>
        </div>
        <div class="characters">
        <p>Год выпуска - </p>
        <p class="product_character">{{ product.year }}</p>
        </div>
        <div class="characters">
        <p>Модель - </p>
        <p class="product_character">{{ product.model }}</p>
        </div>
      </div>
      </div>
    </main>
    </div>
  </template>
  
  <style>
  
  .product_page_div {
      display: flex;
      margin-top: 5%;
  
  }
  
  
  .page_add_cart_btn {
      width: 500px;
      height: 107px;
      background-color: white;
      border-radius: 10px;
      border: none;
      font-size: 40px;
      font-family: "font";
      margin-top: 5%;
      margin-left: 10%;
  }
  
  .product_page_div1 img {
      width: 900px;
      margin-left: 10%;
  }
  
  .product_page_div2 {
      text-align: start;
      margin-top: 1%;
      margin-left: 10%;
  }
  
  .product_page_name, .product_page_price {
      font-size: 48px;
      text-decoration: none;
      color: black;
  }
  
  .product_page_price {
      margin-top: 15%;
  }
  
  .main_charaсters {
      font-size: 48px;
      margin-top: 5%;
  }
  
  .characters {
      display: flex;
  }
  
  .characters p {
      font-size: 40px;
      margin-top: 10%;
      white-space: nowrap;
  }
  
  .product_character {
      margin-left: 10%;
  }
  
  </style>

<script>
export default {
  name: "Home",
  data() {
    return {
      is_logged: false,
        is_admin: false,
        product_id: window.location.href.substring(window.location.href.lastIndexOf('/') + 1),
        product: null,
        add_to_cart_success: ''
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
            .get("http://127.0.0.1:8000/api-samohod/product/"+this.product_id,{
                headers: { Authorization: 'Bearer '+ localStorage.token }
            })
            .then((response) => {
                this.product = response.data.content[0]
            });
  },
  methods: {
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
                this.add_to_cart_success = response.data.content.message
            });
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