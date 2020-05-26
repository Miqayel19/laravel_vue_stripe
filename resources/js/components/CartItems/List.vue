<template>
    <div>
        <div v-if="errors.length" class="alert alert-danger">
            <b>Please fix following errors</b>
            <ul>
                <strong><li v-for="(error,index) in errors" :key="index">{{ error }}</li></strong>
            </ul>
        </div>
        <table class="table">
            <template>
                <thead>
                    <th>ID</th>
                    <th>Plan Name</th>
                    <th>Plan Price</th>
                    <th>Period</th>
                    <th colspan="3">Actions</th>
                </thead>
            </template>
            <tbody>
                <template v-if="!cartItems.length">
                    <tr>
                        <td colspan="4" class="text-center">No Cart Items Available</td>
                    </tr>
                </template>
                <template v-else>
                    <tr v-for="(cartItem,index) in cartItems" :key="index">
                        <td>{{ cartItem.id}}</td>
                        <td>{{ cartItem.plan.name }}</td>
                        <td>{{ cartItem.planPrice }}</td>
                        <td>{{ cartItem.period }}</td>
                        <td>
                             <input type="submit" value="Delete"  @click="deleteCartItem(cartItem.id,cartItem.planPrice,index)" class="btn btn-danger"/>
                        </td>

                    </tr>
                    <div v-if="total">
                        <p>Summary:{{total}}</p>
                        <div>
                            <input type="submit" value="Order"  @click="order(accountID)" class="btn btn-success"/>
                        </div>
                    </div>

                </template>
            </tbody>
        </table>
    </div>
</template>

<script>
    export default {
        name: 'list',
        beforeMount() {
           this.fetchData();
        },
        computed:{
            cartItems(){
                return this.$store.getters.cartItems
            },
            accountID(){
                return this.$store.getters.active_account_id
            },
            total(){
                return this.$store.getters.price
            },
            currentUser(){
                return this.$store.getters.currentUser;
            }
        },
        methods:{
            deleteCartItem(id,price,index){
                axios.delete(`/api/cartItem/${id}`,{
                    headers:{
                        "Authorization":`Bearer ${this.currentUser.token}`,
                    }
                })
                .then(res => {
                    this.$store.commit('updatePrice', this.total - price);
                    if(this.cartItems && this.cartItems.length > 0) {
                        this.cartItems.splice(index, 1);
                    }
                })
                .catch(err => { console.error(err) })
            },
            fetchData(){
                if (this.cartItems.length) {
                    return;
                }
                this.$store.dispatch('getCartItems');
            },
            order(accountID){
                axios.post(`/api/order/new`,{account_id:accountID},{
                    headers:{
                        "Authorization":`Bearer ${this.currentUser.token}`,
                    }
                })
                    .then(res => {
                        if(res.data.data.code === 200) {
                            this.$store.commit('updateCartItems', []);
                            this.$router.push('/subscriptions');
                        }else {
                            this.errors.push(res.data.data.error);
                        }
                    })
                    .catch(err => { console.error(err) })
            }
        },
        data(){
            return{
                errors:[]
            }
        },
        watch: {
            '$route': 'fetchData'
        },
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
