<template>
    <div class="container">
    <header>
        <a class="about underline" href="/">О нас</a>
        <a class="catalogue" href="/catalog">Каталог</a>
        <a class="where" href="/where">Где нас найти?</a>
        <div v-if="is_logged" class="admin_div">
            <a v-if="is_admin" href="/admin" class="admin" >Админ панель</a>
            <a v-else href="/cart" class="cart">Корзина</a>
            <a @click="logout" class="logout">Выйти</a>
        </div>
        <div v-else class="guest_nav">
            <a class="auth" href="/login">Авторизация</a>
            <a class="register_nav" href="/registration">Регистрация</a>
        </div>
    </header>
    <main>
        <div class="home_section_1">
            <div class="home_section_1_title_div"> 
            <h1>Копировать легко вместе с CopyStar</h1>
            </div>
            <img class="logo_img" src="storage/img/logo.png" alt="">
        </div>
        <section class="slider-wrapper">
            <p class="slider_title">Новинки компании</p>
        <button class="slide-arrow" id="slide-arrow-prev">
            &#8249;
        </button>
        <button class="slide-arrow" id="slide-arrow-next">
            &#8250;
        </button>
        <ul class="slides-container" id="slides-container">
            <div class="slide" v-for="product in products">
                            <img :src="'/storage/printer_imgs/'+product.img" alt="">
                            <a :href="$router.resolve({name: 'ProductPage', params: { id: product.id }}).href">{{ product.name }}</a>
                        </div>
        </ul>
        </section>
    </main>
    </div>
</template>

<style>
* {
    margin: 0;
    padding: 0;
}
header {
    background-color: #77a6f6;
}
.guest_nav {
    background-color: #77a6f6;
    display: inline-block;
    margin-left: 11%;
}

.register_nav {
    margin-left: 40%;
}

.logout {
    cursor: pointer;
}

.cart {
    margin-left: 60%;
}



.home_section_1 {
    margin-top: 10%;
}

.logo_img {
    position: relative;
    bottom: 23vh;
    right: 7%;
}

.slider_title {
    font-size: 64px;
    text-align: center;
}

.home_section_1_title_div {
    position: relative;
    left: 10%;
    width: 500px;
    text-align: start;
    font-size: 64px;
}

.slider-wrapper {
  margin: 1rem;
  position: relative;
  overflow: hidden;
}
.slide img{
    width: 100%;
}
.slides-container {
  height: calc(100vh - 2rem);
  width: 100%;
  display: flex;
  overflow: hidden;
  scroll-behavior: smooth;
  list-style: none;
  margin: 0;
  padding: 0;
}
.slide-arrow {
  position: absolute;
  display: flex;
  top: 0;
  bottom: 0;
  margin: auto;
  height: 4rem;
  background-color: white;
  border: none;
  width: 2rem;
  font-size: 3rem;
  padding: 0;
  cursor: pointer;
  opacity: 0.5;
  transition: opacity 100ms;
}
.slide-arrow:hover,
.slide-arrow:focus {
  opacity: 1;
}
#slide-arrow-prev {
  left: 0;
  padding-left: 0.25rem;
  border-radius: 0 2rem 2rem 0;
}
#slide-arrow-next {
  right: 0;
  padding-left: 0.75rem;
  border-radius: 2rem 0 0 2rem;
}
.slide {
  width: 100%;
  height: 100%;
  flex: 1 0 100%;
}

.logo_img {
    background-color: #D2E4FC;
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
        if (localStorage.token) {
            this.is_logged = true;
            this.$axios
                .get("http://127.0.0.1:8000/api-samohod/getowninfo", {
                    headers: { Authorization: "Bearer " + localStorage.token },
                })
                .then((response) => {
                    if (response.data.role == "admin") {
                        this.is_admin = true;
                    }
                });
        }
        this.$axios
            .get("http://127.0.0.1:8000/api-samohod/productsslider")
            .then((response) => {
                this.products = response.data.content;
                console.log(this.products)
            });
            setTimeout(()=>{
                const slidesContainer = document.getElementById("slides-container");
                const slide = document.querySelector(".slide");
                console.log(slide)
                const prevButton = document.getElementById("slide-arrow-prev");
                const nextButton = document.getElementById("slide-arrow-next");
                nextButton.addEventListener("click", () => {
                const slideWidth = slide.clientWidth;
                slidesContainer.scrollLeft += slideWidth;
                });
                prevButton.addEventListener("click", () => {
                    console.log('!!')
                const slideWidth = slide.clientWidth;
                slidesContainer.scrollLeft -= slideWidth;
                });
            }, 2000)
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
                    },
                })
                .then((response) => {
                    localStorage.removeItem("token");
                    window.location.href = "/";
                });
        },
    },
};
</script>