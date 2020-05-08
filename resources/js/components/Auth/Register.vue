<template>
    <div class="login row justify-content-center">
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">Register form</div>
                <div class="card-body">
                    <div v-if="errors.length" class="alert alert-danger">
                        <b>Please fix following errors</b>
                        <ul>
                            <strong><li v-for="(error,index) in errors" :key="index">{{ error }}</li></strong>
                        </ul>
                    </div>
                    <div class="alert alert-success"  v-if="status" >
                        <b>Please check your Email for Confirm Registration</b>
                    </div>
                    <form @submit.prevent="register" novalidate="true">
                        <div class="form-group row">
                            <label>Name:</label>
                            <input type="text" v-model="form.name" class="form-control" placeholder="Name">
                        </div>
                        <div class="form-group row">
                            <label>Email:</label>
                            <input type="email" v-model="form.email" class="form-control" placeholder="Email Address">
                        </div>
                        <div class="form-group row">
                            <label>Password:</label>
                            <input type="password" v-model="form.password" class="form-control" placeholder="Password">
                        </div>
                        <div class="form-group row">
                            <input type="submit" value="Register">
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import {register} from '../../auth';
    export default {
        name: "register",
        computed: {
            regError() {
                return this.$store.getters.regError;
            }
        },
        data() {
            return {
                form: {
                    name:'',
                    email: '',
                    password: ''
                },
                status: false,
                errors:[]
            };
        },
        methods: {
            register:function(e) {
                this.errors = [];
                if (!this.form.name) {
                    this.errors.push('Name required.');
                }
                if (!this.form.password) {
                    this.errors.push('Password required.');
                }
                if (!this.form.email) {
                    this.errors.push('Email required.');
                } else if (!this.validEmail(this.form.email)) {
                    this.errors.push('Please provide valid email address.');
                }
                if (!this.errors.length) {
                    register(this.$data.form)
                        .then((res) => {
                            this.$store.commit("regSuccess", res);
                            this.status = true
                            this.form.name=""
                            this.form.email=""
                            this.form.password=""

                        })
                        .catch((error) => {
                            this.$store.commit("regFailed", {error});
                        });
                }

                e.preventDefault();
            },
            validEmail: function (email) {
                var reg = /[^@]+@[^\.]+\..+/g;
                return reg.test(email);
            }

        }
    }
</script>

<style scoped>
    .error {
        text-align: center;
        color: red;
    }
</style>
