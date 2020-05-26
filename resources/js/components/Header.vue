<template>
    <nav class="navbar navbar-expand-md navbar-light navbar-laravel">
        <div class="container">
            <router-link class="navbar-brand" to="/">My Vue App</router-link>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav">
                    <template v-if="!currentUser">
                        <li>
                            <router-link to="/login" class="nav-link">Login</router-link>
                        </li>
                        <li>
                            <router-link to="/register" class="nav-link">Register</router-link>
                        </li>
                    </template>
                    <template v-else>
                        <li>
                            <router-link to="/account" class="nav-link"> My accounts</router-link>
                        </li>
                        <li>
                            <router-link to="/plan" class="nav-link"> Plans</router-link>
                        </li>
                        <li>
                            <router-link to="/cartItems" class="nav-link"> Cart Items</router-link>
                        </li>
                        <li>
                            <router-link to="/orders" class="nav-link">Orders</router-link>
                        </li>
                        <li>
                            <router-link to="/subscriptions" class="nav-link">Subscriptions</router-link>
                        </li>
                        <li>
                            <router-link to="/card" class="nav-link">Cards</router-link>
                        </li>
                        <li class="nav-item dropdown">
                            <a id="navdrop" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-expanded="false" aria-haspopup="true">
                                {{ currentUser.name }}
                            </a>

                            <div class="dropdown-menu" aria-labelledby="navdrop">
                                <a href="#" @click.prevent="logout" class="dropdown-item">Logout</a>
                            </div>
                        </li>
                    </template>
                </ul>
            </div>
        </div>
    </nav>
</template>
<script>
    export default {
        name:'app-header',
        computed:{
            currentUser(){
                return this.$store.getters.currentUser;
            }
        },
        methods:{
            logout(){
                this.$store.commit("logout");
                this.$router.push({path: '/login'});
            }
        }
    };
</script>
