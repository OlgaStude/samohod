<template>
    <div class="container">
        <header>
            <a class="about" href="/">О нас</a>
            <a class="catalogue" href="/catalog">Каталог</a>
            <a class="where" href="/where">Где нас найти?</a>
            <a class="auth" href="/login">Авторизация</a>
            <a class="register underline" href="/registration">Регистрация</a>
        </header>
        <main>
            <h1>Регистрация</h1>
            <form action="">
                <input type="text" v-model="name" id="name" placeholder="Имя" class="register_input" />
                <p>{{ errors.name }}</p>
                <input
                    type="text"
                    v-model="surname"
                    id="surname"
                    placeholder="Фамилия"
                    class="register_input"
                />
                <p>{{ errors.surname }}</p>
                <input
                    type="text"
                    v-model="paternname"
                    id="paternname"
                    placeholder="Отчество (необязательно)"
                    class="register_input"
                />
                <p>{{ errors.paternname }}</p>
                <input
                    type="text"
                    v-model="login"
                    id="login"
                    placeholder="Логин"
                    class="register_input"
                />
                <p>{{ errors.login }}</p>
                <input
                    type="text"
                    v-model="email"
                    id="email"
                    placeholder="Почта"
                    class="register_input"
                />
                <p>{{ errors.email }}</p>
                <input
                    type="password"
                    v-model="password"
                    id="password"
                    placeholder="Пароль"
                    class="register_input"
                />
                <p>{{ errors.password[0] }}</p>
                <input
                    type="password"
                    v-model="password_confirmation"
                    id="password"
                    placeholder="Подтвердите пароль"
                    class="register_input"
                />
                <p>{{ errors.password[1] }}</p>
                <input
                    @click="checkmark"
                    v-model="is_checked"
                    type="checkbox"
                    id="check"
                    class="register_input"
                />
                <label for="check" class="accept">Я согласен/согласна на обработку данных</label>
                <button
                    disabled
                    id="send_btn"
                    ref="send_btn"
                    type="submit"
                    class="register_btn"
                    @click="register"
                >
                    Регистрация
                </button>
            </form>
        </main>
    </div>
</template>

<style>
.auth {
    text-decoration: none;
}


.underline {
    text-decoration: 2px underline white;
    text-underline-offset: 20px;
}

#check {
    width: 40px;
    height: 40px;
    margin-left: 10%;
}

.accept {
    position: relative;
    left: 2%;
    bottom: 1vh;
    font-size: 32px;
}

.register_input {
    background-color: #ffffff;
    border-radius: 50px;
    border: none;
    width: 729px;
    height: 80px;
    margin-top: 3%;
    font-size: 32px;
    padding-left: 2%;
}


.register_btn {
    width: 345px;
    height: 80px;
    background-color: white;
    border-radius: 10px;
    border: none;
    font-size: 32px;
    color: black;
    position: relative;
    top: 8vh;
    right: 21%;
    cursor: pointer;
    margin-bottom: 2%;
}
</style>
<script>
export default {
    name: "Registration",
    data() {
        return {
            name: "",
            surname: "",
            paternname: "",
            email: "",
            login: "",
            password: "",
            password_confirmation: "",
            is_checked: false,
            errors: {
                name: "",
                surname: "",
                paternname: "",
                email: "",
                login: "",
                password: [],
            },
        };
    },
    created() {
        this.$axios
            .get("http://127.0.0.1:8000/api-samohod/products", {
                headers: { Authorization: "Bearer " + localStorage.token },
            })
            .then((response) => {
                console.log(response.data);
            });
    },
    methods: {
        register(e) {
            e.preventDefault();

            this.errors = {
                name: "",
                surname: "",
                paternname: "",
                login: "",
                email: "",
                password: [],
            };
            this.$axios.get("/sanctum/csrf-cookie").then((response) => {
                this.$axios
                    .post("api-samohod/signup", {
                        name: this.name,
                        email: this.email,
                        login: this.login,
                        surname: this.surname,
                        password: this.password,
                        paternname: this.paternname,
                        password_confirmation: this.password_confirmation,
                    })
                    .then((response) => {
                        localStorage.token = response.data.token;
                        window.location.href = "/";
                    })
                    .catch((err) => {
                        console.log(err.response.data.warning.warnings);
                        err.response.data.warning.warnings.forEach(
                            (element) => {
                                if (element.name) {
                                    this.errors.name = element.name;
                                    console.log(this.errors.name);
                                }
                                if (element.surname) {
                                    this.errors.surname = element.surname;
                                    console.log(this.errors.surname);
                                }
                                if (element.email) {
                                    this.errors.email = element.email;
                                    console.log(this.errors.email);
                                }
                                if (element.login) {
                                    this.errors.login = element.login;
                                    console.log(this.errors.email);
                                }
                                if (
                                    element.password &&
                                    !this.errors.password[0]
                                ) {
                                    this.errors.password[0] = element.password;
                                    console.log(this.errors.password);
                                } else {
                                    this.errors.password[1] = element.password;
                                    console.log(this.errors.password);
                                }
                            }
                        );
                    });
            });
        },
        checkmark() {
            if (!this.is_checked) {
                document.getElementById("send_btn").removeAttribute("disabled");
            } else {
                document
                    .getElementById("send_btn")
                    .setAttribute("disabled", true);
            }
        },
    },
    beforeRouteEnter(to, from, next) {
        if (localStorage.token) {
            return next("/");
        }
        next();
    },
};
</script>
