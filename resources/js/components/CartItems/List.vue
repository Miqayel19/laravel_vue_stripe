<template>
    <div>
        <table class="table">
            <template>
                <thead>
                    <th>ID</th>
                    <th>Plan Name</th>
                    <th>Plan Price</th>
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
                        <td>{{ cartItem.plan.price }}</td>
                        <td>
                             <input type="submit" value="Delete"  @click="deleteCartItem(cartItem.id,index)" class="btn btn-danger"/>
                        </td>

                    </tr>
                    <div v-if="total">
                        <p>Summary:{{total}}</p>
                        <div>
                            <input type="submit" value="Order"  @click="order(cartItem.id,index)" class="btn btn-success"/>
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
        mounted() {
            if (this.cartItems.length) {
                return;
            }
            this.$store.dispatch('getCartItems');
        },
        computed:{
            cartItems(){
                return this.$store.getters.cartItems
            },
            total(){
                return this.$store.getters.price
            },
            currentUser(){
                return this.$store.getters.currentUser;
            }
        },
        methods:{
            deleteCartItem(id,index){
                axios.delete(`/api/cartItem/${id}`,{
                    headers:{
                        "Authorization":`Bearer ${this.currentUser.token}`,
                    }
                })
                .then(res => {
                    if(this.cartItems && this.cartItems.length > 0) {
                        this.cartItems.splice(index, 1);
                    }
                    this.$store.commit('updateCartItems', res.data.data);
                })
                .catch(err => { console.error(err) })
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
