import Home from './components/Home.vue';
import Register from './components/Auth/Register.vue';
import Login from './components/Auth/Login.vue';
import Confirm from './components/Auth/Confirmation.vue';





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
    // {
    //     path: '/resumes',
    //     component: ResumesMain,
    //     children:[
    //         {
    //             path:'/',
    //             component:ResumesList
    //         },
    //         {
    //             path:'new',
    //             component:ResumeNew
    //         },
    //         {
    //             path:':id',
    //             component:Resume
    //         },
    //         {
    //             path:':id/edit',
    //             component:ResumeEdit
    //         },
    //
    //     ]
    // },
]
