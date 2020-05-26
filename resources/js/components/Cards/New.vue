<template>
    <div class="card-new">
        <form @submit.prevent="submitForm()">
            <div v-if="errors.length" class="alert alert-danger" >
                <b>Please fix following errors</b>
                <ul>
                    <strong><li v-for="(error,index) in errors" :key="index">{{ error }}</li></strong>
                </ul>
            </div>
            <table class="table">
                <tr>
                    <th>Full Name</th>
                    <td>
                        <input type="text" class="form-control" v-model="card.fullname" placeholder="Full Name"/>
                    </td>
                    <td>
                         <span v-if="errors.fullname">
                            {{ errors.fullname }}
                        </span>
                    </td>
                </tr>
                <tr>
                    <th>Card Number</th>
                    <td>
                        <input type="string" class="form-control" v-model="card.card_number" placeholder="Card Number"/>
                    </td>
                    <td>
                         <span v-if="errors.card_number">
                            {{ errors.card_number }}
                        </span>
                    </td>
                </tr>
                <tr>
                    <th>Expiration Month</th>
                    <td>
                        <input type="number" class="form-control" v-model="card.exp_month" placeholder="Expiration Month"/>
                    </td>
                    <td>
                         <span v-if="errors.exp_month">
                            {{ errors.exp_month }}
                        </span>
                    </td>
                </tr>
                <tr>
                    <th>Expiration Year</th>
                    <td>
                        <input type="number" class="form-control" v-model="card.exp_year" placeholder="Expiration Year"/>
                    </td>
                    <td>
                         <span v-if="errors.exp_year">
                            {{ errors.exp_year }}
                        </span>
                    </td>
                </tr>
                <tr>
                    <td>
                        <router-link to="/card" class="btn btn-danger">Cancel</router-link>
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
            },
            accountID(){
                return this.$store.getters.active_account_id;
            }
        },
        data() {
            return {
                card: {
                    fullname:'',
                    card_number:'',
                    exp_month: null,
                    exp_year: null,
                    account_id:''
                },
                errors: [],
            };
        },
        methods:{
            submitForm: function () {

                this.errors = [];

                if (!this.card.fullname) {
                    this.errors.push("Full Name required")
                }else if(this.card.fullname.length < 3){
                    this.errors.push("Full Name must contains minimum 3 characters")
                }
                if (!this.card.card_number) {
                    this.errors.push("Card Number required")
                }else if(this.card.card_number.length !== 16){
                    this.errors.push("Card Number must contains 16 symbols")
                }
                if (!this.card.exp_month) {
                    this.errors.push("Expiration Month required")
                }
                else if(this.card.exp_month.length !== 2){
                    this.errors.push("Expiration Month must contains 2 symbols")
                }
                if (!this.card.exp_year) {
                    this.errors.push("Expiration Year required")
                }else if(this.card.exp_year.length !== 4){
                    this.errors.push("Expiration Year must contains 4 symbols")
                }
                if (!this.errors.length) {
                    this.card.account_id = this.accountID
                    axios.post('/api/card/new',this.$data.card,{
                        headers:{
                            'Authorization':`Bearer ${this.currentUser.token}`
                        }
                    })
                    .then((response) => {
                        if(response.data.data.code === 200) {
                            this.$store.commit('updateCards',response.data.data);
                            this.$router.push('/card')
                        }else {
                            this.errors.push(response.data.data.error);
                        }
;
                    });
                }
                // e.preventDefault();
            },

        }
    }
</script>
