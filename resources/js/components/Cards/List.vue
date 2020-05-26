<template>
    <div>
        <div class="btn-wrapper">
            <router-link to="/card/new" class="btn btn-primary">New</router-link>
        </div>
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
                    <th>User</th>
                    <th>Full Name</th>
                    <th>Last 4 digits</th>
                    <th>Subscribe</th>
                    <th colspan="3">Actions</th>
                </thead>
            </template>
            <tbody>
                <template v-if="!cards.length">
                    <tr>
                        <td colspan="4" class="text-center">No cards Available</td>
                    </tr>
                </template>
                <template v-else>
                    <tr v-for="(card,index) in cards" :key="index">
                        <td>{{ card.id}}</td>
                        <td>{{ card.user.email}}</td>
                        <td>{{ card.fullname }}</td>
                        <td v-if="period">{{ card.price * period}}</td>
                        <td>
                            <router-link :to="`/card/${card.id}`">Show</router-link>
                        </td>
                        <td>
                            <router-link :to="`/card/${card.id}/edit`">Edit</router-link>
                        </td>
                        <td>
                             <input type="submit" value="Delete"  @click="deletecard(card.id,index)" class="btn btn-danger"/>
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
            axios.get('/api/card',{
                headers:{
                    'Authorization':`Bearer ${this.currentUser.token}`
                }
            }).then((response) => {
                    this.cards = response.data.data;
                })
        },
        data() {
            return {
                period:1,
                cards: {
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
            accountId(){
                return this.$store.getters.active_account_id;
            }
        },
        methods:{
            deletecard(id,index){
                axios.delete(`/api/card/${id}`,{
                    headers:{
                        "Authorization":`Bearer ${this.currentUser.token}`,
                    }
                })
                .then(res => {
                    if(this.cards && this.cards.length > 0) {
                        this.cards.splice(index, 1);
                    }
                    this.$store.commit('updatecards', res.data.data);
                })
                .catch(err => { console.error(err) })
            },
            addToCart(id,accountId){
                axios.post(`/api/cartItem/new/${id}`,{'period':this.period,'account_id':accountId},{
                    headers:{
                        "Authorization":`Bearer ${this.currentUser.token}`,
                    }
                })
                .then(res => {
                    if(res.data.data.code === 200) {
                        this.$store.commit('updateCartItems', res.data.data);
                        this.$router.push('/cartItems');
                    }else {
                        this.errors.push(res.data.data.error);
                    }
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
