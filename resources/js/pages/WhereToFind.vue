<template>
    <div class="container">
    <header>
      <a class="about" href="/">О нас</a>
      <a class="catalogue" href="/catalog">Каталог</a>
      <a class="where underline" href="/where">Где нас найти?</a>
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
  <main>
    <div class="where_my_2019">
        <div class="where_text">
            <p class="office">Главный офис нашей компании находится по адресу: </p>
            <p class="address">ул. Пушкина, д. Колотушкина</p>
            <p class="num_phone">Контактный номер телефона: </p>
            <p class="num">(880)-055-53-35</p>
            <p class="mail_address">Адрес электронной почты: </p>
            <p class="mail_real_address">copystar@gmail.com</p>
        </div>
        <img src="storage/img/map.png" alt="">
    </div>
  </main>
    </div>
  </template>
  
  <style>
    .where_my_2019 {
        display: grid;
        grid-template-columns: 2fr 1fr;
        width: 800px;
    }

    .where_my_2019 img {
        position: relative;
        right: 35%;
    }

    .where_text {
        position: relative;
        left: 15%;
        width: 500px;
        margin-top: 30%;
    }

    .office, .num_phone, .mail_address {
        width: 400px;
    }

    .num_phone, .mail_address {
        margin-top: 15%;
    }

    .address, .num, .mail_real_address {
        margin-top: 3%;
    }

    .where_text p {
        text-align: start;
        font-size: 32px;
    }


  </style>


<script>
export default {
  name: "Home",
  data() {
    return {
      is_logged: false,
            is_admin: false,
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
  },
  methods: {
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