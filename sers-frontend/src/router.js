import { createRouter, createWebHistory } from 'vue-router'
import Courses from './views/Courses.vue'
import Propose from './views/Student/Propose.vue'
import Proposed from './views/Admin/Proposed.vue'
import Login from './views/Login.vue'
import Contact from './views/Contact.vue'
import Register from './views/Register.vue'
import Pending from './views/Pending.vue'
import StudentCourses from './views/Student/Courses.vue'
import Accounts from './views/Admin/Accounts.vue'
import AddCourse from './views/Admin/AddCourse.vue'
import UpdateCourse from './views/Admin/UpdateCourse.vue' 
import Dashboard from './views/Student/Dashboard.vue'
import YourClasses from './views/Instructor/YourClasses.vue'
import InstructorCourses from './views/Instructor/Courses.vue'
import ClassHistory from './views/Instructor/ClassHistory.vue'
import ManagementCourses from './views/Management/Courses.vue'
import ReportInsights from './views/ReportInsights.vue'
import CourseHistory from './views/Management/CourseHistory.vue'
import Feedback from './views/Feedback.vue'
import Certificates from './views/Certificates.vue'
import InstructorDatabase from './views/InstructorDatabase.vue'
import ResetPassword from './views/ResetPassword.vue'
import Profile from'./views/Profile.vue'
// import store from './store'

const routes = [
    // Admin
    {
        name: 'Courses',
        component: Courses,
        path: '/'
    },
    {
        name: 'AddCourse',
        component: AddCourse,
        path: '/addcourse'
    },

    {
        name: 'Accounts',
        component: Accounts,
        path: '/accounts'
    },
    // Students
    {
        name: 'StudentCourses',
        component: StudentCourses,
        path: '/courses'
    },
    {
        name: 'Dashboard',
        component: Dashboard,
        path: '/dashboard'
    },
    
    // Instructor
    {
        name: 'InstructorCourses',
        component: InstructorCourses,
        path: '/instructorcourses'
    },
    {
        name: 'YourClasses',
        component: YourClasses,
        path: '/yourclasses'
    },
    {
        name: 'ClassHistory',
        component: ClassHistory,
        path: '/classhistory'
    },

    // Management
    {

        name: 'ManagementCourses',
        component: ManagementCourses,
        path: '/managementcourses'
    },

    // Management & Admin
    {
        name: 'InstructorDatabase',
        component: InstructorDatabase,
        path: '/instructordatabase'
    },
    {
        name: 'ReportInsights',
        component: ReportInsights,
        path: '/reportinsights'
    },
    {
        name: 'Certificates',
        component: Certificates,
        path: '/certificates'
    },
    {
        name: 'CourseHistory',
        component: CourseHistory,
        path: '/coursehistory/:course_id'
    },
    {
        name: 'Proposed',
        component: Proposed,
        path: '/proposed'
    },
    // Must be logged in
    {
        name: 'Profile',
        component: Profile,
        path: '/profile'
    },



// All Access

    {
        name: 'Login',
        component: Login,
        path: '/login'
    },
    {
        name: 'Contact',
        component: Contact,
        path: '/contact'
    },

    {
        name: 'Register',
        component: Register,
        path: '/register'
    },


    {
        name: 'Feedback',
        component: Feedback,
        path: '/feedback/:course_id/:course_startdate/:class/:cf_id'
    },
    // If user is not verified
    // Verification TBC, may be through email
    {
        name: 'Pending',
        component: Pending,
        path: '/pending'
    },
    // Might not need
    {
        name: 'Propose',
        component: Propose,
        path: '/propose'
    },
    {
        name: 'UpdateCourse',
        component: UpdateCourse,
        path: '/updatecourse'
        
    },
    {
        name: 'ResetPassword',
        component: ResetPassword,
        path: '/resetpassword'
    }
]

const router = createRouter({
    history: createWebHistory(process.env.BASE_URL),
    routes
  })

export default router