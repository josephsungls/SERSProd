<template>
    <div class="container-fluid" v-if="access">
        <h1 class = "text-center mb-5">Your Dashboard</h1>
        <div class="row tabs">
                <p :class="tabs.registered? 'active': ''" @click="toggleTab('registered')">Registered</p>
                <p :class="tabs.interested? 'active': ''" @click="toggleTab('interested')">Interested</p>
                <p :class="tabs.proposed? 'active': ''" @click="toggleTab('proposed')">Proposed</p>
                <p :class="tabs.attended? 'active': ''" @click="toggleTab('attended')">Attended</p>
                <p :class="tabs.certificates? 'active': ''" @click="toggleTab('certificates')">Certificates</p>
        </div>
        <div v-if="tabs.interested">
            <Interested/>
        </div>
        <div v-if="tabs.registered">
            <Registered/>
        </div>
        <div v-if="tabs.proposed">
            <Proposed/>
        </div>
        <div v-if="tabs.attended">
            <Attended/>
        </div>
        <div v-if="tabs.certificates">
            <Certificates/>
        </div>
        <div v-if="tabs.addcert">
            <AddCert/>
        </div>
    </div>
</template>
<script>
    import Interested from '../../components/Student/Dashboard/Interested'
    import Registered from '../../components/Student/Dashboard/Registered'
    import Proposed from '../../components/Student/Dashboard/Proposed'
    import Attended from '../../components/Student/Dashboard/Attended'
    import Certificates from '../../components/Student/Dashboard/Certificates'
    import AddCert from '../../components/Student/Dashboard/AddCert'
    import axios from 'axios'
    import Swal from 'sweetalert2'
    import router from '../../router.js'
    import {GETUSERROLE_URL} from '../../variables.js'
    export default{
        data(){
            return {
                tabs:{
                        registered: true,
                        interested: false,
                        proposed: false,
                        attended: false,
                        certificates: false,
                        addcert: false
                },
                courses: [],
                filtered_courses: [],
                access: false
            }
        },
        components:{
            Interested,
            Registered,
            Proposed,
            Attended,
            Certificates,
            AddCert
        },
        methods:{
            toggleTab(tab){
                for (const property in this.tabs){
                    property == tab ? this.tabs[property] = true : this.tabs[property] = false
                }
            },
            addCert(){
                for (const tab in this.tabs){
                    tab == 'addcert'? this.tabs[tab] = true:this.tabs[tab] = false
                }

            },
            back(){
                for (const tab in this.tabs){
                    tab == 'certificates'? this.tabs[tab] = true:this.tabs[tab] = false
                }  
            },
            checkUserRole(){
                axios.get(GETUSERROLE_URL,
                {withCredentials: true}                
                ).then(response=>{
                    if (response.data != 1){
                        this.redirectToCourse()
                        
                    }else{
                        this.access = true
                        
                    }
                })
            },
            redirectToCourse(){
                let timerInterval
                Swal.fire({
                icon: 'error',
                title: 'Access denied!',
                html: 'You do not have the access rights! Redirecting...',
                timer: 1000,
                timerProgressBar: true,
                didOpen: () => {
                    Swal.showLoading()
                },
                willClose: () => {
                    clearInterval(timerInterval)
                }
                })
                router.push('/courses')
            },

        },
        mounted(){
            this.checkUserRole()
        }
    }
</script>
<style scoped>
    h1{
        margin-top:3em;
    }
</style>