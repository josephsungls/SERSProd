<template>
    <div class ="container-fluid" v-if="access">
        <div class="row tabs">
                <p :class="tabs.all? 'active': ''" @click="toggleTab('all')">All Courses</p>
                <p :class="tabs.registration? 'active': ''" @click="toggleTab('registration')" v-if="role==1">Register</p>
                <p :class="tabs.campaign? 'active': ''" @click="toggleTab('campaign')" v-if="role==1">Interest</p>
                <!-- <p :class="tabs.proposed? 'active': ''" @click="toggleTab('proposed')" v-if="$store.state.role == 1">Proposed</p> -->
        </div>
        <div class = "animate__animated animate__fadeIn animate__faster" v-if="tabs.all">
            <CourseDB  :registration="registration" :filtered_courses="filtered_courses" class = "mb-5"/>
            <Proposed/>
        </div>
        <div class = "animate__animated animate__fadeIn animate__faster" v-if="tabs.registration">
            <Registration :registrations="registration"/>
        </div>
        <div class = "animate__animated animate__fadeIn animate__faster" v-if="tabs.campaign">
            <Campaign :filtered_courses="filtered_courses"/>
        </div>
        <!-- <div class = "animate__animated animate__fadeIn animate__faster" v-if="tabs.proposed">
            <Proposed/>
        </div> -->
    </div>
</template>
<script>
    import CourseDB from '../../components/Student/CourseDB'
    import Campaign from '../../components/Student/Campaign'
    import Registration from '../../components/Student/Registration'
    import Proposed from '../../components/Student/Proposed'
    import axios from 'axios'
    
    import {GETUSERROLE_URL, ALLCOURSESREGISTRATIONSTUDENT_URL, ALLCOURSESFILTEREDCOURSESTUDENT_URL} from '../../variables.js'
    import router from '../../router'
    export default{
        data(){
            return {
                tabs:{
                        all: true,
                        registration: false,
                        campaign: false,
                        proposed: false,
                },
                registration: [],
                filtered_courses: [],
                role: 0,
                access: false
            }
        },
        components:{
            CourseDB,
            Campaign,
            Registration,
            Proposed
        },
        methods:{
            checkUserRole(){
                axios.get(GETUSERROLE_URL,
                {withCredentials: true}                
                ).then(response=>{
                    if (response.data == 3){
                        
                        router.push('/instructorcourses')
                    }else if (response.data == 4){
                        
                        router.push('/managementcourses')
                    }else if (response.data == 2){
                        
                        router.push('/')
                    }else{
                        if(response.data == 1){
                            this.role = 1
                        }
                        this.access = true
                        this.getRegistrations()
                        
                    }
                })
            },
            toggleTab(tab){
                for (var x in this.tabs){
                    if (tab == x){
                        this.tabs[x] = true
                    }else{
                        this.tabs[x] = false
                    }
                }
                
            },
            getRegistrations(){
                
                axios.post(ALLCOURSESREGISTRATIONSTUDENT_URL,
                {
                },
                {withCredentials: true}
                ).then(response => {    
                    if (response.data === 'no rights'){
                        this.redirectToCourse()
                    }else{           
                        this.registration = response.data
                        this.getFilteredCourse()
                    }
                })
            },
            getFilteredCourse(){
                axios.post(ALLCOURSESFILTEREDCOURSESTUDENT_URL,
                {},
                {
                    withCredentials:true
                }
                ).then(response => {
                    console.log(response)
                    if (response.data === 'no rights'){
                        this.redirectToCourse()
                    }else {
                        this.filtered_courses = response.data
                    }
                    
                })
            },
        },
        mounted(){
            this.checkUserRole()
        }

    }
</script>
<style scoped>



</style>