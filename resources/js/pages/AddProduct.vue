<template>
  <div class="container">
  <header>
    <a class="about" href="/">О нас</a>
    <a class="catalogue" href="/catalog">Каталог</a>
    <a class="where" href="/where">Где нас найти?</a>
    <div class="admin_div"> 
        <a href="/admin" class="admin">Админ панель</a>
        <a @click="logout" class="logout">Выйти</a>
    </div>

</header>
  <main>
    <h1>Добавление товара</h1>
    <form action="">
      <input type="text" v-model="name" id="name" placeholder="Название">
      <p class="add_product_error">{{ errors.name }}</p>
      <label class="custom-file-upload input-file">
        <input @change="show_name()" class="file_upload" type="file" id="pfp" ref="img">
        <span id="file_selected">{{ file_name }}</span>
      </label>
      <p class="add_product_error">{{ errors.img }}</p>
      <select @change="change_category" name="" id="categories" class="category_input">
        <option value="">Категория</option>
        <option v-for="category in categories" :value="category.id">{{ category.name }}</option>
      </select>
      <p class="add_product_error">{{ errors.category_id }}</p>
      <input type="text" v-model="manufacturer" id="manufacturer" placeholder="Производитель">
      <p class="add_product_error">{{ errors.manufacturer }}</p>
      <input type="text" v-model="year" id="year" placeholder="Год выпуска">
      <p class="add_product_error">{{ errors.year }}</p>
      <input type="model" v-model="model" id="model" placeholder="Модель">
      <p class="add_product_error">{{ errors.model }}</p>
      <input type="price" v-model="price" id="price" placeholder="Цена">
      <p class="add_product_error">{{ errors.price }}</p>
      <p class="add_product_error">{{ product_add_success }}</p>
      <button id="send_btn" @click="add_product" class="add_product_btn">Добавить товар</button>
  </form>
  </main>
</div>
</template>

<style>

.admin_div {
    background-color: #77a6f6;
    display: inline-block;
    margin-left: 11%;  
}

.logout {
  position: relative;
  left: 80%;
}

.add_product_btn {
  width: 320px;
  height: 80px;
  background-color: white;
  border-radius: 10px;
  border: none;
  font-size: 32px;
  font-family: "font";
  cursor: pointer;
  margin-top: 5%;
}

.category_input {
  width: 760px;
  height: 80px;
  border-radius: 50px;
  border: none;
  background-color: white;
  font-size: 32px;
  padding-left: 2%;
  color: #838383;
  font-family: "font";
  margin-top: 3%;
}

.add_product_error {
    margin-top: 1%;
    font-size: 32px;
}

.file_upload{
   display: none;
}

label{
   width: 760px;
   height: 80px;
   border-radius: 50px;
   font-size: 32px;
   display: block;
   text-align: center;
   padding-top: 5px;
   cursor: pointer;
   margin-top: 3%;
   padding-left: 2%;
   padding-top: 1%;
   box-sizing: border-box;
   background-color: #fff;
   margin-left: 30%;
  }
  label span{
   background-color: #fff;
  }

</style>

<script>

import axios from 'axios'

export default {
  name: "Home",
  data() {
    return {
      categories: [],
      category: '',
      name: null,
      manufacturer: null,
      year: null,
      model: null,
      price: null,
      product_add_success: '',
      errors: {
        name: null,
        img: null,
        categories: null,
        manufacturer: null,
        year: null,
        model: null,
        price: null,
      },
      file_name: 'Загрузите фото'

    };
  },
  created() {
    this.$axios
            .get("http://127.0.0.1:8000/api-samohod/getcategories",{
                headers: { Authorization: 'Bearer '+ localStorage.token }
            })
            .then((response) => {
                this.categories = response.data;
            });
  },
  methods: {
    show_name(){
        this.file_name = this.$refs.img.files[0].name
    },
    change_category(e){
      console.log(e.target.value)
      this.category = e.target.value
    },
    add_product(e){
      e.preventDefault();
      this.errors = {
        name: null,
        img: null,
        categories_id: null,
        manufacturer: null,
        year: null,
        model: null,
        price: null,
      }
      this.$axios
            .request({
                url: "http://127.0.0.1:8000/api-samohod/product",
                method: "POST",
                headers: {
                    Authorization: "Bearer " + localStorage.token,
                    'Content-Type': 'multipart/form-data'
                },
                data: {
                  name: this.name,
                  img: this.$refs.img.files[0],
                  category_id: this.category,
                  manufacturer: this.manufacturer,
                  year: this.year,
                  model: this.model,
                  price: this.price,
                }
            })
            .then((response) => {
                console.log(response.data.content.message)
                this.product_add_success = response.data.content.message
                this.category = ''
                this.name = null
                this.manufacturer = null
                this.year = null
                this.model = null
                this.price = null
                this.$refs.img.files[0] = null
            }).catch((err) => {
              console.log(err.response.data.warning.warnings)
              err.response.data.warning.warnings.forEach(element => {
                  if (element.name) {
                      this.errors.name = element.name;
                  }
                  if (element.img) {
                      this.errors.img = element.img;
                  }
                  if (element.category_id) {
                      this.errors.category_id = element.category_id;
                  }
                  if (element.manufacturer) {
                      this.errors.manufacturer = element.manufacturer;
                  }
                  if (element.year) {
                      this.errors.year = element.year;
                  }
                  if (element.model) {
                      this.errors.model = element.model;
                  }
                  if (element.price) {
                      this.errors.price = element.price;
                  }
              });
              
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
    beforeRouteEnter(to, from, next) {
    if (localStorage.token) {
      axios
            .get("http://127.0.0.1:8000/api-samohod/getowninfo",{
                headers: { Authorization: 'Bearer '+ localStorage.token }
            })
            .then((response) => {
                      if(response.data.role != 'admin'){
                          return next("/");

                      };
            });
    }
    next();
}
};
</script>