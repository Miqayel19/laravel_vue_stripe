<template>
    <div>
        <table class="table">
            <template>
                <thead>
                    <th>ID</th>
                    <th>User</th>
                    <th>Price</th>
                    <th>Currency</th>
                    <th>Date</th>
                    <th>Status</th>
                </thead>
            </template>
            <tbody>
                <template v-if="!orders.length">
                    <tr>
                        <td colspan="4" class="text-center">No Orders Available</td>
                    </tr>
                </template>
                <template v-else>
                    <tr v-for="(order,index) in orders" :key="index">
                        <td>{{ order.id}}</td>
                        <td>{{ order.user.email}}</td>
                        <td>{{ order.price }}</td>
                        <td>{{ order.currency }}</td>
                        <td>{{ order.date }}</td>
                        <td>{{ order.status }}</td>

                    </tr>
<!--                    <div v-if="total">-->
<!--                        <p>Summary:{{total}}</p>-->
<!--                        <div>-->
<!--                            <input type="submit" value="Order"  @click="order(order.id,index)" class="btn btn-success"/>-->
<!--                        </div>-->
<!--                    </div>-->

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
            orders(){
                return this.$store.getters.orders
            },
            total(){
                return this.$store.getters.price
            },
            currentUser(){
                return this.$store.getters.currentUser;
            }
        },
        methods:{
            fetchData(){
                if (this.orders.length) {
                    return;
                }
                this.$store.dispatch('getOrders');
            },
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
