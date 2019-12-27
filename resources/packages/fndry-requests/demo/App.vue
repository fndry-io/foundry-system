<template>
    <div id="app">
        <!--<img src="./assets/logo.png">-->
        <router-view></router-view>
    </div>
</template>

<script>
    export default {
        name: 'app',
        created: function () {
            this.$http.interceptors.response.use(undefined, function (err) {
                return new Promise(function (resolve, reject) {
                    if (err.status === 401 && err.config && !err.config.__isRetryRequest) {
                        this.$store.dispatch('logout');
                        this.$router.push('/');
                    }
                    throw err;
                });
            });
            this.$http.interceptors.response.use(undefined, function (err) {
                return new Promise(function (resolve, reject) {
                    if (err.status === 403) {
                        this.$bvToast.toast(`Permission denied`, {
                            autoHideDelay: 5000,
                            append: true
                        });
                    }
                });
            });
        }
    }
</script>

<style>

    body {
        background-color: #2f2f2f !important;
    }

    #app {
        font-family: 'Avenir', Helvetica, Arial, sans-serif;
        -webkit-font-smoothing: antialiased;
        -moz-osx-font-smoothing: grayscale;
        text-align: center;
        color: #efefef;
        margin-top: 20px;
    }

    h1, h2 {
        font-weight: normal;
    }

    hr {
        color: #ccc;
        border-color: #ccc;
    }

    a {
        color: #ccc;
    }

    .card {
        background: #ccc !important;
        color: #000;
    }

</style>
