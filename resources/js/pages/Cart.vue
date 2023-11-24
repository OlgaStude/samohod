<template>
    <div class="container">
    <header>
        <a class="about" href="/">О нас</a>
        <a class="catalogue" href="/catalog">Каталог</a>
        <a class="where" href="/where">Где нас найти?</a>
        <div class="admin_div">
            <a v-if="is_admin" href="/admin">Админ панель</a>
            <a v-else href="/cart" class="cart underline">Корзина</a>
            <a @click="logout" class="logout">Выйти</a>
        </div>
    </header>
    <main>
        <form v-if="order_form_is_on" action="" class="order_window">
            <p class="order_window_title">Подтвердите пароль для формирования заказа</p>
            <input v-model="password" type="password" name="password" id="password" placeholder="Пароль">
            <p v-if="password_message != ''" class="cart_error">{{ password_message }}</p>
            <p v-else class="cart_error" >{{ order_message }}</p>
            <button @click="order" class="order_btn">Сформировать заказ</button>
            <button @click="close_order_form" class="close_order_window">Закрыть</button>
        </form>
        <div class="cart_title_div">
        <a href="/orders"><button class="orders_list">Список заказов</button></a>
        <h1>Список товаров</h1>
        <button @click="open_order_form" class="order_form_btn">Оформить заказ</button>
        </div>
        <div class="cart_product_div">
        <div v-for="product in products">
            <div class="product_item_div">
                <a :href="$router.resolve({name: 'ProductPage', params: { id: product.id }}).href"><img :src="'/storage/printer_imgs/'+product.img" alt=""></a>
            <p class="product_item_name">{{ product.name }}</p>
            <p class="product_item_price">{{ product.price }} ₽</p>
            </div>
            <button @click="delete_from_cart($event, product.id)" class="delete_from_cart_btn">Удалить из корзины</button>
        </div>
        </div>
    </main>
    </div>
</template>

<style>

    .cart_title_div {
        display: flex;
        margin-top: 6%;
        margin-left: 6%;
        
    }

    .orders_list, .order_form_btn {
        width: 400px;
        height: 97px;
        background-color: white;
        border-radius: 10px;
        border: none;
        font-size: 40px;
        font-family: "font";
        cursor: pointer;
    }

    .order_form_btn {
        margin-left: 12%;
    }

    .cart_title_div h1 {
        position: relative;
        margin-left: 11%;
        bottom: 8vh;
        white-space: nowrap;
    }

    .cart_product_div {
        display: grid;
        grid-template-columns: 1fr 1fr 1fr;
        grid-row-gap: 4%;
        margin-left: 4%;
        margin-top: 5%;
    }

    .delete_from_cart_btn {
        width: 250px;
        height: 62px;
        border-radius: 10px;
        border: none;
        font-size: 24px;
        font-family: "font";
        cursor: pointer;
        margin-right: 16%;
        margin-top: 2%;
    }

    .order_window {
        margin-top: 5%;
    }

    .order_window_title {
        font-size: 48px;
    }


    .order_btn {
        width: 479px;
        height: 97px;
        background-color: white;
        border-radius: 10px;
        border: none;
        font-size: 40px;
        font-family: "font";
        cursor: pointer;
        margin-top: 2%;
    }

    .close_order_window {
        width: 150px;
        height: 70px;
        background-color: white;
        border-radius: 10px;
        border: none;
        font-size: 24px;
        font-family: "font";
        cursor: pointer;
        margin-left: 5%;
    }

    .cart_error {
        font-size: 32px;
        margin-top: 1%;
    }

</style>

<script>
export default {
    name: "Home",
    data() {
        return {
            is_admin: false,
            products: [],
            order_message: '',
            password_message: '',
            order_form_is_on: false,
            password: '',
            user_email: ''
        };
    },
    created() {
        this.$axios
            .get("http://127.0.0.1:8000/api-samohod/getowninfo",{
                headers: { Authorization: 'Bearer '+ localStorage.token }
            })
            .then((response) => {
                this.user_email = response.data.email;
                if(response.data.role == 'admin'){
                    this.is_admin = true
                }
            });
            this.$axios
            .get("http://127.0.0.1:8000/api-samohod/cart",{
                headers: { Authorization: 'Bearer '+ localStorage.token }
            })
            .then((response) => {
                this.products = response.data.content
            });
    },
    methods: {
        open_order_form(e){
            e.preventDefault();
            this.order_form_is_on = true;
        },
        close_order_form(e){
            e.preventDefault();
            this.order_form_is_on = false;
        },
        order(e){
            this.order_message = ''
            this.password_message = ''
            e.preventDefault();
            this.$axios
            .request({
                url: "http://127.0.0.1:8000/api-samohod/checkpassword",
                method: "POST",
                headers: {
                    Authorization: "Bearer " + localStorage.token,
                },
                data: {
                    password: this.password,
                    email: this.user_email
                }
            })
            .then((response) => {
                this.$axios
            .request({
                url: "http://127.0.0.1:8000/api-samohod/order/",
                method: "POST",
                headers: {
                    Authorization: "Bearer " + localStorage.token,
                }
            })
            .then((response) => {
                this.products = []
                this.order_message = response.data.content.message
                this.password = ''
            }).catch((err) => {
                this.order_message = err.response.data.content.message
              
          });
            }).catch((err) => {
                this.password_message = err.response.data.warning.message
              
            });
            
        },
        delete_from_cart(e, id){
            e.preventDefault();
            this.$axios
            .request({
                url: "http://127.0.0.1:8000/api-samohod/cart/"+id,
                method: "DELETE",
                headers: {
                    Authorization: "Bearer " + localStorage.token,
                }
            })
            .then((response) => {
                this.products = response.data.content.content
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
    if (!localStorage.token) {
      return next("/");
    }
    next();
}
};
</script>