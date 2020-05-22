import Home from './components/Home.vue';
import Register from './components/Auth/Register.vue';
import Login from './components/Auth/Login.vue';
import Confirm from './components/Auth/Confirmation.vue';
import AccountConfirm from './components/Accounts/AccountConfirmation.vue';
import AccountMain from './components/Accounts/Main.vue';
import AccountList from './components/Accounts/List.vue';
import AccountNew from './components/Accounts/New.vue';
import Account from './components/Accounts/Show.vue';
import AccountEdit from './components/Accounts/Edit.vue';
import PlanMain from './components/Plans/Main.vue';
import PlanList from './components/Plans/List.vue';
import PlanNew from './components/Plans/New.vue';
import Plan from './components/Plans/Show.vue';
import PlanEdit from './components/Plans/Edit.vue';
import CartItemMain from './components/CartItems/Main.vue';
import CartItemList from './components/CartItems/List.vue';



export const routes = [
    {
        path: '/',
        component: Home
    },
    {
        path: '/register',
        component: Register,
    },
    {
        path: '/login',
        component: Login,
    },
    {
        path: '/confirmation/:token',
        component: Confirm,
    },
    {
        path: '/account',
        component: AccountMain,
        children:[
            {
                path:'/',
                component:AccountList
            },
            {
                path:'new',
                component:AccountNew
            },
            {
                path:':id',
                component:Account
            },
            {
                path:':id/edit',
                component:AccountEdit
            },


        ]
    },
    {
        path: '/account_confirmation/:token/:id',
        component: AccountConfirm,
    },
    {
        path: '/plan',
        component: PlanMain,
        children:[
            {
                path:'/',
                component:PlanList
            },
            {
                path:'new',
                component:PlanNew
            },
            {
                path:':id',
                component:Plan
            },
            {
                path:':id/edit',
                component:PlanEdit
            },
        ]
    },
    {
        path: '/cartItems',
        component: CartItemMain,
        children:[
            {
                path:'/',
                component:CartItemList
            },
        ]
    }
]
