<template>
    <div class = "container-fluid animate__animated animate__fadeIn animate__faster fill-height">
        <div class="row">
            <div class="image col-md-7 d-none d-md-block">
                <div class="white-filter">
                </div>
                <div class="login-description">
                    <h1>SCIS <br/>Enrichment</h1>
                    <p>Log in to view, register and propose courses!</p>
                </div>
                
            </div>
            <div class="login-form-wrapper col-md-5 col-sm-12">
                <div class="login-form">
                    <div class="school-logo">
                        <img src = "../assets/smu-logo.png"/>
                    </div>
                    <form>
                        <!-- <label for="email">Email</label> -->
                        <input type="email" class="form-control" placeholder="SMU Email" v-model="info.email" v-on:keyup.enter="onEnter">
                        <input type="password" class="form-control"  placeholder="Password" v-model="info.password" v-on:keyup.enter="onEnter" >
                        <small id="forgotPassword" class="form-text text-muted"><router-link to ="/resetpassword">Forgot Password?</router-link></small>
                        <button type="button" class="login-btn btn btn-primary" @click="submit()">Log in</button>
                        <!-- <button type="button" class="login-btn btn btn-primary" @click="test()">test in</button> -->
                    </form>
                    <br/>
                    <p>Do not have an account yet? <router-link to= "/register" class="register"><span >Click here to register!</span></router-link></p>
                    <br/>
                    <p class = "text-danger" v-if="attempt">{{attempt}}</p>
                </div>
            </div>
            
        </div>
    </div>
</template>
<script>
    import axios from 'axios'
    import router from '../router'
    import {sha256} from 'js-sha256'
    // import {LOGIN_URL, GETUSER_URL} from '../variables'
    import {LOGIN_URL} from '../variables'
    export default{
        data(){
            return{
                info:{
                    email: '',
                    password:''
                },
                attempt: ''
            }
        },
        methods:{
            submit(){
                axios.post(LOGIN_URL,{
                    'email': this.info.email,
                    'pw': sha256(this.info.password),   
                },
                {withCredentials: true}
                ).catch(function(error){
                    console.log(error)
                    return
                })
                .then(response=>{
                    if (response.data._token){  
                        this.$store.commit('setAuthentication', response.data)
                        console.log(this.$store)
                        if (response.data.role == 5){
                            router.push('/pending')
                        }else{
                            router.push('/')
                        }
                    }else{
                        this.attempt = response.data
                    }
                })
                
            },
            onEnter: function() {
                this.submit();
            }
            
            // test(){
            //     axios.get(GETUSER_URL,
            //     {withCredentials: true}
            //     ).catch(function(error){
            //         console.log(error)
            //         return
            //     })
            //     .then(response=>{
            //         console.log(response)
            //         this.$store.dispatch('getRole')
            //     })
                
            // }
        }
    }
</script>
<style scoped>

    .container-fluid{
        /* min-height: calc(100vh - var(--nav-height) - var(--footer-height)); */
        margin-top: 50px;
        height: 100%;
        overflow-y: hidden;
        height: 90vh;
    }
    .row{
        height: 100%;
        overflow-y: auto;
    }
    .image{
        background: linear-gradient(rgba(255, 255, 255, 0.294), rgba(255,255,255,.5)), url("https://images.pexels.com/photos/891059/pexels-photo-891059.jpeg?auto=compress&cs=tinysrgb&dpr=2&h=750&w=1260");
        background-size: cover;
        background-position: center;
        background-repeat: no-repeat;
    }
    .white-filter{
        position: absolute;
        background-color: white;
        top: 0;
        left: 0;
        height: 100%;
        width: 100%;
        opacity: 0.5
    }
    .login-description{
        position:absolute;
        text-align: center;
        top:50%;
        left: 50%;
        transform: translate(-50%, -50%);
    }
    .login-description h1{
        
        font-size: 4em;
        font-weight: bolder;
        font-family: var(--title-text);
    }
    .login-description p{
        font-size: 1.5em;
    }
    .login-form-wrapper{
        display: flex;
        justify-content: center;
        margin-top: 8vh;
    }
    .login-form{
        text-align: center;
        max-width: 600px;
        width: 100%;

    }

    .login-btn{
        background-color: var(--button-color);
        border: none;
        font-weight: bold;
        width: 100%;
        margin-top: 2em;
        transition: 0.2s;
    }
    .login-btn:hover{
        background-color: var(--button-color-hover);
    }
    input{
        margin-top: 1em;
        font-weight:bold;
        margin: 1em auto 0 auto;
    }
    #forgotPassword{
        text-align: end;
        
    }
    #forgotPassword a{
        color: var(--light-gray);
        text-decoration: none;
        transition: 0.1s;
    }

    #forgotPassword a:hover{
        color: var(--button-color-hover);
        
    }
    .school-logo img{
        max-width:100%;
    }
    .register{
        color: var(--light-gray);
        text-decoration: none;
        transition: 0.1s;
    }
    .register:hover{
        color: var(--button-color-hover)
    }
</style>