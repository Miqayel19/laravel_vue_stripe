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
                    <th>Price</th>
                    <td>
                        <input type="number" class="form-control" v-model="plan.price" placeholder="Price"/>
                    </td>
                    <td>
                         <span v-if="errors.price">
                            {{ errors.country }}
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
                if (!this.plan.price) {
                    this.errors.push("Price required")
                }
                if (!this.errors.length) {
                    axios.post('/api/plan/new', this.$data.plan,{
                        headers:{
                            'Authorization':`Bearer ${this.currentUser.token}`
                        }
                    })
                    .then((response) => {
                        this.$store.commit('updatePlans',response.data.data);
                        this.$router.push('/plan');
                    });
                }
                e.preventDefault();
            },

        }
    }
</script>
