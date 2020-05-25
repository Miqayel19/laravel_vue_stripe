<template>
    <div>
        <table class="table">
            <template>
                <thead>
                    <th>ID</th>
                    <th>User</th>
                    <th>Date</th>
                    <th>Renew Date</th>
                    <th>Plan Name</th>
                    <th>Plan Price</th>
                    <th>Period</th>
                    <th>Status</th>
                </thead>
            </template>
            <tbody>
                <template v-if="!subscriptions.length">
                    <tr>
                        <td colspan="8" class="text-center">No Subscriptions Available</td>
                    </tr>
                </template>
                <template v-else>
                    <tr v-for="(subscription,index) in subscriptions" :key="index">
                        <td>{{ subscription.id}}</td>
                        <td>{{ subscription.user.email}}</td>
                        <td>{{ subscription.date }}</td>
                        <td>{{ subscription.renew_date }}</td>
                        <td>{{ subscription.name }}</td>
                        <td>{{ subscription.price }}</td>
                        <td>{{ subscription.period }}</td>
                        <td>{{ subscription.status }}</td>
                    </tr>
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
            subscriptions(){
                return this.$store.getters.subscriptions
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
                if (this.subscriptions.length) {
                    return;
                }
                this.$store.dispatch('getSubscriptions');
            },
            order(id){

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
