<template>
    <div>
        <div class="btn-wrapper">
            <router-link to="/plan/new" class="btn btn-primary">New</router-link>
        </div>
        <div>
            <p>Period</p>
            <select class="form-control" v-model="period">
                <option value="1">Monthly</option>
                <option value="3">Quartly</option>
                <option value="12">Annually</option>
            </select>
        </div>
        <table class="table">
            <template>
                <thead>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Price</th>
                    <th>Subscribe</th>
<!--                    <th colspan="3">Actions</th>-->
                </thead>
            </template>
            <tbody>
                <template v-if="!plans.length">
                    <tr>
                        <td colspan="4" class="text-center">No plans Available</td>
                    </tr>
                </template>
                <template v-else>
                    <tr v-for="(plan,index) in plans" :key="index">
                        <td>{{ plan.id}}</td>
                        <td>{{ plan.name }}</td>
                        <td v-if="period">{{ plan.price * period}}</td>
                        <td>
                            <input type="submit" value="Add to Cart"  @click="addToCart(plan.id)" class="btn btn-success"/>
                        </td>
<!--                        <td>-->
<!--                            <router-link :to="`/plan/${plan.id}`">Show</router-link>-->
<!--                        </td>-->
<!--                        <td>-->
<!--                            <router-link :to="`/plan/${plan.id}/edit`">Edit</router-link>-->
<!--                        </td>-->
<!--                        <td>-->
<!--                             <input type="submit" value="Delete"  @click="deletePlan(plan.id,index)" class="btn btn-danger"/>-->
<!--                        </td>-->
                    </tr>
                </template>
            </tbody>
        </table>
    </div>
</template>

<script>
    export default {
        name: 'list',
        mounted() {
            axios.get('/api/plan',{
                headers:{
                    'Authorization':`Bearer ${this.currentUser.token}`
                }
            }).then((response) => {
                    this.plans = response.data.data;
                })
        },
        data() {
            return {
                period:1,
                plans: {
                    id: "",
                    name: "",
                    price: ""
                },
                price:null,
                errors: [],
            };
        },
        computed:{
            currentUser(){
                return this.$store.getters.currentUser;
            },
        },
        methods:{
            deletePlan(id,index){
                axios.delete(`/api/plan/${id}`,{
                    headers:{
                        "Authorization":`Bearer ${this.currentUser.token}`,
                    }
                })
                .then(res => {
                    if(this.plans && this.plans.length > 0) {
                        this.plans.splice(index, 1);
                    }
                    this.$store.commit('updatePlans', res.data.data);
                })
                .catch(err => { console.error(err) })
            },
            addToCart(id){
                axios.post(`/api/cartItem/new/${id}`,{'period':this.period},{
                    headers:{
                        "Authorization":`Bearer ${this.currentUser.token}`,
                    }
                })
                .then(res => {
                    this.$store.commit('updateCartItems', res.data.data);
                })
            },
        }
    }
</script>
<style scoped>

    .btn-wrapper {
        text-align: right;
        margin-bottom: 20px;
    }
    table thead, table tbody{
        text-align:center!important
    }
    table tr td{
        vertical-align:middle
    }
    .table thead th{
        text-align:center
    }

</style>
