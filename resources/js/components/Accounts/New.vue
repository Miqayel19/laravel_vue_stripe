<template>
    <div class="account-new">
        <form @submit.prevent="submitForm">
            <div v-if="errors.length" class="alert alert-danger" >
                <b>Please fix following errors</b>
                <ul>
                    <strong><li v-for="(error,index) in errors" :key="index">{{ error }}</li></strong>
                </ul>
            </div>
            <table class="table">
                <tr>
                    <th>Name</th>
                    <td>
                        <input type="text" class="form-control" v-model="account.name" placeholder="Name"/>
                    </td>
                    <td>
                         <span v-if="errors.name">
                            {{ errors.name }}
                        </span>
                    </td>
                </tr>
                <tr>
                    <th>Address</th>
                    <td>
                        <input type="text" class="form-control" v-model="account.address" placeholder="Address"/>
                    </td>
                    <td>
                         <span v-if="errors.address">
                            {{ errors.description }}
                        </span>
                    </td>
                </tr>
                <tr>
                    <th>Country</th>
                    <td>
                        <input type="text" class="form-control" v-model="account.country" placeholder="Country"/>
                    </td>
                    <td>
                         <span v-if="errors.country">
                            {{ errors.country }}
                        </span>
                    </td>
                </tr>
                <tr>
                    <th>Phone</th>
                    <td>
                        <input type="number" class="form-control" v-model="account.phone" placeholder="Phone"/>
                    </td>
                    <td>
                         <span v-if="errors.phone">
                            {{ errors.phone }}
                        </span>
                    </td>
                </tr>
                <tr>
                    <td>
                        <router-link to="/account" class="btn btn-danger">Cancel</router-link>
                    </td>
                    <td class="text-right">
                        <input type="submit" value="Create" class="btn btn-primary">
                    </td>
                </tr>
            </table>
        </form>
    </div>
</template>

<script>
    export default {
        name:'new',
        computed:{
            currentUser(){
                return this.$store.getters.currentUser;
            }
        },
        data() {
            return {
                account: {
                    name:'',
                    address: '',
                    phone: '',
                    country: '',
                },
                errors: [],
            };
        },
        methods:{
            submitForm: function (e) {

                this.errors = [];

                if (!this.account.name) {
                    this.errors.push("Name required")
                }else if(this.account.name.length < 3){
                    this.errors.push("Name must contains minimum 3 characters")
                }
                if (!this.account.address) {
                    this.errors.push("Address required")
                }else if(this.account.address.length < 4){
                    this.errors.push("Address must contains minimum 4 characters")
                }
                if (!this.account.country) {
                    this.errors.push("Country required")
                }else if(this.account.country.length < 4){
                    this.errors.push("Country must contains minimum 3 characters")
                }
                if (!this.account.phone) {
                    this.errors.push("Phone required")
                }
                if (!this.errors.length) {
                    axios.post('/api/account/new', this.$data.account,{
                        headers:{
                            'Authorization':`Bearer ${this.currentUser.token}`
                        }
                    })
                    .then((response) => {
                        if(response.data.code === 200){
                            this.$store.commit('updateAccounts',response.data.data);
                            this.$router.push('/account');
                        }
                    });
                }
                e.preventDefault();
            },

        }
    }
</script>
