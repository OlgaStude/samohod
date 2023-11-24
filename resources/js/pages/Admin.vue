<template>
  <div class="container">
  <header>
    <a class="about" href="/">О нас</a>
    <a class="catalogue" href="/catalog">Каталог</a>
    <a class="where" href="/where">Где нас найти?</a>
    <div class="admin_div">
        <a href="/admin" class="underline">Админ панель</a>
        <a @click="logout" class="logout">Выйти</a>
    </div>

</header>
  <main>
    <div class="category_control_div">
    <h1>Управление категориями</h1>
    <div class="delete_category_div">
      <select  @change="change_category" name="" id="categories" class="category_input">
        <option value="">Выберите категорию</option>
        <option v-for="category in categories" :value="category.id">{{ category.name }}</option>
      </select>
      <p class="category_control_message">{{ category_delete_success }}</p>
      <button @click="deleteCategory" class="delete_category_btn">Удалить категорию</button>
  </div>

  <div class="add_category_div">
  <form action="" method="post">
      <input type="text" v-model="new_category" name="name" placeholder="Название категории" class="admin_category_input">
      <p class="category_control_message">{{ error.category }}</p>
      <p class="category_control_message">{{ category_create_success }}</p>
      <button @click="addCategory" class="add_category_btn">Добавить категорию</button>
  </form>
  </div>
  </div>
  <div class="product_control_div">
  <h1>Управление товарами</h1>
  <a href="/addproduct"><button class="add_product_btn">Добавить товар</button></a>

  <div class="admin_product_div">
    <div v-for="product in products">
      <div class="product_item_div">
      <img :src="'/storage/printer_imgs/'+product.img" alt="">
      <p class="product_item_name">{{ product.name }}</p>
      <p class="product_item_price">{{ product.price }} ₽</p>
      </div>
      <div class="product_control_btns">
      <button @click="delete_product($event, product.id)" class="delete_product_btn">Удалить товар</button>
      <button @click="open_form($event, product.id)" class="update_product_btn">Обновить товар</button>
      </div>
    </div>
  </div>

  <form v-if="show_form" action="">
    <h1>Редактирование товара</h1>
    <input type="text" v-model="updated_name" id="updated_name" placeholder="Название" class="update_product_input">
      <p>{{ error.updated_name }}</p>
      <input type="file" ref="updated_img" id="img" class="update_product_input">
      <p>{{ error.updated_img }}</p>
      <input type="text" v-model="updated_price" id="updated_price" placeholder="Цена" class="update_product_input">
      <p>{{ error.updated_price }}</p>
      <button @click="update_product" class="window_update_product_btn">Редактировать товар</button>
      <button @click="close_form" class="close_form_btn">Закрыть</button>
  </form>
  </div>

  <div class="orders_control_div">
  <h1>Управление заказами</h1>
  <div class="orders_div">
    <div v-for="order in orders">
      <div class="order_item_div">
        <div class="order_info">
        <p class="order_user_name">{{ order.user_name }}</p>
        <p class="order_time">{{ order.time }}</p>
        </div>
        <div v-for="product in order.products" class="order_item_div2">
          <p class="order_product_name">{{ product.name }}</p>
          <p class="order_product_price">{{ product.price }}</p>
        </div>
        <p class="order_status2">{{ order.status }}</p>
      </div>
      <div v-if="order.status == 'Новый'">
        <button @click="change_order_status($event, 'yes', order.id)" class="order_confirm">Потвердить заказ</button>
        <button @click="open_cancel_form" class="order_decline">Отменить заказ</button>
        <div name="cancel_form" class="cancel_form">
          <textarea name="" id="" v-model="reason" cols="30" rows="10" class="cancel_input" placeholder="Причина отмены"></textarea>
          <p>{{ error.reason }}</p>
          <button @click="change_order_status($event, 'no', order.id)" class="order_cancel_btn">Отменить</button>
        </div>
      </div>
    </div>
  </div>
  </div>

  </main>
  </div>
</template>

<style>

.cancel_form{
  display: none;
}

.cancel_input {
  width: 500px;
  height: 125px;
  background-color: white;
  border-radius: 10px;
  border: none;
  margin-top: 3%;
  margin-right: 20%;
  padding: 2%;
  font-size: 16px;
  font-family: "font";
}

.order_cancel_btn {
  width: 219px;
  height: 54px;
  border-radius: 10px;
  border: none;
  background-color: white;
  font-size: 20px;
  font-family: "font";
  margin-top: 1%;
  margin-right: 17%;
}

.category_control_div {
  text-align: left;
}

.category_control_message {
  position: absolute;
  font-size: 24px;
  left: 5%;
}

.category_control_div h1 {
  text-align: center;
  font-size: 64px;
}

.admin_category_input {
    background-color: #ffffff;
    border-radius: 50px;
    border: none;
    width: 729px;
    height: 80px;
    margin-top: 3%;
    font-size: 32px;
    padding-left: 2%; 
}

.delete_category_div, .add_category_div {
  margin-top: 2%;
  margin-left: 5%;
}

.delete_category_btn, .add_category_btn {
  width: 479px;
  height: 97px;
  border-radius: 10px;
  border: none;
  background-color: white;
  font-size: 40px;
  font-family: "font";
  margin-left: 20%;
  cursor: pointer;
}

.product_control_div .add_product_btn {
  width: 479px;
  height: 97px;
  font-size: 40px;
}

.admin_product_div {
  display: grid;
  grid-template-columns: 1fr 1fr 1fr;
  grid-row-gap: 5%;
  margin-top: 5%;
  margin-left: 5%;
}

.admin_product_div .product_item_div {
  width: 500px;
  height: 480px;
}

.delete_product_btn, .update_product_btn {
  width: 219px;
  height: 54px;
  background-color: white;
  border-radius: 10px;
  border: none;
  font-size: 20px;
  font-family: "font";
  margin-top: 4%;
  cursor: pointer;
}

.delete_product_btn {
  margin-right: 26%;
}

.update_product_input {
    background-color: #ffffff;
    border-radius: 50px;
    border: none;
    width: 729px;
    height: 80px;
    margin-top: 3%;
    font-size: 32px;
    padding-left: 2%;
}

.update_product_btn {
  margin-right: 18%;
}

.product_control_btns {
  display: flex;
}

.window_update_product_btn {
  width: 479px;
  height: 97px;
  background-color: white;
  border-radius: 10px;
  border: none;
  font-size: 40px;
  font-family: "font";
  margin-top: 2%;
  cursor: pointer;
}

.close_form_btn {

  width: 150px;
  height: 75px;
  background-color: white;
  border-radius: 10px;
  border: none;
  font-size: 24px;
  margin-bottom: 1%;
  margin-left: 2%;
  cursor: pointer;
}

.order_info {
  display: flex;
  background-color: #E5F0FF
}

.order_info .order_user_name, .order_time {
  font-size: 16px;
  color: #686868;
  margin-top: 2%;
  background-color: #E5F0FF
}

.order_item_div2 {
  background-color: #E5F0FF
}


.order_status2 {
  background-color: #E5F0FF;
  font-size: 32px;
  text-align: center;
  position: relative;
  top: 10%;
}

.order_user_name {
  margin-left: 2%;
}

.order_time {
  position: relative;
  left: 40%;
}

.order_confirm, .order_decline {
  width: 219px;
  height: 54px;
  background-color: white;
  border-radius: 10px;
  border: none;
  font-size: 20px;
  font-family: "font";
  cursor: pointer;
  margin-top: 2%;
}

.order_confirm {
  position: relative;
  right: 15%;
}

.order_decline {
  position: relative;
  right: 3%;
}

</style>

<script>

import axios from 'axios'

export default {
  name: "Home",
  data() {
    return {
      categories: [],
      new_category: '',
      category_create_success: '',
      category_delete_success: '',
      category: '',
      updated_name: null,
      updated_price: null,
      updated_img: null,
      error: {
        category: null,
        updated_name: null,
        updated_price: null,
        updated_img: null,
        reason: null
      },
      products: [],
      show_form: false,
      product_id: 0,
      orders: [],
      reason: ''
    };
  },
  created() {
    this.$axios
            .get("http://127.0.0.1:8000/api-samohod/getcategories")
            .then((response) => {
                this.categories = response.data;
            });

            this.$axios
            .get("http://127.0.0.1:8000/api-samohod/products")
            .then((response) => {
                this.products = response.data.content;
            });
            this.$axios
            .get("http://127.0.0.1:8000/api-samohod/getallorders",{
                headers: { Authorization: 'Bearer '+ localStorage.token }
            })
            .then((response) => {
                console.log(response.data.content)
                this.orders = response.data.content
            });
            
  },
  methods: {
    open_cancel_form(e){
      e.preventDefault()
      var ele = document.getElementsByName("cancel_form");
      for(var i=0;i<ele.length;i++)
      ele[i].style.display = 'none'
    this.reason = ''
    e.target.nextElementSibling.style.display = 'block'
  },
    change_category(e){
      this.category = e.target.value
    },
    addCategory(e){
      e.preventDefault()
      this.error = {
        category: null
      }
      this.category_create_success = ''
      this.$axios
            .request({
                url: "http://127.0.0.1:8000/api-samohod/addcategory",
                method: "POST",
                headers: {
                    Authorization: "Bearer " + localStorage.token,
                },
                data: {
                  name: this.new_category
                }
            })
            .then((response) => {
                this.category_create_success = response.data.content.message
                this.categories = response.data.content.categories
                this.new_category = ''
            }).catch((err) => {
              console.log(err.response.data.warning.warnings)
              err.response.data.warning.warnings.forEach(element => {
                  if (element.name) {
                      this.error.category = element.name;
                      console.log(this.error.category);
                  }
              });
              
          });
    },
    deleteCategory(e){
      e.preventDefault()
      this.$axios
          .request({
              url: "http://127.0.0.1:8000/api-samohod/deletecategory",
              method: "POST",
              headers: {
                  Authorization: "Bearer " + localStorage.token,
              },
              data: {
                id: this.category
              }
          })
          .then((response) => {
              this.category_delete_success = response.data.content.message
              this.categories = response.data.content.categories
          })
    },
    delete_product(e, id){
      e.preventDefault()
      this.$axios
          .request({
              url: "http://127.0.0.1:8000/api-samohod/product/"+id,
              method: "DELETE",
              headers: {
                Authorization: "Bearer " + localStorage.token,
              }
          })
          .then((response) => {
              this.products = response.data.content.product
          })
    },
    open_form(e, id){
      e.preventDefault()
      this.show_form = true
      this.product_id = id;
    },
    close_form(e){
      e.preventDefault()
      this.show_form = false
      this.product_id = 0;
    },
    update_product(e){
      e.preventDefault()
      this.error = {
        updated_name: null,
        updated_price: null,
        updated_img: null
      }
      this.$axios
          .request({
              url: "http://127.0.0.1:8000/api-samohod/product/"+this.product_id,
              method: "POST",
              headers: {
                  Authorization: "Bearer " + localStorage.token,
                  'Content-Type': 'multipart/form-data'
              },
              data: {
                name: this.updated_name,
                img: this.$refs.updated_img.files[0],
                price: this.updated_price,
              }
          })
          .then((response) => {
              console.log(response.data)
              this.products = response.data.content.products
          }).catch((err) => {
              console.log(err.response.data.warning.warnings)
              err.response.data.warning.warnings.forEach(element => {
                  if (element.name) {
                      this.error.updated_name = element.name;
                  }
                  if (element.price) {
                      this.error.updated_price = element.price;
                  }
                  if (element.img) {
                      this.error.updated_img = element.img;
                  }
              });
              
          });
    },
    change_order_status(e, status, id){
      e.preventDefault()
            this.$axios
            .request({
                url: "http://127.0.0.1:8000/api-samohod/changeorderstatus",
                method: "POST",
                headers: {
                    Authorization: "Bearer " + localStorage.token,
                },
                data: {
                  id: id,
                  status: status,
                  reason: this.reason
                }
            })
            .then((response) => {
                this.$axios
              .get("http://127.0.0.1:8000/api-samohod/getallorders",{
                  headers: { Authorization: 'Bearer '+ localStorage.token }
              })
              .then((response) => {
                  console.log(response.data.content)
                  this.orders = response.data.content
                  this.reason = ''
                  e.target.nextElementSibling.style.display = 'none'
              });
            }).catch((err) => {
              this.error.reason = err.response.data.warning.warnings[0].reason
              
              
          });;
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