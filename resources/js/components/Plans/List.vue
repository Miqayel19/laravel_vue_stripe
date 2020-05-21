<template>
    <div>
        <div class="btn-wrapper">
            <router-link to="/plan/new" class="btn btn-primary">New</router-link>
        </div>

        <table class="table">
            <template>
                <thead>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Price</th>
                    <th>Subscribe</th>
                    <th colspan="3">Actions</th>
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
                        <td>{{ plan.price }}</td>
                        <td>
                            <input type="submit" value="Add to Cart"  @click="addToCart(plan.id)" class="btn btn-success"/>
                        </td>
                        <td>
                            <router-link :to="`/plan/${plan.id}`">Show</router-link>
                        </td>
                        <td>
                            <router-link :to="`/plan/${plan.id}/edit`">Edit</router-link>
                        </td>
                        <td>
                             <input type="submit" value="Delete"  @click="deletePlan(plan.id,index)" class="btn btn-danger"/>
                        </td>
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
            if (this.plans.length) {
                return;
            }
            this.$store.dispatch('getPlans');
        },
        computed:{
            plans(){
                return this.$store.getters.plans
            },
            currentUser(){
                return this.$store.getters.currentUser;
            }
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
                axios.post(`/api/plan/addToCart/${id}`,{},{
                    headers:{
                        "Authorization":`Bearer ${this.currentUser.token}`,
                    }
                })
                .then(res => {
                    console.log(res.data)
                    // this.$store.commit('updatePlans', res.data.data);
                })
            }
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
