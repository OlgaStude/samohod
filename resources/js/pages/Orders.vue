<template>
    <div class="container">
    <header>
        <a class="about" href="/">О нас</a>
        <a class="catalogue" href="/catalog">Каталог</a>
        <a class="where" href="/where">Где нас найти?</a>
        <div class="admin_div">
            <a v-if="is_admin" href="/admin" class="admin">Админ панель</a>
            <a v-else href="/cart" class="cart">Корзина</a>
            <a @click="logout" class="cart">Выйти</a>
        </div>
    </header>
    <main>
        <h1>Список заказов</h1>
        <div class="orders_div">
            <div v-for="order in orders">
                <div class="order_item_div">
                <div v-for="product in order.products">
                    <p class="order_product_name">{{ product.name }}</p>
                    <p class="order_product_price">{{ product.price }} ₽</p>
                </div>
                <p class="order_status">{{ order.status }}</p>
                </div>
            </div>
        </div>
    </main>
    </div>
</template>

<style>

.orders_div {
    display: grid;
    grid-template-columns: 1fr 1fr 1fr;
    margin-left: 5%;
    margin-top: 5%;

}

.order_item_div {
    width: 500px;
    height: 522px;
    background-color: #E5F0FF;
}

.order_item_div .order_product_name, .order_product_price{
    font-size: 20px;
    margin-top: 5%;
}

.order_status {
    font-size: 32px;
    position: absolute;
    bottom: 0;
    margin-left: 10%;
}

</style>


<script>
export default {
    name: "Orders",
    data() {
        return {
            is_admin: false,
            orders: []
        };
    },
    created() {
        this.$axios
            .get("http://127.0.0.1:8000/api-samohod/getowninfo",{
                headers: { Authorization: 'Bearer '+ localStorage.token }
            })
            .then((response) => {
                if(response.data.role == 'admin'){
                    this.is_admin = true
                }
            });
            this.$axios
            .get("http://127.0.0.1:8000/api-samohod/order",{
                headers: { Authorization: 'Bearer '+ localStorage.token }
            })
            .then((response) => {
                console.log(response.data.content)
                this.orders = response.data.content
            });
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
    beforeRouteEnter(to, from, next) {
    if (!localStorage.token) {
      return next("/");
    }
    next();
}
};
</script>
