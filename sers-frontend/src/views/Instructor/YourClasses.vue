<template>
<div class="container-fluid" v-if="access">
    <div class="row tabs">
            <p :class="tabs.confirmed? 'active': ''" @click="toggleTab('confirmed')" >Confirmed Classes</p>
            <p :class="tabs.action? 'active': ''" @click="toggleTab('action')">Pending Updates</p>
            <p :class="tabs.ongoing? 'active': ''" @click="toggleTab('ongoing')">Ongoing Registrations</p>
            
    </div>
    <div class = "animate__animated animate__fadeIn animate__faster" v-if="tabs.confirmed" >
        <YourClasses/>
    </div>
    <div class="animate__animated animate__fadeIn animate__faster" v-if="tabs.ongoing">
        <OngoingRegistrations/>
    </div>
    <div class = "animate__animated animate__fadeIn animate__faster" v-if="tabs.action">
        <PendingAction/>
    </div>
    <div class = "animate__animated animate__fadeIn animate__faster" v-if="tabs.individual">
        <IndividualCourse :selectedCourse="current"/>
    </div>
</div>
</template>
<script>
    import YourClasses from '../../components/Instructor/ViewCourses/YourClasses.vue'
    import OngoingRegistrations from '../../components/Instructor/ViewCourses/OngoingRegistrations.vue'
    import PendingAction from '../../components/Instructor/PendingAction'
    import IndividualCourse from '../../components/Instructor/IndividualCourse'
    import axios from 'axios'
    import {GETUSERROLE_URL} from '../../variables.js'
    import Swal from 'sweetalert2'
    import router from '../../router.js'
    export default{
        name: "PendingClasses",
        components: {
            YourClasses,
            OngoingRegistrations,
            PendingAction,
            IndividualCourse
        },
        data(){
            return{
                tabs:{
                    confirmed: true,
                    action: false,
                    ongoing: false,
                    individual:false,
                    
                },
                savedTabs: {},
                current: {},
                access: false
            }
        },
        methods:{
            individualCourse(){
                this.savedTabs = {...this.tabs}
                this.tabs.action = false
                this.tabs.individual = true
            },
            checkUserRole(){
                axios.get(GETUSERROLE_URL,
                {withCredentials: true}                
                ).then(response=>{
                    
                    if (response.data != 3){
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
            back(){
                this.tabs = {...this.savedTabs}
                this.tabs.indivudal = false
            },
            toggleTab(selectedTab){
                for (var tab in this.tabs){
                    tab == selectedTab? this.tabs[tab] = true : this.tabs[tab] = false
                }
                
            }
        },
        mounted(){
            this.checkUserRole()
        }
    }
</script>
<style scoped>
    .tabs{
        margin-top: 7em;
    }
</style>