import {getLoggedinUser} from './auth'

const user = getLoggedinUser();
export default {
    state:{
        welcome:"Welcome to my App",
        currentUser:user,
        auth_error:null,
        registeredUser: null,
        reg_error:null,
        accounts:[],
        plans:[],
        cartItems:[],
        price:null,
        orders:[],
        subscriptions:[],
        active_account_id:null
    },
    getters:{
        welcome(state){
            return state.welcome;
        },
        currentUser(state){
            return state.currentUser
        },
        authError(state){
            return state.auth_error
        },
        isLogged(state){
            return state.isLogged
        },
        regError(state){
            return state.reg_error;
        },
        registeredUser(state){
            return state.registeredUser;
        },
        accounts(state){
            return state.accounts
        },
        plans(state){
            return state.plans
        },
        cartItems(state){
            return state.cartItems
        },
        price(state){
            return state.price
        },
        orders(state){
            return state.orders
        },
        subscriptions(state){
            return state.subscriptions
        },
        active_account_id(state){
            return state.active_account_id
        },
        cards(state){
            return state.cards
        },

    },
    mutations:{
        login(state) {
            state.auth_error = null;
        },
        loginSuccess(state,payload){
            state.auth_error = null
            state.currentUser = Object.assign({},payload.user,{token:payload.token})
            localStorage.setItem('user',JSON.stringify(state.currentUser))
        },
        loginFailed(state,payload){
            state.auth_error = payload.data.error
        },
        regFailed(state,payload){
            state.reg_error = payload.error
        },
        regSuccess(state,payload){
            state.reg_error = null
            state.registeredUser = payload.user;
        },
        logout(state){
            state.isLogged = false
            localStorage.removeItem('user')
            state.currentUser = null
        },
        updateAccounts(state,payload){
            state.accounts = payload
        },
        updatePlans(state,payload){
            state.plans = payload
        },
        updateCartItems(state,payload){
            state.cartItems = payload
        },
        updatePrice(state,payload){
            state.price = payload
        },
        updateOrders(state,payload){
            state.orders = payload
        },
        updateSubscriptions(state,payload){
            state.subscriptions = payload
        },
        updateActiveAccount(state,payload){
            state.active_account_id = payload
        },
        updateCards(state,payload){
            state.cards = payload
        },
    },
    actions:{
        login(context){
            context.commit('login')
        },
        getAccounts(context){
            axios.get('/api/account',{
                headers:{
                    'Authorization':`Bearer ${context.state.currentUser.token}`
                }
            })
                .then((response) => {
                    context.commit('updateAccounts', response.data.data);
                })
        },
        getPlans(context){
            axios.get('/api/plan',{
                headers:{
                    'Authorization':`Bearer ${context.state.currentUser.token}`
                }
            })
                .then((response) => {
                    context.commit('updatePlans', response.data.data);
                })
        },
        getCartItems(context){
            axios.get('/api/cartItems',{
                headers:{
                    'Authorization':`Bearer ${context.state.currentUser.token}`
                }
            })
                .then((response) => {
                    context.commit('updatePrice', response.data.total);
                    context.commit('updateCartItems', response.data.data);
                })
        },
        getOrders(context){
            axios.get('/api/orders',{
                headers:{
                    'Authorization':`Bearer ${context.state.currentUser.token}`
                }
            })
                .then((response) => {
                    context.commit('updateOrders', response.data.data);
                })
        },
        getSubscriptions(context){
            axios.get('/api/subscriptions',{
                headers:{
                    'Authorization':`Bearer ${context.state.currentUser.token}`
                }
            })
                .then((response) => {
                    context.commit('updateSubscriptions', response.data.data);
                })
        },
        getCards(context){
            axios.get('/api/card',{
                headers:{
                    'Authorization':`Bearer ${context.state.currentUser.token}`
                }
            })
                .then((response) => {
                    context.commit('updateCards', response.data.data);
                })
        },
    }
};
