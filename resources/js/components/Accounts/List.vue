<template>
    <div>
        <div class="btn-wrapper">
            <router-link to="/account/new" class="btn btn-primary">New</router-link>
        </div>

        <table class="table">
            <template>
                <thead>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Role</th>
                    <th>Confirmed</th>
                    <th>State of Account</th>
                    <th colspan="3">Actions</th>
                </thead>
            </template>
            <tbody>
                <template v-if="!accounts.length">
                    <tr>
                        <td colspan="4" class="text-center">No Accounts Available</td>
                    </tr>
                </template>
                <template v-else>
                    <tr v-for="(account,index) in accounts" :key="index">
                        <td>{{ account.id}}</td>
                        <td>{{ account.name }}</td>
                        <td>{{account.pivot.role}}</td>
                        <td>{{account.pivot.confirmed}}</td>
                        <td>
                            <toggle-button v-model="myDataVariable" @change="changeState(account.id)"/>
                        </td>
                        <td>
                            <router-link :to="`/account/${account.id}`">Show</router-link>
                        </td>
                        <td>
                            <router-link :to="`/account/${account.id}/edit`">Edit</router-link>
                        </td>
                        <td>
                             <input type="submit" value="Delete"  @click="deleteAccount(index,account.id)" class="btn btn-danger"/>
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
            if (this.accounts.length) {
                return;
            }
            this.$store.dispatch('getAccounts');
        },
        computed:{
            accounts(){
                return this.$store.getters.accounts
            },
            currentUser(){
                return this.$store.getters.currentUser;
            }
        },
        data(){
            return {
                myDataVariable:false
            }
        },
        methods:{
            deleteAccount(index,id){
                axios.delete(`/api/account/${id}`,{
                    headers:{
                        "Authorization":`Bearer ${this.currentUser.token}`,
                    }
                })
                .then(res => {
                    this.$store.commit('updateAccounts', res.data.data);
                })
                .catch(err => { console.error(err) })
            },
            changeState(id){
                if(this.myDataVariable){
                    this.$router.push('/');
                    this.$store.commit('updateActiveAccount',id)
                }else
                    this.$store.commit('updateActiveAccount',null)
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
