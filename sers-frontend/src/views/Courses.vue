<template>
<!-- This view is for admin -->

    <div class = "container-fluid" v-if="access">
        <div class="row tabs">
                <p :class="tabs.courses? 'active': ''" @click="toggleTab('courses')">Course Database</p>
                <p :class="tabs.registration? 'active': ''" @click="toggleTab('registration')">Registration</p>
                <p :class="tabs.campaign? 'active': ''" @click="toggleTab('campaign')">Voting Campaign</p>
                
        </div>
        <div class = "animate__animated animate__fadeIn animate__faster" v-if="tabs.courses" >
            
            <CourseDB/>
        </div>
        <div class = "animate__animated animate__fadeIn animate__faster" v-if="tabs.campaign">
            <Campaign/>
        </div>
        <div class = "animate__animated animate__fadeIn animate__faster" v-if="tabs.registration">
            <Registration/>
        </div>
    </div>
</template>
<script>
    import CourseDB from '../components/Admin/CourseDB'
    import Campaign from '../components/Admin/Campaign'
    import Registration from '../components/Admin/Registration'
    
    import axios from 'axios'
    // import store from '../store'
    import router from '../router.js'
    // import {COURSES_URL, ONGOINGCAMPAIGN_URL, GETUSERROLE_URL} from '../variables'
    import {GETUSERROLE_URL} from '../variables'
    export default{
        data(){
            return {
                tabs:{
                        
                        registration: false,
                        campaign: false,
                        courses: true,
                },
                courses: [],
                filtered_courses: [],
                access: false
            }
        },
        components:{
            CourseDB,
            Campaign,
            Registration
        },
        methods:{
            toggleTab(tab){
                for (const property in this.tabs){
                    property == tab ? this.tabs[property] = true : this.tabs[property] = false
                }
            },

            checkUserRole(){
                axios.get(GETUSERROLE_URL,
                {withCredentials: true}                
                ).then(response=>{
                    if (response.data == 3){
                        router.push('/instructorcourses')
                    }else if (response.data == 4){
                        router.push('/managementcourses')
                    }else if (response.data != 2){
                        
                        router.push('/courses')
                    }else{
                        this.access = true
                        // this.getCourses()
                        // this.getOngoingFilteredCourses()
                    }
                })
            },
        },
        mounted(){
            this.checkUserRole()
         
        },
        computed:{
    
        }

    }
</script>
<style scoped>

    .container-fluid{
        margin-top:30px;
    }
    
    .row{
        margin-top: 110px;
        display: flex;
        justify-content: center;
        background-color: rgba(78, 47, 49, 0.767);
        color: white;
        box-shadow: rgba(0, 0, 0, 0.35) 0px 5px 15px;  
        border-radius: 10px;
    }

    .row p:hover {
        color: rgba(70,35,38,255);
    }

    .tabs .active {
        color: rgba(78, 47, 49, 0.767);
    }

    .active {
        border-radius: 10px;
    }

</style>