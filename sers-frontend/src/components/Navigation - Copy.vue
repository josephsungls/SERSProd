<template>
    <div class="container-fluid">
        <div class="web-view navbar-collapse collapse">
            <div>
                <router-link to = "/"><img class="logo nav navbar-nav narbar-left" src = "../assets/smu-logo2.png"/></router-link>
            </div>
            <nav class="nav navbar-nav navbar-center sticky">
                <ul class = "nav-items align-content-center text-align-center">
                    <li :class="menuItems.courses || menuItems.studentcourses || menuItems.instructorcourses || menuItems.managementcourses? 'active': ''"><router-link to="/">View Courses</router-link></li>
                    <li :class="menuItems.dashboard? 'active': ''" v-if="$store.state.role==1"><router-link to = "/dashboard">Your Dashboard</router-link></li>
                    <li :class="menuItems.yourclasses? 'active': ''" v-if="$store.state.role==3"><router-link to = "/yourclasses">Your Classes</router-link></li>
                    <li :class="menuItems.proposed? 'active': ''" v-if="$store.state.role == 2 || $store.state.role == 4 && $store.state.authenticated" ><router-link to="/proposed">Student Proposals</router-link></li>
                    <li :class="menuItems.instructordatabase? 'active': ''" v-if="$store.state.role == 2 || $store.state.role == 4"><router-link to="/instructordatabase">Instructor Database</router-link></li>
                    <li :class="menuItems.contact? 'active': ''"><router-link to="/contact">Contact us</router-link></li>
                </ul>
            </nav>
            <div class="account nav navbar-nav navbar-right">
                <router-link to = "/login" v-if="!$store.state.authenticated"><button class = "log-in-btn" >  Log in</button></router-link>

                <div class="dropdown"  v-else-if="$store.state.authenticated && $store.state.role == 1">
                    <button class="account btn dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        Hi, {{transformName($store.state.user_name)}}
                    </button>
                    <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                        <router-link to = "/profile" class ="dropdown-item"><i class="fas fa-address-card"></i> Profile</router-link>
                        <a class="dropdown-item" href="#" @click='logout()'><i class="fas fa-sign-out-alt"></i> Log out </a>
                    </div>
                </div>

                <div class="dropdown"  v-else-if="$store.state.authenticated && $store.state.role == 3">
                    <button class="instructor btn dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class="fas fa-user-circle"></i> Instructor
                    </button>
                    <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                        <router-link to = "/profile" class ="dropdown-item instructor-item"><i class="fas fa-address-card"></i> Profile</router-link>
                        <router-link to = "/classhistory" class ="dropdown-item instructor-item"><i class="fas fa-history"></i> Class History </router-link>
                        <a class="dropdown-item instructor-item" href="#" @click='logout()'><i class="fas fa-sign-out-alt"></i> Log out </a>
                    </div>
                </div>

                <div class="dropdown"  v-else-if="$store.state.authenticated && $store.state.role == 2">
                    <button class="admin btn dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class="fas fa-user-circle"></i> Admin
                    </button>
                    <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                        <router-link to = "/profile" class ="dropdown-item admin-item"><i class="fas fa-address-card"></i> Profile</router-link>
                        <router-link to ="/certificates" class ="dropdown-item admin-item"> <i class="fas fa-certificate"></i> Certificates</router-link>
                        <router-link to="/accounts" class ="dropdown-item admin-item"> <i class="fas fa-user-tag"></i> Accounts</router-link>
                        <router-link to = "/reportinsights" class ="dropdown-item admin-item"> <i class="fas fa-chart-bar"></i> Reports & Insights </router-link>
                        <a class="dropdown-item admin-item" href="#" @click='logout()'><i class="fas fa-sign-out-alt"></i> Log out </a>
                    </div>
                </div>

                <div class="dropdown"  v-else-if="$store.state.authenticated && $store.state.role == 4">
                    <button class="management btn dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class="fas fa-user-circle"></i> Management
                    </button>
                    <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                        <router-link to = "/profile" class ="dropdown-item management-item"><i class="fas fa-address-card"></i> Profile</router-link>
                        <router-link to ="/certificates" class ="dropdown-item management-item"> <i class="fas fa-certificate"></i> Certificates</router-link>
                        <router-link to = "/reportinsights" class ="dropdown-item management-item"> <i class="fas fa-chart-bar"></i> Reports & Insights </router-link>
                        <a class="dropdown-item management-item" href="#" @click='logout()'><i class="fas fa-sign-out-alt"></i> Log out </a>
                    </div>
                </div>

                <button class = "account btn" @click='logout()' v-else> Log out </button>
                <!-- <button class = "account btn" @click='testaccount()'> test </button> -->
                <!-- <button class = "account btn" @click="toggleUser()">{{$store.state.authenticated}}</button>  -->
            </div>
        </div>
        <div class="mobile-view navbar-collapse collapse">
            <div>
                <router-link to = "/"><img class="logo nav navbar-nav narbar-left" src = "../assets/smu-logo2.png"/></router-link>
            </div>
            <div class = "burger-wrapper">
                <a :class="[menuActive? 'active' : '', 'burger']" @click = "toggleMenu" ><i>{{menuText}}</i></a>
            </div>

        </div>
        <div :class="[menuActive? 'active': '', 'mobile-menu']">
            <!-- <ul class = "mobile-nav-items">
                <li :class="menuItems.courses || menuItems.studentcourses? 'active': ''"><router-link to="/">View Courses</router-link></li>
                <li :class="menuItems.propose? 'active': ''"><router-link to="/propose">Propose a Course</router-link></li>
                <li :class="menuItems.contact? 'active': ''"><router-link to = "/contact">Contact us</router-link></li>
                <li :class="menuItems.accounts? 'active': ''" v-if="$store.state.role==2"><router-link to="/accounts">Accounts</router-link></li>
                <li>
                    <div class="account">
                        <router-link to = "login"  v-if="!$store.state.authenticated"><button class = "account btn">  Log in </button></router-link>
                        <button class = "account btn" @click='logout()' v-else> Log out </button> -->
                        <!-- <button class = "account btn" @click="toggleUser()">{{$store.state.authenticated}}</button>  -->
                    <!-- </div>
                </li>
            </ul> -->
            <nav>
                <ul class = "mobile-nav-items">
                    <li :class="menuItems.courses || menuItems.studentcourses || menuItems.instructorcourses || menuItems.managementcourses? 'active': ''"><router-link to="/">View Courses</router-link></li>
                    <li :class="menuItems.dashboard? 'active': ''" v-if="$store.state.role==1"><router-link to = "/dashboard">Your Dashboard</router-link></li>
                    <li :class="menuItems.yourclasses? 'active': ''" v-if="$store.state.role==3"><router-link to = "/yourclasses">Your Classes</router-link></li>
                    <li :class="menuItems.proposed? 'active': ''" v-if="$store.state.role == 2 || $store.state.role == 4 && $store.state.authenticated" ><router-link to="/proposed">Student Proposals</router-link></li>
                    <li :class="menuItems.instructordatabase? 'active': ''" v-if="$store.state.role == 2 || $store.state.role == 4"><router-link to="/instructordatabase">Instructor Database</router-link></li>
                    <li :class="menuItems.contact? 'active': ''"><router-link to="/contact">Contact us</router-link></li>
                </ul>
            </nav>
                <div class="account d-flex align-content-center flex-wrap">
                    <router-link to = "/login" v-if="!$store.state.authenticated"><button class = "account btn log-in" >  Log in</button></router-link>

                    <div class="dropdown"  v-else-if="$store.state.authenticated && $store.state.role == 1">
                        <button class="account btn dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            Hi, {{transformName($store.state.user_name)}}
                        </button>
                        <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                            <!-- <router-link to = "/dashboard" class ="dropdown-item"><i class="fas fa-chart-bar"></i> Dashboard</router-link> -->
                            <a class="dropdown-item" href="#" @click='logout()'><i class="fas fa-sign-out-alt"></i> Log out </a>
                        </div>
                    </div>

                    <div class="dropdown"  v-else-if="$store.state.authenticated && $store.state.role == 3">
                        <button class="instructor btn dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="fas fa-user-circle"></i> Instructor
                        </button>
                        <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                            <router-link to = "/classhistory" class ="dropdown-item instructor-item"><i class="fas fa-history"></i> Class History </router-link>
                            <a class="dropdown-item instructor-item" href="#" @click='logout()'><i class="fas fa-sign-out-alt"></i> Log out </a>
                        </div>
                    </div>

                    <div class="dropdown"  v-else-if="$store.state.authenticated && $store.state.role == 2">
                        <button class="admin btn dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="fas fa-user-circle"></i> Admin
                        </button>
                        <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                            <router-link to ="/certificates" class ="dropdown-item admin-item"> <i class="fas fa-certificate"></i> Certificates</router-link>
                            <router-link to="/accounts" class ="dropdown-item admin-item"> <i class="fas fa-user-tag"></i> Accounts</router-link>
                            <router-link to = "/reportinsights" class ="dropdown-item admin-item"> <i class="fas fa-chart-bar"></i> Reports & Insights </router-link>
                            <a class="dropdown-item admin-item" href="#" @click='logout()'><i class="fas fa-sign-out-alt"></i> Log out </a>
                        </div>
                    </div>

                    <div class="dropdown"  v-else-if="$store.state.authenticated && $store.state.role == 4">
                        <button class="management btn dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="fas fa-user-circle"></i> Management
                        </button>
                        <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                            <router-link to ="/certificates" class ="dropdown-item management-item"> <i class="fas fa-certificate"></i> Certificates</router-link>
                            <router-link to = "/reportinsights" class ="dropdown-item management-item"> <i class="fas fa-chart-bar"></i> Reports & Insights </router-link>
                            <a class="dropdown-item management-item" href="#" @click='logout()'><i class="fas fa-sign-out-alt"></i> Log out </a>
                        </div>
                    </div>

                    <button class = "account btn" @click='logout()' v-else> Log out </button>

                </div>
        </div>
    </div>

</template>
<script>
    import axios from 'axios'
    import router from '../router'
    import {LOGOUT_URL} from '../variables'
    export default{
        data(){
            return{
                menuItems : {
                    courses: false,
                    instructorcourses:false,
                    instructordatabase: false,
                    studentcourses: false,
                    managementcourses: false,
                    dashboard: false,
                    yourclasses: false,
                    propose: false,
                    proposed: false,
                    database: false,
                    contact: false,
                    accounts: false,
                    profile: false,
                    
                    login: false,
                },
                menuActive: false,
                menuText: "Menu",
                windowWidth: 0,
            }
        },
        watch: {
            '$route' (to){
                // console.log(to)
                this.changeMenu(to.name.toLowerCase())
            },

        },
        created(){
            window.addEventListener('resize', this.handleResize)
            this.handleResize()
            
        },
        unmounted(){
            window.removeEventListener('resize', this.handleResize);
            window.onscrol
        },
        methods:{
            changeMenu(loc){
                for (const property in this.menuItems){
                    property == loc ? this.menuItems[property] = true : this.menuItems[property] = false
                }
                this.menuActive = false
            },
            toggleMenu(){
                this.menuActive = !this.menuActive
                
                this.menuActive ? this.menuText = "Close" : this.menuText = "Menu"
                
            },
            handleResize() {
                this.windowWidth = window.innerWidth;
                if (this.windowWidth >= 985){
                    this.menuActive = false
                }
            },
            toggleUser(){
                this.$store.commit('setAuthentication')
            },
            logout(){
                
                axios.get(LOGOUT_URL,
                {withCredentials: true},
                ).then(response => (
                    console.log(response.data),
                    this.$store.commit('logout'),
                    router.push('/login')
                ))
            },
            testaccount(){
                console.log(this.$store.state.authenticated)
            },
            transformName(name){
                if (name.length > 12){
                    return name.slice(0,9) + '...'
                }
                return name
            }
        },

    }
</script>
<style scoped>

    .logo{
        height: 80px;
        width: auto;
        position: relative;
        left: 140%;
    }

    @media screen and (min-width: 985px ){
        .navbar-collapse{
            left: 0px;
            top:0;
            z-index: 100;
            position: fixed;
            width: 100%;
            background-color: white;
            border-top: rgba(187, 187, 187, 0.219) 3px solid;
            border-bottom: rgba(187, 187, 187, 0.219) 3px solid;
        }
        .navbar-nav.navbar-center {
            position: absolute;
            left: 50%;
            transform: translatex(-50%);
            vertical-align: middle;
            font-weight: bold;
        }        
        .navbar-nav.navbar-left {
            position: absolute;
        }
        .navbar-nav.navbar-right {
            position: absolute;
            right: 7%;
        }        

    }

    @media screen and (max-width: 985px){
        .logo{
            left: 0;
        }
    }
 
    .nav-items{
        list-style-type: none;
        display: flex;
        padding: 0;
        align-content: center;
        margin:0;
        justify-content: center;
        overflow: hidden;
    }

    .nav-items li{
        margin: 0 3em;
        align-content: center;
        transition: 0.2s;
        color: rgba(70, 35, 38, 0.616);
        border-bottom: 2px solid var(--navigation-color);
        position: relative;
        text-decoration: none;
    }

    .nav-items li:hover{
        color: rgba(70,35,38,255);
    }

    .nav-items li:before {
        content: "";
        position: absolute;
        display: block;
        width: 100%;
        height: 2px;
        bottom: 0;
        left: 0;
        background-color: #000;
        transform: scaleX(0);
        transition: transform 0.3s ease;        
    }

    .nav-items li:hover::before {
        transform: scaleX(1);
    }

    .nav-items .active{
        color: rgba(78, 47, 49, 0.979);
        border-bottom: 2px solid rgba(78, 47, 49, 0.767);
    } 

    .dropdown, .log-in-btn{
        border-radius: 5px;
        align-items: center;
        justify-content: center;
        vertical-align: center;
    }

    .dropdown button, .log-in, .log-in-btn{
        margin-top:10px;
        margin-left: 50px;
        background-color: rgba(78, 47, 49, 0.767);
        color: white;   
        padding: 7px 60px;
    }

    .dropdown-item{
        background-color: white;        
        color: rgba(78, 47, 49, 0.767);        
        transition: 0.1s;
        line-height: 0.8cm;
    }

    .dropdown-item:hover{
        background-color: rgba(78, 47, 49, 0.767);
        color: white;
    }

    .dropdown-item i {
        padding-right: 8px;
    }

    .fas::after{
        content: none;
    }

    a {
        text-decoration: none;
        color:inherit;
    }

    @media screen and (max-width: 1230px ){
        .nav-items li{
            margin: 0 2em;
        }
        nav{
            padding: 0 3%;
        }
    }

    @media screen and (max-width: 1150px ){
        .nav-items li{
            margin: 0 1em;
        }
        nav{
            padding: 0 1%;
        }
    }

    /* mobile-view */
    

    .mobile-view{
        display: flex;
        position:fixed;
        top:0;
        right:0;
        padding-left: 20px;
        justify-content: space-between;
        font-weight: bold;
        width: 100%;
        background-color: white;
    }


    .burger {
        width:25px;
        height:18px;
        cursor: pointer;
        text-decoration: none;
    }

    .burger i {
        position: relative;
        display: inline-block;
        width: 25px;
        height: 3px;
        color:#252525;
        font:bold 14px/.4 var(--body-text);
        text-transform: uppercase;
        text-indent:-55px;
        background: #252525;
        transition:all .2s ease-out;
    }

    .burger i::before, a i::after {
    content:'';
    width: 25px;
    height: 3px;
    background: #252525;
    position: absolute;
    left:0;
    transition:all .2s ease-out;
    }

    .burger i::before {
    top: -7px;
    }

    .burger i::after {
    bottom: -7px;
    }

    .burger.active i {
        background: var(--navigation-color);
    }

    .burger.active i::before {
    top:0;
    -webkit-transform: rotateZ(45deg);
        -moz-transform: rotateZ(45deg);
        -ms-transform: rotateZ(45deg);
        -o-transform: rotateZ(45deg);
            transform: rotateZ(45deg);
    }

    .burger.active i::after {
    bottom:0;
    -webkit-transform: rotateZ(-45deg);
        -moz-transform: rotateZ(-45deg);
        -ms-transform: rotateZ(-45deg);
        -o-transform: rotateZ(-45deg);
            transform: rotateZ(-45deg);
    }

    .burger-wrapper{
        padding: 20px;
    }

    .mobile-menu{
        z-index: 5;
        transition: 0.3s;
        visibility: hidden;
        opacity: 0;
        background: white;
        position: fixed;
        top:3.6em;
        left:0;
        padding: 0;
        margin: 0;
        height: 100vh;
        width: 100vw;

    }

    .mobile-menu.active{
        opacity: 1;
        visibility: visible;        
    }

    .mobile-nav-items{
        background-color: white;
        list-style-type: none;
        font-weight: bold;
        color: var(--light-gray);
        transition: 0.2s;
        font-size: 1.5em;
        padding: 0;
        margin:0;
        
    }

    .mobile-nav-items li{
        margin: 0.7em 0em 0em 2em;
        transition: 0.2s;
        
    }

    .mobile-nav-items li:hover, .mobile-nav-items .active{
        color: var(--font-color);
        
    }

 
    @media screen and (min-width: 985px ){
        .mobile-view{
            display: none;
        }
        
        .web-view{
            display: flex;
            
        }
    }

</style>