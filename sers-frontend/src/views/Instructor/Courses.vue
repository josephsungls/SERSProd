<template>

    <div class = "container-fluid" v-if="access">
        <div class="row tabs">
                <!-- <p :class="tabs.yourclasses? 'active': ''" @click="toggleTab('yourclasses')" >Your Classes</p> -->
                <p :class="tabs.courses? 'active': ''" @click="toggleTab('courses')">All Courses</p>
                <p :class="tabs.campaign? 'active': ''" @click="toggleTab('campaign')">Voting Campaign</p>
                <p :class="tabs.proposed? 'active': ''" @click="toggleTab('proposed')" >Proposed Courses</p>
        </div>
        <!-- <div class = "animate__animated animate__fadeIn animate__faster" v-if="tabs.yourclasses" >
            <YourClasses/>
        </div> -->
        <div class = "animate__animated animate__fadeIn animate__faster" v-if="tabs.courses">
            <AllCourses/>
        </div>
        <div class = "animate__animated animate__fadeIn animate__faster" v-if="tabs.campaign">
            <VotingCampaign/>
        </div>
        <div class ="animate_animated animate__fadeIn animate__faster"  v-if="tabs.proposed">
            <Proposed/>
        </div>

        <div v-if="tabs.courseAdmission">
            <CourseAdmission :selectedCourse="selectedRun"/>
        </div>

        <div v-if="tabs.feedback">

        </div>
    </div>
</template>
<script>
    // import YourClasses from '../../components/Instructor/ViewCourses/YourClasses'
    import VotingCampaign from '../../components/Instructor/ViewCourses/VotingCampaign'
    import AllCourses from '../../components/Instructor/ViewCourses/AllCourses'
    import Proposed from '../../components/Student/Proposed'
    import CourseAdmission from '../../components/Admin/CourseAdmission'
    // import Registration from '../components/Admin/Registration'
    import axios from 'axios'
    
    import router from '../../router.js'
    import {GETUSERROLE_URL} from '../../variables'
    export default{
        data(){
            return {
                tabs:{
                        // yourclasses: true,
                        courses: true,
                        campaign: false,
                        proposed: false,
                        courseAdmission: false,

                },
                courses: [],
                filtered_courses: [],
                selectedRun: [],
                access: false
            }
        },
        components:{
            // YourClasses,
            VotingCampaign,
            AllCourses,
            Proposed,
            CourseAdmission
            // Campaign,
            // Registration
        },
        methods:{
            toggleTab(tab){
                for (const property in this.tabs){
                    property == tab ? this.tabs[property] = true : this.tabs[property] = false
                }
            },
            enterCourseAdmission(){
                for (const property in this.tabs){
                    property == 'courseAdmission'? this.tabs[property] = true : this.tabs[property] = false
                }
            },

            back(){
                for (const property in this.tabs){
                    property == 'yourclasses' || property == 'proposed' ? this.tabs[property] = true : this.tabs[property] = false
                }
            },
            checkUserRole(){
                axios.get(GETUSERROLE_URL,
                {withCredentials: true}                
                ).then(response=>{
                    if (response.data == 2){
                        
                        router.push('/')
                    }else if (response.data == 4){
                        
                        router.push('/managementcourses')
                    }else if (response.data != 3){
                        
                        router.push('/courses')
                    }else{
                        this.access = true
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
    .row{
        display: flex;
        justify-content: center;
        margin-top: 7em;
    }

</style>