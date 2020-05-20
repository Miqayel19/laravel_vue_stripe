<template>
    <div class="plan-new">
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
                        <input type="text" class="form-control" v-model="plan.name" placeholder="Name"/>
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
                        <input type="text" class="form-control" v-model="plan.address" placeholder="Address"/>
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
                        <input type="text" class="form-control" v-model="plan.country" placeholder="Country"/>
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
                        <input type="text" class="form-control" v-model="plan.phone" placeholder="Phone"/>
                    </td>
                    <td>
                         <span v-if="errors.phone">
                            {{ errors.phone }}
                        </span>
                    </td>
                </tr>
                <tr>
                    <td>
                        <router-link to="/plan" class="btn btn-danger">Cancel</router-link>
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
                plan: {
                    name:'',
                    price: '',
                    userID: '',
                },
                errors: [],
            };
        },
        methods:{
            submitForm: function (e) {

                this.errors = [];

                if (!this.plan.name) {
                    this.errors.push("Name required")
                }else if(this.plan.name.length < 3){
                    this.errors.push("Name must contains minimum 3 characters")
                }
                if (!this.plan.address) {
                    this.errors.push("Address required")
                }else if(this.plan.address.length < 4){
                    this.errors.push("Address must contains minimum 4 characters")
                }
                if (!this.plan.country) {
                    this.errors.push("Country required")
                }else if(this.plan.country.length < 4){
                    this.errors.push("Country must contains minimum 3 characters")
                }
                if (!this.plan.phone) {
                    this.errors.push("Phone required")
                }
                if (!this.errors.length) {
                    axios.post('/api/plan/new', this.$data.plan,{
                        headers:{
                            'Authorization':`Bearer ${this.currentUser.token}`
                        }
                    })
                    .then((response) => {
                        this.$store.commit('updateplans',response.data.plans);
                        this.$router.push('/plan');
                    });
                }else {
                    console.log()
                }
                e.preventDefault();
            },

        }
    }
</script>
